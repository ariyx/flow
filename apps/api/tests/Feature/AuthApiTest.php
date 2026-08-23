<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\SessionGuard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use RuntimeException;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register_and_receives_one_owned_ulid_workspace(): void
    {
        $response = $this->spa()->postJson('/api/v1/register', $this->registrationPayload());

        $response->assertCreated()
            ->assertJsonPath('user.email', 'owner@example.test')
            ->assertJsonStructure(['user' => ['id', 'name', 'email'], 'workspace' => ['id']]);

        $user = User::query()->where('email', 'owner@example.test')->firstOrFail();
        $workspace = Workspace::query()->where('owner_id', $user->id)->firstOrFail();

        $this->assertSame($workspace->id, $response->json('workspace.id'));
        $this->assertMatchesRegularExpression('/^[0-9a-hjkmnp-tv-z]{26}$/', $workspace->id);
        $this->assertSame(1, Workspace::query()->where('owner_id', $user->id)->count());
        $this->assertAuthenticatedAs($user);
    }

    public function test_registration_rejects_duplicate_and_invalid_input(): void
    {
        User::factory()->create(['email' => 'owner@example.test']);

        $this->spa()->postJson('/api/v1/register', [
            'name' => '',
            'email' => 'owner@example.test',
            'password' => 'short',
            'password_confirmation' => 'different',
        ])->assertUnprocessable()->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_registration_rolls_back_when_workspace_creation_fails(): void
    {
        Workspace::creating(function (): void {
            throw new RuntimeException('workspace creation failed');
        });

        $this->spa()->postJson('/api/v1/register', $this->registrationPayload())
            ->assertServerError();

        Workspace::flushEventListeners();

        $this->assertDatabaseMissing('users', ['email' => 'owner@example.test']);
        $this->assertDatabaseCount('workspaces', 0);
    }

    public function test_a_user_can_log_in_and_invalid_credentials_are_rejected(): void
    {
        $user = $this->owner(['email' => 'owner@example.test', 'password' => Hash::make('correct-password')]);

        $this->spa()->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'correct-password',
        ])->assertOk()->assertJsonPath('workspace.id', $user->workspace->id);

        $this->assertAuthenticatedAs($user);

        $this->spa()->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertUnprocessable()->assertJsonValidationErrors('email');
    }

    public function test_authenticated_session_returns_only_the_current_users_workspace(): void
    {
        $first = $this->owner();
        $second = $this->owner();

        $this->actingAs($first)->getJson('/api/v1/session')
            ->assertOk()
            ->assertJsonPath('user.id', $first->id)
            ->assertJsonPath('workspace.id', $first->workspace->id)
            ->assertJsonMissing(['id' => $second->workspace->id]);
    }

    public function test_unauthenticated_session_access_is_rejected(): void
    {
        $this->getJson('/api/v1/session')->assertUnauthorized();
    }

    public function test_an_authenticated_user_without_an_owned_workspace_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->getJson('/api/v1/session')->assertForbidden();
    }

    public function test_logout_invalidates_the_authenticated_session(): void
    {
        $user = $this->owner(['email' => 'owner@example.test', 'password' => Hash::make('correct-password')]);

        $this->spa()->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'correct-password',
        ])->assertOk();

        $this->spa()->postJson('/api/v1/logout')
            ->assertNoContent()
            ->assertSessionMissing('login_web_'.sha1(SessionGuard::class));
    }

    public function test_password_reset_request_uses_laravels_password_broker(): void
    {
        Notification::fake();
        $user = $this->owner(['email' => 'owner@example.test']);

        $this->spa()->postJson('/api/v1/forgot-password', ['email' => $user->email])
            ->assertStatus(202);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_a_valid_password_reset_token_changes_the_password(): void
    {
        Notification::fake();
        $user = $this->owner(['email' => 'owner@example.test']);
        $this->spa()->postJson('/api/v1/forgot-password', ['email' => $user->email]);

        $token = '';
        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use (&$token): bool {
            $token = $notification->token;

            return true;
        });

        $this->spa()->postJson('/api/v1/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertOk();

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    public function test_an_invalid_password_reset_token_is_rejected(): void
    {
        $user = $this->owner(['email' => 'owner@example.test']);

        $this->spa()->postJson('/api/v1/reset-password', [
            'token' => 'invalid-token',
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertUnprocessable()->assertJsonValidationErrors('email');
    }

    public function test_unverified_users_can_log_in_without_email_verification(): void
    {
        $user = $this->owner(['email_verified_at' => null, 'password' => Hash::make('correct-password')]);

        $this->spa()->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'correct-password',
        ])->assertOk();
    }

    /** @return array{name: string, email: string, password: string, password_confirmation: string} */
    private function registrationPayload(): array
    {
        return [
            'name' => 'Owner',
            'email' => 'owner@example.test',
            'password' => 'correct-password',
            'password_confirmation' => 'correct-password',
        ];
    }

    /** @param array<string, mixed> $attributes */
    private function owner(array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        Workspace::query()->create(['owner_id' => $user->id]);

        return $user->load('workspace');
    }

    private function spa(): static
    {
        return $this->withHeader('Origin', 'http://localhost:5173');
    }
}

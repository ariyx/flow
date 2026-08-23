<?php

namespace Tests\Feature;

use App\Models\Bot;
use App\Models\User;
use App\Models\Workspace;
use App\Telegram\BotProfile;
use App\Telegram\FakeTelegramClient;
use App\Telegram\TelegramApiException;
use App\Telegram\TelegramClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class ConnectBotTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.telegram.webhook_base_url', 'https://hooks.example.test');
    }

    public function test_an_owner_can_connect_a_validated_bot_with_an_encrypted_token(): void
    {
        $workspace = $this->workspace();
        $token = '123456:secret-token-value';
        $this->fake(new BotProfile('123456', 'webilo_bot', 'Webilo Bot'));

        $response = $this->actingAs($workspace->owner)->postJson('/api/v1/bots', ['token' => $token]);

        $response->assertCreated()
            ->assertJsonPath('bot.telegram_id', '123456')
            ->assertJsonPath('bot.telegram_username', 'webilo_bot')
            ->assertJsonPath('bot.display_name', 'Webilo Bot')
            ->assertJsonPath('bot.token_masked', '********alue')
            ->assertJsonMissing(['token' => $token]);

        $bot = Bot::query()->sole();
        $this->assertSame($workspace->id, $bot->workspace_id);
        $this->assertNotSame($token, $bot->getRawOriginal('token'));
        $this->assertSame($token, Crypt::decryptString($bot->getRawOriginal('token')));
        $this->assertStringNotContainsString($token, $response->getContent());
    }

    public function test_an_invalid_token_does_not_persist_a_bot(): void
    {
        $workspace = $this->workspace();
        $this->fake();

        $this->actingAs($workspace->owner)->postJson('/api/v1/bots', ['token' => 'invalid-token'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('token');

        $this->assertDatabaseCount('bots', 0);
    }

    public function test_an_unauthenticated_request_cannot_connect_a_bot(): void
    {
        $this->fake(new BotProfile('111', null, 'Private Bot'));

        $this->postJson('/api/v1/bots', ['token' => 'valid-token'])
            ->assertUnauthorized();

        $this->assertDatabaseCount('bots', 0);
    }

    public function test_a_telegram_api_failure_does_not_persist_a_bot(): void
    {
        $workspace = $this->workspace();
        $this->fake(failure: new TelegramApiException);

        $this->actingAs($workspace->owner)->postJson('/api/v1/bots', ['token' => 'valid-token'])
            ->assertStatus(503);

        $this->assertDatabaseCount('bots', 0);
    }

    public function test_bot_connection_uses_the_authenticated_users_workspace_not_a_submitted_workspace(): void
    {
        $first = $this->workspace();
        $second = $this->workspace();
        $this->fake(new BotProfile('222', null, 'Second Bot'));

        $this->actingAs($second->owner)->postJson('/api/v1/bots', [
            'token' => 'second-token',
            'workspace_id' => $first->id,
        ])->assertCreated();

        $this->assertDatabaseHas('bots', ['workspace_id' => $second->id, 'telegram_id' => '222']);
        $this->assertDatabaseMissing('bots', ['workspace_id' => $first->id, 'telegram_id' => '222']);
    }

    public function test_the_same_telegram_bot_cannot_be_connected_to_another_workspace(): void
    {
        $first = $this->workspace();
        $second = $this->workspace();
        $profile = new BotProfile('999', 'unique_bot', 'Unique Bot');
        $telegram = $this->fake($profile);

        $this->actingAs($first->owner)->postJson('/api/v1/bots', ['token' => 'first-token'])->assertCreated();
        $this->actingAs($second->owner)->postJson('/api/v1/bots', ['token' => 'second-token'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('token');

        $this->assertDatabaseCount('bots', 1);
        $this->assertCount(1, $telegram->webhookRegistrations);
    }

    private function fake(?BotProfile $profile = null, ?TelegramApiException $failure = null): FakeTelegramClient
    {
        $client = new FakeTelegramClient($profile, $failure);
        $this->app->instance(TelegramClient::class, $client);

        return $client;
    }

    private function workspace(): Workspace
    {
        $user = User::factory()->create();

        return Workspace::query()->create(['owner_id' => $user->id])->load('owner');
    }
}

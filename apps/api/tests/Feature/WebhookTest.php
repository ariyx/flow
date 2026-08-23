<?php

namespace Tests\Feature;

use App\Models\Bot;
use App\Models\User;
use App\Models\Workspace;
use App\Telegram\BotProfile;
use App\Telegram\FakeTelegramClient;
use App\Telegram\TelegramApiException;
use App\Telegram\TelegramClient;
use App\Telegram\WebhookInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class WebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.telegram.webhook_base_url', 'https://hooks.example.test');
    }

    public function test_connecting_a_bot_registers_a_unique_url_and_strong_secret(): void
    {
        $workspace = $this->workspace();
        $telegram = $this->fake(new BotProfile('100', 'first_bot', 'First Bot'));

        $response = $this->actingAs($workspace->owner)->postJson('/api/v1/bots', ['token' => 'first-secret-token']);

        $response->assertCreated()
            ->assertJsonPath('bot.webhook_status', 'healthy')
            ->assertJsonMissing(['token' => 'first-secret-token'])
            ->assertJsonMissing(['webhook_secret']);

        $bot = Bot::query()->sole();
        $secret = Crypt::decryptString($bot->getRawOriginal('webhook_secret'));
        $this->assertSame('https://hooks.example.test/telegram/webhooks/'.$bot->id, $bot->webhook_url);
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $secret);
        $this->assertNotSame($secret, $bot->getRawOriginal('webhook_secret'));
        $this->assertSame([['token' => 'first-secret-token', 'url' => $bot->webhook_url, 'secret' => $secret]], $telegram->webhookRegistrations);
        $this->assertStringNotContainsString($secret, $response->getContent());
    }

    public function test_each_bot_receives_a_unique_webhook_url_and_secret(): void
    {
        $workspace = $this->workspace();
        $this->app->instance(TelegramClient::class, new FakeTelegramClient(new BotProfile('101', null, 'First Bot')));
        $this->actingAs($workspace->owner)->postJson('/api/v1/bots', ['token' => 'first-token'])->assertCreated();

        $this->app->instance(TelegramClient::class, new FakeTelegramClient(new BotProfile('102', null, 'Second Bot')));
        $this->actingAs($workspace->owner)->postJson('/api/v1/bots', ['token' => 'second-token'])->assertCreated();

        $bots = Bot::query()->orderBy('telegram_id')->get();
        $this->assertNotSame($bots[0]->webhook_url, $bots[1]->webhook_url);
        $this->assertNotSame($bots[0]->webhook_secret, $bots[1]->webhook_secret);
    }

    public function test_webhook_registration_failure_does_not_persist_or_report_a_bot_as_configured(): void
    {
        $workspace = $this->workspace();
        $this->fake(new BotProfile('103', null, 'Unavailable Bot'), webhookFailure: new TelegramApiException);

        $this->actingAs($workspace->owner)->postJson('/api/v1/bots', ['token' => 'valid-token'])
            ->assertStatus(503);

        $this->assertDatabaseCount('bots', 0);
    }

    public function test_bot_detail_maps_telegram_webhook_status_and_detects_url_mismatches(): void
    {
        $workspace = $this->workspace();
        $bot = $this->bot($workspace, '104', 'https://hooks.example.test/telegram/webhooks/expected');
        $this->fake(webhookInfo: new WebhookInfo('https://different.example.test/telegram/webhooks/elsewhere'));

        $this->actingAs($workspace->owner)->getJson('/api/v1/bots/'.$bot->id)
            ->assertOk()
            ->assertJsonPath('bot.webhook_status', 'mismatch')
            ->assertJsonMissing(['webhook_secret']);

        $this->assertSame('mismatch', $bot->fresh()->webhook_status);
    }

    public function test_bot_detail_maps_an_empty_telegram_webhook_url_as_not_configured(): void
    {
        $workspace = $this->workspace();
        $bot = $this->bot($workspace, '109', 'https://hooks.example.test/telegram/webhooks/expected');
        $this->fake(webhookInfo: new WebhookInfo(''));

        $this->actingAs($workspace->owner)->getJson('/api/v1/bots/'.$bot->id)
            ->assertOk()
            ->assertJsonPath('bot.webhook_status', 'not_configured');
    }

    public function test_bot_detail_reports_a_telegram_delivery_error_without_exposing_the_secret(): void
    {
        $workspace = $this->workspace();
        $bot = $this->bot($workspace, '105', 'https://hooks.example.test/telegram/webhooks/expected');
        $secret = $bot->webhook_secret;
        $this->fake(webhookInfo: new WebhookInfo($bot->webhook_url, 'Webhook delivery failed'));

        $response = $this->actingAs($workspace->owner)->getJson('/api/v1/bots/'.$bot->id);

        $response->assertOk()->assertJsonPath('bot.webhook_status', 'error');
        $this->assertStringNotContainsString($secret, $response->getContent());
    }

    public function test_bot_overviews_are_scoped_to_the_current_workspace(): void
    {
        $first = $this->workspace();
        $second = $this->workspace();
        $firstBot = $this->bot($first, '106', 'https://hooks.example.test/telegram/webhooks/first');
        $secondBot = $this->bot($second, '107', 'https://hooks.example.test/telegram/webhooks/second');

        $this->actingAs($second->owner)->getJson('/api/v1/bots')
            ->assertOk()
            ->assertJsonCount(1, 'bots')
            ->assertJsonPath('bots.0.id', $secondBot->id);
        $this->actingAs($second->owner)->getJson('/api/v1/bots/'.$firstBot->id)->assertNotFound();
    }

    public function test_a_public_https_webhook_base_url_is_required(): void
    {
        $workspace = $this->workspace();
        config()->set('services.telegram.webhook_base_url', 'http://localhost:8080');
        $this->fake(new BotProfile('108', null, 'Local Bot'));

        $this->actingAs($workspace->owner)->postJson('/api/v1/bots', ['token' => 'valid-token'])
            ->assertStatus(503);

        $this->assertDatabaseCount('bots', 0);
    }

    private function fake(?BotProfile $profile = null, ?WebhookInfo $webhookInfo = null, ?TelegramApiException $webhookFailure = null): FakeTelegramClient
    {
        $client = new FakeTelegramClient($profile, webhookInfo: $webhookInfo, webhookFailure: $webhookFailure);
        $this->app->instance(TelegramClient::class, $client);

        return $client;
    }

    private function workspace(): Workspace
    {
        $user = User::factory()->create();

        return Workspace::query()->create(['owner_id' => $user->id])->load('owner');
    }

    private function bot(Workspace $workspace, string $telegramId, string $url): Bot
    {
        return Bot::query()->create([
            'workspace_id' => $workspace->id,
            'telegram_id' => $telegramId,
            'display_name' => 'Test Bot',
            'token' => 'bot-token-'.$telegramId,
            'webhook_url' => $url,
            'webhook_secret' => bin2hex(random_bytes(32)),
            'webhook_status' => 'healthy',
        ]);
    }
}

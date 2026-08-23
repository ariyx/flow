<?php

namespace Tests\Unit;

use App\Telegram\BotProfile;
use App\Telegram\FakeTelegramClient;
use App\Telegram\InvalidTelegramToken;
use App\Telegram\RealTelegramClient;
use App\Telegram\TelegramApiException;
use App\Telegram\WebhookInfo;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TelegramClientTest extends TestCase
{
    public function test_successful_token_validation_maps_the_telegram_response_to_a_bot_profile(): void
    {
        Http::fake([
            'https://api.telegram.org/botvalid-token/getMe' => Http::response([
                'ok' => true,
                'result' => ['id' => 123456789, 'first_name' => 'Webilo Bot', 'username' => 'webilo_bot'],
            ]),
        ]);

        $profile = (new RealTelegramClient)->validateToken('valid-token');

        $this->assertSame('123456789', $profile->telegramId);
        $this->assertSame('webilo_bot', $profile->username);
        $this->assertSame('Webilo Bot', $profile->displayName);
        Http::assertSent(fn ($request): bool => $request->url() === 'https://api.telegram.org/botvalid-token/getMe');
    }

    public function test_invalid_token_response_is_reported_without_exposing_the_token(): void
    {
        Http::fake(['https://api.telegram.org/*' => Http::response(['ok' => false, 'error_code' => 401, 'description' => 'Unauthorized'], 401)]);

        $this->expectException(InvalidTelegramToken::class);

        (new RealTelegramClient)->validateToken('invalid-token');
    }

    public function test_telegram_api_failure_is_reported(): void
    {
        Http::fake(['https://api.telegram.org/*' => Http::response(['ok' => false, 'error_code' => 500, 'description' => 'Internal Server Error'], 500)]);

        $this->expectException(TelegramApiException::class);

        (new RealTelegramClient)->validateToken('valid-token');
    }

    public function test_fake_client_is_deterministic_and_uses_the_same_interface(): void
    {
        $profile = new BotProfile('42', null, 'Test Bot');
        $client = new FakeTelegramClient($profile);

        $this->assertSame($profile, $client->validateToken('any-token'));

        $this->expectException(InvalidTelegramToken::class);

        (new FakeTelegramClient)->validateToken('any-token');
    }

    public function test_real_client_sets_a_webhook_with_the_expected_secret_and_reads_its_status(): void
    {
        Http::fake([
            'https://api.telegram.org/botvalid-token/setWebhook' => Http::response(['ok' => true, 'result' => true]),
            'https://api.telegram.org/botvalid-token/getWebhookInfo' => Http::response(['ok' => true, 'result' => ['url' => 'https://hooks.example.test/telegram/webhooks/01H']]),
        ]);

        $client = new RealTelegramClient;
        $client->setWebhook('valid-token', 'https://hooks.example.test/telegram/webhooks/01H', 'secret-token');
        $info = $client->getWebhookInfo('valid-token');

        $this->assertEquals(new WebhookInfo('https://hooks.example.test/telegram/webhooks/01H'), $info);
        Http::assertSent(fn ($request): bool => $request->url() === 'https://api.telegram.org/botvalid-token/setWebhook'
            && $request['url'] === 'https://hooks.example.test/telegram/webhooks/01H'
            && $request['secret_token'] === 'secret-token');
    }
}

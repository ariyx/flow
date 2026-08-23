<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\User;
use App\Models\Workspace;
use App\Telegram\BotWebhookConfigurator;
use App\Telegram\InvalidTelegramToken;
use App\Telegram\InvalidWebhookConfiguration;
use App\Telegram\TelegramApiException;
use App\Telegram\TelegramClient;
use DateTimeInterface;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BotController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(['bots' => $this->workspace($request)->bots()->latest()->get()->map($this->botData(...))]);
    }

    public function show(Request $request, string $bot, TelegramClient $telegram, BotWebhookConfigurator $webhooks): JsonResponse
    {
        $bot = $this->workspace($request)->bots()->findOrFail($bot);

        try {
            $webhooks->refresh($bot, $telegram);
        } catch (InvalidTelegramToken|TelegramApiException) {
            $bot->forceFill(['webhook_status' => 'error', 'webhook_checked_at' => now()]);
        }

        $bot->save();

        return response()->json(['bot' => $this->botData($bot)]);
    }

    public function store(Request $request, TelegramClient $telegram, BotWebhookConfigurator $webhooks): JsonResponse
    {
        $attributes = $request->validate(['token' => ['required', 'string', 'max:256']]);

        try {
            $profile = $telegram->validateToken($attributes['token']);
        } catch (InvalidTelegramToken) {
            throw ValidationException::withMessages(['token' => ['The Telegram bot token is invalid.']]);
        } catch (TelegramApiException) {
            abort(503, 'Telegram is unavailable. Please try again.');
        }

        if (Bot::query()->where('telegram_id', $profile->telegramId)->exists()) {
            throw ValidationException::withMessages(['token' => ['This Telegram bot is already connected.']]);
        }

        $workspace = $this->workspace($request);
        $bot = new Bot([
            'workspace_id' => $workspace->id,
            'telegram_id' => $profile->telegramId,
            'telegram_username' => $profile->username,
            'display_name' => $profile->displayName,
            'token' => $attributes['token'],
        ]);
        $bot->id = (string) Str::ulid();

        try {
            $webhooks->configure($bot, $telegram);
        } catch (InvalidWebhookConfiguration) {
            abort(503, 'A public HTTPS webhook URL must be configured before connecting a bot.');
        } catch (InvalidTelegramToken|TelegramApiException) {
            abort(503, 'Telegram webhook registration failed. Please try again.');
        }

        try {
            DB::transaction(fn () => $bot->save());
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages(['token' => ['This Telegram bot is already connected.']]);
        }

        return response()->json(['bot' => $this->botData($bot)], 201);
    }

    private function workspace(Request $request): Workspace
    {
        /** @var User $user */
        $user = $request->user();
        $workspace = $user->workspace;
        abort_unless($workspace !== null && $workspace->owner_id === $user->id, 403);

        return $workspace;
    }

    /**
     * @return array{id: string, telegram_id: string, telegram_username: ?string, display_name: string, token_masked: string, webhook_status: string, webhook_checked_at: ?string}
     */
    private function botData(Bot $bot): array
    {
        $checkedAt = $bot->getAttribute('webhook_checked_at');

        return [
            'id' => $bot->id,
            'telegram_id' => $bot->telegram_id,
            'telegram_username' => $bot->telegram_username,
            'display_name' => $bot->display_name,
            'token_masked' => $bot->maskedToken(),
            'webhook_status' => $bot->webhook_status,
            'webhook_checked_at' => $checkedAt instanceof DateTimeInterface ? $checkedAt->format(DATE_ATOM) : null,
        ];
    }
}

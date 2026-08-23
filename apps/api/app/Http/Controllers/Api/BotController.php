<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\User;
use App\Telegram\InvalidTelegramToken;
use App\Telegram\TelegramApiException;
use App\Telegram\TelegramClient;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BotController extends Controller
{
    public function store(Request $request, TelegramClient $telegram): JsonResponse
    {
        $attributes = $request->validate(['token' => ['required', 'string', 'max:256']]);

        try {
            $profile = $telegram->validateToken($attributes['token']);
        } catch (InvalidTelegramToken) {
            throw ValidationException::withMessages(['token' => ['The Telegram bot token is invalid.']]);
        } catch (TelegramApiException) {
            abort(503, 'Telegram is unavailable. Please try again.');
        }

        /** @var User $user */
        $user = $request->user();
        $workspace = $user->workspace;
        abort_unless($workspace !== null && $workspace->owner_id === $user->id, 403);

        try {
            $bot = DB::transaction(fn (): Bot => Bot::query()->create([
                'workspace_id' => $workspace->id,
                'telegram_id' => $profile->telegramId,
                'telegram_username' => $profile->username,
                'display_name' => $profile->displayName,
                'token' => $attributes['token'],
            ]));
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages(['token' => ['This Telegram bot is already connected.']]);
        }

        return response()->json([
            'bot' => [
                'id' => $bot->id,
                'telegram_id' => $bot->telegram_id,
                'telegram_username' => $bot->telegram_username,
                'display_name' => $bot->display_name,
                'token_masked' => $bot->maskedToken(),
            ],
        ], 201);
    }
}

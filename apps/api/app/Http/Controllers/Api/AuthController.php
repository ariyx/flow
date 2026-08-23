<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\RegisterUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request, RegisterUser $registerUser): JsonResponse
    {
        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $user = $registerUser->handle($attributes);
        Auth::login($user);
        $request->session()->regenerate();

        return response()->json($this->sessionData($user), 201);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $request->session()->regenerate();

        /** @var User $user */
        $user = $request->user();

        return response()->json($this->sessionData($user));
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(status: 204);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $credentials = $request->validate(['email' => ['required', 'string', 'email']]);

        Password::sendResetLink($credentials);

        return response()->json(status: 202);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $status = Password::reset($credentials, function (User $user, string $password): void {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();
        });

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages(['email' => [__($status)]]);
        }

        return response()->json();
    }

    /**
     * @return array{user: array{id: int, name: string, email: string}, workspace: array{id: string}}
     */
    public function session(Request $request): array
    {
        /** @var User $user */
        $user = $request->user();

        return $this->sessionData($user);
    }

    /**
     * @return array{user: array{id: int, name: string, email: string}, workspace: array{id: string}}
     */
    private function sessionData(User $user): array
    {
        $workspace = $user->workspace;

        abort_unless($workspace !== null && $workspace->owner_id === $user->id, 403);

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'workspace' => ['id' => $workspace->id],
        ];
    }
}

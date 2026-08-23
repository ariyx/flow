<?php

namespace App\Services;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterUser
{
    /**
     * @param  array{name: string, email: string, password: string}  $attributes
     */
    public function handle(array $attributes): User
    {
        return DB::transaction(function () use ($attributes): User {
            $user = User::query()->create([
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'password' => Hash::make($attributes['password']),
            ]);

            Workspace::query()->create(['owner_id' => $user->id]);

            return $user->load('workspace');
        });
    }
}

<?php

namespace App\Auth\Actions;

use App\Models\User;
use Illuminate\Support\Str;

class CreateUserTokenAction
{
    private const TOKEN_LENGTH = 32;

    public function execute(User $user): string
    {
        return $user->createToken(
            Str::random(
                self::TOKEN_LENGTH
            )
        )->plainTextToken;
    }
}

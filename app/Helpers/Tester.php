<?php

namespace App\Helpers;

use App\Models\PersonalAccessToken;

class Tester
{
    public static function createApiUrlFrom(string $path): string
    {
        return "/api/v1/$path";
    }

    public static function createTokenFrom(Tokens $token): string
    {
        return match ($token) {
            Tokens::FIRST_TOKEN => "1|" . Tokens::FIRST_TOKEN->value,
            Tokens::SECOND_TOKEN => "2|" . Tokens::SECOND_TOKEN->value,
            Tokens::THIRD_TOKEN => "3|" . Tokens::THIRD_TOKEN->value,
        };
    }

    public static function accessRefreshTokenOf(int $id): string|bool
    {
        $check = PersonalAccessToken::find($id);
        if(!$check) return false;

        return $check->plain_refresh_token;
    }
}

<?php

namespace App\Helpers;

class Tester
{
    public static function createApiUrlFrom(string $path): string
    {
        return "/api/v1/$path";
    }
}

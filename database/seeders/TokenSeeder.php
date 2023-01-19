<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TokenSeeder extends Seeder
{
    const FIRST_TOKEN = "abc";
    const SECOND_TOKEN = "def";
    const THIRD_TOKEN = "ghi";

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();

        // Create normal token
        $user->tokens()->create([
            'id' => 1,
            'name' => 'web',
            'token' => hash('sha256', self::FIRST_TOKEN),
            'abilities' => ['*'],
            'expires_at' => now()->addMinute(2),
        ]);

        // Create token that already expired.
        $user->tokens()->create([
            'id' => 2,
            'name' => 'web',
            'token' => hash('sha256', self::SECOND_TOKEN),
            'abilities' => ['*'],
            'expires_at' => now()->subMinute(3),
        ]);

        // Create token that the refresh token already expired
        $user->tokens()->create([
            'id' => 3,
            'name' => 'web',
            'token' => hash('sha256', self::THIRD_TOKEN),
            'abilities' => ['*'],
            'expires_at' => now()->addMinute(2),
            'created_at' => now()->subHour(1)
        ]);
    }
}

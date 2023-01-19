<?php

namespace App\Console\Commands;

use App\Models\PersonalAccessToken;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PruneExpiredTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prune:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune expired token but instead from token expiration, use expiration from refresh.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tokens = PersonalAccessToken::get();

        foreach ($tokens as $token) {
            $refreshExpr = Carbon::parse($token->created_at)->addMinute(config('sanctum.refresh_expiration'));

            if($refreshExpr->lte(now())) {
                $token->delete();
            }
        }

        $this->info("Token cleared successfully!");

        return Command::SUCCESS;
    }
}

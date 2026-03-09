<?php

namespace App\Console\Commands;

use App\Models\AccountManager;
use Illuminate\Console\Command;

class ResetMonthlyAccountManagerData extends Command
{
    protected $signature = 'account-managers:reset-monthly';

    protected $description = 'Reset monthlyDeposit, monthlySpent, monthlyWithdraw, and monthlyPackage for all Account Managers';

    public function handle()
    {
        AccountManager::query()->update([
            'monthlyDeposit' => 0,
            'monthlySpent' => 0,
            'monthlyWithdraw' => 0,
            'monthlyPackage' => 0,
            'noOfUsers' => 0,
        ]);

        $this->info('Monthly stats reset successfully for all account managers.');
    }
}

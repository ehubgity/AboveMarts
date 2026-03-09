<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\AccountManager;
use Illuminate\Support\Facades\DB;


class TransactionHelper
{
    public static function updateAccountManagerTotals($userId, $amount, $type)
    {
        $user = User::find($userId);

        if (!$user || !$user->manager) {
            return;
        }

        $manager = AccountManager::find($user->manager);
        if (!$manager) return;

        switch (strtolower($type)) {
            case 'Deposit':
                $manager->totalDeposit += $amount;
                $manager->monthlyDeposit += $amount;
                break;

            case 'Withdraw':
                $manager->totalWithdraw += $amount;
                $manager->monthlyWithdraw += $amount;
                break;

            case 'Package Transaction':
                $manager->totalPackage += $amount;
                $manager->monthlyPackage += $amount;
                break;

            default:
                $manager->totalSpent += $amount;
                $manager->monthlySpent += $amount;
                break;
        }

        $manager->save();
    }
}


// composer dump-autoload

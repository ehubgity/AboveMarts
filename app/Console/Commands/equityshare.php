<?php

namespace App\Console\Commands;
use App\Models\bonus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class equityshare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'equity:share';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'How equity is shared';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $totalBonusEarning = DB::table('bonuses')
            ->where('sponsor', '=', 'Admin')
            ->sum('amount');

        $equityEarning = DB::table('bonuses')
            ->where('username', '=', 'Equity')
            ->sum('amount');
        $balance = $totalBonusEarning - $equityEarning;
        $ictjointequity = $balance * 6/100;
        $sagetonyequity = $balance * 3/100;
        $deprinceequity = $balance * 0.10/100;
        $successmartsequity = $balance * 0.10/100;

        bonus::create([
            'bonusId' => $this->randomDigit(),
            'sponsor' => 'ictjoint',
            'sponsorId' => 'ictjoint',
            'username' => 'Equity',
            'email' => 'Equity',
            'amount' => $ictjointequity,
            'package' => 'Equity',
            'status' => 'Confirm',
            'dayCounter' => 0,
        ]);
        bonus::create([
            'bonusId' => $this->randomDigit(),
            'sponsor' => 'Sagetony',
            'sponsorId' => 'Sagetony',
            'username' => 'Equity',
            'email' => 'Equity',
            'amount' => $sagetonyequity,
            'package' => 'Equity',
            'status' => 'Confirm',
            'dayCounter' => 0,
        ]);
        bonus::create([
            'bonusId' => $this->randomDigit(),
            'sponsor' => 'deprince',
            'sponsorId' => 'deprince',
            'username' => 'Equity',
            'email' => 'Equity',
            'amount' => $deprinceequity,
            'package' => 'Equity',
            'status' => 'Confirm',
            'dayCounter' => 0,
        ]);

        bonus::create([
            'bonusId' => $this->randomDigit(),
            'sponsor' => 'successmarts',
            'sponsorId' => 'successmarts',
            'username' => 'Equity',
            'email' => 'Equity',
            'amount' => $successmartsequity,
            'package' => 'Equity',
            'status' => 'Confirm',
            'dayCounter' => 0,
        ]);
        $this->info('Successfully Updated.');
    }
}

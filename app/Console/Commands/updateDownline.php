<?php

namespace App\Console\Commands;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class updateDownline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:downline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating Downline';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        User::chunk(1000, function ($users) {
            foreach ($users as $user) {
                DB::table('downlines')->where('downline', $user->username)->update([
                'fullname' => $user->firstName . ' ' . $user->lastName,
                'email' => $user->email,
                'phoneNumber' => $user->phoneNumber,
                'rank' => $user->rank,
                'package' => $user->package,
                ]);
            }
        });
    
        $this->info('Downline table updated successfully!');
    }
}

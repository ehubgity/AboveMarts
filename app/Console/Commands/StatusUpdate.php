<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class StatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating User Profile Status';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $feederOne = 1;
        $feederTwo = 4;
        $feederThree = 10;
        $teamL = 20;
        $groupL = 60;
        $nationB = 120;
        $teamM = 250;
        $seniorM = 500;
        $executiveM = 1000;
        $executiveD = 2000;
        $regionalD = 4000;
        $globalA = 7500;

        $dataUsers = DB::table('users')->get();
        foreach ($dataUsers as $user) {
            $id = $user->id;
            if ($user->point >= $feederOne && $user->point < $feederTwo) {
                DB::table("users")
                    ->where('id', $id)
                    ->update(['rank' => 'Feeder One']);
                $this->info('Successfully Updated.');
            } elseif ($user->point >= $feederTwo && $user->point < $feederThree) {
                DB::table("users")
                    ->where('id', $id)
                    ->update(['rank' => 'Feeder Two']);
                $this->info('Successfully Updated.');
            } elseif ($user->point >= $feederThree && $user->point < $teamL) {
                DB::table("users")
                    ->where('id', $id)
                    ->update(['rank' => 'Feeder Three']);
                $this->info('Successfully Updated.');
            } elseif ($user->point >= $teamL && $user->point < $groupL) {
                DB::table("users")
                    ->where('id', $id)
                    ->update(['rank' => 'Team Leader']);
                $this->info('Successfully Updated.');
            } elseif ($user->point >= $groupL && $user->point < $nationB) {
                DB::table("users")
                    ->where('id', $id)
                    ->update(['rank' => 'Group Leader']);
                $this->info('Successfully Updated.');
            } elseif ($user->point >= $nationB && $user->point < $teamM) {
                DB::table("users")
                    ->where('id', $id)
                    ->update(['rank' => 'Nation Builder']);
                $this->info('Successfully Updated.');
            } elseif ($user->point >= $teamM && $user->point < $seniorM) {
                DB::table("users")
                    ->where('id', $id)
                    ->update(['rank' => 'Team Manager']);
                $this->info('Successfully Updated.');
            } elseif ($user->point >= $seniorM && $user->point < $executiveM) {
                DB::table("users")
                    ->where('id', $id)
                    ->update(['rank' => 'Senior Manager']);
                $this->info('Successfully Updated.');
            } elseif ($user->point >= $executiveM && $user->point < $executiveD) {
                DB::table("users")
                    ->where('id', $id)
                    ->update(['rank' => 'Executive Manager']);
                $this->info('Successfully Updated.');
            } elseif ($user->point >= $executiveD && $user->point < $regionalD) {
                DB::table("users")
                    ->where('id', $id)
                    ->update(['rank' => 'Executive Director']);
                $this->info('Successfully Updated.');
            } elseif ($user->point >= $regionalD && $user->point < $globalA) {
                DB::table("users")
                    ->where('id', $id)
                    ->update(['rank' => 'Regional Director']);
                $this->info('Successfully Updated.');
            } elseif ($user->point >= $globalA) {
                DB::table("users")
                    ->where('id', $id)
                    ->update(['rank' => 'Global Ambassador']);
                $this->info('Successfully Updated.');
            } else {
                $this->info('No Response');
            }
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class totalearning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'total:earning';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating total earning';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::table("users")
        ->where('id', $id)
        ->update(['rank' => 'Group Leader']);
        $this->info('Successfully Updated.');
    }
}

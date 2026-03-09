<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CronController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    
    public function runCronJobs(Request $request)
    {
        // Call the schedule:run Artisan command
        $exitCode = Artisan::call('schedule:run');

        // Optionally handle the response
        if ($exitCode === 0) {
            return response()->json(['message' => 'Cron jobs executed successfully'], 200);
        } else {
            return response()->json(['message' => 'Error executing cron jobs'], 500);
        }
    }
}

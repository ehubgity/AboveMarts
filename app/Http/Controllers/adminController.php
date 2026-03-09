<?php

namespace App\Http\Controllers;

use App\Models\admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class adminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['otheradmin']);
    }

    public function index()
    {
        $datadeposit = DB::table('funds')
            ->where('name', '!=', 'ictjoint')
            ->sum('amount');

        $databonus = DB::table('bonuses')
            ->where('sponsorId', '!=', 'Admin')
            ->sum('amount');

        $datawithdraw = DB::table('withdraws')
            ->where('paymentType', '!=', 'Transfer')
            ->where('status', 'CONFIRM')
            ->sum('amount');

        $datawithdrawPending = DB::table('withdraws')
            ->where('status', 'PENDING')
            ->sum('amount');

        $datadepositPending = DB::table('funds')
            ->where('status', 'PENDING')
            ->sum('amount');
        $datatransactionPending = DB::table('funds')
            ->where('status', 'PENDING')
            ->sum('amount');

        $dataPreorderPending = DB::table('preordercards')
            ->where('status', 'PENDING')
            ->count();

        $datauser = DB::table('users')->count('id');

        $totalPoints = DB::table('users')->sum('point');

        $points = DB::table('points')
            ->where('status', '!=', 'CONFIRM')
            ->sum('point');

        $dataamount = DB::table('funds')->count('id');

        $datawith = DB::table('withdraws')->count('id');

        $databonusno = DB::table('bonuses')
            ->where('sponsor', '!=', 'Admin')
            ->count('id');

        $equityEarning = DB::table('bonuses')
            ->where('username', '=', 'Equity')
            ->sum('amount');

        $totalBonusEarning = DB::table('bonuses')
            ->where('sponsor', '=', 'Admin')
            ->sum('amount');

        $bonusEarning = DB::table('bonuses')
            ->where('sponsor', '!=', 'Admin')
            ->sum('amount');

        $bonusWithdraw = DB::table('withdraws')
            ->where('paymentType', '=', 'Transfer')
            ->sum('amount');

        $sharedamount = DB::table('withdraws')
            ->where('paymentType', '=', 'Shared')
            ->sum('amount');

        $dataRP = DB::table('transactions')
            ->where('transactionType', 'Recharge Printing')
            ->where('status', 'CONFIRM')
            ->sum('amount');

        $dataRC = DB::table('transactions')
            ->where('username', '!=', 'Admin')
            ->where('transactionType', 'Recharge Card')
            ->where('status', 'CONFIRM')
            ->sum('amount');
        $dataDP = DB::table('transactions')
            ->where('username', '!=', 'Admin')
            ->where('transactionType', 'Data Purchase')
            ->where('status', 'CONFIRM')
            ->sum('amount');

        $dataDS = DB::table('transactions')
            ->where('username', '!=', 'Admin')
            ->where('transactionType', 'Data Share')
            ->where('status', 'CONFIRM')

            ->sum('amount');
        $dataEL = DB::table('transactions')
            ->where('username', '!=', 'Admin')
            ->where('transactionType', 'Electricity')
            ->where('status', 'CONFIRM')
            ->sum('amount');
        $dataCA = DB::table('transactions')
            ->where('username', '!=', 'Admin')
            ->where('transactionType', 'Cable Purchase')
            ->where('status', 'CONFIRM')
            ->sum('amount');
        $totalPackages = DB::table('transactions')
            ->where('transactionType', '=', 'Package Transaction')
            ->where('status', 'CONFIRM')
            ->sum('amount');

        $dataSales = DB::table('transactions')
            ->where('transactionType', '!=', 'Deposit')
            ->where('transactionType', '!=', 'Withdraw')
            ->where('transactionType', '!=', "Charges")
            ->where('transactionType', '!=', 'Withdrawal Charges')
            ->where('transactionType', '!=', 'Package Transaction')
            ->where('status', 'CONFIRM')
            ->sum('amount');
        $expenses = DB::table('transactions')
            ->where('transactionType', '!=', 'Deposit')
            ->where('transactionType', '!=', 'Withdraw')
            ->where('status', 'CONFIRM')
            ->sum('amount');


        $response = Http::withHeaders([
            'AuthorizationToken' => 'b020e27d57869240ce85fefd664a886f', // Replace with your actual token
            'cache-control' => 'no-cache',
        ])->get('https://easyaccessapi.com.ng/api/wallet_balance.php');

        $data = $response->json(); // If the API returns JSON
        // Or use $response->body(); if it returns plain text
        $easyaccessBalance = $data['balance']; // or return $data;


        // $ebillsbalance = $this->fetchEbillsBalance();
                $ebillsbalance = 000;

        // $smsbalance = $this->fetchEstoreSmsBalance();
// return $smsbalance;
        return view('admin.dashboard')
            ->with('datadeposit', $datadeposit)
            ->with('databonus', $databonus)
            ->with('datawithdraw', $datawithdraw)
            ->with('datauser', $datauser)
            ->with('dataamount', $dataamount)
            ->with('dataSales', $dataSales)
            ->with('datawith', $datawith)
            ->with('datawithdrawPending', $datawithdrawPending)
            ->with('datadepositPending', $datadepositPending)
            ->with('dataPreorderPending', $dataPreorderPending)
            ->with('datatransactionPending', $datatransactionPending)
            ->with('dataRP', $dataRP)
            ->with('dataRC', $dataRC)
            ->with('dataDP', $dataDP)
            ->with('dataDS', $dataDS)
            ->with('dataEL', $dataEL)
            ->with("dataCA", $dataCA)
            ->with('databonusno', $databonusno)
            ->with('bonusWithdraw', $bonusWithdraw)
            ->with('bonusEarning', $bonusEarning)
            ->with('totalBonusEarning', $totalBonusEarning)
            ->with('totalPackages', $totalPackages)
            ->with('expenses', $expenses)
            ->with('totalPoints', $totalPoints)
            ->with('points', $points)
            ->with('equityEarning', $equityEarning)
            ->with('sharedamount', $sharedamount)
            ->with('easyaccessBalance', $easyaccessBalance)
            ->with('ebillsbalance', $ebillsbalance);
    }


    private function fetchEbillsBalance()
    {
        // Step 1: Authenticate
        $authResponse = Http::post('https://ebills.africa/wp-json/jwt-auth/v1/token', [
            'username' => env('EBILLS_USERNAME'),  
            'password' => env('EBILLS_PASSWORD'),  
        ]);

        if ($authResponse->failed() || !isset($authResponse['token'])) {
            return response()->json(['error' => 'Authentication failed'], 401);
        }

        $token = $authResponse['token'];

        // Step 2: Get Balance
        $balanceResponse = Http::withToken($token)->get('https://ebills.africa/wp-json/api/v2/balance');

        if ($balanceResponse->failed()) {
            return response()->json(['error' => 'Failed to retrieve balance'], 500);
        }

        $data = $balanceResponse['data'];

        // Step 3: Return only relevant info
        return $data['balance'];
    }


    // private function fetchEstoreSmsBalance()
    // {
    //     $username = env('SMS_USERNAME');
    //     $password = env('SMS_PASSWORD ');

    //     $url = 'http://www.estoresms.com/smsapi.php';

    //     $response = Http::get($url, [
    //         'username' => $username,
    //         'password' => $password,
    //         'balance'  => 'true',
    //     ]);

    //     if ($response->failed()) {
    //         return response()->json(['error' => 'Failed to retrieve SMS balance'], 500);
    //     }

    //     return response($response->body());
    // }
}

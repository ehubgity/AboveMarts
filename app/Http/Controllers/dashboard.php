<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class dashboard extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data = DB::table('transactions')
            ->where('userId', auth()->user()->userId)
            ->where('status', 'CONFIRM')
            ->orderByDesc('id')
            ->take(5)
            ->get();
        $walletamount = DB::table('funds')
            ->where('userId', auth()->user()->userId)
            ->where('status', 'success')
            ->sum('amount');

        $withdrawamount = DB::table('withdraws')
            ->where('userId', auth()->user()->userId)
            ->where('status', 'CONFIRM')
            ->where('paymentType', 'wallet')
            ->sum('amount');
        $withdrawbonus = DB::table('withdraws')
            ->where('userId', auth()->user()->userId)
            ->where('status', 'CONFIRM')
            ->where('paymentType', 'Transfer')
            ->sum('amount');
        $bonusamount = DB::table('bonuses')
            ->where('sponsor', auth()->user()->mySponsorId)
            ->sum('amount');
        $expenses = DB::table('transactions')
            ->where('userId', auth()->user()->userId)
            ->where('status', 'CONFIRM')
            ->where('transactionType', '!=', 'Deposit')
            ->sum('amount');
        $capital = DB::table('funds')
            ->where('userId', auth()->user()->userId)
            ->where('status', 'success')
            ->sum('amount');
        // $bonus = DB::table('transactions')
        //     ->where('userId', auth()->user()->userId)
        //     ->where('transactionService', 'Commission')
        //     ->sum('amount');
        return view('user.dashboard')
            ->with('walletamount', $walletamount)
            ->with('withdrawamount', $withdrawamount)
            ->with('bonusamount', $bonusamount)
            ->with('withdrawbonus', $withdrawbonus)
            ->with('data', $data)
            ->with('capital', $capital)
            // ->with('bonus', $bonus)
            ->with('expenses', $expenses);
    }
}

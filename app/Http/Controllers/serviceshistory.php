<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class serviceshistory extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $data = DB::table('transactions')->where('userId', auth()->user()->userId)
            ->where('transactionType', '!=', 'Deposit')
            ->where('transactionType', '!=', 'Withdraw')
            ->orderByDesc('id')->get();
            
        return view('user.serviceshistory')->with('data', $data);
    }
}

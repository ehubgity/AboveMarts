<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class cardhistory extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $data = DB::table('usedcards')->where('userId', auth()->user()->userId)
                ->where('status', '!=', 'PENDING')->orderByDesc('id')->get();
        return view('user.cardhistory')->with('data', $data);
    }

    public function store(Request $request) {

        $id = $request->id;
        
        $datacard = DB::table('usedcards')->where('transactionId', $id)->first();
        
        // please change to Used
        $cards =  DB::table('cards')->where('status', 'USED')
        ->where('userId', auth()->user()->userId)
        ->where('cardId', $datacard->cardId)
        ->where('amount', $datacard->amount)
        ->where('network', $datacard->network)
        ->take($datacard->quantity)
        ->get();

        return view('user.cardinfo')->with('cards', $cards)->with('datacard', $datacard);

    }
}

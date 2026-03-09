<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class edittransaction extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $datadeposits = DB::table('transactions')
            ->where('transactionId', $request->id)
            ->first();

        if (isset($request->id)) {
            return view('admin.edittransaction', ['datadeposits' => $datadeposits]);
        } else {
            return redirect()->route('transactions', ['datadeposits' => $datadeposits]);
        }
    }

    public function store(Request $request)
    {
        $datadeposits = DB::table('transactions')
            ->where('transactionId', $request->id)
            ->get();

        $validator = Validator::make($request->all(), [
            'amount' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('toast_error', 'Failed transaction');
        } else {
            DB::table('transactions')
                ->where('transactionId', $request->id)
                ->update(['amount' => $request->amount]);
            return back()->with('toast_success', 'Transaction Updated!!');
            // return dd($request->id);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class addinterestController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $datadeposits = DB::table('funds')
            ->where('transactionId', $request->id)
            ->first();

        if (isset($request->id)) {
            return view('admin.interest', ['datadeposits' => $datadeposits]);
        } else {
            return redirect()->route('adminfunding', ['datadeposits' => $datadeposits]);
        }
    }

    public function store(Request $request)
    {
        $datadeposits = DB::table('funds')
            ->where('transactionId', $request->id)
            ->get();

        $validator = Validator::make($request->all(), [
            'amount' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('toast_error', 'Failed transaction');
        } else {
            DB::table('funds')
                ->where('transactionId', $request->id)
                ->update(['amount' => $request->amount]);
            return back()->with('toast_success', 'Deposit Updated!!');
            // return dd($request->id);
        }
    }
}

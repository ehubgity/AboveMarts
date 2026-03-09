<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class adminRC extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('subadmin');
    }

    public function index(Request $request)
    {
        $datadeposits = DB::table('rcpurchases')->orderByDesc('id')
            ->paginate(15);

        if (isset($request->confirmid)) {
            DB::table('rcpurchases')
                ->where('transactionId', $request->confirmid)
                ->update(['status' => 'success']);
            return back();

        } elseif (isset($request->unconfirmid)) {
            DB::table('rcpurchases')
                ->where('transactionId', $request->unconfirmid)
                ->update(['status' => 'PENDING']);
            return back();

        } elseif (isset($request->deleteid)) {
            DB::table('rcpurchases')
                ->where('transactionId', $request->deleteid)
                ->delete();
            return back();

        } else {
            return view('admin.rcpurchases')->with('datadeposits', $datadeposits);

        }


    }

    public function search(Request $request)
    {
        $datarechargepurchase = DB::table('rcpurchases')->orderByDesc('id')
            ->paginate(20);
        $datadeposits = DB::table('rcpurchases')->orderByDesc('id')
            ->paginate(15);

        $query = $request->input('query');

        if ($query != null) {
            $datas = DB::table('rcpurchases')->where('username', 'LIKE', "%$query%")->orWhere('email', 'LIKE', "%$query%")->orWhere('phoneNumber', 'LIKE', "%$query%")
                ->orderByDesc('id')->get();
            return view('admin.rcpurchases')->with('query', $query)->with('datas', $datas)->with('datarechargepurchase', $datarechargepurchase);
        } else {

            if (isset($request->confirmid)) {
                DB::table('rcpurchases')
                    ->where('transactionId', $request->confirmid)
                    ->update(['status' => 'CONFIRM']);
                return back();

            } elseif (isset($request->unconfirmid)) {
                DB::table('rcpurchases')
                    ->where('transactionId', $request->unconfirmid)
                    ->update(['status' => 'PENDING']);
                return back();

            } elseif (isset($request->deleteid)) {
                DB::table('rcpurchases')
                    ->where('transactionId', $request->deleteid)
                    ->delete();

                return back();

            } else {
                return view('admin.rcpurchases')->with('datadeposits', $datadeposits);

            }

        }

    }
}

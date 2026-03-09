<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class adminDP extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('subadmin');
    }

    public function index(Request $request)
    {
        $datadeposits = DB::table('datapurchases')->orderByDesc('id')
            ->paginate(15);

        if (isset($request->confirmid)) {
            DB::table('datapurchases')
                ->where('transactionId', $request->confirmid)
                ->update(['status' => 'CONFIRM']);
            return back();

        } elseif (isset($request->unconfirmid)) {
            DB::table('datapurchases')
                ->where('transactionId', $request->unconfirmid)
                ->update(['status' => 'PENDING']);
            return back();

        } elseif (isset($request->deleteid)) {
            DB::table('datapurchases')
                ->where('transactionId', $request->deleteid)
                ->delete();
            return back();

        } else {
            return view('admin.datapurchases')->with('datadeposits', $datadeposits);

        }


    }

    public function search(Request $request)
    {
        $datarechargepurchase = DB::table('datapurchases')->orderByDesc('id')
            ->paginate(20);
        $datadeposits = DB::table('datapurchases')->orderByDesc('id')
            ->paginate(15);

        $query = $request->input('query');

        if ($query != null) {
            $datas = DB::table('datapurchases')->where('username', 'LIKE', "%$query%")->orWhere('email', 'LIKE', "%$query%")->orWhere('phoneNumber', 'LIKE', "%$query%")
                ->orderByDesc('id')->get();
            return view('admin.datapurchases')->with('query', $query)->with('datas', $datas)->with('datarechargepurchase', $datarechargepurchase);
        } else {

            if (isset($request->confirmid)) {
                DB::table('datapurchases')
                    ->where('transactionId', $request->confirmid)
                    ->update(['status' => 'CONFIRM']);
                return back();

            } elseif (isset($request->unconfirmid)) {
                DB::table('datapurchases')
                    ->where('transactionId', $request->unconfirmid)
                    ->update(['status' => 'PENDING']);
                return back();

            } elseif (isset($request->deleteid)) {
                DB::table('datapurchases')
                    ->where('transactionId', $request->deleteid)
                    ->delete();

                return back();

            } else {
                return view('admin.datapurchases')->with('datadeposits', $datadeposits);

            }

        }

    }
}

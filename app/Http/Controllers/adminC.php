<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class adminC extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('subadmin');
    }

    public function index(Request $request)
    {
        $datadeposits = DB::table('cables')
            ->orderByDesc('id')
            ->paginate(15);

        if (isset($request->confirmid)) {
            DB::table('cables')
                ->where('transactionId', $request->confirmid)
                ->update(['status' => 'CONFIRM']);
            return back();
        } elseif (isset($request->unconfirmid)) {
            DB::table('cables')
                ->where('transactionId', $request->unconfirmid)
                ->update(['status' => 'PENDING']);
            return back();
        } elseif (isset($request->deleteid)) {
            DB::table('cables')
                ->where('transactionId', $request->deleteid)
                ->delete();
            return back();
        } else {
            return view('admin.cables')->with('datadeposits', $datadeposits);
        }
    }

    public function search(Request $request)
    {
        $datarechargepurchase = DB::table('cables')
            ->orderByDesc('id')
            ->paginate(20);
        $datadeposits = DB::table('cables')
            ->orderByDesc('id')
            ->paginate(15);

        $query = $request->input('query');

        if ($query != null) {
            $datas = DB::table('cables')
                ->where('username', 'LIKE', "%$query%")
                ->orWhere('email', 'LIKE', "%$query%")
                ->orWhere('phoneNumber', 'LIKE', "%$query%")->orderByDesc('id')
                ->get();
            return view('admin.cables')
                ->with('query', $query)
                ->with('datadeposits', $datadeposits)
                ->with('datarechargepurchase', $datarechargepurchase);
        } else {
            if (isset($request->confirmid)) {
                DB::table('cables')
                    ->where('transactionId', $request->confirmid)
                    ->update(['status' => 'CONFIRM']);
                return back();
            } elseif (isset($request->unconfirmid)) {
                DB::table('cables')
                    ->where('transactionId', $request->unconfirmid)
                    ->update(['status' => 'PENDING']);
                return back();
            } elseif (isset($request->deleteid)) {
                DB::table('cables')
                    ->where('transactionId', $request->deleteid)
                    ->delete();

                return back();
            } else {
                return view('admin.cables')->with('datadeposits', $datadeposits);
            }
        }
    }
}

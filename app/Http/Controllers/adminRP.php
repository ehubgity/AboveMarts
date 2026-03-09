<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class adminRP extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('subadmin');
    }

    public function index(Request $request)
    {
        $datadeposits = DB::table('rechargeprintings')->where('photo', '!=', 'Sample')->where('status', 'CONFIRM')->orderByDesc('id')
            ->paginate(15);

        if (isset($request->confirmid)) {
            DB::table('rechargeprintings')
                ->where('transactionId', $request->confirmid)
                ->update(['status' => 'success']);
            return back();

        } elseif (isset($request->unconfirmid)) {
            DB::table('rechargeprintings')
                ->where('transactionId', $request->unconfirmid)
                ->update(['status' => 'PENDING']);
            return back();

        } elseif (isset($request->deleteid)) {
            DB::table('rechargeprintings')
                ->where('transactionId', $request->deleteid)
                ->delete();
            return back();

        } else {
            return view('admin.rechargeprintings')->with('datadeposits', $datadeposits);

        }


    }

    public function search(Request $request)
    {
        $datarechargepurchase = DB::table('rechargeprintings')->where('status', 'CONFIRM')->where('photo', '!=', 'Sample')->orderByDesc('id')
            ->paginate(20);
        $datadeposits = DB::table('rechargeprintings')->where('status', 'CONFIRM')->where('photo', '!=', 'Sample')->orderByDesc('id')
            ->paginate(15);

        $query = $request->input('query');

        if ($query != null) {
            $datas = DB::table('rechargeprintings')->where('username', 'LIKE', "%$query%")->orWhere('network', 'LIKE', "%$query%")->orWhere('email', 'LIKE', "%$query%")->orWhere('phoneNumber', 'LIKE', "%$query%")
                ->orderByDesc('id')->get();
            return view('admin.rechargeprintings')->with('query', $query)->with('datas', $datas)->with('datarechargepurchase', $datarechargepurchase);
        } else {

            if (isset($request->confirmid)) {
                DB::table('rechargeprintings')
                    ->where('transactionId', $request->confirmid)
                    ->update(['status' => 'CONFIRM']);
                return back();

            } elseif (isset($request->unconfirmid)) {
                DB::table('rechargeprintings')
                    ->where('transactionId', $request->unconfirmid)
                    ->update(['status' => 'PENDING']);
                return back();

            } elseif (isset($request->deleteid)) {
                DB::table('rechargeprintings')
                    ->where('transactionId', $request->deleteid)
                    ->delete();

                return back();

            } else {
                return view('admin.rechargeprintings')->with('datadeposits', $datadeposits);

            }

        }

    }
}

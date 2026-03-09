<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class totalTransaction extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $usersWithBalances = DB::table('transactions')
            ->select(
                'userId as id',
                'username as name',
                DB::raw(
                    'SUM(CASE WHEN transactionType = "Deposit" THEN amount ELSE -amount END) as balance'
                )
            )
            ->groupBy('userId', 'username')
            ->having('balance', '<', 0)
            ->paginate(20);

        return view('admin.totaltransaction')->with('usersWithBalances', $usersWithBalances);
    }
    public function search(Request $request)
    {
        $usersWithBalances = DB::table('transactions')
            ->select(
                'userId as id',
                'username as name',
                DB::raw(
                    'SUM(CASE WHEN transactionType = "Deposit" THEN amount ELSE -amount END) as balance'
                )
            )
            ->groupBy('userId', 'username')
            ->having('balance', '<', 0)
            ->paginate(20);

        $query = $request->input('query');

        if ($query != null) {
            $datas = DB::table('transactions')
                ->select('userId as id', 'username as name', DB::raw('SUM(amount) as deposit_sum'))
                ->where('username', 'LIKE', "%$query%")
                ->where('transactionType', '=', 'Deposit')
                ->groupBy('userId', 'username')
                ->having('deposit_sum', '>', 0)
                ->orderByDesc('id')
                ->get();

            return view('admin.totaltransaction')
                ->with('query', $query)
                ->with('datas', $datas)
                ->with('usersWithBalances', $usersWithBalances);
        } else {
            return view('admin.totaltransaction')->with('usersWithBalances', $usersWithBalances);
        }
    }
}

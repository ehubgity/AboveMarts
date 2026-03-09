<?php

namespace App\Http\Controllers;

use App\Mail\EmailFunding;
use App\Models\deposit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class adminfundController extends Controller
{
    public function __construct()
    {
        $this->middleware('otheradmin');
    }

    public function index(Request $request)
    {
        $datadeposits = DB::table('funds')
            ->orderByDesc('id')
            ->paginate(20);

        if (isset($request->confirmid)) {
            DB::table('funds')
                ->where('transactionId', $request->confirmid)
                ->update([
                    'status' => 'success',
                    'Admin' => Auth::guard('admin')->user()->username,
                ]);
                 $transaction = DB::table('funds')
                ->where('transactionId', $request->confirmid)
                ->first();
            
            
                // Update User Balance
                $user = User::where('userId', $transaction->userId)->first();
            
                if ($user) {
                    $user->update([
                        'beforeBalance' => $user->currentBalance,
                        'currentBalance' => $user->currentBalance + $transaction->amount,
                    ]);
                }
            
            
            return back();
            // email....

            // $details = [
            //     'name' => $datauser->firstname.' '.$datauser->lastname,
            //     'amount' => $datadepo->amount,

            //     'id' => $datadepo->transactionId,
            // ];

            // Mail::to($datauser->email)->send(new EmailFunding($details));

            return back();
        } elseif (isset($request->unconfirmid)) {
            DB::table('funds')
                ->where('transactionId', $request->unconfirmid)
                ->update([
                    'status' => 'PENDING',
                    'Admin' => Auth::guard('admin')->user()->username,
                ]);
                 $transaction = DB::table('funds')
                ->where('transactionId', $request->confirmid)
                ->first();
            
            
                // Update User Balance
                $user = User::where('userId', $transaction->userId)->first();
            
                if ($user) {
                    $user->update([
                        'beforeBalance' => $user->currentBalance,
                        'currentBalance' => $user->currentBalance - $transaction->amount,
                    ]);
                }
            
            return back();
        } elseif (isset($request->deleteid)) {
             $transaction = DB::table('funds')
                ->where('transactionId', $request->confirmid)
                ->first();
            
            
                // Update User Balance
                $user = User::where('userId', $transaction->userId)->first();
            
                if ($user) {
                    $user->update([
                        'beforeBalance' => $user->currentBalance,
                        'currentBalance' => $user->currentBalance - $transaction->amount,
                    ]);
                }
            
            DB::table('funds')
                ->where('transactionId', $request->deleteid)
                ->delete();
            return back();
        } else {
            return view('admin.adminfunding')->with('datadeposits', $datadeposits);
        }
    }

    public function search(Request $request)
    {
        $datafund = DB::table('funds')
            ->orderByDesc('id')
            ->paginate(20);
        $datadeposits = DB::table('funds')
            ->orderByDesc('id')
            ->paginate(15);

        $query = $request->input('query');

        if ($query != null) {
            $datas = DB::table('funds')
                ->where('name', 'LIKE', "%$query%")
                ->orWhere('status', 'LIKE', "%$query%")
                ->orWhere('transactionId', 'LIKE', "%$query%")
                ->orWhere('email', 'LIKE', "%$query%")
                ->orderByDesc('id')
                ->get();
            return view('admin.adminfunding')
                ->with('query', $query)
                ->with('datas', $datas)
                ->with('datafund', $datafund);
        } else {
            if (isset($request->confirmid)) {
                DB::table('funds')
                    ->where('transactionId', $request->confirmid)
                    ->update([
                        'status' => 'success',
                        'Admin' => Auth::guard('admin')->user()->username,
                    ]);
                return back();
            } elseif (isset($request->unconfirmid)) {
                DB::table('funds')
                    ->where('transactionId', $request->unconfirmid)
                    ->update([
                        'status' => 'PENDING',
                        'Admin' => Auth::guard('admin')->user()->username,
                    ]);
                return back();
            } elseif (isset($request->deleteid)) {
                DB::table('funds')
                    ->where('transactionId', $request->deleteid)
                    ->delete();

                return back();
            } else {
                return view('admin.adminfunding')->with('datadeposits', $datadeposits);
            }
        }
    }
}

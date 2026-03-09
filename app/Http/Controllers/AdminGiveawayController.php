<?php

namespace App\Http\Controllers;

use App\Mail\EmailFunding;
use App\Models\deposit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminGiveawayController extends Controller
{
    public function __construct()
    {
        $this->middleware('subadmin');
    }

    public function index(Request $request)
    {
        $giveawayusers = DB::table('give_away_contacts')
            ->orderByDesc('id')
            ->paginate(20);

        if (isset($request->confirmid)) {
            DB::table('give_away_contacts')
                ->where('transactionId', $request->confirmid)
                ->update([
                    'status' => 'success',
                    'Admin' => Auth::guard('admin')->user()->username,
                ]);
            return back();
        } elseif (isset($request->unconfirmid)) {
            DB::table('give_away_contacts')
                ->where('transactionId', $request->unconfirmid)
                ->update([
                    'status' => 'PENDING',
                    'Admin' => Auth::guard('admin')->user()->username,
                ]);
            return back();
        } elseif (isset($request->deleteid)) {
            DB::table('give_away_contacts')
                ->where('id', $request->deleteid)
                ->delete();
            return back();
        } else {
            return view('admin.admingiveaway')->with('giveawayusers', $giveawayusers);
        }
    }

    public function search(Request $request)
    {
        $giveawayusers = DB::table('give_away_contacts')
            ->orderByDesc('id')
            ->paginate(20);
                       

        $query = $request->input('query');

        if ($query != null) {
            $datas = DB::table('give_away_contacts')
                ->where('name', 'LIKE', "%$query%")
                ->orWhere('phone', 'LIKE', "%$query%")
                ->orWhere('is_win', 'LIKE', "%$query%")
                ->orWhere('lucky_number', 'LIKE', "%$query%")
                 ->orWhere('user_id', 'LIKE', "%$query%")
                ->orderByDesc('id')
                ->get();
            return view('admin.admingiveaway')
                ->with('query', $query)
                ->with('datas', $datas)
                ->with('giveawayusers', $giveawayusers);
        } else {
            if (isset($request->confirmid)) {
                DB::table('give_away_contacts')
                    ->where('transactionId', $request->confirmid)
                    ->update([
                        'status' => 'success',
                        'Admin' => Auth::guard('admin')->user()->username,
                    ]);
                return back();
            } elseif (isset($request->unconfirmid)) {
                DB::table('give_away_contacts')
                    ->where('transactionId', $request->unconfirmid)
                    ->update([
                        'status' => 'PENDING',
                        'Admin' => Auth::guard('admin')->user()->username,
                    ]);
                return back();
            } elseif (isset($request->deleteid)) {
            DB::table('give_away_contacts')
                ->where('id', $request->deleteid)
                ->delete();
            return back();
            } else {
                return view('admin.admingiveaway')->with('giveawayusers', $giveawayusers);
            }
        }
    }
}

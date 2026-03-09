<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class alladmin extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('admin');
    }
    public function index(Request $request)
    {
        $datausers = DB::table('admins')
            ->orderByDesc('id')
            ->paginate(10);

        if (isset($request->lockid)) {
            DB::table('admins')
                ->where('admin_id', $request->lockid)
                ->update(['status' => 'Block']);
            return back();
        } elseif (isset($request->unlockid)) {
            DB::table('admins')
                ->where('admin_id', $request->unlockid)
                ->update(['status' => 'Active']);
            return back();
        } elseif (isset($request->deleteid)) {
            DB::table('admins')
                ->where('admin_id', $request->deleteid)
                ->delete();
            return back();
        } else {
            return view('admin.alladmin')->with('datausers', $datausers);
        }
    }
}

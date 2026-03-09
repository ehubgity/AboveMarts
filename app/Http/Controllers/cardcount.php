<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class cardcount extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('subadmin');
    }

    public function index()
    {
        $mtn100 = DB::table('cards')->where('amount', 100)->where('network', 'mtn')->where('userId', 0)->count();
        $mtn200 = DB::table('cards')->where('amount', 200)->where('network', 'mtn')->where('userId', 0)->count();
        $mtn500 = DB::table('cards')->where('amount', 500)->where('network', 'mtn')->where('userId', 0)->count();
        $airtell00 = DB::table('cards')->where('amount', 100)->where('network', 'airtel')->where('userId', 0)->count();
        $airtel200 = DB::table('cards')->where('amount', 200)->where('network', 'airtel')->where('userId', 0)->count();
        $airtel500 = DB::table('cards')->where('amount', 500)->where('network', 'airtel')->where('userId', 0)->count();
        $glol00 = DB::table('cards')->where('amount', 100)->where('network', 'glo')->where('userId', 0)->count();
        $glo200 = DB::table('cards')->where('amount', 200)->where('network', 'glo')->where('userId', 0)->count();
        $glo500 = DB::table('cards')->where('amount', 500)->where('network', 'glo')->where('userId', 0)->count();
        $mobilel00 = DB::table('cards')->where('amount', 100)->where('network', '9mobile')->where('userId', 0)->count();
        $mobile200 = DB::table('cards')->where('amount', 200)->where('network', '9mobile')->where('userId', 0)->count();
        $mobile500 = DB::table('cards')->where('amount', 500)->where('network', '9mobile')->where('userId', 0)->count();

        return view('admin.cardcount')->with('mtn100', $mtn100)->with('mtn200', $mtn200)->with('mtn500', $mtn500)
        ->with('airtell00', $airtell00)->with('airtel200', $airtel200)->with('airtel500', $airtel500)
        ->with('glol00', $glol00)->with('glo200', $glo200)->with('glo500', $glo500)
        ->with('mobilel00', $mobilel00)->with('mobile200', $mobile200)->with('mobile500', $mobile500);
   
    }
    
}

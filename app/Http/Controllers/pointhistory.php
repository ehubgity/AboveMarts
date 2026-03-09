<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class pointhistory extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $data = DB::table('points')->where('username', auth()->user()->username)->orderByDesc('id')->get();
        return view('user.pointhistory')->with('data', $data);
    }
}

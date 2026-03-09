<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class support extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function randomDigit()
    {
        $pass = substr(str_shuffle("0123453343645451089"), 0, 15);
        return $pass;
    }

    public function index(){
        $datas = DB::table('supports')->where('userId', auth()->user()->userId)
                ->orderByDesc('id')->take(5)->get();
        return view('user.support')->with('datas',  $datas);
    }

    public function store(Request $request){
        DB::table('supports')->insert([
            'ticketId' => $this->randomDigit(),
            'userId' => auth()->user()->userId,
            'email' => $request->email,
            'title' => $request->subject,
            'msg' => $request->message,
            'status' => 'PENDING',
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s'),
        ]);
        return back()->with('toast_success', 'Ticket Has Been Created !!');
    }
}

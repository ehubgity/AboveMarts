<?php

namespace App\Http\Controllers;

use App\Models\admin as ModelsAdmin;
use App\Models\admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class createpin extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
         
            
        $datas = DB::table('epins')->orderByDesc('id')->paginate(15);

        return view('admin.createpin')->with('datas', $datas);
    }
    public function rand()
    {
        return substr(rand(0, 10000000) . time(), 0, 15);
    }
    public function store(Request $request)
    {

        for ($i=0; $i < $request->quantity; $i++) { 
            # code...
            DB::table('epins')->insert([
                'pinId' => $this->rand(),
                'amount' => $request->amount,
                'discount' => 0,
                'quantity' => $request->quantity,
                'status' => 'CONFIRM',
                'dayCounter' => 0,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ]);
       

        }

       
        return back()->with('toast_success', 'E-Pin Created!!');
    }
}

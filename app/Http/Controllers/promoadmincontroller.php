<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\deposit;
use App\Models\promo;
use App\Models\package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class promoadmincontroller extends Controller

{
    public function __construct()
            {
                $this->middleware(['admin']);
            }

    public function index(){
       
        return view ('admin.promoadmin');
    }

    public function randomDigit(){
        $pass = substr(str_shuffle("0123456789abcnost"), 0, 12);
        return $pass;
    }
    
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'amount'=>'required',
            'username' => 'required',
            ]);
       if($validator->fails()){
        return back()->with('toast_error', $validator->messages()->all()[0])->withInput();

       }
       $datauser = DB::table('users')->where('username', $request->username)->first();
        if($datauser == null){
            return back()->with('toast_error', 'Invalid Username !!');
        }else{
            $amountOld = DB::table('promos')->where('userId', $datauser->userId)->sum('amount');
            $newamout = $request->amount + $amountOld;
            if (DB::table('promos')->where('userId', $datauser->userId)->exists()) {
                DB::table('promos')->where('userId', $datauser->userId)->update([ 'amount' => $newamout,]);
            return back()->with('toast_success', 'Transaction Successfull !!');

            }else{
                DB::table('promos')
                    ->insert([
                    'promoId' => $this->randomDigit(),
                    'userId' => $datauser->userId,
                    'username' => $datauser->username,
                    'email' => $datauser->email,
                    'phone' => $datauser->phoneNumber,
                    'discount' => 0,
                    'amount' => $request->amount,
                    'status' => 'CONFIRM',
                    "created_at" =>  date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s'),
                    
        ]);
        return back()->with('toast_success', 'Transaction Successfull !!');
            }
            
        
    }
      
       
    }
}

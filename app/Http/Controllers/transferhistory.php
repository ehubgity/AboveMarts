<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class transferhistory extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('subadmin');
    }

    public function index(Request $request){
                $datawithdraws = DB::table('withdraws')->where('paymentType','!=' , 'wallet')
                ->orderByDesc('id')->paginate(10);
                
                if(isset($request->confirmid)){
                            $datawithdraw = DB::table('withdraws')
                        ->where('transactionId', $request->confirmid)
                        ->first();
                        
                        $datawithuser = DB::table('users')
                        ->where('userId', $datawithdraw->userId)
                        ->first();
                    DB::table('withdraws')
                    ->where('transactionId', $request->confirmid)
                    ->update(['status' => 'CONFIRM']);
                    //email.....

                    $details = [
                        'name' => $datawithuser->firstname. ' ' .$datawithuser->lastname,
                        'amount' => $datawithdraw->amount,
                        'wallet' => $datawithdraw->wallet_address,
                        'id' => $datawithdraw->transactionId,
                    ]; 

                    // Mail::to($datawithuser->email)->send(new EmailWithdraw($details));
                    return back()->with('toast_success', 'Successful');
                
                }elseif(isset($request->unconfirmid)){
                        DB::table('withdraws')
                            ->where('transactionId', $request->unconfirmid)
                            ->update(['status' => 'PENDING']);
                            return back()->with('toast_success', 'Successful');
        
                    }elseif(isset($request->deleteid)){
                        DB::table('withdraws')
                        ->where('transactionId', $request->deleteid)
                        ->delete();
                        return back()->with('toast_success', 'Successful');
        
                    }else{
                    return view('admin.withdrawal')->with('datawithdraws', $datawithdraws);  

                }

        
    }
}

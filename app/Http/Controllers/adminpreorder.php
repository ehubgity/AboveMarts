<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class adminpreorder extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('subadmin');
    }

    public function index(Request $request){

                $datapreorders = DB::table('preordercards')->orderByDesc('id')
                ->paginate(15);


                if(isset($request->confirmid)){
                    $datacard = DB::table('preordercards')->where('transactionId', $request->confirmid)->first();
                    DB::table('preordercards')
                    ->where('transactionId', $request->confirmid)
                    ->update(['status' => 'CONFIRM']);

                    DB::table('usedcards')
                    ->where('cardId', $datacard->cardId)
                    ->update(['status' => 'CONFIRM']);
                    // email....
        
                    // $details = [
                    //     'name' => $datauser->firstname.' '.$datauser->lastname,
                    //     'amount' => $datadepo->amount,
                        
                    //     'id' => $datadepo->transactionId,
                    // ]; 
        
                    // Mail::to($datauser->email)->send(new EmailFunding($details));
        
                    return back()->with('toast_success', 'Transaction Successfull !!');
                
                }elseif(isset($request->unconfirmid)){
                        DB::table('preordercards')
                            ->where('transactionId', $request->unconfirmid)
                            ->update(['status' => 'PENDING']);
                            return back()->with('toast_success', 'Transaction Successfull !!');
        
                    }elseif(isset($request->deleteid)){
                        DB::table('preordercards')
                        ->where('transactionId', $request->deleteid)
                        ->delete();
                        return back()->with('toast_success', 'Transaction Successfull !!');
        
                    }else{
                    return view('admin.preordercard')->with('datapreorders', $datapreorders);  
        
                }

        
    }
}

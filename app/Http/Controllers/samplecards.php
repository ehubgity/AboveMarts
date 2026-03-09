<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class samplecards extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('user.samplecards');
    }

    public function randomDigit()
    {
        $pass = substr(str_shuffle("01234561089abcDEfadsasfdasdfasdsfa3425542FGHIJnostXYZ"), 0, 30);
        return $pass;
    }

    public function randomDigit16()
    {
        $pass = substr(str_shuffle("01234561089abcDEfadsasfdasdfasdsfa3425542FGHIJnostXYZ"), 0, 16);
        return $pass;
    }

    public function store(Request $request)
    {
// sample
            if($request->network == 'None'){
                return back()->with('toast_error', 'Select Network');
            }else{
                    $cardId =  $this->randomDigit16();
                    DB::table('rechargeprintings')
                    ->where('userId', auth()->user()->userId)
                    ->insert([
                        'transactionId' => $this->randomDigit(),
                        'userId' => auth()->user()->userId,
                        'username' => auth()->user()->username,
                        'email' => auth()->user()->email,
                        'phoneNumber' => auth()->user()->phoneNumber,
                        'amount' => $request->amount,
                        'network' => $request->network,
                        'networkPlan' => $request->network,
                        'businessName' => $request->businessName,
                        'photo' => 'Sample',
                        'quantity' => $request->quantity,
                        'status' => 'CONFIRM',
                        'cost' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s'),
                    ]);

                    $data = DB::table('rechargeprintings')
                        ->where('userId', auth()->user()->userId)
                        ->orderBy('id', 'desc')
                        ->first();
                    return redirect()->route('cardprinting', ['id' => $data->transactionId]);
                }
    }
}

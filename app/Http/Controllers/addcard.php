<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class addcard extends Controller
{
    public function __construct()
            {
                $this->middleware(['admin']);
            }

    public function index(){
       
        return view ('admin.addcard');
    }

    public function randomDigit(){
        $pass = substr(str_shuffle("0123456789abcnost"), 0, 12);
        return $pass;
    }
    
    public function store(Request $request){
        $file = $request->file('file');
        $contents = file_get_contents($file->getRealPath());
        $cards = explode("\n", $contents);

        foreach ($cards as $card){
            $data = explode(',', $card);
            $pin = $data[0];
            $serialNumber = $data[1];
            $encryptedpin =  Crypt::encryptString($pin);

            DB::table('cards')
            ->insert([
            'transactionId' => $this->randomDigit(),
            'pin' => $encryptedpin,
            'serialNumber' =>  $serialNumber,
            'amount' =>$request->amount,
            'network' =>$request->network,
            'userId' => $request->userId,
            'cardId' =>  $request->cardId,
            'status' =>  $request->status,
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s'),
        ]);

        }
            
        DB::table('usedcards')->where('cardId', $request->cardId)->update([
            'status' => 'CONFIRM',
        ]);
        return back()->with('toast_success', 'Created Successfull !!');
        
    }
}

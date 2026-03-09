<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class usedcardhistory extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('subadmin');
    }

    public function index(Request $request){
                $datadeposits = DB::table('usedcards')->orderByDesc('id')
                ->paginate(20);

                    if(isset($request->deleteid)){
                        DB::table('usedcards')
                        ->where('transactionId', $request->deleteid)
                        ->delete();
                        return back();
        
                    }else{
                    return view('admin.usedcard')->with('datadeposits', $datadeposits);  
        
                }

        
    }

    public function search(Request $request){
        $datausedcard = DB::table('usedcards')->orderByDesc('id')
               ->paginate(20);
        $datadeposits = DB::table('usedcards')->orderByDesc('id')
               ->paginate(20);

       $query = $request->input('query');

       if ($query != null){
        $datas = DB::table('usedcards')->where('username', 'LIKE', "%$query%")->orWhere('network', 'LIKE', "%$query%")->orWhere('status', 'LIKE', "%$query%")
        ->get();
        return view('admin.usedcard')->with('query', $query)->with('datas', $datas)->with('datausedcard', $datausedcard);
       }

       else{

        if(isset($request->deleteid)){
                DB::table('usedcards')
                ->where('transactionId', $request->deleteid)
                ->delete();
                return back();

            }else{
            return view('admin.usedcard')->with('datadeposits', $datadeposits);  

        }

       }
       
   }
}

<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class member extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        
        $downlines = DB::table('downlines')->where('owner', auth()->user()->username)->orderByDesc('id')
        ->paginate(5);
        return view('user.members')->with('downlines', $downlines);
    }

    public function search(Request $request){
        $downlines = DB::table('downlines')->where('owner', auth()->user()->username)->orderByDesc('id') ->paginate(5);

      $owner = auth()->user()->username;
        $searchQuery =    $request->input('query');
 // Replace 'your_search_query_here' with the actual search query
        
        $datas = DB::table('downlines')
            ->where('owner', $owner)
            ->where(function($innerQuery) use ($searchQuery) {
                $innerQuery->where('downline', 'LIKE', "%$searchQuery%")
                      ->orWhere('fullname', 'LIKE', "%$searchQuery%")
                      ->orWhere('email', 'LIKE', "%$searchQuery%")
                      ->orWhere('package', 'LIKE', "%$searchQuery%")
                      ->orWhere('rank', 'LIKE', "%$searchQuery%")
                      ->orWhere('phoneNumber', 'LIKE', "%$searchQuery%");
            })
            ->orderByDesc('id')
            ->get();
       return view('user.members')->with('query', $searchQuery)->with('datas', $datas)->with('downlines', $downlines);
   }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class downline extends Controller
{

    public function downline()
    {
        $datasponsor = DB::table('users')
            ->where('mySponsorId', auth()->user()->sponsor)
            ->first();
        $fullname =  auth()->user()->lastName + ' ' + auth()->user()->firstName;
        // return dd( $datasponsor->downlineOne);
        if (
            User::where('mySponsorId', auth()->user()->sponsor)->exists()
        ) {

            DB::table('downlines')->insert([
                'userId' => $datasponsor->userId,
                'owner' => auth()->user()->sponsor,
                'downline' => auth()->user()->mySponsorId,
                'fullname' => $fullname,
                'email' => auth()->user()->email,
                'rank' => auth()->user()->rank,
                'package' => auth()->user()->package,
                'status' => auth()->user()->status,
            ]);
            
        } else{
            DB::table('users')
                ->where('mySponsorId', auth()->user()->sponsor)
                ->update(['downlineOne' => 'Admin']);
        }
    }
}

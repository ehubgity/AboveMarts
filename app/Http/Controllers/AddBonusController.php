<?php

namespace App\Http\Controllers;

use App\Models\bonus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddBonusController extends Controller
{
    public function __construct()
    {
        $this->middleware(['admin']);
    }

    public function index()
    {
        return view('admin.addbonus');
    }

    public function randomDigit()
    {
        $pass = substr(str_shuffle("0123456789abcnost"), 0, 12);
        return $pass;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sponsor' => 'required',
            'sponsorId' => 'required',
            'package' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('errors', $validator->messages()->all()[0])
                ->withInput();
        }

        $user = User::where('mySponsorId', $request->sponsorId)->first();
        $sponsor = User::where('mySponsorId', $request->sponsor)->first();


        if ($sponsor == null) {
            return back()->with('toast_error', 'Invalid Sponsor ');
        } else {
            bonus::create([
                'bonusId' => $this->randomDigit(),
                'sponsor' => $request->sponsor,
                'sponsorId' => $request->sponsorId,
                'username' => $sponsor->username,
                'email' => $sponsor->email,
                'amount' => $request->amount,
                'package' => $request->package,
                'status' => 'CONFIRM',
                'dayCounter' => 0,
            ]);
            return back()->with('toast_success', 'Transaction Successfull !!');
        }
    }
}

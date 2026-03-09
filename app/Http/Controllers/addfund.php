<?php

namespace App\Http\Controllers;

use App\Helpers\TransactionHelper;
use App\Models\deposit;
use App\Models\package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class addfund extends Controller
{
    public function __construct()
    {
        $this->middleware(['admin']);
    }

    public function index()
    {
        return view('admin.walletfund');
    }

    public function randomDigit()
    {
        $pass = substr(str_shuffle("0123456789abcnost"), 0, 12);
        return $pass;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'username' => 'required',
        ]);
        if ($validator->fails()) {
            return back()
                ->with('toast_error', $validator->messages()->all()[0])
                ->withInput();
        }
        $datauser = DB::table('users')
            ->where('username', $request->username)
            ->first();

        if ($datauser == null) {
            return back()->with('toast_error', 'Invalid Username');
        } else {
            DB::table('funds')->insert([
                'transactionId' => $this->randomDigit(),
                'userId' => $datauser->userId,
                'name' => $datauser->username,
                'email' => $datauser->email,
                'amount' => $request->amount,
                'status' => 'PENDING',
                'paymentType' => 'Admin',
                'accountName' => 'Admin',
                'accountNumber' => 'Admin',
                'bankName' => 'Admin',
                'Admin' => Auth::guard('admin')->user()->username,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ]);

            DB::table('transactions')->insert([
                'transactionId' => $this->randomDigit(),
                'userId' => $datauser->userId,
                'username' => $datauser->username,
                'email' => $datauser->email,
                'phoneNumber' => $datauser->phoneNumber,
                'amount' => $request->amount,
                'transactionType' => 'Deposit',
                'transactionService' => 'Funding Wallet',
                'status' => 'PENDING',
                'paymentMethod' => 'Admin',
                'Admin' => Auth::guard('admin')->user()->username,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ]);
            // TransactionHelper::updateAccountManagerTotals(auth()->user()->userId, $request->amount, 'Deposit');

            return back()->with('toast_success', 'Transaction Successfull !!');
        }
    }
}

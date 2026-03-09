<?php

namespace App\Http\Controllers;

use App\Helpers\TransactionHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class adminBuyCard extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        return view('admin.adminbuycard');
    }

    public function randomDigit()
    {
        $pass = substr(str_shuffle("01234561089abcDEfadsasfdasdfasdsfa3425542FGHIJnostXYZ"), 0, 30);
        return $pass;
    }

    public function store(Request $request)
    {
        $api = getenv('TELECOM_API');
        $phoneNumber = $request->phoneNumber;
        $productCode = $request->package;
        $amount = $request->amount;
        $ch = curl_init();

        curl_setopt(
            $ch,
            CURLOPT_URL,
            "https://smartrecharge.ng/api/v2/airtime/?api_key={$api}&product_code={$productCode}&phone={$phoneNumber}&amount={$amount}"
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POST, true);
        $response = curl_exec($ch);
        $response = json_decode($response);

        if ($response->status == true) {
            DB::table('rcpurchases')->insert([
                'transactionId' => $this->randomDigit(),
                'userId' => 'Admin',
                'username' => 'Admin',
                'email' => 'Admin',
                'phoneNumber' => $request->phoneNumber,
                'amount' => $request->amount,
                'network' => $request->package,
                'status' => 'CONFIRM',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ]);

            DB::table('transactions')->insert([
                'transactionId' => $this->randomDigit(),
                'userId' => 'Admin',
                'username' => 'Admin',
                'email' => 'Admin',
                'phoneNumber' => $request->phoneNumber,
                'amount' => $request->amount,
                'transactionType' => 'Recharge Card',
                'transactionService' => $request->package,
                'status' => 'CONFIRM',
                'paymentMethod' => 'Admin',
                'Admin' => Auth::guard('admin')->user()->username,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ]);
            TransactionHelper::updateAccountManagerTotals(auth()->user()->userId, $request->amount, 'Recharge Card');

            // email......
            // $userData = DB::table('users')
            //     ->where('userId', auth()->user()->username)
            //     ->first();

            // $details = [
            //     'name' => auth()->user()->firstName . ' ' . auth()->user()->lastName,
            //     'amount' => $request->amount,
            //     'network' => $request->package,
            //     'date' => date('Y-m-d H:i:s'),
            // ];
            // Mail::to(auth()->user()->email)->send(new recharge($details));
            return back()->with('toast_success', 'Transaction Successful !!');
        }
    }
}

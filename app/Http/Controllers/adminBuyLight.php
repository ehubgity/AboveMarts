<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class adminBuyLight extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        return view('admin.adminbuylight');
    }

    public function store(Request $request)
    {
        if ($request->package == 'none') {
            return back()->with('toast_error', 'Select an electricity services');
        } else {
            $api = getenv('TELECOM_API');
            $productCode = $request->package;
            $amount = $request->amount;
            $meterNumber = $request->meterNumber;

            $ch = curl_init();

            // $curl = curl_init();

            curl_setopt(
                $ch,
                CURLOPT_URL,
                "https://smartrecharge.ng/api/v2/electric/?api_key={$api}&meter_number={$meterNumber}&product_code={$productCode}&task=verify"
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POST, true);
            $result = curl_exec($ch);

            $result = json_decode($result);
            // return dd($result->status);
            if ($result->status == true) {
                curl_setopt(
                    $ch,
                    CURLOPT_URL,
                    "https://smartrecharge.ng/api/v2/electric/?api_key={$api}&product_code={$productCode}&meter_number={$meterNumber}&amount={$amount}"
                );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POST, true);
                $response = curl_exec($ch);
                $response = json_decode($response);

                // return dd($response);

                if ($response->status == true) {
                    DB::table('lightpurchases')->insert([
                        'transactionId' => $this->randomDigit(),
                        'userId' => "Admin",
                        'username' => "Admin",
                        'email' => "Admin",
                        'phoneNumber' => "Admin",
                        'amount' => $request->amount,
                        'meter' => $request->meterNumber,
                        'product' => $request->package,
                        'status' => 'CONFIRM',
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s'),
                    ]);
                    DB::table('transactions')->insert([
                        'transactionId' => $this->randomDigit(),
                        'userId' => "Admin",
                        'username' => "Admin",
                        'email' => "Admin",
                        'phoneNumber' => $request->phoneNumber,
                        'amount' => $request->amount,
                        'transactionType' => 'Electricity',
                        'transactionService' => $request->package,
                        'status' => 'CONFIRM',
                        'paymentMethod' => 'Admin',
                        'Admin' => Auth::guard('admin')->user()->username,

                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s'),
                    ]);
                    return back()->with('toast_success', 'Transaction Successful !!');
                } else {
                    return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                }
            } else {
                return back()->with('toast_error', 'Oops!!, Meter number failed to verify');
            }
        }
    }
}

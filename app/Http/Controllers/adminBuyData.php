<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class adminBuyData extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        return view('admin.adminbuydata');
    }

    public function randomDigit()
    {
        $pass = substr(str_shuffle("01234561089abcDEfadsasfdasdfasdsfa3425542FGHIJnostXYZ"), 0, 30);
        return $pass;
    }
    public function store(Request $request)
    {
        if ($request->network == 'mtn') {
            $api = getenv('TELECOM_API');
            $phoneNumber = $request->phoneNumber;
            $productCode = $request->packageMTN;
            $amount = $request->amount;
            $ch = curl_init();
            curl_setopt(
                $ch,
                CURLOPT_URL,
                "https://smartrecharge.ng/api/v2/directdata/?api_key={$api}&product_code={$productCode}&phone={$phoneNumber}"
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POST, true);
            $result = curl_exec($ch);
            $response = json_decode($result);

            if ($response->status == true) {
                DB::table('datapurchases')->insert([
                    'transactionId' => $this->randomDigit(),
                    'userId' => "Admin",
                    'username' => "Admin",
                    'email' => "Admin",
                    'phoneNumber' => $request->phoneNumber,
                    'amount' => $request->amount,
                    'network' => $request->network,
                    'product' => $request->amount,
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
                    'transactionType' => 'Data Purchase',
                    'transactionService' => $request->network,
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
        } elseif ($request->network == 'glo') {
            $api = getenv('TELECOM_API');
            $phoneNumber = $request->phoneNumber;
            $productCode = $request->packageGLO;
            $amount = $request->amount;
            $ch = curl_init();
            curl_setopt(
                $ch,
                CURLOPT_URL,
                "https://smartrecharge.ng/api/v2/directdata/?api_key={$api}&product_code={$productCode}&phone={$phoneNumber}"
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POST, true);
            $result = curl_exec($ch);
            $response = json_decode($result);
            if ($response->status == true) {
                DB::table('datapurchases')->insert([
                    'transactionId' => $this->randomDigit(),
                    'userId' => "Admin",
                    'username' => "Admin",
                    'email' => "Admin",
                    'phoneNumber' => $request->phoneNumber,
                    'amount' => $request->amount,
                    'network' => $request->network,
                    'product' => $request->amount,
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
                    'transactionType' => 'Data Purchase',
                    'transactionService' => $request->network,
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
        } elseif ($request->network == 'airtel') {
            $api = getenv('TELECOM_API');
            $phoneNumber = $request->phoneNumber;
            $productCode = $request->packageAirtel;
            $amount = $request->amount;
            $ch = curl_init();
            curl_setopt(
                $ch,
                CURLOPT_URL,
                "https://smartrecharge.ng/api/v2/directdata/?api_key={$api}&product_code={$productCode}&phone={$phoneNumber}"
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POST, true);
            $result = curl_exec($ch);
            $response = json_decode($result);
            if ($response->status == true) {
                DB::table('datapurchases')->insert([
                    'transactionId' => $this->randomDigit(),
                    'userId' => "Admin",
                    'username' => "Admin",
                    'email' => "Admin",
                    'phoneNumber' => $request->phoneNumber,
                    'amount' => $request->amount,
                    'network' => $request->network,
                    'product' => $request->amount,
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
                    'transactionType' => 'Data Purchase',
                    'transactionService' => $request->network,
                    'status' => 'CONFIRM',
                    'paymentMethod' => 'wallet',
                    'Admin' => Auth::guard('admin')->user()->username,
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s'),
                ]);
                return back()->with('toast_success', 'Transaction Successful !!');
            } else {
                return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
            }
        } elseif ($request->network == '9mobile') {
            $api = getenv('TELECOM_API');
            $phoneNumber = $request->phoneNumber;
            $productCode = $request->package9MOBILE;
            $amount = $request->amount;
            $ch = curl_init();
            curl_setopt(
                $ch,
                CURLOPT_URL,
                "https://smartrecharge.ng/api/v2/directdata/?api_key={$api}&product_code={$productCode}&phone={$phoneNumber}"
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POST, true);
            $result = curl_exec($ch);
            $response = json_decode($result);
            if ($response->status == true) {
                DB::table('datapurchases')->insert([
                    'transactionId' => $this->randomDigit(),
                    'userId' => "Admin",
                    'username' => "Admin",
                    'email' => "Admin",
                    'phoneNumber' => $request->phoneNumber,
                    'amount' => $request->amount,
                    'network' => $request->network,
                    'product' => $request->amount,
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
                    'transactionType' => 'Data Purchase',
                    'transactionService' => $request->network,
                    'status' => 'CONFIRM',
                    'paymentMethod' => 'wallet',
                    'Admin' => Auth::guard('admin')->user()->username,
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s'),
                ]);
                return back()->with('toast_success', 'Transaction Successful !!');
            } else {
                return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
            }
        } else {
            return back()->with('toast_error', 'Contact Admin');
        }
    }
}

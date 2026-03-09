<?php

namespace App\Http\Controllers;

use App\Helpers\TransactionHelper;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Paystack;
use Unicodeveloper\Paystack\Facades\Paystack as FacadesPaystack;
use Illuminate\Support\Facades\Http;
use App\Models\User;


class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function randomDigit()
    {
        $pass = substr(str_shuffle("01234561089abcDEfadsasfdasdfasdsfa3425542FGHIJnostXYZ"), 0, 30);
        return $pass;
    }
    /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function redirectToGateway()
    {
        try {
            return FacadesPaystack::getAuthorizationUrl()->redirectNow();
        } catch (\Exception $e) {
            return Redirect::back()->withMessage([
                'msg' => 'The paystack token has expired. Please refresh the page and try again.',
                'type' => 'error',
            ]);
        }
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback(Request $request)
    {
        try {
            $paymentDetails = FacadesPaystack::getPaymentData();
            if ($paymentDetails['status'] == true) {
                $amount = $paymentDetails['data']['amount'] / 100;

                if ($amount >= 2500) {
                    $x = $amount - 100;
                    $fee = $x / 1.015;

                    DB::table('funds')->insert([
                        'transactionId' => $paymentDetails['data']['reference'],
                        'userId' => auth()->user()->userId,
                        'name' => auth()->user()->username,
                        'email' => $paymentDetails['data']['customer']['email'],
                        'amount' => $fee,
                        'accountName' => "None",
                        'accountNumber' => "None",
                        'bankName' => "None",
                        'paymentType' => 'wallet',
                        'Admin' => 'None',
                        'status' => 'success',
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s'),
                    ]);

                    DB::table('transactions')->insert([
                        'transactionId' => $paymentDetails['data']['reference'],
                        'userId' => auth()->user()->userId,
                        'username' => auth()->user()->username,
                        'email' => auth()->user()->email,
                        'phoneNumber' => auth()->user()->phoneNumber,
                        'amount' => $fee,
                        'transactionType' => 'Deposit',
                        'transactionService' => 'Funding Wallet',
                        'status' => 'CONFIRM',
                        'paymentMethod' => 'wallet',
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s'),
                    ]);
                    TransactionHelper::updateAccountManagerTotals(auth()->user()->userId, $request->amount, 'Deposit');

                    return back()->with('toast_success', 'Transaction Successful !!');
                } else {
                    $fee = $amount / 101;

                    DB::table('funds')->insert([
                        'transactionId' => $paymentDetails['data']['reference'],
                        'userId' => auth()->user()->userId,
                        'name' => auth()->user()->username,
                        'email' => $paymentDetails['data']['customer']['email'],
                        'amount' => $amount - $fee,
                        'accountName' => "None",
                        'accountNumber' => "None",
                        'bankName' => "None",
                        'paymentType' => 'wallet',
                        'status' => 'success',
                        'Admin' => 'None',
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s'),
                    ]);

                    DB::table('transactions')->insert([
                        'transactionId' => $paymentDetails['data']['reference'],
                        'userId' => auth()->user()->userId,
                        'username' => auth()->user()->username,
                        'email' => auth()->user()->email,
                        'phoneNumber' => auth()->user()->phoneNumber,
                        'amount' => $amount - $fee,
                        'transactionType' => 'Deposit',
                        'transactionService' => 'Funding Wallet',
                        'status' => 'CONFIRM',
                        'paymentMethod' => 'wallet',
                        'Admin' => 'None',
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s'),
                    ]);
                    TransactionHelper::updateAccountManagerTotals(auth()->user()->userId, $request->amount, 'Deposit');

                    //  Update User Balance
                    $user = User::findOrFail(auth()->id());

                    $user->update([
                        'beforeBalance' =>  $user->currentBalance,
                        'currentBalance' => $user->currentBalance + $request->amount,
                    ]);

                    return back()->with('toast_success', 'Transaction Successful !!');
                }
            } else {
                return back()->with('toast_error', 'Failed transaction');
            }
        } catch (Exception $e) {
            // Exception handling
            return back()->with('toast_error', 'Failed transaction, Contact Admin');
        }
        // amount rounded

        // Now you have the payment details,
        // you can store the authorization_code in your db to allow for recurrent subscriptions
        // you can then redirect or do whatever you want
    }

    public function manualpay(Request $request)
    {
        $headers = [
            'Authorization' => 'Bearer sandbox_sk_09aa059be981a22e6c164deeaeb479084558df4ee60b',
            'Content-Type' => 'application/json',
        ];
        // $customerData = [
        //     "customer_identifier" => "CC443O434OC",
        //     "first_name" => "Above E-Business Hub",
        //     "last_name" => "Ayodele",
        //     "mobile_num" => "08139011943",
        //     "email" => "ayo@gmail.com",
        //     "bvn" => "12343211654",
        //     "dob" => "30/10/1990",
        //     "address" => "22 Kota street, UK",
        //     "gender" => "1",
        //     "beneficiary_account" => "4920299492",
        // ];

        // $response = Http::withHeaders($headers)->post(
        //     'https://sandbox-api-d.squadco.com/virtual-account',
        //     $customerData
        // );

        // $customerData = [
        //     "transaction_ref" => $this->randomDigit(),
        //     "amount" => 890000,
        //     "email" => "abioye.et2016@gmail.com",
        // ];

        // $response = Http::withHeaders($headers)->post(
        //     'https://sandbox-api-d.squadco.com/transaction/initiate/transfer/dynamic',
        //     $customerData
        // );

        // $data = $response->json();
        // if ($response->successful()) {
        //     // Request was successful
        //     if ($data['success']) {
        //         // Process successful response data
        //         $virtualAccountName = $data['data']['account_name'];
        //         $virtualAccountNumber = $data['data']['account_number'];
        //         $virtualAccountBank = $data['data']['bank'];

        //         // return dd($data);

        //         $accountData = [
        //             "virtual_account_number" => "3239145486",
        //             "amount" => "1000",
        //             "type" => "dynamic",
        //         ];

        //         // return ($accountData);

        //         $responseData = Http::withHeaders($headers)->post(
        //             'https://sandbox-api-d.squadco.com/virtual-account/simulate/payment',
        //             $accountData
        //         );

        //     } else {
        //         // Handle API error

        //         $errorMessage = $data['message'];
        //         return dd($errorMessage);
        //     }
        // } else {
        //     // Handle HTTP request error
        //     $statusCode = $response->status();
        //     return dd($response);
        // }
        DB::table('funds')->insert([
            'transactionId' => $this->randomDigit(),
            'userId' => auth()->user()->userId,
            'name' => auth()->user()->username,
            'email' => auth()->user()->email,
            'amount' => $request->amountManual,
            'paymentType' => 'wallet',
            'accountName' => $request->accountName,
            'accountNumber' => $request->accountNumber,
            'bankName' => $request->bankName,
            'status' => 'PENDING',
            'Admin' => 'None',
            "created_at" => $request->date,
            "updated_at" => date('Y-m-d H:i:s'),
        ]);

        DB::table('transactions')->insert([
            'transactionId' => $this->randomDigit(),
            'userId' => auth()->user()->userId,
            'username' => auth()->user()->username,
            'email' => auth()->user()->email,
            'phoneNumber' => auth()->user()->phoneNumber,
            'amount' => $request->amountManual,
            'transactionType' => 'Deposit',
            'transactionService' => 'Funding Wallet',
            'status' => 'PENDING',
            'paymentMethod' => 'wallet',
            'Admin' => 'None',
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s'),
        ]);

        DB::table('transactions')->insert([
            'transactionId' => $this->randomDigit(),
            'userId' => auth()->user()->userId,
            'username' => auth()->user()->username,
            'email' => auth()->user()->email,
            'phoneNumber' => auth()->user()->phoneNumber,
            'amount' => $request->feeManual,
            'transactionType' => 'Deposit',
            'transactionService' => 'Funding Wallet',
            'status' => 'CONFIRM',
            'paymentMethod' => 'wallet',
            'Admin' => 'None',
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s'),
        ]);
        TransactionHelper::updateAccountManagerTotals(auth()->user()->userId, $request->amount, 'Deposit');

        return back()->with('toast_success', 'Transaction Successful !!');
    }
    public function getAccount(Request $request)
    {
        try {
            if (auth()->user()->accountNumber == Null) {
                // Your JSON data
                $jsonData = [
                    "username" => getenv('VPAY_USERNAME'),
                    "password" => getenv('VPAY_PASSWORD'),
                ];
                // API endpoint URL
                $endpointUrl = 'https://services2.vpay.africa/api/service/v1/query/merchant/login';

                // Initialize cURL session
                $ch = curl_init($endpointUrl);

                // Set cURL options
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($jsonData));
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'publicKey: ' . getenv('VPAY_API')
                ]);

                // Execute cURL session and get the response
                $response = curl_exec($ch);

                // Check for cURL errors
                if (curl_errno($ch)) {
                    echo 'cURL error: ' . curl_error($ch);
                } else {

                    // Output the response from the server
                    $responseData = json_decode($response, true);
                    $token = $responseData['token'];

                    // Your JSON data
                    $jsonData = [
                        "email" => auth()->user()->email,
                        "phone" => auth()->user()->phoneNumber,
                        "contactfirstname" => auth()->user()->firstName,
                        "contactlastname" => auth()->user()->lastName,
                    ];

                    // API endpoint URL
                    $endpointUrl = 'https://services2.vpay.africa/api/service/v1/query/customer/add';

                    // Initialize cURL session
                    $ch = curl_init($endpointUrl);

                    // Set cURL options
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($jsonData));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'publicKey: ' . getenv('VPAY_API'),
                        'b-access-token: ' . $token
                    ]);


                    // Execute cURL session and get the response
                    $response = curl_exec($ch);
                    // return $response;

                    // Check for cURL errors
                    if (curl_errno($ch)) {
                        echo 'cURL error: ' . curl_error($ch);
                    } else {
                        // Output the response from the server
                        $responseData = json_decode($response, true);
                        $id = $responseData['id'];
                        // Append the data to the URL as query parameters
                        $url = "https://services2.vpay.africa/api/service/v1/query/customer/$id/show";

                        // Initialize cURL session
                        $ch = curl_init($url);

                        // Set cURL options
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                            'Content-Type: application/json',
                            'publicKey: ' . getenv('VPAY_API'),
                            'b-access-token: ' . $token
                        ]);

                        // Execute cURL session and get the response
                        $response = curl_exec($ch);

                        // Check for cURL errors
                        if (curl_errno($ch)) {
                            echo 'cURL error: ' . curl_error($ch);
                        } else {
                            // Output the response from the server
                            $responseData = json_decode($response, true);
                            $accountNumber = $responseData['nuban'];

                            User::where('userId', auth()->user()->userId)->update(['accountNumber' => $accountNumber]);

                            return back()->with('toast_success', 'Account created successful');
                        }
                        // Close cURL session
                        curl_close($ch);
                    }
                    // Close cURL session
                    curl_close($ch);
                }
            } else {
                return back()->with('toast_error', 'You have an account number');
            }
            // Close cURL session
            curl_close($ch);
        } catch (\Throwable $th) {
            return back()->with('toast_error', 'Try Again');

            // return $th->getMessage();

        }
    }
}

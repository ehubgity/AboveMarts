<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class PaymentWebhookController extends Controller
{
  //
//   public function __construct()
//   {
//     $this->middleware('auth');
//   }

  public function index()
  {
    return view('user.webhook');
  }
  public function handleWebhook(Request $request)
  {
    try {
      // Validate incoming request
      $request->validate([
        'reference' => 'required|string',
        'session_id' => 'required|string',
        'amount' => 'required|numeric',
        'account_number' => 'required|string',
      ]);

      // Get the specific values from the payload
      $amount = $request->amount;
      $accountNumber = $request->account_number;
      $reference = $request->reference;
                  $user = DB::table('users')->where('accountNumber', $accountNumber)->first();

      
      if($amount >= 3500){
        $newamount = $amount - ($amount * 1.5/100);
      if ($user != null) {
        // Insert into the 'funds' table
        DB::table('funds')->insert([
          'transactionId' => $reference,
          'userId' => $user->userId,
          'name' => $user->username,
          'email' => $user->email,
          'amount' => $newamount,
          'accountName' => "None",
          'accountNumber' => "None",
          'bankName' => "None",
          'paymentType' => 'wallet',
          'status' => 'success',
          'Admin' => 'None',
          "created_at" => now(),
          "updated_at" => now(),
        ]);

        DB::table('transactions')->insert([
          'transactionId' => $reference,
          'userId' => $user->userId,
          'username' => $user->username,
          'email' => $user->email,
          'phoneNumber' => $user->phoneNumber,
          'amount' => $newamount,
          'transactionType' => 'Deposit',
          'transactionService' => 'Funding Wallet',
          'status' => 'CONFIRM',
          'paymentMethod' => 'wallet',
          "created_at" => date('Y-m-d H:i:s'),
          "updated_at" => date('Y-m-d H:i:s'),
        ]);

        // Respond with a JSON success message
        \Illuminate\Support\Facades\Log::info('Webhook processed successfully', [
          'reference' => $reference,
          'amount' => $amount,
          'account_number' => $accountNumber,
          'user_id' => optional($user)->userId,
        ]);

        return response()->json(['status' => 'Payment webhook received successfully']);
      } else {
        return response()->json(['status' => 'No User found']);
      }
      }else{
        $newamount = $amount - ($amount * 1/100);

      if ($user != null) {
        // Insert into the 'funds' table
        DB::table('funds')->insert([
          'transactionId' => $reference,
          'userId' => $user->userId,
          'name' => $user->username,
          'email' => $user->email,
          'amount' => $newamount,
          'accountName' => "None",
          'accountNumber' => "None",
          'bankName' => "None",
          'paymentType' => 'wallet',
          'status' => 'success',
          'Admin' => 'None',
          "created_at" => now(),
          "updated_at" => now(),
        ]);

        DB::table('transactions')->insert([
          'transactionId' => $reference,
          'userId' => $user->userId,
          'username' => $user->username,
          'email' => $user->email,
          'phoneNumber' => $user->phoneNumber,
          'amount' => $newamount,
          'transactionType' => 'Deposit',
          'transactionService' => 'Funding Wallet',
          'status' => 'CONFIRM',
          'paymentMethod' => 'wallet',
          "created_at" => date('Y-m-d H:i:s'),
          "updated_at" => date('Y-m-d H:i:s'),
        ]);

        // Respond with a JSON success message
        \Illuminate\Support\Facades\Log::info('Webhook processed successfully', [
          'reference' => $reference,
          'amount' => $amount,
          'account_number' => $accountNumber,
          'user_id' => optional($user)->userId,
        ]);

        return response()->json(['status' => 'Payment webhook received successfully']);
      } else {
        return response()->json(['status' => 'No User found']);
      }
      }
    } catch (ValidationException $e) {
      // Handle validation errors
      \Illuminate\Support\Facades\Log::error('Error processing webhook', [
        'error_message' => $e->getMessage(),
      ]);

    } catch (QueryException $e) {
      // Handle database errors
      \Illuminate\Support\Facades\Log::error('Error processing webhook', [
        'error_message' => $e->getMessage(),
      ]);

    } catch (\Exception $e) {
      // Handle other exceptions
      \Illuminate\Support\Facades\Log::error('Error processing webhook', [
        'error_message' => $e->getMessage(),
      ]);

    }
  }

}

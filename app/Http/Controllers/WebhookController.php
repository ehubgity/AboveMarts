<?php

namespace App\Http\Controllers;

use App\Helpers\TransactionHelper;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class WebhookController extends Controller
{
  public function handleDataPurchase(Request $request): JsonResponse
{
    try {
        // Log the raw request
        Log::info('Webhook received', $request->all());
        
        // Get the webhook data - handle the weird JSON-in-key format
        $data = $this->extractWebhookData($request);
        
        if (!$data) {
            Log::error('Could not extract webhook data');
            return response()->json(['error' => 'Invalid webhook format'], 400);
        }
        
        Log::info('Extracted webhook data', $data);
        
        // Get required fields
        $status = $data['status'] ?? null;
        $clientReference = $data['client_reference'] ?? null;
        
        // Validate required fields
        if (!$status || !$clientReference) {
            Log::error('Missing required fields', [
                'status' => $status,
                'client_reference' => $clientReference
            ]);
            return response()->json(['error' => 'Missing required fields'], 400);
        }
        
        // Find transaction records with retry logic
        $transaction = null;
        $datapurchase = null;
        $maxAttempts = 3;
        $retryDelay = 2; // seconds
        
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            Log::info('Attempting to find transaction', [
                'client_reference' => $clientReference,
                'attempt' => $attempt,
                'max_attempts' => $maxAttempts
            ]);
            
            $transaction = DB::table('transactions')->where('transactionId', $clientReference)->first();
            $datapurchase = DB::table('datapurchases')->where('transactionId', $clientReference)->first();
            
            if ($transaction && $datapurchase) {
                Log::info('Transaction found successfully', [
                    'client_reference' => $clientReference,
                    'attempt' => $attempt,
                    'transaction_id' => $transaction->id ?? 'N/A',
                    'user_id' => $transaction->userId ?? 'N/A'
                ]);
                break;
            }
            
            // If not the last attempt, wait before retrying
            if ($attempt < $maxAttempts) {
                Log::info('Transaction not found, retrying...', [
                    'client_reference' => $clientReference,
                    'attempt' => $attempt,
                    'next_retry_in_seconds' => $retryDelay,
                    'transaction_found' => $transaction ? 'yes' : 'no',
                    'datapurchase_found' => $datapurchase ? 'yes' : 'no'
                ]);
                sleep($retryDelay);
            }
        }
        
        // Final check after all attempts
        if (!$transaction || !$datapurchase) {
            Log::warning('Transaction not found after all retry attempts', [
                'client_reference' => $clientReference,
                'total_attempts' => $maxAttempts,
                'transaction_found' => $transaction ? 'yes' : 'no',
                'datapurchase_found' => $datapurchase ? 'yes' : 'no'
            ]);
            
            return response()->json([
                'error' => 'Transaction not found',
                'message' => 'Transaction may still be processing. Please retry webhook in a few seconds.',
                'client_reference' => $clientReference,
                'attempts_made' => $maxAttempts
            ], 404);
        }
        
        // Determine final status
        $isSuccess = in_array(strtolower($status), ['success', 'successful']);
        $finalStatus = $isSuccess ? 'CONFIRM' : 'Failed';
        
        Log::info('Processing transaction status update', [
            'client_reference' => $clientReference,
            'original_status' => $status,
            'final_status' => $finalStatus,
            'is_success' => $isSuccess
        ]);
        
        // Update both tables
        DB::transaction(function () use ($clientReference, $finalStatus) {
            $updatedTransactions = DB::table('transactions')
                ->where('transactionId', $clientReference)
                ->update([
                    'status' => $finalStatus,
                    'updated_at' => now()
                ]);
                
            $updatedDataPurchases = DB::table('datapurchases')
                ->where('transactionId', $clientReference)
                ->update([
                    'status' => $finalStatus,
                    'updated_at' => now()
                ]);
            
            Log::info('Database records updated', [
                'client_reference' => $clientReference,
                'transactions_updated' => $updatedTransactions,
                'datapurchases_updated' => $updatedDataPurchases,
                'new_status' => $finalStatus
            ]);
        });
        
        // If successful, update user balance
        if ($isSuccess) {
            Log::info('Processing successful transaction - updating user balance');
            $this->updateUserBalance($transaction, $datapurchase);
        } else {
            Log::info('Transaction marked as failed - no balance update', [
                'client_reference' => $clientReference,
                'status' => $status
            ]);
        }
        
        Log::info('Webhook processed successfully', [
            'client_reference' => $clientReference,
            'final_status' => $finalStatus,
            'user_balance_updated' => $isSuccess ? 'yes' : 'no'
        ]);
        
        return response()->json([
            'message' => 'Webhook processed successfully',
            'client_reference' => $clientReference,
            'status' => $finalStatus
        ], 200);
        
    } catch (\Exception $e) {
        Log::error('Webhook processing failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->all(),
            'client_reference' => $clientReference ?? 'unknown'
        ]);
        
        return response()->json([
            'error' => 'Processing failed',
            'message' => 'An internal error occurred while processing the webhook'
        ], 500);
    }
}
    
    /**
     * Extract webhook data from the weird JSON-in-key format
     */
    private function extractWebhookData(Request $request): ?array
    {
        $requestData = $request->all();
        
        // Debug log
        Log::info('Request data analysis', [
            'count' => count($requestData),
            'keys' => array_keys($requestData),
            'values' => array_values($requestData)
        ]);
        
        // Handle the specific format: {JSON_STRING: null}
        if (count($requestData) === 1) {
            $key = array_keys($requestData)[0];
            $value = array_values($requestData)[0];
            
            // If value is null and key looks like JSON, parse it
            if ($value === null && is_string($key) && str_starts_with($key, '{')) {
                Log::info('Found JSON in key', ['json_key' => $key]);
                
                $decoded = json_decode($key, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $decoded;
                }
                
                Log::error('JSON decode failed', ['error' => json_last_error_msg()]);
            }
        }
        
        // Fallback to normal request data
        return $requestData ?: null;
    }
    
    private function updateUserBalance($transaction, $datapurchase): void
    {
        try {
            // Update account manager totals
            if (class_exists('App\Helpers\TransactionHelper')) {
                TransactionHelper::updateAccountManagerTotals(
                    $transaction->userId, 
                    $datapurchase->amount, 
                    'Data Share'
                );
            }
            
            // Update user balance
            $user = User::where('userId', $transaction->userId)->first();
            if ($user) {
                $before = $user->currentBalance;
                $newBalance = $before - $datapurchase->amount;
                
                $user->update([
                    'beforeBalance' => $before,
                    'currentBalance' => $newBalance,
                ]);
                
                Log::info('User balance updated', [
                    'userId' => $user->userId,
                    'before' => $before,
                    'after' => $newBalance,
                    'deducted' => $datapurchase->amount
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Balance update failed', [
                'userId' => $transaction->userId ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            // Don't throw - let the main transaction succeed
        }
    }
}
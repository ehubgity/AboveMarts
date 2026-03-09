<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class EBillsService
{
    private $baseUrl = 'https://ebills.africa/wp-json';
    private $username;
    private $password;
    private $token;

    public function __construct()
    {
        $this->username = config('services.ebills.username');
        $this->password = config('services.ebills.password');
    }

    /**
     * Authenticate and get JWT token
     */
    public function authenticate()
    {
        try {
            $response = Http::timeout(30)->post("{$this->baseUrl}/jwt-auth/v1/token", [
                'username' => $this->username,
                'password' => $this->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->token = $data['token'] ?? null;

                if (!$this->token) {
                    throw new Exception('Token not found in response');
                }

                return $this->token;
            }

            throw new Exception('Authentication failed: ' . $response->body());
        } catch (Exception $e) {
            Log::error('eBills authentication error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Purchase airtime
     */
    public function purchaseAirtime($phone, $serviceId, $amount, $requestId = null)
    {
        try {
            // Authenticate if no token exists
            if (!$this->token) {
                $this->authenticate();
            }

            // Generate request ID if not provided
            if (!$requestId) {
                $requestId = 'req_' . time() . '_' . rand(1000, 9999);
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->token,
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->baseUrl}/api/v2/airtime", [
                    'request_id' => $requestId,
                    'phone' => $phone,
                    'service_id' => $serviceId,
                    'amount' => $amount,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            // If unauthorized, try to re-authenticate once
            if ($response->status() === 401) {
                $this->authenticate();

                $response = Http::timeout(30)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->token,
                        'Content-Type' => 'application/json',
                    ])
                    ->post("{$this->baseUrl}/api/v2/airtime", [
                        'request_id' => $requestId,
                        'phone' => $phone,
                        'service_id' => $serviceId,
                        'amount' => $amount,
                    ]);

                if ($response->successful()) {
                    return $response->json();
                }
            }

            throw new Exception('Airtime purchase failed: ' . $response->body());
        } catch (Exception $e) {
            Log::error('eBills airtime purchase error: ' . $e->getMessage());
            throw $e;
        }
    }
    public function verifyCustomer($customerId, $serviceId, $variationId)
    {
        try {
            // Authenticate if no token exists
            if (!$this->token) {
                $this->authenticate();
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->token,
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->baseUrl}/api/v2/verify-customer", [
                    'customer_id' => $customerId,
                    'service_id' => $serviceId,
                    'variation_id' => $variationId,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'code' => $data['code'] ?? null,
                    'message' => $data['message'] ?? null,
                    'customer_info' => $data['data'] ?? $data['customer_info'] ?? null,
                    'raw_response' => $data
                ];
            }

            // If unauthorized, try to re-authenticate once
            if ($response->status() === 401) {
                $this->authenticate();

                $response = Http::timeout(30)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->token,
                        'Content-Type' => 'application/json',
                    ])
                    ->post("{$this->baseUrl}/api/v2/verify-customer", [
                        'customer_id' => $customerId,
                        'service_id' => $serviceId,
                        'variation_id' => $variationId,
                    ]);

                if ($response->successful()) {
                    $data = $response->json();

                    return [
                        'code' => $data['code'] ?? null,
                        'message' => $data['message'] ?? null,
                        'customer_info' => $data['data'] ?? $data['customer_info'] ?? null,
                        'raw_response' => $data
                    ];
                }
            }

            throw new Exception('Customer verification failed: ' . $response->body());
        } catch (Exception $e) {
            Log::error('eBills customer verification error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Purchase electricity/utility bill
     */
    public function purchaseElectricity($customerId, $serviceId, $variationId, $amount, $requestId = null)
    {
        try {
            // Authenticate if no token exists
            if (!$this->token) {
                $this->authenticate();
            }

            // Generate request ID if not provided
            if (!$requestId) {
                $requestId = 'req_' . time() . '_' . rand(1000, 9999);
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->token,
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->baseUrl}/api/v2/electricity", [
                    'request_id' => $requestId,
                    'customer_id' => $customerId,
                    'service_id' => $serviceId,
                    'variation_id' => $variationId,
                    'amount' => $amount,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'code' => $data['code'] ?? null,
                    'message' => $data['message'] ?? null,
                    'transaction_id' => $data['transaction_id'] ?? $data['reference'] ?? null,
                    'token' => $data['token'] ?? $data['meter_token'] ?? null,
                    'units' => $data['units'] ?? $data['kwh_units'] ?? null,
                    'customer_info' => $data['customer_info'] ?? null,
                    'raw_response' => $data
                ];
            }

            // If unauthorized, try to re-authenticate once
            if ($response->status() === 401) {
                $this->authenticate();

                $response = Http::timeout(30)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->token,
                        'Content-Type' => 'application/json',
                    ])
                    ->post("{$this->baseUrl}/api/v2/electricity", [
                        'request_id' => $requestId,
                        'customer_id' => $customerId,
                        'service_id' => $serviceId,
                        'variation_id' => $variationId,
                        'amount' => $amount,
                    ]);

                if ($response->successful()) {
                    $data = $response->json();

                    return [
                        'code' => $data['code'] ?? null,
                        'message' => $data['message'] ?? null,
                        'transaction_id' => $data['transaction_id'] ?? $data['reference'] ?? null,
                        'token' => $data['token'] ?? $data['meter_token'] ?? null,
                        'units' => $data['units'] ?? $data['kwh_units'] ?? null,
                        'customer_info' => $data['customer_info'] ?? null,
                        'raw_response' => $data
                    ];
                }
            }

            throw new Exception('Electricity purchase failed: ' . $response->body());
        } catch (Exception $e) {
            Log::error('eBills electricity purchase error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function purchaseTvSubscription($customerId, $serviceId, $variationId, $requestId = null)
    {
        try {
            // Authenticate if no token exists
            if (!$this->token) {
                $this->authenticate();
            }

            // Generate request ID if not provided
            if (!$requestId) {
                $requestId = 'req_' . time() . '_' . rand(1000, 9999);
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->token,
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->baseUrl}/api/v2/tv", [
                    'request_id' => $requestId,
                    'customer_id' => $customerId,
                    'service_id' => $serviceId, // e.g., 'dstv', 'gotv', 'startimes'
                    'variation_id' => $variationId, // e.g., '3713' for specific package
                ]);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'code' => $data['code'] ?? null,
                    'message' => $data['message'] ?? null,
                    'transaction_id' => $data['transaction_id'] ?? $data['reference'] ?? null,
                    'customer_name' => $data['customer_name'] ?? null,
                    'customer_id' => $data['customer_id'] ?? $customerId,
                    'package_name' => $data['package_name'] ?? null,
                    'expiry_date' => $data['expiry_date'] ?? null,
                    'status' => $data['status'] ?? null,
                    'raw_response' => $data
                ];
            }

            // If unauthorized, try to re-authenticate once
            if ($response->status() === 401) {
                $this->authenticate();

                $response = Http::timeout(30)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->token,
                        'Content-Type' => 'application/json',
                    ])
                    ->post("{$this->baseUrl}/api/v2/tv", [
                        'request_id' => $requestId,
                        'customer_id' => $customerId,
                        'service_id' => $serviceId,
                        'variation_id' => $variationId,
                    ]);

                if ($response->successful()) {
                    $data = $response->json();

                    return [
                        'code' => $data['code'] ?? null,
                        'message' => $data['message'] ?? null,
                        'transaction_id' => $data['transaction_id'] ?? $data['reference'] ?? null,
                        'customer_name' => $data['customer_name'] ?? null,
                        'customer_id' => $data['customer_id'] ?? $customerId,
                        'package_name' => $data['package_name'] ?? null,
                        'expiry_date' => $data['expiry_date'] ?? null,
                        'status' => $data['status'] ?? null,
                        'raw_response' => $data
                    ];
                }
            }

            throw new Exception('TV subscription purchase failed: ' . $response->body());
        } catch (Exception $e) {
            Log::error('eBills TV subscription purchase error: ' . $e->getMessage());
            throw $e;
        }
    }


    // Alternative method to get variations for a specific TV service
    public function getTvServiceVariations($serviceId)
    {
        return $this->getTvVariations($serviceId);
    }

    public function getTvVariations($serviceId = null)
    {
        try {
            // Authenticate if no token exists
            if (!$this->token) {
                $this->authenticate();
            }

            $url = "{$this->baseUrl}/api/v2/variations/tv";

            // Add service_id as query parameter if provided
            if ($serviceId) {
                $url .= "?service_id=" . urlencode($serviceId);
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->token,
                    'Content-Type' => 'application/json',
                ])
                ->get($url);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'success' => true,
                    'variations' => $data['variations'] ?? $data['data'] ?? $data,
                    'raw_response' => $data
                ];
            }

            // If unauthorized, try to re-authenticate once
            if ($response->status() === 401) {
                $this->authenticate();

                $response = Http::timeout(30)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->token,
                        'Content-Type' => 'application/json',
                    ])
                    ->get($url);

                if ($response->successful()) {
                    $data = $response->json();

                    return [
                        'success' => true,
                        'variations' => $data['variations'] ?? $data['data'] ?? $data,
                        'raw_response' => $data
                    ];
                }
            }

            throw new Exception('Failed to fetch TV variations: ' . $response->body());
        } catch (Exception $e) {
            Log::error('eBills TV variations fetch error: ' . $e->getMessage());
            throw $e;
        }
    }

    // Remove the getAllTvServices method since that endpoint doesn't exist
    // Instead, use this method to get variations grouped by service
    public function getTvVariationsByService()
    {
        try {
            $allVariations = $this->getTvVariations();

            if (!$allVariations['success']) {
                return $allVariations;
            }

            // Group variations by service_id if the API returns them flat
            $groupedVariations = [];
            $variations = $allVariations['variations'];

            if (is_array($variations)) {
                foreach ($variations as $variation) {
                    $serviceId = $variation['service_id'] ?? 'unknown';
                    $groupedVariations[$serviceId][] = $variation;
                }
            }

            return [
                'success' => true,
                'services' => $groupedVariations,
                'raw_response' => $allVariations['raw_response']
            ];
        } catch (Exception $e) {
            Log::error('eBills TV variations grouping error: ' . $e->getMessage());
            throw $e;
        }
    }
    /**
     * Get available service providers
     */
    public function getServiceProviders()
    {
        return [
            'mtn' => 'MTN',
            'airtel' => 'Airtel',
            'glo' => 'Glo',
            '9mobile' => '9Mobile',
        ];
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class BiteshipService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.biteship.api_key');
        $this->baseUrl = config('services.biteship.base_url', 'https://api.biteship.com');
    }

    /**
     * Get shipping rates from Biteship
     */
    public function getShippingRates(array $params): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/v1/rates/couriers', [
                'origin_postal_code' => $params['origin_postal_code'] ?? '40115', // Default: Bandung
                'destination_postal_code' => $params['destination_postal_code'],
                'couriers' => $params['couriers'] ?? 'jne,pos,tiki,anteraja,jnt,sicepat,ide,wahana',
                'items' => $params['items'],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'data' => $data,
                ];
            }

            Log::error('Biteship API Error: ' . $response->body());
            return [
                'success' => false,
                'message' => 'Failed to get shipping rates',
                'error' => $response->json()
            ];

        } catch (Exception $e) {
            Log::error('Biteship Service Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Service unavailable. Please try again later.',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get area information by postal code
     */
    public function getAreaByPostalCode(string $postalCode): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/v1/maps/areas', [
                'countries' => 'ID',
                'input' => $postalCode,
                'type' => 'postal_code'
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to get area information',
            ];

        } catch (Exception $e) {
            Log::error('Biteship Get Area Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Service unavailable',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create delivery order in Biteship
     */
    public function createDeliveryOrder(array $orderData): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/v1/orders', $orderData);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            Log::error('Biteship Create Delivery Order Error: ' . $response->body());
            return [
                'success' => false,
                'message' => 'Failed to create delivery order',
                'error' => $response->json()
            ];

        } catch (Exception $e) {
            Log::error('Biteship Create Delivery Order Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Service unavailable',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create order in Biteship (alias for createDeliveryOrder for backward compatibility)
     */
    public function createOrder(array $orderData): array
    {
        return $this->createDeliveryOrder($orderData);
    }

    /**
     * Cancel delivery order
     */
    public function cancelDeliveryOrder(string $orderId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->delete($this->baseUrl . "/v1/orders/{$orderId}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            Log::error('Biteship Cancel Order Error: ' . $response->body());
            return [
                'success' => false,
                'message' => 'Failed to cancel delivery order',
                'error' => $response->json()
            ];

        } catch (Exception $e) {
            Log::error('Biteship Cancel Order Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Service unavailable',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Track order by order ID
     */
    public function trackOrder(string $orderId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . "/v1/orders/{$orderId}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to track order',
            ];

        } catch (Exception $e) {
            Log::error('Biteship Track Order Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Service unavailable',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get order details
     */
    public function getOrderDetails(string $orderId): array
    {
        return $this->trackOrder($orderId);
    }

    /**
     * Get tracking information with history
     */
    public function getTrackingHistory(string $orderId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . "/v1/trackings/{$orderId}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to get tracking history',
            ];

        } catch (Exception $e) {
            Log::error('Biteship Get Tracking History Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Service unavailable',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Format items for Biteship API
     */
    public static function formatItems(array $items): array
    {
        return array_map(function ($item) {
            return [
                'name' => $item['name'],
                'description' => $item['description'] ?? $item['name'],
                'value' => (int) $item['value'], // in rupiah
                'weight' => (int) $item['weight'], // in grams
                'quantity' => (int) $item['quantity'],
            ];
        }, $items);
    }

    /**
     * Parse shipping rates response
     */
    public static function parseShippingRates(array $response): array
    {
        if (!isset($response['pricing']) || empty($response['pricing'])) {
            return [];
        }

        $rates = [];
        foreach ($response['pricing'] as $pricing) {
            // Filter hanya yang tersedia (available)
            if (isset($pricing['available_for_cash_on_delivery']) && 
                $pricing['available_for_cash_on_delivery'] === true) {
                
                $rates[] = [
                    'courier_name' => $pricing['courier']['name'] ?? '',
                    'courier_code' => $pricing['courier']['code'] ?? '',
                    'service_name' => $pricing['courier_service_name'] ?? '',
                    'service_code' => $pricing['courier_service_code'] ?? '',
                    'description' => $pricing['description'] ?? '',
                    'price' => $pricing['price'] ?? 0,
                    'minimum_day' => $pricing['minimum_day'] ?? 0,
                    'maximum_day' => $pricing['maximum_day'] ?? 0,
                    'range_price' => $pricing['range_price'] ?? null,
                    'company' => $pricing['company'] ?? '',
                    'type' => $pricing['type'] ?? '',
                ];
            }
        }

        return $rates;
    }

    /**
     * Validate API key
     */
    public function validateApiKey(): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/v1/maps/areas', [
                'countries' => 'ID',
                'input' => '40115',
                'type' => 'postal_code'
            ]);

            return [
                'success' => $response->successful(),
                'message' => $response->successful() ? 'API key valid' : 'API key invalid'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Unable to validate API key: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get supported couriers
     */
    public function getSupportedCouriers(): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/v1/couriers');

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to get supported couriers',
            ];

        } catch (Exception $e) {
            Log::error('Biteship Get Couriers Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Service unavailable',
                'error' => $e->getMessage()
            ];
        }
    }
}
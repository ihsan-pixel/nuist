<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenWAService
{
    protected $baseUrl;
    protected $apiKey;
    protected $sessionId;

    public function __construct()
    {
        $this->baseUrl = config('services.openwa.base_url', 'http://localhost:3000');
        $this->apiKey = config('services.openwa.api_key');
        $this->sessionId = config('services.openwa.session_id', 'default');
    }

    /**
     * Check if OpenWA session is active
     */
    public function checkSession()
    {
        $lastException = null;

        for ($attempt = 1; $attempt <= $this->retryAttempts; $attempt++) {
            try {
                $response = Http::timeout($this->timeout)
                    ->withHeaders($this->getHeaders())
                    ->get("{$this->baseUrl}/sessions/{$this->sessionId}");

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'message' => 'Session is active',
                        'data' => $data,
                        'attempt' => $attempt
                    ];
                }

                // If it's the last attempt, return failure
                if ($attempt === $this->retryAttempts) {
                    return [
                        'success' => false,
                        'message' => 'Session not found or inactive',
                        'data' => $response->json(),
                        'attempt' => $attempt
                    ];
                }

            } catch (\Exception $e) {
                $lastException = $e;

                // If it's not the last attempt, wait before retrying
                if ($attempt < $this->retryAttempts) {
                    sleep(1); // Wait 1 second before retry
                    continue;
                }
            }
        }

        Log::error('OpenWA session check failed after all retries', [
            'error' => $lastException?->getMessage(),
            'session_id' => $this->sessionId,
            'attempts' => $this->retryAttempts
        ]);

        return [
            'success' => false,
            'message' => 'Connection failed after ' . $this->retryAttempts . ' attempts: ' . $lastException?->getMessage(),
            'attempts' => $this->retryAttempts
        ];
    }

    /**
     * Send broadcast message to multiple numbers
     */
    public function sendBroadcast(array $numbers, string $message)
    {
        $results = [
            'total' => count($numbers),
            'success' => 0,
            'failed' => 0,
            'details' => []
        ];

        foreach ($numbers as $number) {
            try {
                $result = $this->sendMessage($number, $message);

                if ($result['success']) {
                    $results['success']++;
                } else {
                    $results['failed']++;
                }

                $results['details'][] = [
                    'number' => $number,
                    'success' => $result['success'],
                    'message' => $result['message']
                ];

                // Add small delay to avoid rate limiting
                usleep(500000); // 0.5 seconds

            } catch (\Exception $e) {
                $results['failed']++;
                $results['details'][] = [
                    'number' => $number,
                    'success' => false,
                    'message' => $e->getMessage()
                ];

                Log::error('Failed to send message to number', [
                    'number' => $number,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $results;
    }

    /**
     * Send single message
     */
    public function sendMessage(string $number, string $message)
    {
        try {
            // Format number (remove any non-numeric characters and ensure it starts with country code)
            $formattedNumber = $this->formatNumber($number);

            $payload = [
                'to' => $formattedNumber,
                'text' => $message
            ];

            $response = Http::timeout($this->timeout)
                ->withHeaders($this->getHeaders())
                ->post("{$this->baseUrl}/sessions/{$this->sessionId}/messages", $payload);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'message' => 'Message sent successfully',
                    'data' => $data
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to send message: ' . $response->body(),
                'data' => $response->json()
            ];

        } catch (\Exception $e) {
            Log::error('OpenWA send message failed', [
                'number' => $number,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Format WhatsApp number
     */
    protected function formatNumber(string $number)
    {
        // Remove all non-numeric characters
        $cleanNumber = preg_replace('/\D/', '', $number);

        // If number starts with 0, replace with 62 (Indonesia country code)
        if (str_starts_with($cleanNumber, '0')) {
            $cleanNumber = '62' . substr($cleanNumber, 1);
        }

        // Ensure it starts with country code
        if (!str_starts_with($cleanNumber, '62')) {
            $cleanNumber = '62' . $cleanNumber;
        }

        return $cleanNumber . '@s.whatsapp.net';
    }

    /**
     * Get session info
     */
    public function getSessionInfo()
    {
        return $this->checkSession();
    }
}

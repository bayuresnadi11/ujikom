<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

if (!function_exists('kirimWa')) {
    function kirimWa($target, $pesan)
    {
        try {
            // Ambil setting dari database
            $setting = Setting::first();
            
            if (!$setting || !$setting->japati_api_token || !$setting->japati_gateway_number) {
                Log::error('Gagal kirim WA: Konfigurasi Japati belum diatur di database');
                return false;
            }

            // Bersihkan nomor target (hanya angka, pastikan diawali 62)
            $target = preg_replace('/[^0-9]/', '', $target);
            if (str_starts_with($target, '08')) {
                $target = '628' . substr($target, 2);
            } elseif (str_starts_with($target, '8')) {
                $target = '628' . substr($target, 1);
            }
            
            $apiToken = trim(str_replace(["\r", "\n", ' '], '', $setting->japati_api_token));
            $gateway = trim(str_replace(["\r", "\n", ' '], '', $setting->japati_gateway_number));

            // Retry mechanism: 3 attempts with exponential backoff
            $maxAttempts = 3;
            $attempt = 0;
            $lastError = null;

            while ($attempt < $maxAttempts) {
                $attempt++;
                
                try {
                    // Panggil API Japati dengan timeout
                    $response = Http::timeout(30)
                        ->retry(2, 100) // Laravel's built-in retry
                        ->withHeaders([
                            'Authorization' => 'Bearer ' . $apiToken,
                            'Content-Type' => 'application/json',
                            'Accept' => 'application/json'
                        ])
                        ->post('https://app.japati.id/api/send-message', [
                            'gateway' => $gateway,
                            'number' => $target,
                            'type' => 'text',
                            'message' => $pesan,
                        ]);

                    if ($response->successful()) {
                        Log::info("✅ Kirim WA Success ke $target (attempt $attempt)", [
                            'response' => $response->json()
                        ]);
                        return true;
                    }
                    
                    // Store error for logging
                    $lastError = [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ];
                    
                    // If 404 or 500, retry after delay
                    if (in_array($response->status(), [404, 500, 502, 503])) {
                        $delay = pow(2, $attempt) * 1000; // Exponential backoff: 2s, 4s, 8s
                        Log::warning("⚠️ Japati server error (attempt $attempt/$maxAttempts), retrying in {$delay}ms", [
                            'status' => $response->status(),
                            'target' => $target
                        ]);
                        usleep($delay * 1000); // Convert to microseconds
                        continue;
                    }
                    
                    // For other errors, don't retry
                    break;
                    
                } catch (\Exception $e) {
                    $lastError = ['exception' => $e->getMessage()];
                    Log::warning("⚠️ Japati request exception (attempt $attempt/$maxAttempts): " . $e->getMessage());
                    
                    if ($attempt < $maxAttempts) {
                        $delay = pow(2, $attempt) * 1000;
                        usleep($delay * 1000);
                        continue;
                    }
                }
            }
            
            // All attempts failed
            Log::error("❌ Gagal kirim WA ke $target setelah $maxAttempts attempts", [
                'last_error' => $lastError,
                'message_preview' => substr($pesan, 0, 100)
            ]);
            
            return false;
            
        } catch (\Exception $e) {
            Log::error('❌ Gagal kirim WA (Fatal Error): ' . $e->getMessage(), [
                'target' => $target ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
}
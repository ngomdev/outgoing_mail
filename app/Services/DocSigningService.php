<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DocSigningService
{
    public function signDocument(string $signingCode, string $filePath)
    {
        $url = config('app.doc_signing_url');
        $workerId = config('app.doc_signing_worker_id');

        Log::info('Sending document signing request', [
            'url' => $url,
            'workerId' => $workerId,
            'filePath' => $filePath
        ]);

        try {
            $response = Http::asMultipart()->post($url, [
                [
                    'name' => 'workerId',
                    'contents' => $workerId,
                ],
                [
                    'name' => 'codePin',
                    'contents' => $signingCode,
                ],
                [
                    'name' => 'filereceivefile',
                    'contents' => fopen($filePath, 'r')
                ],
            ]);

            Log::info('Received response', [
                'url' => $url,
                'response_status' => $response->status(),
            ]);

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'data' => $response->body()
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Unexpected HTTP status: ' . $response->status() . ' ' . $response->reason(),
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error signing document', [
                'error_message' => $e->getMessage(),
            ]);

            return [
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }
}

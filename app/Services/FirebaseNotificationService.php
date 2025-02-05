<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;

class FirebaseNotificationService
{

    /**
     * This function request an google oauth2 authentification token before sending notification with title and body to firebase using the device's token and firebase will then send the notification to the device
     * Summary of sendNotification
     * @param mixed $title
     * @param mixed $body
     * @param mixed $fcm_token
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function sendNotification($title, $body, $fcm_token)
    {

        $firebaseConfig = config('firebase');

        // Construct the get Oauth2 (JWT) token for authentication
        $data = [
            "grant_type" => "urn:ietf:params:oauth:grant-type:jwt-bearer",
            "assertion" => JWT::encode([
                "iss" => $firebaseConfig["client_email"],
                "scope" => "https://www.googleapis.com/auth/firebase.messaging",
                "aud" => "https://accounts.google.com/o/oauth2/token",
                "exp" => time() + 3600,  // Token expires in 1 hour
                "iat" => time(),
            ], $firebaseConfig["private_key"], 'RS256')
        ];

        //Sending a request to get a firebase access token from oauth 2.0 playground
        $response = Http::asForm()->post('https://accounts.google.com/o/oauth2/token', $data);


        // Get access_token from response
        $firebase_access_token = $response->json()["access_token"];

        // Construct the firebase notification body
        $message = [
            "message" => [
                "token" => $fcm_token,
                "notification" => [
                    "title" => $title,
                    "body" => $body
                ]
            ]
        ];

        // Construct the firebase cloud messaging api v1 headers
        $headers = [
            "Authorization" => "Bearer " . $firebase_access_token,
            "Content-Type" => "application/json"
        ];


        // Sending the message via firebase cloud messaging v1 api
        $response = Http::withHeaders($headers)->post('https://fcm.googleapis.com/v1/projects/orbus-courriers/messages:send', $message);

        return response()->json([
            'status' => $response->status(),
            'message' => $response->successful() ? 'Notification sent successfully' : 'Failed to send notification',
            'response' => $response->json()
        ]);
    }

    /**
     * The function below is to send sms to a specific user.
     *  It takes as arguments the user to notify and the message that we want to send
     * @param User $notifiable
     * @param mixed $body
     * @return mixed|\Illuminate\Http\JsonResponse
     */

    public function sendSms($notifiable, $body)
    {

        // Create an array of the query parameters
        $url = env('sms_gateway_url');
        $notifiable = str_replace("+", "", $notifiable);

        $params = [
            'username' => env('sms_username'),
            'password' => env('sms_password'),
            'to' => $notifiable,
            'text' => $body,
            'sms_platform' => env('sms_platform'),
            'signature' => env('sms_signature')
        ];

        $response = Http::get($url, $params);

        return response()->json([
            'status' => $response->status(),
            'message' => $response->successful() ? 'SMS sent successfully' : 'Failed to send SMS',
            'response' => $response->json()
        ]);
    }
}

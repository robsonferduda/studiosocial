<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class TokenController extends Controller
{

    public function checkFacebookToken(String $token)
    {
        $token_app = $this->getTokenApp();

        $url = "https://graph.facebook.com/debug_token";
        $params = [
            'input_token' => $token,
            'access_token' => $token_app

        ];

        $response = Http::get($url,$params);
       
        if(isset($response->json()['data']['error'])) {
            return json_encode([
                'is_valid' => $response->json()['data']['is_valid'], 
                'message' => $response->json()['data']['error']['message']
            ]);
        }
            
        $date = date("Y-m-d H:i:s ", $response->json()['data']['data_access_expires_at']);

        return json_encode([
            'is_valid' => true,
            'expires_at' => $date
        ]);
    } 

    private function getTokenApp(): String
    {
        $url = "https://graph.facebook.com/oauth/access_token";
        $params = [
            'client_id' => env('FACEBOOK_CLIENT_ID'),
            'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
            'grant_type' => 'client_credentials'

        ];

        $response = Http::get($url,$params);
        return $response->json()['access_token'];
    }
}

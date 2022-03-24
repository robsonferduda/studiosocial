<?php 

use Illuminate\Support\Facades\Http;

function dateTimeUtcToLocal(String $date_time): \DateTime
{
    $utc = new DateTimeZone('UTC');
    $date = new \DateTime($date_time, $utc);
    return $date->setTimezone(new \DateTimeZone(config('app.timezone')));
}

function getTokenApp(): String
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
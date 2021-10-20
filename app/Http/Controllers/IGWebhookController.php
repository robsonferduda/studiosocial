<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IGWebhookController extends Controller
{

    public function receive(Request $request)
    {
        
        Log::info($request);

        return $request->hub_challenge;

    }

    public function validate(Request $request)
    {
        $token = '$a1b2C3d4e5f6$';
        if($request->hub_mode == 'subscribe' && $request->hub_verify_token == $token) {

            Log::info($request);
            return $request->hub_challenge;
        }
        return false;
    }
   
}

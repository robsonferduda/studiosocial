<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IGWebhookController extends Controller
{

    public function receive(Request $request)
    {
        
        foreach($request['entry'] as $entry) {
            foreach($entry['changes'] as $change) {
                Log::error($change);
            }
        }

        return $request->hub_challenge;

    }

    public function urlValidade(Request $request)
    {
        $token = '$a1b2C3d4e5f6$';
        
        if($request->hub_mode == 'subscribe' && $request->hub_verify_token == $token) {
            return $request->hub_challenge;
        }

        return '';
    }
   
}

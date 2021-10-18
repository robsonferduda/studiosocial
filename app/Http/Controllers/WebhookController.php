<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{

    public function receive(Request $request)
    {
        Log::info($request);

        return $request->hub_challenge;

    }
   
}

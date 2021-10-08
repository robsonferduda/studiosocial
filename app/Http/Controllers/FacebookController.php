<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->scopes(['instagram_basic'])->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $user_facebook = Socialite::driver('facebook')->stateless()->user();
            
            Session::put('token', $$user_facebook->token);
            
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }
}

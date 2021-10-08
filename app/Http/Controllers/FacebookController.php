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
            
            dd($user_facebook);

            Auth::login($user);

            return redirect('login');

        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }
}

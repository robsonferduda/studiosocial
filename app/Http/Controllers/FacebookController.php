<?php

namespace App\Http\Controllers;

use App\Enums\SocialMedia;
use App\FbAccount;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->scopes(['instagram_basic', 'pages_show_list'])->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $user_facebook = Socialite::driver('facebook')->stateless()->user();
            
            $fb_account = FbAccount::created([
                'social_media_id' => SocialMedia::FACEBOOK,
                'clients_id' => 1,
                'name' => $user_facebook->name,
                'user_id' => $user_facebook->id,
                'token' => $user_facebook->token,
                'token_expires' => $user_facebook->expiresIn
            ]);
            
            dd($user_facebook);
            
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }
}

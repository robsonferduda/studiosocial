<?php

namespace App\Http\Controllers;

use App\EndPoints;
use App\Enums\SocialMedia;
use App\FbAccount;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use stdClass;

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
            
            // $user_facebook = new stdClass();

            // $user_facebook->token = 'EAAInyDkHeeYBAPedGMTj1vfSQ0g02102lxZATilPsp4teCEZC9AF2IWYS7VgzblocPbXMuGYtZA8izi27LiSTk4jof1iQAiVM2wIDnV9nT3DAM8zqHNIvxn8tHuajUo8xA8AvgCpgZCvSJqpE0tgFiaiBUqu7luuToscB2KzJUxZBls53M9Mnh43YZCNfLNFEeWQYrbydcSAZDZD';
            // $user_facebook->expiresIn = 5161190;
            // $user_facebook->id = '4661463177237611';
            // $user_facebook->name = 'Rafael Costa';

            $fb_account = FbAccount::updateOrcreate(
            ['user_id' => $user_facebook->id],
            [
                'social_media_id' => SocialMedia::FACEBOOK,
                'clients_id' => 1,
                'name' => $user_facebook->name,
                'token' => $user_facebook->token,
                'token_expires' => $user_facebook->expiresIn
            ]);
            
            $fbPages = $this->getFBPages($fb_account->token);

            foreach ($fbPages['data'] as $fbPage) {
                
            }

            
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }

    private function getFBPages(String $token)
    {
        $url = EndPoints::getFBPagesLink();

        $params = ['access_token' => $token];

        return Http::get($url,$params);
    }
}

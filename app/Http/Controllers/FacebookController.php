<?php

namespace App\Http\Controllers;

use App\EndPoints;
use App\Enums\SocialMedia;
use App\FbAccount;
use App\FbPage;
use App\IgPage;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->scopes([
                                                        'instagram_basic',
                                                        'instagram_manage_insights',
                                                        'instagram_manage_comments',
                                                        'pages_show_list',
                                                        'pages_read_engagement',
                                                        'pages_read_user_content',
                                                        'pages_manage_metadata'
                                                    ])->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            
            $user_facebook = Socialite::driver('facebook')->stateless()->user();
            
            $fb_account = FbAccount::updateOrcreate(
            [
                'user_id' => $user_facebook->id,
                'client_id' => 1
            ],
            [
                'social_media_id' => SocialMedia::FACEBOOK,
                'name' => $user_facebook->name,
                'token' => $user_facebook->token,
                'token_expires' => $user_facebook->expiresIn
            ]);
            
            $fbPages = $this->getFBPages($fb_account->token);

            foreach ($fbPages['data'] as $fbPage) {
                $fb_page = FbPage::updateOrCreate(
                    [
                        'page_id' => $fbPage['id'],
                        'fb_account_id' => $fb_account->id
                    ],
                    [
                     'name' => $fbPage['name'],
                     'token' => $fbPage['access_token']
                    ]
                );

                $ig_business_account = $this->getIGBusinessAccount($fb_page->page_id, $fb_account->token);

                if(isset($ig_business_account['instagram_business_account']['id'])) {
                    $ig_page = IgPage::updateOrCreate(
                        [
                            'page_id' => $ig_business_account['instagram_business_account']['id'],
                            'fb_page_id' => $fb_page->id
                        ],
                        [
                            'name' => $ig_business_account['instagram_business_account']['username']
                        ]
                        );
                    dd($this->subscribeApps($ig_business_account['instagram_business_account']['id'], $user_facebook->token)->json());
                }

                
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

    private function getIGBusinessAccount(String $fb_page_id, String $token)
    {
        $url = EndPoints::getIGBusinessAccountLink($fb_page_id);

        $params = [
                    'access_token' => $token,
                    'fields' => 'instagram_business_account{username}'
                  ];

        return Http::get($url,$params);
    }

    private function subscribeApps(String $fb_page_id, String $token)
    {
        $url = EndPoints::getSubscribeAppsLink($fb_page_id);

        $params = [
            'access_token' => $token,
            'subscribed_fields' => 'mentions, story_insights'
        ];

        return Http::post($url,$params);
    }
}

<?php

namespace App\Classes;

use App\Enums\SocialMedia;
use App\Hashtag;
use App\Jobs\FbHashtag as JobsFbHashtag;

class FbHashtag{

    function __construct() {
        
    }

    public function runJob()
    {

        $hashtags_ativas = Hashtag::where('social_media_id', SocialMedia::FACEBOOK)
        ->where('is_active',true)->get();

        foreach ($hashtags_ativas as $hashtag) {
            JobsFbHashtag::dispatch($hashtag);                
        }
    }

}
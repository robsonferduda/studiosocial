<?php

namespace App\Classes;

use App\Enums\SocialMedia;
use App\Jobs\FbTerm as JobsFbTerm;
use App\Term;

class FbTerm{

    function __construct() {
        
    }

    public function runJob()
    {
        $termos_ativos = Term::where('social_media_id', SocialMedia::FACEBOOK)
        ->where('is_active',true)->get();

        foreach ($termos_ativos as $termo) {     
            JobsFbTerm::dispatch($termo);              
        }  
    }

}
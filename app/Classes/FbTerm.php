<?php

namespace App\Classes;

use App\Term;
use App\FbPagePost;
use App\ClientPageMonitor;
use App\Enums\SocialMedia;
use App\FbPagePostComment;
use App\Jobs\FbTerm as JobsFbTerm;

class FbTerm{

    function __construct() {
        
    }

    public function runJob()
    {
        JobsFbTerm::dispatch();                
    }

}
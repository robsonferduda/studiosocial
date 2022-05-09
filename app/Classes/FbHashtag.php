<?php

namespace App\Classes;

use App\Jobs\Fbhashtag as JobsFbhashtag;

class FbHashtag{

    function __construct() {
        
    }

    public function runJob()
    {
        JobsFbhashtag::dispatch();                
    }

}
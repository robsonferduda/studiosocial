<?php

namespace App\Classes;

use App\Jobs\Fbhashtag;

class FbTerm{

    function __construct() {
        
    }

    public function runJob()
    {
        Fbhashtag::dispatch();                
    }

}
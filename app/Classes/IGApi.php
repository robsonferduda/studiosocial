<?php

namespace App\Classes;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class IGApi{

    protected $id = '';

    protected function makeApiCall($url, $params): Response
    {     
        return Http::get($url,$params);
    }

    protected function getAfter($response): String
    {
        return isset($response['paging']['cursors']['after'])
          ? $response['paging']['cursors']['after']
          : '';
    }

    protected function hasAfter($response): Bool
    {
        return isset($response['paging']['cursors']['after'])
          ? true
          : false;
    }

    protected function getId(): String
    {
        return $this->id;
    }
}
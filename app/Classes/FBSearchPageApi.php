<?php

namespace App\Classes;

use App\EndPoints;

class FBSearchPageApi extends IGApi{

    function __construct() {
        
    }

    public function getPages(String $query,Array $params = []): Array
    {
        $url = EndPoints::getFBSearchPagesLink($query);
    
        $response = $this->makeApiCall($url,$params);
        
        if($response->successful()) {
            return $response->json();
        }
        
        return [];
    }

    public function getPageInfo(String $id,Array $params = []): Array
    {
        $url = EndPoints::getFBSearchPageInfoLink($id);
    
        $response = $this->makeApiCall($url,$params);
        
        if($response->successful()) {
            return $response->json();
        }
        
        return [];
    }


    public function getFbPostFields()
    {
        $fields = [
            'id',
            'message',
            'permalink_url',
            'updated_time'
        ];

        return implode(',',$fields);
    } 
}
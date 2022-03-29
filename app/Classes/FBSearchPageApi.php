<?php

namespace App\Classes;

use App\EndPoints;

class FBSearchPageApi extends IGApi{

    function __construct() {
        
    }

    public function getPages(Array $params = []): Array
    {
        $url = EndPoints::getFBSearchPagesLink();
    
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


    public function getPageInfoFields()
    {
        $fields = [
            'picture',
            'name',
            'link',
            'description',
            'category'
        ];

        return implode(',',$fields);
    } 
}
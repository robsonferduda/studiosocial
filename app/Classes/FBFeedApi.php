<?php

namespace App\Classes;

use App\EndPoints;

class FBFeedApi extends IGApi{

    function __construct(String $id) {
        $this->id = $id;
    }

    public function getFeed(Array $params = []): Array
    {
        $url = EndPoints::getFBPagesFeedLink($this->getId());
    
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
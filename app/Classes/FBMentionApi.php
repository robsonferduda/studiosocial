<?php

namespace App\Classes;

use App\EndPoints;

class FBMentionApi extends IGApi{

    function __construct(String $id) {
        $this->id = $id;
    }

    public function getMentions(Array $params = []): Array
    {
        $url = EndPoints::getFBPagesTaggedLink($this->getId());
    
        $response = $this->makeApiCall($url,$params);
        
        if($response->successful()) {
            return $response->json();
        }
        
        return [];
    }

    // public function getMetionHooked(Array $params = []) : Array
    // {
    //     $url = EndPoints::getMetionWebhookLink($this->getId());
   
    //     $response = $this->makeApiCall($url,$params);
        
    //     if($response->successful()) {
    //         return $response->json();
    //     }
        
    //     return [];
    // }


    public function getFbMentionFields()
    {
        $fields = [
            'post_id',
            'message',
            'permalink_url',
            'updated_time',
            'tagged_time'
        ];

        return implode(',',$fields);
    }
}
<?php

namespace App\Classes;
use App\Media;

class HashTag{

    function __construct() {
        
    }

    public function pullMedias()
    {
        $access_token = "EAAInyDkHeeYBAFIDRakpEhFGUQUrpoJIynjagOP3apS3G4mZARchKcdtU3UcXUdZBrj63BHBYt2LUzPhN9cK3uEmsvJeOH2KR8tAgL3W4Nc3OcjJCFD6jZAqjHsN6aL3zsLEt5vhhwrI2fcAryJw9ZAOwDu1XCB6X36Nik1iRMs04R4nZB6DicjGGU3ZAxp6edr3ehbvNTTwZDZD";
        $id_user_id = '17841437726599322';
        $after = '';

        while (@ob_end_flush());      
        ob_implicit_flush(true);
    
        $ig_hash_tag = new IGHashTag($id_user_id);
        $params = [
            'q' => 'trilhasemsc',
            'access_token' => $access_token,
            'user_id' =>  $id_user_id,
            'after' => $after
        ];

        $ig_hash_tag = new IGHashTag();
        $id_hash_tag = $ig_hash_tag->getIdHashTag($params);
    
        do {
        
            $params = [
                'fields' => $ig_hash_tag->getIGHashTagFields(),
                'access_token' => $access_token,
                'after' => $after,
                'user_id' => $id_user_id
            ];

            $medias = $ig_hash_tag->getRecentMediaByHashTag($id_hash_tag, $params);

            foreach ($medias['data'] as $media) {

                echo "<pre>";
                print_r($media);
                echo "<br />";

                $media = Media::updateOrCreate(
                ['media_id' => $media['id']],    
                [
                    'caption' => $media['caption'],
                    'comments_count' => $media['comments_count'],
                    'media_product_type' => $media['media_product_type'],                    
                    'like_count' => $media['like_count'],
                    'media_type' => $media['media_type'],
                    'media_url' => $media['media_url'],
                    'timestamp' =>  $media['timestamp'],
                    'permalink' => $media['permalink']
                ]);
            }

            sleep(2);

            $after = $ig_hash_tag->getAfter($medias);

        } while($ig_hash_tag->hasAfter($medias));
    }
}
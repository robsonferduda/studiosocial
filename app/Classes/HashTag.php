<?php

namespace App\Classes;
use App\Media;

class HashTag{

    function __construct() {
        
    }

    public function pullMedias()
    {
        $access_token = "EAAInyDkHeeYBAEZCgeoBYmJfW3EKudy0WT3OwsWMODZCBKdqoQZAW0euyYz9pZAaZAlSAICZB5F5oR24GWvatSUE2BDUHoDVVYTZBAYptWDxLwrHjDatfqzTwo5afdRpZC7EphTnBcZCMVw9yXg32ySuzwEqcWiiZBWpyChZBNqN8WOXwZDZD";
        $id_user_id = '17841437726599322';
        $after = '';

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

            $after = $ig_hash_tag->getAfter($medias);

        } while($ig_hash_tag->hasAfter($medias));
    }
}
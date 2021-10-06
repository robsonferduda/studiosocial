<?php

namespace App\Classes;
use App\Media;

class HashTag{

    function __construct() {
        
    }

    public function pullMedias()
    {
        $access_token = "EAAInyDkHeeYBAGMurYEoQIuSQQY0QS3MMcPNuwmZA6ocyEwmoQZBwjNIb6jDZAyPOfoI4xI6s4JmP4ZB1ZBaaHFyhgR6tWLBp3QV6cmZAUUJeVvO7KKblo74N7tziyZAKP78y7hcssvQu4AZAviCkoBHTuDCZBpZB7RbUxrW4DYZA6f1p4WKqA12wqGgC6vZBr2kIAgW0y6nHPc1Jq1Q1XTa45Di";
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
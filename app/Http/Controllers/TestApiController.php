<?php

namespace App\Http\Controllers;

use App\Classes\IGHashTag;
use App\Media;


class TestApiController extends Controller
{

    public function test()
    {

        (new IGHashTag())->pullMedias();
        /* // Mentions

        $igMention = new IGMention($id_user_id);

        $params = [
            'fields' => $igMention->getIGMentionFields(),
            'access_token' => $access_token,
            'after' => $after
        ];

        $mentions = $igMention->getMentions($params);
        
        dd($mentions);   */

    
        /* // HashTag
        $ig_hash_tag = new IGHashTag($id_user_id);

        $params = [
            'q' => 'trilhasemsc',
            'access_token' => $access_token,
            'user_id' =>  $id_user_id,
            'after' => $after
        ];

        $ig_hash_tag = new IGHashTag();
        $id_hash_tag = $ig_hash_tag->getIdHashTag($params);
     
        $params = [
            'fields' => $ig_hash_tag->getIGHashTagFields(),
            'access_token' => $access_token,
            'after' => $after,
            'user_id' => $id_user_id
        ];

        $medias = $ig_hash_tag->getRecentMediaByHashTag($id_hash_tag, $params);

        $date = dateTimeUtcToLocal($medias['data'][0]['timestamp']);
      
        dd($date); */

    } 
}

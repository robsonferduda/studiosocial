<?php

namespace App\Twitter;

use App\MediaTwitter;

class TwitterCollect{

    protected $conn = null;

    function __construct() {

        define('CONSUMER_KEY', 'rHn2F4BIhJ17s7jTPJyZ0SrKU');
        define('CONSUMER_SECRET', 'URmePiavhe5NIFSMKpYuHDIaUvW007tGt2SJlDdgykyGWt5FgM');
        define('ACCESS_TOKEN', '725986202-o9342d5gnK1JTn3Rgn5VNoqPSacr6KSHCnGtnbQh');
        define('ACCESS_TOKEN_SECRET','fHb4L1jR2qbSv94A5DByuP26rC7IM4bD5YvwrMTXB4DgI');
       
        //$this->conn = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
        $this->conn = new TwitterOAuth('rHn2F4BIhJ17s7jTPJyZ0SrKU', 'URmePiavhe5NIFSMKpYuHDIaUvW007tGt2SJlDdgykyGWt5FgM', '725986202-o9342d5gnK1JTn3Rgn5VNoqPSacr6KSHCnGtnbQh', 'fHb4L1jR2qbSv94A5DByuP26rC7IM4bD5YvwrMTXB4DgI');

    }

    public function pullMedias()
    {
        $query = array(
            "q" => "Homem Aranha",
            "count" => 1000,
            "lang" => 'pt',
            "result_type" => "recent",
            "exclude_replies" => true,
            "retweeted" => false,
            "tweet_mode" => "extended"
        );
        
        $tweets = $this->conn->get('search/tweets', $query);

        foreach ($tweets->statuses as $tweet) {
            $chave = array('twitter_id' => $tweet->id);
            $dados = array('full_text' => $tweet->full_text,
                        'retweet_count' => $tweet->retweet_count,
                        'favorite_count' => $tweet->favorite_count,
                        'user_id' => $tweet->user->id,
                        'user_name' => $tweet->user->screen_name,
                        'user_screen_name' => $tweet->user->screen_name,
                        'user_location' => $tweet->user->location,
                        'user_followers_count' => $tweet->user->followers_count,
                        'user_friends_count' => $tweet->user->friends_count,
                        'place_name' => ($tweet->place) ? $tweet->place->name : ''
                        );

            $tweet = MediaTwitter::updateOrCreate($chave, $dados); 
        }   
    }
}
<?php

namespace App\Twitter;

use App\Term;
use App\Hashtag;
use App\MediaTwitter;
use App\Enums\SocialMedia;

class TwitterCollect{

    protected $conn = null;

    function __construct() {

        $CONSUMER_KEY = 'rHn2F4BIhJ17s7jTPJyZ0SrKU';
        $CONSUMER_SECRET = 'URmePiavhe5NIFSMKpYuHDIaUvW007tGt2SJlDdgykyGWt5FgM';
        $ACCESS_TOKEN = '725986202-o9342d5gnK1JTn3Rgn5VNoqPSacr6KSHCnGtnbQh';
        $ACCESS_TOKEN_SECRET = 'fHb4L1jR2qbSv94A5DByuP26rC7IM4bD5YvwrMTXB4DgI';
       
        $this->conn = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $ACCESS_TOKEN, $ACCESS_TOKEN_SECRET);
    }

    public function pullMedias()
    {
        $this->getMediasByHashtag();
        $this->getMediasByTermo();
    }

    public function getMediasByHashtag()
    {
        $hashtags_ativas = Hashtag::where('social_media_id', SocialMedia::TWITTER)->where('is_active',true)->get();

        foreach ($hashtags_ativas as $hashtag) {
            
            $query = array(
                "q" => "#".$hashtag->hashtag,
                "count" => 100,
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
                            'client_id' => $hashtag->client_id,
                            'favorite_count' => $tweet->favorite_count,
                            'user_id' => $tweet->user->id,
                            'user_name' => $tweet->user->screen_name,
                            'user_screen_name' => $tweet->user->screen_name,
                            'user_location' => $tweet->user->location,
                            'user_followers_count' => $tweet->user->followers_count,
                            'user_friends_count' => $tweet->user->friends_count,
                            'user_profile_image_url' => $tweet->user->profile_image_url,
                            'created_tweet_at' => $tweet->created_at,
                            'place_name' => ($tweet->place and $tweet->place->place_type) ? $tweet->place->name : ''
                            );
    
                $tweet = MediaTwitter::updateOrCreate($chave, $dados); 
                $tweet->hashtags()->syncWithoutDetaching($hashtag->id);
            }   
        }
    }

    public function getMediasByTermo()
    {
        $termos_ativos = Term::where('social_media_id', SocialMedia::TWITTER)->where('is_active',true)->get();

        foreach ($termos_ativos as $term) {
            
            $query = array(
                "q" => '"$term->term"',
                "count" => 100,
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
                            'client_id' => $term->client_id,
                            'favorite_count' => $tweet->favorite_count,
                            'user_id' => $tweet->user->id,
                            'user_name' => $tweet->user->screen_name,
                            'user_screen_name' => $tweet->user->screen_name,
                            'user_location' => $tweet->user->location,
                            'user_followers_count' => $tweet->user->followers_count,
                            'user_friends_count' => $tweet->user->friends_count,
                            'user_profile_image_url' => $tweet->user->profile_image_url,
                            'created_tweet_at' => $tweet->created_at,
                            'place_name' => ($tweet->place and $tweet->place->place_type) ? $tweet->place->name : ''
                            );
    
                $tweet = MediaTwitter::updateOrCreate($chave, $dados); 
                $tweet->terms()->syncWithoutDetaching($term->id);
            }   
        }
    }
}
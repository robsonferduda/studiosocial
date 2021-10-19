<?php

namespace App\Http\Controllers;

use App\Twitter\TwitterCollect;
use App\MediaTwitter;
use App\TwitterAPIExchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Twitter\TwitterOAuth;

class TwitterController extends Controller
{ 

    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','home');
    }

    public function index()
    {
        (new TwitterCollect())->pullMedias();

        /*
        $settings = array(
            'oauth_access_token' => 'rHn2F4BIhJ17s7jTPJyZ0SrKU', 
            'oauth_access_token_secret' => 'URmePiavhe5NIFSMKpYuHDIaUvW007tGt2SJlDdgykyGWt5FgM', 
            'consumer_key' => '725986202-o9342d5gnK1JTn3Rgn5VNoqPSacr6KSHCnGtnbQh', 
            'consumer_secret' => 'fHb4L1jR2qbSv94A5DByuP26rC7IM4bD5YvwrMTXB4DgI'
        );
    
        define('CONSUMER_KEY', 'rHn2F4BIhJ17s7jTPJyZ0SrKU');
        define('CONSUMER_SECRET', 'URmePiavhe5NIFSMKpYuHDIaUvW007tGt2SJlDdgykyGWt5FgM');
        define('ACCESS_TOKEN', '725986202-o9342d5gnK1JTn3Rgn5VNoqPSacr6KSHCnGtnbQh');
        define('ACCESS_TOKEN_SECRET','fHb4L1jR2qbSv94A5DByuP26rC7IM4bD5YvwrMTXB4DgI');
       
        $conn = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
        $tweets = $conn->get('trends/place', ['id' => 1]);

        /*
        foreach ($tweets as $tweet) {
            dd($tweet);
            echo $tweet->name."<br/>";
           
        }
        */
        
        /*$conn->setApiVersion('2');

        $query = array(
            "query" => "Praia",
            "max_results" => 100
        );

        $tweets = $conn->get('tweets/search/recent', $query);
        //dd($tweets);
        foreach ($tweets->data as $tweet) {

            $t = $conn->get('tweets', ['ids' => $tweet->id]);
            dd($t);
            //dd($tweet);
            //if($tweet->place)
                //echo $tweet->place->name.'<br/>';
            //echo ($tweet->truncated) ? 'true' : 'false';
            echo '<p>'.$tweet->id.' - '.$tweet->text.'<br>';
        }
        
        dd("Fim");
        */

        /* Twitter Timeline
        $twitterUser = 'rfduda';
        $tweets = $conn->get('statuses/home_timeline', array('screen_name' => $twitterUser));

        foreach ($tweets as $tweet) {
            dd($tweet);
           
        }*/
        
        /*
        $query = array(
            "q" => "Adão Negro",
            "count" => 1000,
            "lang" => 'pt',
            "start_time" => '2021-10-12 17:00:00',
            "exclude_replies" => true,
            //"result_type" => "recent",
            "retweeted" => false,
            "tweet_mode" => "extended"
        );
        $tweets = $conn->get('search/tweets', $query);

        foreach ($tweets->statuses as $tweet) {
            //dd($tweet);
            //dd($tweet->user);
            //if($tweet->place){
               //dd($tweet);
            //}
                //echo $tweet->place->name.'<br/>';
            //echo ($tweet->truncated) ? 'true' : 'false';
            //echo '<p>'.$tweet->full_text.'<br>Posted on: <a href="https://twitter.com/'.$tweet->user->screen_name.'/status/'.$tweet->id.'">'.date('Y-m-d H:i', strtotime($tweet->created_at)).'</a></p>';
            //echo (isset($tweet->retweeted_status)) ? 'Retweet'."<br/>" : 'Original'."<br/>";
            //echo 'Retweet: '.$tweet->retweet_count."<br/>";
            //echo 'Likes: '.$tweet->favorite_count."<br/>";
        
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
        */
    }
}
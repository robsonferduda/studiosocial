<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class MediaTwitter extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'media_twitter';

    protected $fillable = [ 'twitter_id',
                            'full_text',
                            'retweet_count',
                            'favorite_count',
                            'user_id',
                            'user_name',
                            'user_screen_name',
                            'user_followers_count',
                            'user_friends_count',
                            'user_location',
                            'place_name',
                            'created_tweet_at',
                            'client_id',
                            'user_profile_image_url'
                        ];

    public function hashtags()
    {
        return $this->belongsToMany('App\Hashtag','twitter_hashtag','media_id','hashtag_id')->withTimestamps();
    }

    public function terms()
    {
        return $this->belongsToMany('App\Term','twitter_term','media_id','term_id')->withTimestamps();
    }

    public function getSentimentos($data_inicial, $data_final)
    {
        return DB::select("SELECT sentiment, count(*) as total 
                            FROM media_twitter 
                            WHERE sentiment NOTNULL 
                            AND created_tweet_at BETWEEN '$data_inicial 00:00:00' AND '$data_final 23:59:59'
                            GROUP BY sentiment 
                            ORDER BY sentiment");
    }

    public function getInfluenciadoresPositivos($client_id)
    {
        return DB::select("SELECT user_name, sentiment, user_profile_image_url, count(*) as total 
                            FROM media_twitter
                            WHERE sentiment IN(1)
                            AND client_id = $client_id
                            GROUP BY sentiment, user_profile_image_url, user_name
                            ORDER BY total DESC
                            LIMIT 10");
    }

    public function getInfluenciadoresNegativos($client_id)
    {
        return DB::select("SELECT user_name, sentiment, user_profile_image_url, count(*) as total 
                            FROM media_twitter
                            WHERE sentiment IN(-1)
                            AND client_id = $client_id
                            GROUP BY sentiment, user_profile_image_url, user_name
                            ORDER BY total DESC
                            LIMIT 10");
    }
}
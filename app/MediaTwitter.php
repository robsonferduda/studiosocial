<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaTwitter extends Model
{

    use SoftDeletes;

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

    public function getInfluenciadoresPositivos()
    {
        return DB::select('SELECT user_name, sentiment, count(*) as total 
                            FROM media_twitter
                            WHERE sentiment IN(-1)
                            GROUP BY sentiment, user_name
                            ORDER BY total DESC
                            LIMIT 10');
    }

    public function getInfluenciadoresNegativos()
    {
        return DB::select('SELECT user_name, sentiment, count(*) as total 
                            FROM media_twitter
                            WHERE sentiment IN(-1)
                            GROUP BY sentiment, user_name
                            ORDER BY total DESC
                            LIMIT 10');
    }
}
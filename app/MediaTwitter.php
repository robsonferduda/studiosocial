<?php

namespace App;

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
                            'created_tweet_at'
                        ];

    public function hashtags()
    {
        return $this->belongsToMany('App\Hashtag','twitter_hashtag','media_id','hashtag_id')->withTimestamps();
    }
}
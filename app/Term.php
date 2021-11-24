<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $table = 'terms';

    protected $fillable = [ 'term',
                            'client_id',
                            'social_media_id'
                        ];

    public function medias()
    {
        return $this->belongsToMany('App\Media','media_term','term_id','media_id')->withTimestamps();
    }

    public function mediasTwitter()
    {
        return $this->belongsToMany('App\MediaTwitter','twitter_term','term_id','media_id')->withTimestamps();
    }

    public function socialMedia()
    {
        return $this->belongsTo('App\SocialMedia', 'social_media_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo('App\Client', 'client_id', 'id');
    }
}

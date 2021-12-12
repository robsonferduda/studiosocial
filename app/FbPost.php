<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FbPost extends Model
{
    protected $table = 'fb_posts';

    protected $fillable = [ 'message',
                            'permalink_url',
                            'updated_time',
                            'tagged_time',
                            'client_id',
                            'post_id',
                            'mentioned',
                            'hooked',
                            'share_count',
                            'comment_count'
                        ];

    public function reactions()
    {
        return $this->belongsToMany('App\FbReaction','fb_post_reaction','post_id','reaction_id')->withTimestamps()->withPivot('count');
    }

    public function comments()
    {
        return $this->hasMany('App\FbComment','post_id','id');
    }
}
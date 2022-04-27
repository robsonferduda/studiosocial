<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FbPagePostComment extends Model
{
    protected $table = 'fb_page_posts_comments';

    protected $fillable = [ 'text',
                            'page_post_id',
                            'created_time',
                            'related_to'                         
                        ];

    public function fbPagePost()
    {
        return $this->hasOne('App\FbPagePost','id','page_post_id');
    }

    public function reactions()
    {
        return $this->belongsToMany('App\FbReaction','fb_page_post_comment_reaction','page_post_comment_id','reaction_id')->withTimestamps()->withPivot('count');
    }
}
    
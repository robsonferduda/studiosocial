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
        return $this->hasMany('App\FbPagePost','id','_page_post_id');
    }
}
    
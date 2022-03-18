<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class FbPagePost extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'fb_page_posts';

    protected $fillable = [ 'message',
                            'fb_page_monitor_id',
                            'permalink_url',
                            'updated_time',                            
                            'post_id',
                            'mentioned',
                            'hooked',
                            'share_count',
                            'comment_count'
                        ];

    public function reactions()
    {
        return $this->belongsToMany('App\FbReaction','fb_page_post_reaction','page_post_id','reaction_id')->withTimestamps()->withPivot('count');
    }
}
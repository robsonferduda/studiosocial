<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'medias';

    protected $fillable = [ 'caption',
                            'comments_count',
                            'media_product_type',
                            'media_id',
                            'like_count',
                            'media_type',
                            'media_url',
                            'timestamp',
                            'permalink',
                            'client_id',
                            'hashtagged',
                            'mentioned'
                        ];
    
    public function hashtags()
    {
        return $this->belongsToMany('App\Hashtag','media_hashtag','media_id','hashtag_id')->withTimestamps();
    }
}
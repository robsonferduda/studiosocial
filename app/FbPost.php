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
                            'mentioned'
                        ];
}
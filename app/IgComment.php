<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IgComment extends Model
{
    protected $table = 'ig_comments';

    protected $fillable = [ 'text',
                            'media_id',
                            'comment_id',
                            'timestamp'                         
                        ];
    
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{
    protected $table = 'hashtags';

    protected $fillable = [ 'hasttag',
                            'client_id',
                            'social_media_id'
                        ];
}

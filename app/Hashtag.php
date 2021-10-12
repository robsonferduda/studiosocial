<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{
    protected $table = 'hashtags';
    protected $fillable = [
                            'hashtag',
                            'social_media_id',
                            'client_id'
                        ];
}
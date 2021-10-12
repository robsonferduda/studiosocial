<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FbAccount extends Model
{
    protected $table = 'fb_accounts';

    protected $fillable = [ 'social_media_id',
                            'client_id',
                            'name',
                            'user_id',
                            'token',
                            'token_expires'
                        ];
}

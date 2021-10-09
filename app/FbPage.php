<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FbPage extends Model
{
    protected $table = 'fb_pages';

    protected $fillable = [ 'name',
                            'page_id',
                            'fb_accounts_id'
                        ];
}

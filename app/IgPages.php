<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IgPages extends Model
{
    protected $table = 'ig_pages';

    protected $fillable = [ 'name',
                            'page_id',
                            'fb_pages_id'
                        ];
}

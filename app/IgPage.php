<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IgPage extends Model
{
    protected $table = 'ig_pages';

    protected $fillable = [ 'name',
                            'page_id',
                            'fb_page_id'
                        ];
}

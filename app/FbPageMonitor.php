<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class FbPageMonitor extends Model
{

    use SoftDeletes;

    protected $table = 'fb_pages_monitor';

    protected $fillable = [ 'name',
                            'url',
                            'mention',
                            'post'
                        ];

}

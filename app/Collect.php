<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collect extends Model
{
    use SoftDeletes;

    protected $table = 'collect';
    protected $fillable = ['id_type_collect','id_social_media','id_type_message','description','total'];
}
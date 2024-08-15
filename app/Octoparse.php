<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Octoparse extends Model
{
    
    protected $connection = 'mysql_coleta';
    protected $table = 'twitter';

    protected $fillable = [""];

}
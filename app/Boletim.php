<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Boletim extends Model
{
    protected $connection = 'mysql';
    protected $table = 'app_boletins';
}
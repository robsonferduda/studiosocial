<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ColetaOctoparse extends Model
{
    use SoftDeletes;

    protected $table = 'octoparse';
    protected $fillable = ['id'];

}
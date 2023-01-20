<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    const UPDATED_AT = null;
    
    protected $connection = 'mysql';
    protected $table = 'app_clientes';

    protected $fillable = ['status_envio'];
}
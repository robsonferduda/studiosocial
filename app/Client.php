<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';
    protected $fillable = ['email','name'];

    public function fbAccounts()
    {
        return $this->hasMany(FbAccount::class, 'client_id', 'id');
    }

    public function hashtags()
    {
        return $this->hasMany(Hashtag::class, 'client_id', 'id');
    }
}
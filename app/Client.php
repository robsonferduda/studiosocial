<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

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

    public function terms()
    {
        return $this->hasMany(Term::class, 'client_id', 'id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'client_id', 'id');
    } 

    public function fbAccount()
    {
        return $this->hasMany('App\FbAccount', 'client_id', 'id');
    } 

    public static function boot() {
        
        parent::boot();

        static::deleting(function($client) { 

            //Quando remover o cliente, remove também o perfil associado e o usuário
            $user = User::where('client_id',$client->id)->first();
            (!$user->roles) ?? $user->roles()->detach();
            $user->delete();
        });

    }
}
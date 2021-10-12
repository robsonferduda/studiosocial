<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $table = 'clients';
    protected $fillable = ['email','name'];

    public function user()
    {
        return $this->hasOne('App\User', 'client_id', 'id');
    } 

    public static function boot() {
        
        parent::boot();

        static::deleting(function($client) { 

            $user = User::where('client_id',$client->id)->first();
            $user->roles()->detach();
            $user->delete();
        });
    }
}
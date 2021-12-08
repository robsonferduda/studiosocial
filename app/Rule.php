<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $table = 'rules';
    protected $fillable = [
                            'name',
                            'client_id'
                        ];

    public function expressionsType($type)
    {
        return $this->hasMany('App\ExpressionRule','rule_id','id')->where('type_rule_id', $type);
    }

    public function expressions()
    {
        return $this->belongsToMany('App\TypeRule','expressions_rules','rule_id','type_rule_id')->withPivot('expression')->withTimestamps();
    }    

    public function igPosts()
    {
        return $this->morphedByMany('App\Media','rules' ,'rule_message', 'rule_id','message_id', 'id', 'id')->withTimestamps();
    }

    public function igComments()
    {
        return $this->morphedByMany('App\IgComment','rules' ,'rule_message', 'rule_id','message_id', 'id', 'id')->withTimestamps();
    }

    public function fbPosts()
    {
        return $this->morphedByMany('App\FbPost','rules' ,'rule_message', 'rule_id','message_id', 'id', 'id')->withTimestamps();
    }

    public function fbComments()
    {
        return $this->morphedByMany('App\FbComment','rules' ,'rule_message', 'rule_id','message_id', 'id', 'id')->withTimestamps();
    }
    
    public function twPosts()
    {
        return $this->morphedByMany('App\MediaTwitter','rules' ,'rule_message', 'rule_id','message_id', 'id', 'id')->withTimestamps();
    }

}

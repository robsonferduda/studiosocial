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

    public function expressions($type)
    {
        return $this->hasMany('App\ExpressionRule','rule_id','id')->where('type_rule_id', $type);
    }
}

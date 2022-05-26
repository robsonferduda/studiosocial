<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MediaRuleFilteredVw extends Model
{
    protected $table = 'medias_materialized_rule_filtered_vw';

    public function rule()
    {
        return $this->hasOne('App\RuleMessage', 'message_id', 'id');
    } 
}
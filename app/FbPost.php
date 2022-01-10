<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class FbPost extends Model
{
    protected $table = 'fb_posts';

    protected $fillable = [ 'message',
                            'permalink_url',
                            'updated_time',
                            'tagged_time',
                            'client_id',
                            'post_id',
                            'mentioned',
                            'hooked',
                            'share_count',
                            'comment_count'
                        ];

    public function reactions()
    {
        return $this->belongsToMany('App\FbReaction','fb_post_reaction','post_id','reaction_id')->withTimestamps()->withPivot('count');
    }

    public function comments()
    {
        return $this->hasMany('App\FbComment','post_id','id');
    }

    public function getSentimentos($data_inicial, $data_final)
    {
        $dt_inicial = $data_inicial->format('Y-m-d');
        $dt_final = $data_final->format('Y-m-d');
        
        return DB::select("SELECT sentiment, count(*) as total 
                            FROM fb_posts 
                            WHERE sentiment NOTNULL 
                            AND tagged_time BETWEEN '$dt_inicial 00:00:00' AND '$dt_final 23:59:59'
                            GROUP BY sentiment 
                            ORDER BY sentiment");
    }
}
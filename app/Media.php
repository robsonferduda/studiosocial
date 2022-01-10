<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'medias';

    protected $fillable = [ 'caption',
                            'comments_count',
                            'media_product_type',
                            'media_id',
                            'like_count',
                            'media_type',
                            'media_url',
                            'timestamp',
                            'permalink',
                            'client_id',
                            'hashtagged',
                            'mentioned',
                            'username',
                            'video_title',
                            'hooked'
                        ];
    
    public function hashtags()
    {
        return $this->belongsToMany('App\Hashtag','media_hashtag','media_id','hashtag_id')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany('App\IgComment','media_id','id');
    }

    public function getSentimentos($data_inicial, $data_final)
    {
        $dt_inicial = $data_inicial->format('Y-m-d');
        $dt_final = $data_final->format('Y-m-d');

        return DB::select("SELECT sentiment, count(*) as total 
                            FROM medias 
                            WHERE sentiment NOTNULL 
                            AND timestamp BETWEEN '$dt_inicial 00:00:00' AND '$dt_final 23:59:59'
                            GROUP BY sentiment 
                            ORDER BY sentiment");
    }
}
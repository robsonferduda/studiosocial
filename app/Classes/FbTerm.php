<?php

namespace App\Classes;

use App\ClientPageMonitor;
use App\Enums\SocialMedia;
use App\FbPageMonitor;
use App\FbPagePost;
use App\Jobs\FbTerm as JobsFbTerm;
use App\Term;

class FbTerm{

    function __construct() {
        
    }

    public function runJob()
    {
        //JobsFbTerm::dispatch();   

        $posts = FbPagePost::whereHas('terms', function ($query) {
            $query->where('client_id', 1);
        })->get();

        dd($posts);

        $clients = ClientPageMonitor::get();
        foreach ($clients as $client) {
            
            $termos_ativos = Term::where('social_media_id', SocialMedia::FACEBOOK)
                            ->where('is_active',true)
                            ->where('client_id', $client->client_id)->get();
            
            foreach ($termos_ativos as $termo) {

                $posts = FbPagePost::where('message', 'ilike', '%'.strtolower($termo->term).'%')
                                    ->where('fb_page_monitor_id', $client->fb_page_monitor_id)
                                    ->get();

                $termo->pagePosts()->syncWithoutDetaching($posts->pluck('id')->toArray());

            }

        }
    }

}
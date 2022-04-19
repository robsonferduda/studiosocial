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

        $clients = ClientPageMonitor::get();
        
        foreach ($clients as $client) {
            
            $termos_ativos = Term::where('social_media_id', SocialMedia::FACEBOOK)
                            ->where('is_active',true)
                            ->where('client_id', $client->client_id)->get();
            
            foreach ($termos_ativos as $termo) {
                
                $last = $termo->pagePosts()->latest('created_at')->first();

                $posts = FbPagePost::where(function ($query) use ($termo) {
                                        $query->where('message', 'ilike', '% '.strtolower($termo->term).' %')
                                        ->orWhere('message', 'ilike', '%'.strtolower($termo->term).' %')
                                        ->orWhere('message', 'ilike', '% '.strtolower($termo->term).'%');
                                    })
                                    ->where('fb_page_monitor_id', $client->fb_page_monitor_id)
                                    ->when($last, function ($q) use ($last){
                                        return $q->where('updated_time', '>=', $last->created_at->subDay()->toDateString());
                                    })                                    
                                    ->get();

                $termo->pagePosts()->syncWithoutDetaching($posts->pluck('id')->toArray());
            }
        }
    }

}
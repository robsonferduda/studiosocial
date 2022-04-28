<?php

namespace App\Classes;

use App\Term;
use App\FbPagePost;
use App\ClientPageMonitor;
use App\Enums\SocialMedia;
use App\FbPagePostComment;
use App\Jobs\FbTerm as JobsFbTerm;

class FbTerm{

    function __construct() {
        
    }

    public function runJob()
    {
        // $clients = ClientPageMonitor::get();
        // dd($clients);
        // foreach ($clients as $client) {
            $termos_ativos = Term::where('social_media_id', SocialMedia::FACEBOOK)
                                ->where('is_active',true)->get();
                              //  ->where('client_id', $client->client_id)->get();

            foreach ($termos_ativos as $termo) {                    
                $last = $termo->pagePosts()->latest('created_at')->first();
                $last_comment = $termo->pagePostsComments()->latest('created_at')->first();

                $posts = FbPagePost::select('id')->where(function ($query) use ($termo) {
                                    $query->where('message', 'ilike', '% '.strtolower($termo->term).' %')
                                        ->orWhere('message', 'ilike', '%'.strtolower($termo->term).' %')
                                        ->orWhere('message', 'ilike', '% '.strtolower($termo->term).'%');
                                    })
                                    //->where('fb_page_monitor_id', $client->fb_page_monitor_id)
                                    ->when($last, function ($q) use ($last){
                                        return $q->where('updated_time', '>=', $last->created_at->subDay()->toDateString());
                                    })                                    
                                    ->get();
                $termo->pagePosts()->syncWithoutDetaching($posts->pluck('id')->toArray());

               // $page_id = $client->fb_page_monitor_id;                                    
                $comments = FbPagePostComment::select('id')->where(function ($query) use ($termo) {
                    $query->where('text', 'ilike', '% '.strtolower($termo->term).' %')
                        ->orWhere('text', 'ilike', '%'.strtolower($termo->term).' %')
                        ->orWhere('text', 'ilike', '% '.strtolower($termo->term).'%');
                    })
                    // ->whereHas('fbPagePost', function($query) use ($page_id){
                    //     $query->where('fb_page_monitor_id', $page_id);
                    // })                  
                    ->when($last_comment, function ($q) use ($last_comment){
                        return $q->where('created_time', '>=', $last_comment->created_at->subDay()->toDateString());
                    })                                    
                    ->get();
                $termo->pagePostsComments()->syncWithoutDetaching($comments->pluck('id')->toArray());

            }
       // }

        //JobsFbTerm::dispatch();                
    }

}
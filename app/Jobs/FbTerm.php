<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\ClientPageMonitor;
use App\Enums\SocialMedia;
use App\FbPagePost;
use App\FbPagePostComment;
use App\Term;

class FbTerm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1000;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(0);

        $clients = ClientPageMonitor::get();
        
        foreach ($clients as $client) {
            $termos_ativos = Term::where('social_media_id', SocialMedia::FACEBOOK)
                                ->where('is_active',true)
                                ->where('client_id', $client->client_id)->get();
                
            foreach ($termos_ativos as $termo) {
                    
                $last = $termo->pagePosts()->latest('created_at')->first();
                $last_comment = $termo->pagePostsComments()->latest('created_at')->first();

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

                $page_id = $client->fb_page_monitor_id;                                    
                $comments = FbPagePostComment::where(function ($query) use ($termo) {
                    $query->where('text', 'ilike', '% '.strtolower($termo->term).' %')
                        ->orWhere('text', 'ilike', '%'.strtolower($termo->term).' %')
                        ->orWhere('text', 'ilike', '% '.strtolower($termo->term).'%');
                    })
                    ->whereHas('fbPagePost', function($query) use ($page_id){
                        $query->where('fb_page_monitor_id', $page_id);
                    })                  
                    ->when($last_comment, function ($q) use ($last_comment){
                        return $q->where('created_time', '>=', $last_comment->created_at->subDay()->toDateString());
                    })                                    
                    ->get();
                $termo->pagePostsComments()->syncWithoutDetaching($comments->pluck('id')->toArray());

            }
        }
    }
}

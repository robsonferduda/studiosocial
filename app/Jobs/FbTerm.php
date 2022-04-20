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
use App\Term;

class FbTerm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 240;

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

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\FbPagePost;
use App\FbPagePostComment;

class FbTerm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 2000;
    protected $termo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($termo)
    {
        $this->termo = $termo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(0);

        $termo = str_replace('"','',$this->termo);

        $last = $termo->pagePosts()->latest('created_at')->first();
        $last_comment = $termo->pagePostsComments()->latest('created_at')->first();
        $posts = FbPagePost::select('id')->where(function ($query) use ($termo) {
                $query->where('message', 'ilike', '% '.strtolower($termo).' %')
                    ->orWhere('message', 'ilike', '%'.strtolower($termo).' %')
                    ->orWhere('message', 'ilike', '% '.strtolower($termo).'%');
                })
                ->when($last, function ($q) use ($last){
                    return $q->where('updated_time', '>=', $last->created_at->subDay()->toDateString());
                })
                ->get();
        $termo->pagePosts()->syncWithoutDetaching($posts->pluck('id')->toArray());

        $comments = FbPagePostComment::select('id')->where(function ($query) use ($termo) {
                $query->where('text', 'ilike', '% '.strtolower($termo).' %')
                    ->orWhere('text', 'ilike', '%'.strtolower($termo).' %')
                    ->orWhere('text', 'ilike', '% '.strtolower($termo).'%');
                })
                ->when($last_comment, function ($q) use ($last_comment){
                    return $q->where('created_time', '>=', $last_comment->created_at->subDay()->toDateString());
                })
                ->get();
        $termo->pagePostsComments()->syncWithoutDetaching($comments->pluck('id')->toArray());

    }
}

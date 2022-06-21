<?php

namespace App\Classes;

use App\FbAccount;
use App\FbPageMonitor;
use App\FbPagePost;
use App\FbPagePostComment;
use App\Jobs\FbFeed as JobsFbFeed;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class FBFeed{

    public function pullMedias()
    {
        set_time_limit(0);

        $hour = \Carbon\Carbon::now()->format('H');

        $order = (int) $hour%2 == 0 ? 'ASC' : 'DESC';
        
        $pages = FbPageMonitor::orderBy('id', $order)->get();

        foreach ($pages as $page) {

            JobsFbFeed::dispatch($page);
           
        }
    }

    private function getTokenValid($token_app, $stepOne = true, Array $idsAccount = []) {

        if($stepOne) {
            $idsAccount = FbAccount::pluck('id')->toArray();
        }

        $key = array_rand($idsAccount);

        $account = FbAccount::where('id', $idsAccount[$key])->first();
       
        $url = "https://graph.facebook.com/debug_token";
        $params = [
            'input_token' => $account->token,
            'access_token' => $token_app
        ];

        $response = Http::get($url,$params);

        $response = $response->json();

        if($response['data']['is_valid'] == true) {
            return $account->token;
        } else  {
            unset($idsAccount[$key]);

            if(empty($idsAccount)) {
                return $token_app;
            }

            $this->getTokenValid($token_app, $stepOne = false, $idsAccount);
        }

    }

    public function fetchPostCount()
    {
        set_time_limit(0);

        $pages = DB::select("select 	
                                t0.id,	
                                count(distinct t1.id) as post_count ,
                                count(t2.id) as comment_count
                            from 
                                fb_pages_monitor t0
                                inner join fb_page_posts t1 on (t0.id = t1.fb_page_monitor_id)
                                left join fb_page_posts_comments t2 on (t1.id = t2.page_post_id)
                            where
                                t0.deleted_at is null
                            group by 
                                t0.id
                            having count(distinct t1.id) > 0
                            order by count(distinct t1.id) desc");

        foreach ($pages as $page) {

            $page_post_count = $page->post_count;
            $page_post_comment_count = $page->comment_count;

            DB::table('fb_pages_monitor')->where('id', $page->id)->update([
                'page_post_count' => $page_post_count,
                'page_post_comment_count' => $page_post_comment_count
            ]);
        }
    }
}
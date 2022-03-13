<?php

namespace App\Classes;

use App\Client;
use App\FbComment;
use App\FbPost;
use App\FbPage;
use App\FbPageMonitor;
use App\FbPagePost;
use Illuminate\Support\Facades\Log;

class FBFeed{

    function __construct() {
        
    }

    public function pullMedias()
    {
        $pages = FbPageMonitor::all();

        foreach ($pages as $page) {
            
            $access_token = 'EAAICnmS4fO0BAMfnRZBWoq4A7Cxvjln1wphBBjPDdZBdJlzc0ybOus9feXKqPnhFGq3q1ZBlTWGZBVGeokbnrNzXCd02zNnZCKsZBFaFqz0kdxxze5ZBZCID1EfYvJpSTJIST0HGijHrzBGtPhx5Py9wngPmu1KkdFrdgfnzaZB3wEsUelbKu1ED4';
            $id_page_id = 'trilhasemsc';

            $fb_feed = new FBFeedApi($id_page_id);
            $after = '';
                        
            do {
                            
                $params = [
                    'fields' => $fb_feed->getFbPostFields(),
                    'access_token' => $access_token,
                    'after' => $after,
                    'limit' => 50
                ];
        
                $posts = $fb_feed->getFeed($params);
        
                foreach ($posts['data'] as $post) {

                    $date_updated = new \DateTime($post['updated_time']);
                    $strtotime_date_updated =  strtotime($date_updated->format('Y-m-d'));
                    $strtotime_date_yesterday =  strtotime(\Carbon\Carbon::now()->subDay()->format('Y-m-d'));
                   // $reactions = $this->getReactions($post['id'], $fb_feed, $access_token);
                                                              
                    $post = FbPagePost::updateOrCreate(
                            [
                                'post_id' => $post['id'],                                
                            ],    
                            [
                                'fb_page_monitor_id' => 3,
                                'message' => isset($post['message']) ? $post['message']: null,
                                'permalink_url' => isset($post['permalink_url']) ? $post['permalink_url']: null,
                                'updated_time' => isset($post['updated_time']) ? $post['updated_time']: null,                                                                                                            
                                //'comment_count' => $reactions['qtd_comments'],
                                //'share_count' => $reactions['qtd_shares'],
                            ]);    

                    // $reaction_buffer = [];
                    // foreach ($reactions['types'] as $type => $qtd) {
                    //     if($qtd > 0) {

                    //         $reaction = constant('App\Enums\FbReaction::'. $type);
                    //         $reaction_buffer[$reaction] = ['count' => $qtd];
                                       
                    //     }                                    
                    // }

                    // if (!empty($reaction_buffer)) {
                    //     $post->reactions()->sync($reaction_buffer);
                    // }
                }            
                              
                $after = $fb_feed->getAfter($posts);
        
            } while($fb_feed->hasAfter($posts) && ($strtotime_date_updated >= $strtotime_date_yesterday));
                       
                
            
        }
    }

    public function getReactions($post_id, $fb_feed, $access_token) {

        $params = [
            'fields' => $fb_feed->getFBReactionsFields($post_id),
            'access_token' => $access_token
        ];

        $post_reactions = $fb_feed->getFBPostReactions($post_id,$params);

        $reactions = [
            'id' => $post_reactions['id'],
            'qtd_shares' => isset($post_reactions['shares']['count']) ? $post_reactions['shares']['count'] : null,
            'qtd_comments' => isset($post_reactions['comments']['summary']['total_count']) ? $post_reactions['comments']['summary']['total_count'] : null,
            'types' => [
                'LIKE' => isset($post_reactions['LIKE']['summary']['total_count']) ? $post_reactions['LIKE']['summary']['total_count'] : null,
                'LOVE' => isset($post_reactions['LOVE']['summary']['total_count']) ? $post_reactions['LOVE']['summary']['total_count'] : null,
                'WOW' => isset($post_reactions['WOW']['summary']['total_count']) ? $post_reactions['WOW']['summary']['total_count'] : null,
                'HAHA' => isset($post_reactions['HAHA']['summary']['total_count']) ? $post_reactions['HAHA']['summary']['total_count'] : null,
                'SAD' => isset($post_reactions['SAD']['summary']['total_count']) ? $post_reactions['SAD']['summary']['total_count'] : null,
                'ANGRY' => isset($post_reactions['ANGRY']['summary']['total_count']) ? $post_reactions['ANGRY']['summary']['total_count'] : null,
                'THANKFUL' => isset($post_reactions['THANKFUL']['summary']['total_count']) ? $post_reactions['THANKFUL']['summary']['total_count'] : null
            ]
        ];

        return $reactions;
        
    }
}
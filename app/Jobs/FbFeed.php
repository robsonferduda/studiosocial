<?php

namespace App\Jobs;

use App\Classes\FBFeedApi;
use App\FbPagePost;
use App\FbPagePostComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FbFeed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3000;
    protected $page;
    protected $token;
    protected $limit;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($page)
    {
        $this->page = $page;
        $this->token = env('COLETA1');
        $hour = \Carbon\Carbon::now()->format('H');
        $this->limit = (int) $hour%2 == 0 ? 5 : 20;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $id_page_id = $this->page->page_id;

        $fb_feed = new FBFeedApi($id_page_id);
        $after = '';

        do {

            $params = [
                'fields' => $fb_feed->getFbPostFields().','.$fb_feed->getFBReactionsFields(),
                'access_token' => $this->token,
                'after' => $after,
                'since' => \Carbon\Carbon::now()->subDays(5)->toDateString(),
                'limit' => $this->limit
            ];

            $posts = $fb_feed->getFeed($params);

            if(empty($posts['data'])) {
                continue;
            }

            foreach ($posts['data'] as $post) {
                $reactions = $this->getReactions($post);
                $comments = [];

                // if(!empty($post['message'])) {
                //     if(isLanguagePortuguese($post['message']) == false)
                //         continue;
                // }

                if(isset($post['comments']['data'])) {
                    $comments = $post['comments'];
                }

                $post = FbPagePost::updateOrCreate(
                        [
                            'post_id' => $post['id'],
                            'fb_page_monitor_id' => $this->page->id,
                        ],
                        [
                            'message' => isset($post['message']) ? $post['message']: null,
                            'permalink_url' => isset($post['permalink_url']) ? $post['permalink_url']: null,
                            'updated_time' => isset($post['updated_time']) ? $post['updated_time']: null,
                            'comment_count' => $reactions['qtd_comments'],
                            'share_count' => $reactions['qtd_shares'],
                        ]);

                foreach($comments['data'] as $comment) {

                    $reactions_comment = $this->getReactions($comment);

                    $comment_bd = FbPagePostComment::updateOrCreate(
                    [
                        'page_post_id' => $post['id'],
                        'created_time' => \Carbon\Carbon::parse($comment['created_time'])->toDateTimeString()
                    ],
                    [
                        'text' => $comment['message'],

                    ]);

                    $reaction_comment_buffer = [];
                    foreach ($reactions_comment['types'] as $type => $qtd) {
                        if($qtd > 0) {

                            $reaction = constant('App\Enums\FbReaction::'. $type);
                            $reaction_comment_buffer[$reaction] = ['count' => $qtd];

                        }
                    }

                    if (!empty($reaction_comment_buffer)) {
                        $comment_bd->reactions()->sync($reaction_comment_buffer);
                    }

                    if(isset($comment['comments']['data'])) {
                        foreach($comment['comments']['data'] as $relatedComment) {

                            $reactions_comment_related = $this->getReactions($relatedComment);

                            $comment_bd_related = FbPagePostComment::updateOrCreate(
                                [
                                    'page_post_id' => $post['id'],
                                    'related_to' => $comment_bd['id'],
                                    'created_time' => \Carbon\Carbon::parse($relatedComment['created_time'])->toDateTimeString()
                                ],
                                [
                                    'text' => $relatedComment['message'],
                                ]);


                            $reaction_comment_related_buffer = [];
                            foreach ($reactions_comment_related['types'] as $type => $qtd) {
                                if($qtd > 0) {

                                    $reaction = constant('App\Enums\FbReaction::'. $type);
                                    $reaction_comment_related_buffer[$reaction] = ['count' => $qtd];

                                }
                            }

                            if (!empty($reaction_comment_related_buffer)) {
                                $comment_bd_related->reactions()->sync($reaction_comment_related_buffer);
                            }
                        }
                    }

                }

                $reaction_buffer = [];
                foreach ($reactions['types'] as $type => $qtd) {
                    if($qtd > 0) {

                        $reaction = constant('App\Enums\FbReaction::'. $type);
                        $reaction_buffer[$reaction] = ['count' => $qtd];

                    }
                }

                if (!empty($reaction_buffer)) {
                    $post->reactions()->sync($reaction_buffer);
                }
            }

            $after = $fb_feed->getAfter($posts);
            
        } while($fb_feed->hasAfter($posts) && count($posts['data']) >= 20);

    }

    public function getReactions($post_reactions) {

        $reactions = [
            'id' => isset($post_reactions['id']) ? isset($post_reactions['id']) : null,
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

<?php

namespace App\Classes;

use App\FbPagePost;
use Illuminate\Support\Carbon;

class FBPost{

    public function pullReactions()
    {
        set_time_limit(0);

        ini_set("memory_limit","2048M");

        $posts = FbPagePost::where('created_at', '>=', Carbon::now()->subMonths(1)->toDateString())->select('id', 'post_id')->get();

        $token = env('COLETA1');

        $fb_feed = new FBFeedApi(0);

        $params = [
            'fields' => $fb_feed->getFBReactionsFields(),
            'access_token' => $token
        ];

        foreach ($posts as $post) {

            $post_reactions = $fb_feed->getFBPostReactions($post->post_id, $params);

            $reactions = $this->getReactions($post_reactions);

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

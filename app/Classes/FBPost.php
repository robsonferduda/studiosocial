<?php

namespace App\Classes;

use App\FbPagePost;
use Illuminate\Support\Carbon;

class FBPost{

    public function pullReactionsFiltered()
    {
        set_time_limit(0);

        ini_set("memory_limit","2048M");

        $posts = FbPagePost::has('terms')->where('created_at', '>=', Carbon::now()->subMonths(1)->toDateString())->select('id', 'post_id')->get();

        $token = env('COLETA2');

        $fb_feed = new FBFeedApi(0);

        $params = [
            'fields' => $this->getFBReactionsFields(),
            'access_token' => $token
        ];

        foreach ($posts as $post) {
            sleep(1);
            $this->getReactionsPost($post, $fb_feed, $params);
        }

        $posts = FbPagePost::has('hashtags')->where('created_at', '>=', Carbon::now()->subMonths(1)->toDateString())->select('id', 'post_id')->get();

        foreach ($posts as $post) {
            sleep(1);
            $this->getReactionsPost($post, $fb_feed, $params);
        }

    }

    public function pullReactions()
    {
        set_time_limit(0);

        ini_set("memory_limit","2048M");

        $posts = FbPagePost::where('created_at', '>=', Carbon::now()->subMonths(1)->toDateString())->select('id', 'post_id')->get();

        $token = env('COLETA2');

        $fb_feed = new FBFeedApi(0);

        $params = [
            'fields' => $this->getFBReactionsFields(),
            'access_token' => $token
        ];

        foreach ($posts as $post) {
            $this->getReactionsPost($post, $fb_feed, $params);
        }

    }

    public function getReactionsPost($post, $fb_feed, $params)
    {
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

    public function getReactions($post_reactions) {

        $reactions = [
            'id' => isset($post_reactions['id']) ? isset($post_reactions['id']) : null,
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

    public function getFBReactionsFields()
    {
        $fields = [
            'reactions.type(LIKE).limit(0).summary(true).as(LIKE)',
            'reactions.type(LOVE).limit(0).summary(true).as(LOVE)',
            'reactions.type(WOW).limit(0).summary(true).as(WOW)',
            'reactions.type(HAHA).limit(0).summary(true).as(HAHA)',
            'reactions.type(SAD).limit(0).summary(true).as(SAD)',
            'reactions.type(ANGRY).limit(0).summary(true).as(ANGRY)',
            'reactions.type(THANKFUL).limit(0).summary(true).as(THANKFUL)'
        ];

        return implode(',',$fields);
    }
}

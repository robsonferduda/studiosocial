<?php

namespace App\Classes;

use App\Client;
use App\FbPost;

class FBMention{

    function __construct() {
        
    }

    public function pullMedias()
    {
        $clients = Client::get();

        foreach ($clients as $client) {
            
            foreach ($client->fbAccounts as $fbAccount) {

                if($fbAccount->mention === false) {
                    continue;
                }

                foreach ($fbAccount->fbPages as $fbPage) {

                    $access_token = $fbPage->token;
                    $id_page_id = $fbPage->page_id;

                    $fb_mention = new FBMentionApi($id_page_id);
                    $after = '';
                        
                    do {
                            
                        $params = [
                            'fields' => $fb_mention->getFBMentionFields(),
                            'access_token' => $access_token,
                            'after' => $after,
                            'limit' => 50
                        ];
        
                        $posts = $fb_mention->getMentions($params);
                        
                        foreach ($posts['data'] as $post) {

                                $date_tagged = new \DateTime($post['tagged_time']);

                                $strtotime_date_tagged =  strtotime($date_tagged->format('Y-m-d'));
                                $strtotime_date_yesterday =  strtotime(\Carbon\Carbon::now()->subDay()->format('Y-m-d'));

                                $reactions = $this->getReactions($post['id'], $fb_mention, $access_token);
                                                               
                                $post = FbPost::updateOrCreate(
                                        [
                                            'post_id' => $post['id'],
                                            'client_id' => $client->id
                                        ],    
                                        [
                                            'message' => isset($post['message']) ? $post['message']: null,
                                            'permalink_url' => isset($post['permalink_url']) ? $post['permalink_url']: null,
                                            'updated_time' => isset($post['updated_time']) ? $post['updated_time']: null,                    
                                            'tagged_time' => isset($post['tagged_time']) ? $post['tagged_time']: null,                            
                                            'mentioned' => 'S',
                                            'comment_count' => $reactions['qtd_comments'],
                                            'share_count' => $reactions['qtd_shares'],
                                        ]);    
                        }            
                               
                        $after = $fb_mention->getAfter($posts);
        
                    } while($fb_mention->hasAfter($posts) && ($strtotime_date_tagged >= $strtotime_date_yesterday));
                       
                }
            }
        }
    }

    public function getReactions($post_id, $fb_mention, $access_token) {

        $params = [
            'fields' => $fb_mention->getFBReactionsFields($post_id),
            'access_token' => $access_token
        ];

        $post_reactions = $fb_mention->getFBPostReactions($post_id,$params);

        $reactions = [
            'id' => $post_reactions['id'],
            'qtd_shares' => isset($post_reactions['shares']['count']) ? $post_reactions['shares']['count'] : null,
            'qtd_comments' => isset($post_reactions['comments']['summary']['total_count']) ? $post_reactions['comments']['summary']['total_count'] : null,
            'qtd_like' => isset($post_reactions['LIKE']['summary']['total_count']) ? $post_reactions['LIKE']['summary']['total_count'] : null,
            'qtd_love' => isset($post_reactions['LOVE']['summary']['total_count']) ? $post_reactions['LOVE']['summary']['total_count'] : null,
            'qtd_wow' => isset($post_reactions['WOW']['summary']['total_count']) ? $post_reactions['WOW']['summary']['total_count'] : null,
            'qtd_haha' => isset($post_reactions['HAHA']['summary']['total_count']) ? $post_reactions['HAHA']['summary']['total_count'] : null,
            'qtd_sad' => isset($post_reactions['SAD']['summary']['total_count']) ? $post_reactions['SAD']['summary']['total_count'] : null,
            'qtd_angry' => isset($post_reactions['ANGRY']['summary']['total_count']) ? $post_reactions['ANGRY']['summary']['total_count'] : null,
            'qtd_thankful' => isset($post_reactions['THANKFUL']['summary']['total_count']) ? $post_reactions['THANKFUL']['summary']['total_count'] : null

        ];

        return $reactions;
        
    }

    // public function getMediaWebHook($id, $changes)
    // {
    //     //$changes['media_id'] = '18194579986190935';
    //     //$id = '17841437726599322';

    //     $ig_mention = new IGMentionApi($id);

    //     $igPages =  IgPage::where('page_id', $id)->get();

    //     foreach($igPages as $igPage) {
    //         $params = [
    //             'fields' => "mentioned_media.media_id({$changes['media_id']}){{$ig_mention->getIGMentionFields()}}",
    //             'access_token' => $igPage->fbPage->fbAccount->token
    //         ];
    
    //         $media = $ig_mention->getMetionHooked($params);

    //         $media = $media['mentioned_media'];

    //         $media = Media::updateOrCreate(
    //             [
    //                 'media_id' => $media['id'],
    //                 'client_id' => $igPage->fbPage->fbAccount->client_id
    //             ],    
    //             [
    //                 'caption' => isset($media['caption']) ? $media['caption']: null,
    //                 'comments_count' => isset($media['comments_count']) ? $media['comments_count']: null,
    //                 'media_product_type' => isset($media['media_product_type']) ? $media['media_product_type']: null,                    
    //                 'like_count' => isset($media['like_count']) ? $media['like_count']: null,
    //                 'media_type' => isset($media['media_type']) ? $media['media_type']: null,
    //                 'media_url' => isset($media['media_url']) ? $media['media_url'] : null,
    //                 'timestamp' =>  isset($media['timestamp']) ? $media['timestamp']: null,
    //                 'permalink' =>  isset($media['permalink']) ? $media['permalink']: null,
    //                 'username' =>  isset($media['username']) ? $media['username']: null,
    //                 'video_title' =>  isset($media['video_title']) ? $media['video_title']: null,                                    
    //                 'mentioned' => 'S'
    //         ]);        
    //     }
    // }
}
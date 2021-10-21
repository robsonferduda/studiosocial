<?php

namespace App\Classes;

use App\Media;
use App\Client;
use App\IgPage;
use Illuminate\Support\Facades\Log;

class IGMention{

    function __construct() {
        
    }

    public function pullMedias()
    {
        $clients = Client::get();

        foreach ($clients as $client) {
            
            foreach ($client->fbAccounts as $fbAccount) {

                foreach ($fbAccount->fbPages as $fbPage) {

                    if(isset($fbPage->igPage)){

                        $access_token = $fbAccount->token;
                        $id_user_id = $fbPage->igPage->page_id;

                        $ig_mention = new IGMentionApi($id_user_id);
                        $after = '';

                        $medias_buffer = [];
                        $medias_limit_not_ordered = 0;

                        do {
                            
                            $params = [
                                'fields' => $ig_mention->getIGMentionFields(),
                                'access_token' => $access_token,
                                'after' => $after,
                                'limit' => 50
                            ];
        
                            $medias = $ig_mention->getMentions($params);
                                              
                            foreach ($medias['data'] as $media) {

                                $date_created = new \DateTime($media['timestamp']);

                                $strtotime_date_created =  strtotime($date_created->format('Y-m-d'));
                                $strtotime_date_yesterday =  strtotime(\Carbon\Carbon::now()->subDay()->format('Y-m-d'));
                                
                                if($strtotime_date_created < $strtotime_date_yesterday) {
                                    $medias_buffer[] = $media;
                                    $medias_limit_not_ordered++;
                                } else {
                                 
                                    $media = Media::updateOrCreate(
                                        [
                                            'media_id' => $media['id'],
                                            'client_id' => $client->id
                                        ],    
                                        [
                                            'caption' => isset($media['caption']) ? $media['caption']: null,
                                            'comments_count' => isset($media['comments_count']) ? $media['comments_count']: null,
                                            'media_product_type' => isset($media['media_product_type']) ? $media['media_product_type']: null,                    
                                            'like_count' => isset($media['like_count']) ? $media['like_count']: null,
                                            'media_type' => isset($media['media_type']) ? $media['media_type']: null,
                                            'media_url' => isset($media['media_url']) ? $media['media_url'] : null,
                                            'timestamp' =>  isset($media['timestamp']) ? $media['timestamp']: null,
                                            'permalink' =>  isset($media['permalink']) ? $media['permalink']: null,
                                            'username' =>  isset($media['username']) ? $media['username']: null,
                                            'video_title' =>  isset($media['video_title']) ? $media['video_title']: null,                                        
                                            'mentioned' => 'S'
                                        ]);                                  
                                    $medias_limit_not_ordered = 0;
                                }                           
                            }            
                               
                            $after = $ig_mention->getAfter($medias);
        
                        } while($ig_mention->hasAfter($medias) && ($strtotime_date_created >= $strtotime_date_yesterday) && $medias_limit_not_ordered <= 5);
                       
                        foreach($medias_buffer as $media) {

                            $media = Media::updateOrCreate(
                                [
                                    'media_id' => $media['id'],
                                    'client_id' => $client->id
                                ],    
                                [
                                    'caption' => isset($media['caption']) ? $media['caption']: null,
                                    'comments_count' => isset($media['comments_count']) ? $media['comments_count']: null,
                                    'media_product_type' => isset($media['media_product_type']) ? $media['media_product_type']: null,                    
                                    'like_count' => isset($media['like_count']) ? $media['like_count']: null,
                                    'media_type' => isset($media['media_type']) ? $media['media_type']: null,
                                    'media_url' => isset($media['media_url']) ? $media['media_url'] : null,
                                    'timestamp' =>  isset($media['timestamp']) ? $media['timestamp']: null,
                                    'permalink' =>  isset($media['permalink']) ? $media['permalink']: null,
                                    'username' =>  isset($media['username']) ? $media['username']: null,
                                    'video_title' =>  isset($media['video_title']) ? $media['video_title']: null,                                    
                                    'mentioned' => 'S'
                            ]);                                 
                        }
                    }
                }
            }
        }
    }

    public function getMediaWebHook($id, $changes)
    {

        $changes['media_id'] = '18194579986190935';
        $id = '17841437726599322';

        $ig_mention = new IGMentionApi($id);

        $igPages =  IgPage::where('page_id', $id)->get();

        foreach($igPages as $igPage) {
            $params = [
                'fields' => "mentioned_media.media_id({$changes['media_id']}){{$ig_mention->getIGMentionFields()}}",
                'access_token' => $igPage->fbPage->fbAccount->token
            ];
    
            $media = $ig_mention->getMetionHooked($params);

            Log::warning($media);

            exit;

            $media = Media::updateOrCreate(
                [
                    'media_id' => $media['id'],
                    'client_id' => $igPage->fbPage->fbAccount->client_id
                ],    
                [
                    'caption' => isset($media['caption']) ? $media['caption']: null,
                    'comments_count' => isset($media['comments_count']) ? $media['comments_count']: null,
                    'media_product_type' => isset($media['media_product_type']) ? $media['media_product_type']: null,                    
                    'like_count' => isset($media['like_count']) ? $media['like_count']: null,
                    'media_type' => isset($media['media_type']) ? $media['media_type']: null,
                    'media_url' => isset($media['media_url']) ? $media['media_url'] : null,
                    'timestamp' =>  isset($media['timestamp']) ? $media['timestamp']: null,
                    'permalink' =>  isset($media['permalink']) ? $media['permalink']: null,
                    'username' =>  isset($media['username']) ? $media['username']: null,
                    'video_title' =>  isset($media['video_title']) ? $media['video_title']: null,                                    
                    'mentioned' => 'S'
            ]);        
        }
    }
}
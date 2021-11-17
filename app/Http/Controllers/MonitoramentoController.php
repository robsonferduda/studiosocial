<?php

namespace App\Http\Controllers;

use App\FbPost;
use App\Media;
use App\MediaTwitter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MonitoramentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','monitoramento');
    }

    public function index()
    {
        $totais = array();
        $client_id = Session::get('cliente')['id'];

        if($client_id){
            $totais = array('total_insta' => Media::where('client_id',$client_id)->count(),
                            'total_face' => FbPost::where('client_id',$client_id)->count(),
                            'total_twitter' => MediaTwitter::where('client_id',$client_id)->count());
        }

        return view('monitoramento/index', compact('totais'));
    }

    public function seleciona($rede)
    {
        $client_id = Session::get('cliente')['id'];
        $medias = array();

        switch ($rede) {
            case 'instagram':
                $medias_temp = Media::where('client_id', $client_id)->paginate(20);
                foreach ($medias_temp as $key => $media) {
                    
                    $medias[] = array('id' => $media->media_id,
                                      'text' => $media->caption,
                                      'username' => '',
                                      'created_at' => $media->timestamp,
                                      'like_count' => $media->like_count,
                                      'comments_count' => $media->like_count,
                                      'social_media_id' => $media->social_media_id,
                                      'tipo' => 'instagram');

                }
                break;

            case 'facebook':
                $medias_temp = FbPost::where('client_id', $client_id)->paginate(20);
                foreach ($medias_temp as $key => $media) {
                    
                    $medias[] = array('id' => $media->id,
                                      'text' => $media->message,
                                      'username' => '',
                                      'created_at' => $media->tagged_time,
                                      'like_count' => $media->like_count,
                                      'comments_count' => $media->like_count,
                                      'social_media_id' => $media->social_media_id,
                                     'tipo' => 'facebook');

                }
                break;
            
            case 'twitter':
                $medias_temp = MediaTwitter::where('client_id', $client_id)->paginate(20);
                foreach ($medias_temp as $key => $media) {
                    
                    $medias[] = array('id' => $media->twitter_id,
                                      'text' => $media->full_text,
                                      'username' => $media->user_name,
                                      'created_at' => $media->created_tweet_at,
                                      'like_count' => $media->favorite_count,
                                      'comments_count' => 0,
                                      'social_media_id' => $media->social_media_id,
                                      'tipo' => 'twitter');

                }
            break;
        }

        return view('monitoramento/medias', compact('medias', 'medias_temp'));
    }
}
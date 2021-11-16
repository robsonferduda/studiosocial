<?php

namespace App\Http\Controllers;

use App\Hashtag;
use App\Enums\SocialMedia;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HashtagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
    }

    public function atualizarSituacao($hashtag)
    {
        $hashtag = Hashtag::find($hashtag);
        $hashtag->is_active = !$hashtag->is_active;
        $hashtag->save();
        
        return redirect('client/hashtags/'.$hashtag->client->id);
    }

    public function medias($hashtag)
    {
        $hashtag = Hashtag::find($hashtag);
        $medias = array();

        switch ($hashtag->social_media_id) {
            case SocialMedia::INSTAGRAM:
                $medias_temp = $hashtag->medias()->orderBy('timestamp', 'DESC')->paginate(20);
                
                foreach ($medias_temp as $key => $media) {
                    
                    $medias[] = array('id' => $media->media_id,
                                      'text' => $media->caption,
                                      'username' => '',
                                      'created_at' => $media->timestamp,
                                      'like_count' => $media->like_count,
                                      'comments_count' => $media->like_count,
                                      'social_media_id' => $media->social_media_id);

                }
                break;

            case SocialMedia::TWITTER:
                $medias_temp = $hashtag->mediasTwitter()->orderBy('created_tweet_at', 'DESC')->paginate(20);
                foreach ($medias_temp as $key => $media) {
                    
                    $medias[] = array('id' => $media->twitter_id,
                                      'text' => $media->full_text,
                                      'username' => $media->user_name,
                                      'created_at' => $media->created_tweet_at,
                                      'like_count' => $media->favorite_count,
                                      'comments_count' => 0,
                                      'social_media_id' => $media->social_media_id);

                }
                break;
            
            default:
                //
                break;
        }

        return view('hashtags/medias', compact('hashtag','medias','medias_temp'));
    }

    public function create(Request $request)
    {
        if($request->social_media and $request->hashtag)
        {
            for ($i=0; $i < count($request->social_media); $i++) { 
                
                $dados = array('hashtag' => $request->hashtag,
                               'client_id' => $request->client_id,
                               'social_media_id' => $request->social_media[$i] );

                Hashtag::create($dados);
            }
            Flash::success('<i class="fa fa-check"></i> Cadastro de hashtag realizado com sucesso');

        }else{
            Flash::info('<i class="fa fa-info"></i> Informe uma hashtag e uma Mídia Social para realizar o cadastro');
        }
        return redirect('client/hashtags/'.$request->client_id);
    }
}
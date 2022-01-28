<?php

namespace App\Http\Controllers;

use App\Utils;
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
        $lista_hastags = array();

        switch ($hashtag->social_media_id) {
            case SocialMedia::INSTAGRAM:
                $medias_temp = $hashtag->medias()->orderBy('timestamp', 'DESC')->paginate(20);
                
                foreach ($medias_temp as $key => $media) {

                    $lista_hastags = Utils::getHashtags($media->caption, $lista_hastags);
                    
                    $medias[] = array('id' => $media->id,
                                      'media_id' => $media->media_id,  
                                      'type_message' => 'instagram',   
                                      'text' => $media->caption,
                                      'username' => '',
                                      'sentiment' => $media->sentiment,
                                      'user_profile_image_url' => null,
                                      'created_at' => dateTimeUtcToLocal($media->timestamp),
                                      'like_count' => $media->like_count,
                                      'comments_count' => $media->like_count,
                                      'social_media_id' => $media->social_media_id,
                                      'link' => $media->permalink
                                    );

                }
                break;

            case SocialMedia::TWITTER:
                $medias_temp = $hashtag->mediasTwitter()->orderBy('created_tweet_at', 'DESC')->paginate(20);
                foreach ($medias_temp as $key => $media) {

                    $lista_hastags = Utils::getHashtags($media->full_text, $lista_hastags);                   
                    
                    $medias[] = array('id' => $media->id,
                                      'media_id' => $media->twitter_id,
                                      'type_message' => 'tweets', 
                                      'text' => $media->full_text,
                                      'username' => $media->user_name,
                                      'created_at' => dateTimeUtcToLocal($media->created_tweet_at),
                                      'like_count' => $media->favorite_count,
                                      'comments_count' => 0,
                                      'sentiment' => $media->sentiment,
                                      'user_profile_image_url' => $media->user_profile_image_url,
                                      'user_followers_count' => $media->user_followers_count,
                                      'user_friends_count' => $media->user_friends_count,
                                      'social_media_id' => $media->social_media_id,
                                      'link' => 'https://twitter.com/'.$media->user_screen_name.'/status/'.$media->twitter_id,
                                      'retweet_count' => $media->retweet_count);

                }
                break;
            
            default:
                //
                break;
        }
        
        Utils::contaOrdenaLista($lista_hastags);

        return view('hashtags/medias', compact('hashtag','medias','medias_temp'));
    }

    public function create(Request $request)
    {
        if($request->social_media and $request->hashtag)
        {
            for ($i=0; $i < count($request->social_media); $i++) { 
                
                $dados = array('hashtag' => strtolower($request->hashtag),
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

    public function destroy($id)
    {
        $hashtag = Hashtag::with('client')->find($id);
        
        if($hashtag->delete())
            Flash::success('<i class="fa fa-check"></i> Hashtag <strong>'.$hashtag->hashtag.'</strong> excluída com sucesso');
        else
            Flash::error("Erro ao excluir a hashtag");

        return redirect('client/hashtags/'.$hashtag->client->id);
    }
}
<?php

namespace App\Http\Controllers;

use App\Term;
use App\Client;
use App\Enums\FbReaction;
use App\SocialMedia;
use App\Enums\SocialMedia as SM;
use App\FbPagePost;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TermController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
    }

    public function getTerms($client_id)
    {
        $client = Client::with(['terms' => function($query){
            $query->withCount('mediasTwitter')->withCount('medias')->withCount('pagePosts');
        }])->find($client_id);

        $social_medias = SocialMedia::where('fl_term',true)->orderBy('name')->get();
        return view('clientes/terms', compact('client','social_medias'));
    }

    public function atualizarSituacao($term_id)
    {
        $term = Term::find($term_id);
        $term->is_active = !$term->is_active;
        $term->save();
        
        return redirect('terms/client/'.$term->client->id);
    }

    public function medias($term_id)
    {
        $term = Term::find($term_id);
        $medias = array();

        switch ($term->social_media_id) {
            case SM::INSTAGRAM:
                $medias_temp = $hashtag->medias()->orderBy('timestamp', 'DESC')->paginate(20);
                
                foreach ($medias_temp as $key => $media) {
                    
                    $medias[] = array('id' => $media->id,
                                      'media_id' => $media_id,  
                                      'type_message' => 'instagram',   
                                      'text' => $media->caption,
                                      'username' => '',
                                      'sentiment' => $media->sentiment,
                                      'created_at' => dateTimeUtcToLocal($media->timestamp),
                                      'like_count' => $media->like_count,
                                      'comments_count' => $media->like_count,
                                      'social_media_id' => $media->social_media_id,
                                      'link' => $media->permalink
                                    );

                }
                break;

            case SM::TWITTER:
                $medias_temp = $term->mediasTwitter()->orderBy('created_tweet_at', 'DESC')->paginate(20);
                foreach ($medias_temp as $key => $media) {
                    
                    $medias[] = array('id' => $media->id,
                                      'media_id' => $media->twitter_id,
                                      'type_message' => 'twitter', 
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
            case SM::FACEBOOK:
               
                $medias_temp = $term->pagePosts()->orderBy('updated_time', 'DESC')->paginate(20);
        
                $medias = [];
        
                foreach ($medias_temp as $key => $media) {
                    
                    // $bag_comments = [];
                    // if ($media->comments) {
                    //     foreach($media->comments as $comment) {
                    //         $bag_comments[] = ['text' => $comment->text, 'created_at' => $comment->timestamp];
                    //     }p
                    // }
        
                    $likes_count = 0;
                    $loves = $media->reactions()->wherePivot('reaction_id',FbReaction::LOVE)->first();                
                    $likes = $media->reactions()->wherePivot('reaction_id',FbReaction::LIKE)->first();
                    if(!empty($loves)) {
                        $likes_count += $loves->pivot->count;
                    }
        
                    if(!empty($likes)) {
                        $likes_count += $likes->pivot->count;
                    }
        
                    $medias[] = array('id' => $media->id,
                        'text' => $media->message,
                        'username' => $media->page->name,
                        'created_at' => dateTimeUtcToLocal($media->updated_time),
                        'sentiment' => '',
                        'type_message' => 'facebook-page',
                        'like_count' => $likes_count,
                        'comments_count' => !empty($media->comment_count) ? $media->comment_count : 0,
                        'social_media_id' => $media->social_media_id,
                        'tipo' => 'facebook',
                        'comments' => [],
                        'link' => $media->permalink_url,
                        'share_count' => !empty($media->share_count) ? $media->share_count : 0,
                        'user_profile_image_url' => $media->page->picture_url
                     );
        
                }
            
            default:
                //
                break;
        }

        return view('term/medias', compact('term','medias','medias_temp'));
    }

    public function store(Request $request)
    {
        if($request->social_media and $request->term)
        {
            for ($i=0; $i < count($request->social_media); $i++) { 
                
                $dados = array('term' => $request->term,
                               'client_id' => $request->client_id,
                               'social_media_id' => $request->social_media[$i] );

                Term::create($dados);
            }
            Flash::success('<i class="fa fa-check"></i> Cadastro de termo realizado com sucesso');

        }else{
            Flash::info('<i class="fa fa-info"></i> Informe um termo e uma Mídia Social para realizar o cadastro');
        }
        return redirect('terms/client/'.$request->client_id);
    }

    public function destroy($id)
    {
        $term = Term::find($id);
 
        if($term->delete())
            Flash::success('<i class="fa fa-check"></i> Termo <strong>'.$term->term.'</strong> excluído com sucesso');
        else
            Flash::error("Erro ao excluir o termo");

        return redirect('terms/client/'.$term->client->id);
    }
}
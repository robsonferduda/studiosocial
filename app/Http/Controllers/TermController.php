<?php

namespace App\Http\Controllers;

use App\Term;
use App\Client;
use App\SocialMedia;
use App\Enums\SocialMedia as SM;
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
            $query->withCount('mediasTwitter')->withCount('medias');
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
                    
                    $medias[] = array('id' => $media->media_id,
                                      'text' => $media->caption,
                                      'username' => '',
                                      'created_at' => dateTimeUtcToLocal($media->timestamp),
                                      'like_count' => $media->like_count,
                                      'comments_count' => $media->like_count,
                                      'social_media_id' => $media->social_media_id);

                }
                break;

            case SM::TWITTER:
                $medias_temp = $term->mediasTwitter()->orderBy('created_tweet_at', 'DESC')->paginate(20);
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
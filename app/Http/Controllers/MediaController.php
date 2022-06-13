<?php

namespace App\Http\Controllers;

use DB;
use DOMPDF;
use Carbon\Carbon;
use App\FbPost;
use App\Media;
use App\MediaTwitter;
use App\FbPagePost;
use App\FbPagePostComment;
use App\MediaFilteredVw;
use App\MediaRuleFilteredVw;
use App\Configs;
use App\Enums\TypeMessage;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MediaController extends Controller
{
    private $client_id;
    private $periodo_padrao;
    private $flag_regras;
    private $mediaModel;

    public function __construct()
    {
        $this->middleware('auth');
        $this->client_id = session('cliente')['id'];
        $this->periodo_padrao = Configs::where('key', 'periodo_padrao')->first()->value;
        Session::put('url','monitoramento');
        $this->flag_regras = Session::get('flag_regras');

        if($this->flag_regras) {
            $this->mediaModel = new MediaRuleFilteredVw();
        } else {
            $this->mediaModel = new MediaFilteredVw();
        }
    }

    public function index()
    {
        
    }

    const FB_POSTS = 1;
    const FB_COMMENT = 2;
    const TWEETS = 3;
    const IG_POSTS = 4;
    const IG_COMMENT = 5;
    const FB_PAGE_POST = 7;
    const FB_PAGE_POST_COMMENT = 8;

    public function atualizaSentimento($id, $tipo, $sentimento)
    {
        $media = null;

        switch ($tipo) {
            case 'twitter':
                $media = MediaTwitter::where('id',$id)->first();
                break;
            
            case 'facebook':
                $media = FbPost::where('id',$id)->first();
                break;

            case 'facebook-page':
                $media = FbPagePost::where('id',$id)->first();
                break;
                
            case 'instagram':
                $media = Media::where('id',$id)->first();
                break;
            
            case 'facebook-page-comment':
                $media = FbPagePostComment::where('id',$id)->first();
                break;
        }

        if($media){
            $media->sentiment = $sentimento;
    
            if($media->update()){
                Flash::success('<i class="fa fa-check"></i> Sentimento da mídia atualizado com sucesso');
            }else{
                Flash::error('<i class="fa fa-check"></i> Erro ao atualizar o sentimento da mídia');
            }

        }else{
            Flash::warning('<i class="fa fa-exclamation"></i> Mídia não encontrada');
        }
        
        return redirect()->back()->withInput();
    }

    public function excluir($id, $tipo)
    {
        $media = null;

        switch ($tipo) {
            case 'twitter':
                $media = MediaTwitter::where('id',$id)->first();
                break;
            
            case 'facebook':
                $media = FbPost::where('id',$id)->first();
                break;
            case 'facebook-page':
                $media = FbPagePost::where('id',$id)->first();
                break;
            case 'facebook-page-comment':
                $media = FbPagePostComment::where('id',$id)->first();
                break;
            case 'instagram':
                $media = Media::where('id',$id)->first();
                break;
        }

        if($media){

            if($media->delete()){
                Flash::success('<i class="fa fa-check"></i> Mídia excluída com sucesso');
            }else{
                Flash::error('<i class="fa fa-check"></i> Erro ao excluir mídia');
            }

        }else{
            Flash::warning('<i class="fa fa-exclamation"></i> Mídia não encontrada');
        }
        
        return redirect()->back()->withInput();

    }

    public function relatorio($rede)
    {
        $nome = "Relatório de Redes Sociais";
        $dt_inicial = '10/10/2022';
        $dt_final = '20/10/2022';

        $client_id = Session::get('cliente')['id'];
        $medias = array();

        switch ($rede) {

            case 'instagram':
                $medias = $this->getMediasInstagram();
                break;

            case 'facebook':
                $medias = $this->getMediasFacebook();
                break;
            
            case 'twitter':
                $medias = $this->getMediasTwitter();
                
            break;

            case 'todos':
                $medias_i = $this->getMediasInstagram();
                $medias_t = $this->getMediasTwitter();
                $medias_f = $this->getMediasFacebook();                

                $medias = array_merge($medias_i, $medias_t, $medias_f);
        }
        
        $pdf = DOMPDF::loadView('medias/relatorio', compact('nome','dt_inicial','dt_final','medias'));
        return $pdf->download("Teste.pdf");
    }

    function getMediasInstagram()
    {
        $medias = array();
        $client_id = Session::get('cliente')['id'];
        $medias_temp =  $this->mediaModel::where('tipo', 'IG_POSTS');

        $medias_temp = $medias_temp->where('client_id', $this->client_id)
        ->select('id', 'text', 'date', 'sentiment', 'name', 'link', 'img_link', 'comment_count', 'share_count', 'like_count', 'client_id')
        ->groupBy('id', 'text', 'date', 'sentiment', 'name', 'link', 'img_link', 'comment_count', 'share_count', 'like_count', 'client_id')
        ->orderBy('date', 'DESC')->get();

        foreach($medias_temp as $media) {

            $medias[] = array(  'id' => $media->id,                                       
                                'text' => $media->text,
                                'username' => $media->name,
                                'created_at' => ($media->date) ? dateTimeUtcToLocal($media->date) : null,
                                'sentiment' => $media->sentiment,
                                'type_message' => 'instagram',
                                'like_count' => $media->like_count,
                                'share_count' => $media->share_count,
                                'comments_count' => $media->comments_count,                                
                                'tipo' => 'instagram',
                                'comments' => [],
                                'link' => $media->link,
                                'user_profile_image_url' => $media->img_link                          
                            );
        }

        return $medias;
    }

    function getMediasFacebook()
    {
        $medias_temp = $this->mediaModel::where(function($query) {
            $query->Orwhere('tipo', 'FB_COMMENT')
            ->Orwhere('tipo', 'FB_PAGE_POST')
            ->Orwhere('tipo', 'FB_PAGE_POST_COMMENT')
            ->Orwhere('tipo', 'FB_POSTS');
        });
       
        $medias_temp = $medias_temp->where('client_id', $this->client_id)
        ->select('id', 'text', 'date', 'sentiment', 'name', 'link', 'img_link', 'comment_count', 'share_count', 'like_count', 'client_id', 'tipo')
        ->groupBy('id', 'text', 'date', 'sentiment', 'name', 'link', 'img_link', 'comment_count', 'share_count', 'like_count', 'client_id', 'tipo')
        ->orderBy('date', 'DESC')->get();
        
        foreach($medias_temp as $media) {
            
            switch ($media->tipo) {
                case 'FB_COMMENT':
                        $tipo = '';
                    break;
                case 'FB_PAGE_POST':
                        $tipo = 'facebook-page';
                    break;
                case 'FB_PAGE_POST_COMMENT':
                        $tipo = 'facebook-page-comment';
                    break;
                case 'FB_POSTS':
                        $tipo = 'facebook';
                    break;

            }

            $medias[] = array(  'id' => $media->id,                                       
                                'text' => $media->text,
                                'username' => $media->name,
                                'created_at' => ($media->date) ? dateTimeUtcToLocal($media->date) : null,
                                'sentiment' => $media->sentiment,
                                'type_message' => $tipo,
                                'like_count' => $media->like_count,
                                'share_count' => $media->share_count,
                                'comments_count' => $media->comments_count,                                
                                'tipo' => 'facebook',
                                'comments' => [],
                                'link' => $media->link,
                                'user_profile_image_url' => $media->img_link                          
                            );
        }

        return $medias;
    }

    function getMediasTwitter()
    {
        $medias_temp =  $this->mediaModel::where('tipo', 'TWEETS');
               
        $medias_temp = $medias_temp->where('client_id', $this->client_id)
        ->select('id', 'text', 'date', 'sentiment', 'name', 'link', 'img_link', 'comment_count', 'share_count', 'like_count', 'client_id', 'retweet_count')
        ->groupBy('id', 'text', 'date', 'sentiment', 'name', 'link', 'img_link', 'comment_count', 'share_count', 'like_count', 'client_id', 'retweet_count')
        ->orderBy('date', 'DESC')->get();

        foreach($medias_temp as $media) {

            $medias[] = array(  'id' => $media->id,                                       
                                'text' => $media->text,
                                'username' => $media->name,
                                'created_at' => ($media->date) ? dateTimeUtcToLocal($media->date) : null,
                                'sentiment' => $media->sentiment,
                                'type_message' => 'twitter',
                                'like_count' => $media->like_count,
                                'share_count' => $media->share_count,
                                'comments_count' => $media->comments_count,                                
                                'tipo' => 'twitter',
                                'retweet_count' => $media->retweet_count,
                                'comments' => [],
                                'link' => $media->link,
                                'user_profile_image_url' => $media->img_link                          
                            );
        }

        return $medias;
    }
}
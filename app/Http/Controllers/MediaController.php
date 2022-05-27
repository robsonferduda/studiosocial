<?php

namespace App\Http\Controllers;

use App\FbPost;
use App\Media;
use App\MediaTwitter;
use App\FbPagePost;
use App\FbPagePostComment;
use App\MediaFilteredVw;
use App\MediaRuleFilteredVw;
use App\Enums\TypeMessage;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
        $media_materializada = null;
        $media_materializada_regra = null;

        switch ($tipo) {
            case 'twitter':
                $media = MediaTwitter::where('id',$id)->first();
                $media_materializada = MediaFilteredVw::where('tipo', 'TWEETS')->where('id',$id)->first();
                $media_materializada_regra = MediaRuleFilteredVw::where('tipo', 'TWEETS')->where('id',$id)->first();
                break;
            
            case 'facebook':
                $media = FbPost::where('id',$id)->first();
                $media_materializada = MediaFilteredVw::where('tipo', 'FB_POSTS')->where('id',$id)->first();
                $media_materializada_regra = MediaRuleFilteredVw::where('tipo', 'FB_POSTS')->where('id',$id)->first();
                break;

            case 'facebook-page':
                $media = FbPagePost::where('id',$id)->first();
                $media_materializada = MediaFilteredVw::where('tipo', 'FB_PAGE_POST')->where('id',$id)->first();
                $media_materializada_regra = MediaRuleFilteredVw::where('tipo', 'FB_PAGE_POST')->where('id',$id)->first();
                break;
                
            case 'instagram':
                $media = Media::where('id',$id)->first();
                $media_materializada = MediaFilteredVw::where('tipo', 'IG_POSTS')->where('id',$id)->first();
                $media_materializada_regra = MediaRuleFilteredVw::where('tipo', 'IG_POSTS')->where('id',$id)->first();
                break;
            
            case 'facebook-page-comment':
                $media = FbPagePostComment::where('id',$id)->first();
                $media_materializada = MediaFilteredVw::where('tipo', 'FB_PAGE_POST_COMMENT')->where('id',$id)->first();
                $media_materializada_regra = MediaRuleFilteredVw::where('tipo', 'FB_PAGE_POST_COMMENT')->where('id',$id)->first();
                break;
        }

        if($media){
            $media->sentiment = $sentimento;
            $media_materializada->sentiment = $sentimento;
            $media_materializada_regra->sentiment = $sentimento;

            if($media->update()){

                if($media_materializada) $media_materializada->update();
                if($media_materializada_regra) $media_materializada_regra->update();

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
}
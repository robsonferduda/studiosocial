<?php

namespace App\Http\Controllers;

use DB;
use DOMPDF;
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

    public function relatorio()
    {
        $nome = "Relatório de Redes Sociais";
        $dt_inicial = '10/10/2022';
        $dt_final = '20/10/2022';

        $temp_a = DB::table('fb_posts')  
                    ->select('id')          
                    ->addSelect(DB::raw("created_at as date"))  
                    ->addSelect(DB::raw("message as text")) 
                    ->addSelect(DB::raw("'facebook' as tipo"))  
                    ->addSelect(DB::raw("'facebook' as rede")) 
                    ->addSelect(DB::raw("'username' as user")) 
                    ->addSelect(DB::raw("sentiment"));

        $temp_b = DB::table('fb_page_posts')  
                    ->select('id')          
                    ->addSelect(DB::raw("created_at as date"))  
                    ->addSelect(DB::raw("message as text")) 
                    ->addSelect(DB::raw("'facebook-page' as tipo"))  
                    ->addSelect(DB::raw("'facebook' as rede")) 
                    ->addSelect(DB::raw("'username' as user")) 
                    ->addSelect(DB::raw("sentiment"));

        $temp_c = DB::table('fb_page_posts_comments')  
                    ->select('id')          
                    ->addSelect(DB::raw("created_at as date"))  
                    ->addSelect(DB::raw("text as text")) 
                    ->addSelect(DB::raw("'facebook-page-comment' as tipo"))  
                    ->addSelect(DB::raw("'facebook' as rede")) 
                    ->addSelect(DB::raw("'username' as user")) 
                    ->addSelect(DB::raw("sentiment"));

        //* Dados do Instagram
        $medias_insta = DB::table('medias')  
                    ->select('id')          
                    ->addSelect(DB::raw("timestamp as date"))  
                    ->addSelect(DB::raw("caption as text")) 
                    ->addSelect(DB::raw("'instagram' as tipo"))  
                    ->addSelect(DB::raw("'instagram' as rede")) 
                    ->addSelect(DB::raw("username as user")) 
                    ->addSelect(DB::raw("sentiment"))
                    ->where('client_id', 19);
                    
        // Dados do Twitter
        $media_twitter = DB::table('media_twitter')  
                    ->select('id')          
                    ->addSelect(DB::raw("created_tweet_at as date"))  
                    ->addSelect(DB::raw("full_text as text")) 
                    ->addSelect(DB::raw("'twitter' as tipo"))  
                    ->addSelect(DB::raw("'twitter' as rede")) 
                    ->addSelect(DB::raw("user_name as user")) 
                    ->addSelect(DB::raw("sentiment"))
                    ->where('client_id', 19);

        $fb_posts = $temp_a->union($temp_b)->union($temp_c);
        $medias = $fb_posts->union($media_twitter)->union($medias_insta)->orderBy('date','DESC')->paginate(10);

        
        $pdf = DOMPDF::loadView('medias/relatorio', compact('nome','dt_inicial','dt_final','medias'));
        return $pdf->download("Teste.pdf");
    }
}
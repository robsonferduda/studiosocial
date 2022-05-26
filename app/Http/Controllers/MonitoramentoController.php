<?php

namespace App\Http\Controllers;

use App\Configs;
use App\Hashtag;
use App\Term;
use Carbon\Carbon;
use App\MediaFilteredVw;
use App\MediaRuleFilteredVw;
use Illuminate\Support\Facades\Session;

class MonitoramentoController extends Controller
{
    private $client_id;
    private $periodo_padrao;
    private $flag_regras;

    public function __construct()
    {
        $this->middleware('auth');
        $this->client_id = session('cliente')['id'];
        $this->periodo_padrao = Configs::where('key', 'periodo_padrao')->first()->value;
        Session::put('url','monitoramento');
        $this->flag_regras = Session::get('flag_regras');
    }

    public function index()
    {
        $totais = array();
        $periodo_padrao = $this->periodo_padrao;
        $data_inicial = Carbon::now()->subDays($this->periodo_padrao - 1)->format('Y-m-d');
        $data_final = Carbon::now()->format('Y-m-d');
        $periodo_relatorio = array('data_inicial' => Carbon::now()->subDays($this->periodo_padrao - 1)->format('d/m/Y'),
                                   'data_final'   => Carbon::now()->format('d/m/Y'));

        $hashtags = Hashtag::where('client_id', $this->client_id)->where('is_active',true)->orderBy('hashtag')->get();
        $terms = Term::with('mediasTwitter')->with('medias')->with('pagePosts')->where('client_id', $this->client_id)->where('is_active',true)->orderBy('term')->get();

        $ig_comments_total = 0;

        if($this->flag_regras) {
            $mediaModel = new MediaRuleFilteredVw();
        } else {
            $mediaModel = new MediaFilteredVw();
        }

        $fb_total = $mediaModel::where(function($query) {
            $query->Orwhere('tipo', 'FB_COMMENT')
            ->Orwhere('tipo', 'FB_PAGE_POST')
            ->Orwhere('tipo', 'FB_PAGE_POST_COMMENT')
            ->Orwhere('tipo', 'FB_POSTS');
        })
        ->where('client_id', $this->client_id)
        ->whereBetween('date', [$data_inicial.' 00:00:00',$data_final.' 23:23:59'])
        ->select('id')
        ->count();

        $ig_total = $mediaModel::where(function($query) {
            $query->Orwhere('tipo', 'IG_POSTS')
            ->Orwhere('tipo', 'IG_COMMENT');
        })
        ->where('client_id', $this->client_id)
        ->whereBetween('date', [$data_inicial.' 00:00:00',$data_final.' 23:23:59'])
        ->select('id')
        ->count();

        $twitter_total = $mediaModel::where('tipo', 'TWEETS')
        ->where('client_id', $this->client_id)
        ->whereBetween('date', [$data_inicial.' 00:00:00',$data_final.' 23:23:59'])
        ->select('id')
        ->count();
                                              
        $totais = array('total_insta' => $ig_total, 
                        'total_face' =>  $fb_total,
                        'total_twitter' =>  $twitter_total);

        return view('monitoramento/index', compact('totais','hashtags','terms','periodo_relatorio','periodo_padrao'));
    }

    public function getHistorico($dias)
    {

        if($this->flag_regras) {
            $mediaModel = new MediaRuleFilteredVw();
        } else {
            $mediaModel = new MediaFilteredVw();
        }

        $data_inicial = Carbon::now()->subDays($dias - 1);
        $dados = array();

        for ($i=0; $i < $dias; $i++) { 

            if($i > 0){
                $data = $data_inicial->addDay()->format('Y-m-d');
                $data_formatada = $data_inicial->format('d/m/Y');
            }else{
                $data = $data_inicial->format('Y-m-d');
                $data_formatada = $data_inicial->format('d/m/Y');
            }

            $datas[] = $data;
            $datas_formatadas[] = $data_formatada;

            $fb_total = $mediaModel::where(function($query) {
                $query->Orwhere('tipo', 'FB_COMMENT')
                ->Orwhere('tipo', 'FB_PAGE_POST')
                ->Orwhere('tipo', 'FB_PAGE_POST_COMMENT')
                ->Orwhere('tipo', 'FB_POSTS');
            })
            ->where('client_id', $this->client_id)
            ->whereBetween('date', [$data.' 00:00:00',$data.' 23:23:59'])
            ->select('id')
            ->count();
    
            $ig_total = $mediaModel::where(function($query) {
                $query->Orwhere('tipo', 'IG_POSTS')
                ->Orwhere('tipo', 'IG_COMMENT');
            })
            ->where('client_id', $this->client_id)
            ->whereBetween('date', [$data.' 00:00:00',$data.' 23:23:59'])
            ->select('id')
            ->count();
    
            $twitter_total = $mediaModel::where('tipo', 'TWEETS')
            ->where('client_id', $this->client_id)
            ->whereBetween('date', [$data.' 00:00:00',$data.' 23:23:59'])
            ->select('id')
            ->count();
                                      
            $dados_twitter[] = $twitter_total;
            $dados_facebook[] = $fb_total;
            $dados_instagram[] = $ig_total;
        }

        $dados = array('data' => $datas,
                        'data_formatada' => $datas_formatadas,
                        'dados_twitter' => $dados_twitter,
                        'dados_instagram' => $dados_instagram,
                        'dados_facebook' => $dados_facebook);

        return response()->json($dados);

    }

    public function seleciona($rede)
    {

        if($this->flag_regras) {
            $mediaModel = new MediaRuleFilteredVw();
        } else {
            $mediaModel = new MediaFilteredVw();
        }

        $client_id = Session::get('cliente')['id'];
        $medias = array();

        switch ($rede) {
            case 'instagram':
               
                $medias_temp =  $mediaModel::where('tipo', 'IG_POSTS');

                $medias_temp = $medias_temp->where('client_id', $client_id)
                ->select('id', 'text', 'date', 'sentiment', 'name', 'link', 'img_link', 'comment_count', 'share_count', 'like_count', 'client_id')
                ->groupBy('id', 'text', 'date', 'sentiment', 'name', 'link', 'img_link', 'comment_count', 'share_count', 'like_count', 'client_id')
                ->orderBy('date', 'DESC')->paginate(20);

                foreach($medias_temp as $media) {

                    $medias[] = array(  'id' => $media->id,                                       
                                        'text' => $media->text,
                                        'username' => $media->name,
                                        'created_at' => dateTimeUtcToLocal($media->date),
                                        'sentiment' => $media->sentiment,
                                        'type_message' => 'instagram',
                                        'like_count' => $media->like_count,
                                        'comments_count' => $media->comments_count,                                
                                        'tipo' => 'instagram',
                                        'comments' => [],
                                        'link' => $media->link,
                                        'user_profile_image_url' => $media->img_link                          
                                    );
                }
                
                break;

            case 'facebook':

                $medias_temp =  $mediaModel::where(function($query) {
                    $query->Orwhere('tipo', 'FB_COMMENT')
                    ->Orwhere('tipo', 'FB_PAGE_POST')
                    ->Orwhere('tipo', 'FB_PAGE_POST_COMMENT')
                    ->Orwhere('tipo', 'FB_POSTS');
                });
               
                $medias_temp = $medias_temp->where('client_id', $client_id)
                ->select('id', 'text', 'date', 'sentiment', 'name', 'link', 'img_link', 'comment_count', 'share_count', 'like_count', 'client_id', 'tipo')
                ->groupBy('id', 'text', 'date', 'sentiment', 'name', 'link', 'img_link', 'comment_count', 'share_count', 'like_count', 'client_id', 'tipo')
                ->orderBy('date', 'DESC')->paginate(20);
                
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
                                        'created_at' => dateTimeUtcToLocal($media->date),
                                        'sentiment' => $media->sentiment,
                                        'type_message' => 'instagram',
                                        'like_count' => $media->like_count,
                                        'comments_count' => $media->comments_count,                                
                                        'tipo' => $tipo,
                                        'comments' => [],
                                        'link' => $media->link,
                                        'user_profile_image_url' => $media->img_link                          
                                    );
                }
    
                break;
            
            case 'twitter':
                $medias_temp =  $mediaModel::where('tipo', 'TWEETS');
               
                $medias_temp = $medias_temp->where('client_id', $client_id)
                ->select('id', 'text', 'date', 'sentiment', 'name', 'link', 'img_link', 'comment_count', 'share_count', 'like_count', 'client_id', 'retweet_count')
                ->groupBy('id', 'text', 'date', 'sentiment', 'name', 'link', 'img_link', 'comment_count', 'share_count', 'like_count', 'client_id', 'retweet_count')
                ->orderBy('date', 'DESC')->paginate(20);

                foreach($medias_temp as $media) {

                    $medias[] = array(  'id' => $media->id,                                       
                                        'text' => $media->text,
                                        'username' => $media->name,
                                        'created_at' => dateTimeUtcToLocal($media->date),
                                        'sentiment' => $media->sentiment,
                                        'type_message' => 'instagram',
                                        'like_count' => $media->like_count,
                                        'comments_count' => $media->comments_count,                                
                                        'tipo' => 'twitter',
                                        'retweet_count' => $media->retweet_count,
                                        'comments' => [],
                                        'link' => $media->link,
                                        'user_profile_image_url' => $media->img_link                          
                                    );
                }
                
            break;
        }
    
        return view('monitoramento/medias', compact('medias', 'medias_temp'));
    }
}
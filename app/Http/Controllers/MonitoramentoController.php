<?php

namespace App\Http\Controllers;

use DB;
use App\Configs;
use App\FbComment;
use App\FbPost;
use App\Hashtag;
use App\IgComment;
use App\Media;
use App\MediaTwitter;
use App\Term;
use Carbon\Carbon;
use App\Enums\FbReaction;
use App\FbPagePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MonitoramentoController extends Controller
{
    private $client_id;
    private $periodo_padrao;

    public function __construct()
    {
        $this->middleware('auth');
        $this->client_id = session('cliente')['id'];
        $this->periodo_padrao = Configs::where('key', 'periodo_padrao')->first()->value - 1;
        Session::put('url','monitoramento');
    }

    public function index()
    {
        $totais = array();
        $periodo_padrao = $this->periodo_padrao;
        $periodo_relatorio = array('data_inicial' => Carbon::now()->subDays($this->periodo_padrao)->format('d/m/Y'),
                                   'data_final'   => Carbon::now()->format('d/m/Y'));

        $hashtags = Hashtag::where('client_id', $this->client_id)->where('is_active',true)->orderBy('hashtag')->get();
        $terms = Term::with('mediasTwitter')->with('medias')->where('client_id', $this->client_id)->where('is_active',true)->orderBy('term')->get();

        $ig_comments_total = DB::table('ig_comments')
                            ->join('medias','medias.id','=','ig_comments.media_id')
                            ->where('medias.client_id','=',$this->client_id)
                            ->count();

        $fb_comments_total = DB::table('fb_comments')
                            ->join('fb_posts','fb_posts.id','=','fb_comments.post_id')
                            ->where('fb_posts.client_id','=',$this->client_id)
                            ->count();

        $totais = array('total_insta' => Media::where('client_id',$this->client_id)->count() + $ig_comments_total, 
                        'total_face' => FbPost::where('client_id',$this->client_id)->count() + $fb_comments_total,
                        'total_twitter' => MediaTwitter::where('client_id',$this->client_id)->count());

        return view('monitoramento/index', compact('totais','hashtags','terms','periodo_relatorio','periodo_padrao'));
    }

    public function getHistorico($dias)
    {
        $data_inicial = Carbon::now()->subDays($dias - 1);
        $dados = array();

        for ($i=0; $i < $dias; $i++) { 

            $data = $data_inicial->addDay()->format('Y-m-d');
            $data_formatada = $data_inicial->format('d/m/Y');

            $datas[] = $data;
            $datas_formatadas[] = $data_formatada;

            $ig_comments_total = DB::table('ig_comments')
                                ->join('medias','medias.id','=','ig_comments.media_id')
                                ->where('medias.client_id','=', $this->client_id)
                                ->whereBetween('ig_comments.timestamp', [$data.' 00:00:00',$data.' 23:23:59'])
                                ->count();

            $fb_comments_total = DB::table('fb_comments')
                                ->join('fb_posts','fb_posts.id','=','fb_comments.post_id')
                                ->where('fb_posts.client_id','=',$this->client_id)
                                ->whereBetween('fb_comments.created_time', [$data.' 00:00:00',$data.' 23:23:59'])
                                ->count();

            $dados_twitter[] = MediaTwitter::where('client_id',$this->client_id)->whereBetween('created_tweet_at',[$data.' 00:00:00',$data.' 23:23:59'])->count();
            $dados_facebook[] = FbPost::where('client_id',$this->client_id)->whereBetween('tagged_time',[$data.' 00:00:00',$data.' 23:23:59'])->count() + $fb_comments_total;
            $dados_instagram[] = Media::where('client_id',$this->client_id)->whereBetween('timestamp',[$data.' 00:00:00',$data.' 23:23:59'])->count() + $ig_comments_total;
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
        $client_id = Session::get('cliente')['id'];
        $medias = array();

        switch ($rede) {
            case 'instagram':
                $medias_temp = Media::with('comments')->where('client_id', $client_id)->orderBy('timestamp','DESC')->paginate(20);
                foreach ($medias_temp as $key => $media) {
                    
                    $bag_comments = [];
                    if ($media->comments) {
                        foreach($media->comments as $comment) {
                            $bag_comments[] = ['text' => $comment->text, 'created_at' => $comment->timestamp];
                        }
                    }

                    $medias[] = array('id' => $media->id,
                                      'media_id' => $media->media_id,
                                      'text' => $media->caption,
                                      'username' => $media->username,
                                      'created_at' => dateTimeUtcToLocal($media->timestamp),
                                      'sentiment' => $media->sentiment,
                                      'type_message' => 'instagram',
                                      'like_count' => $media->like_count,
                                      'comments_count' => $media->comments_count,
                                      'social_media_id' => $media->social_media_id,
                                      'tipo' => 'instagram',
                                      'comments' => $bag_comments,
                                      'link' => $media->permalink,
                                      'user_profile_image_url' => ''                            
                                    );

                }
                break;

            case 'facebook':
                $medias_temp_a = FbPost::select(['id', 'message', 'share_count', 'comment_count', 'permalink_url','updated_time'])->addSelect(DB::raw("0 as tipo"))->addSelect(DB::raw("'post' as tipo"))->with('comments')->with('reactions')->where('client_id', $client_id);
                $medias_temp_b = FbPagePost::select(['id', 'message', 'share_count', 'comment_count', 'permalink_url','updated_time', 'fb_page_monitor_id'])->addSelect(DB::raw("'post_page' as tipo"))->with('page')->with('reactions')->whereHas('terms', function ($query) use ($client_id){
                    $query->where('client_id', $client_id);
                });
                $medias_temp = $medias_temp_b->union($medias_temp_a)->orderBy('updated_time','DESC')->paginate(20);
                
                foreach ($medias_temp as $key => $media) {

                    if($media->tipo == 'post') {
                        $media = FbPost::find($media->id);
                        $img = '';
                        $name = '';
                    } else {                     
                        $img = $media->page->picture_url;
                        $name = $media->page->name;
                    }

                    $bag_comments = [];
                    if ($media->comments) {
                        foreach($media->comments as $comment) {
                            $bag_comments[] = ['text' => $comment->text, 'created_at' => $comment->timestamp];
                        }
                    }
  
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
                                      'username' => $name,
                                      'created_at' => dateTimeUtcToLocal($media->updated_time),
                                      'sentiment' => $media->sentiment,
                                      'type_message' => 'facebook',
                                      'like_count' => $likes_count,
                                      'comments_count' => !empty($media->comment_count) ? $media->comment_count : 0,
                                      'social_media_id' => $media->social_media_id,
                                      'tipo' => 'facebook',
                                      'comments' => $bag_comments,
                                      'link' => $media->permalink_url,
                                      'share_count' => !empty($media->share_count) ? $media->share_count : 0,
                                      'user_profile_image_url' => $img
                                    );

                } 
                break;
            
            case 'twitter':
                $medias_temp = MediaTwitter::where('client_id', $client_id)->orderBy('created_tweet_at','DESC')->paginate(20);
                foreach ($medias_temp as $key => $media) {
                    
                    $medias[] = array('id' => $media->id,
                                      'media_id' => $media->twitter_id,
                                      'text' => $media->full_text,
                                      'username' => $media->user_name,
                                      'created_at' => dateTimeUtcToLocal($media->created_tweet_at),
                                      'sentiment' => $media->sentiment,
                                      'type_message' => 'twitter',
                                      'like_count' => $media->favorite_count,
                                      'comments_count' => 0,
                                      'social_media_id' => $media->social_media_id,
                                      'comments' => [],
                                      'tipo' => 'twitter',
                                      'link' => 'https://twitter.com/'.$media->user_screen_name.'/status/'.$media->twitter_id,
                                      'retweet_count' => $media->retweet_count,
                                      'user_profile_image_url' => $media->user_profile_image_url
                                    );

                }
            break;
        }
    
        return view('monitoramento/medias', compact('medias', 'medias_temp'));
    }
}
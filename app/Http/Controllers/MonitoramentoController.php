<?php

namespace App\Http\Controllers;

use DB;
use App\FbPost;
use App\FbComment;
use App\Media;
use App\IgComment;
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

        $ig_comments_total = DB::table('ig_comments')
                            ->join('medias','medias.id','=','ig_comments.media_id')
                            ->where('medias.client_id','=',$client_id)
                            ->count();

        $fb_comments_total = DB::table('fb_comments')
                            ->join('fb_posts','fb_posts.id','=','fb_comments.post_id')
                            ->where('fb_posts.client_id','=',$client_id)
                            ->count();

        if($client_id){
            $totais = array('total_insta' => Media::where('client_id',$client_id)->count() + $ig_comments_total, 
                            'total_face' => FbPost::where('client_id',$client_id)->count() + $fb_comments_total,
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
                $medias_temp = Media::with('comments')->where('client_id', $client_id)->orderBy('timestamp','DESC')->paginate(20);
                foreach ($medias_temp as $key => $media) {
                    
                    $bag_comments = [];
                    if ($media->comments) {
                        foreach($media->comments as $comment) {
                            $bag_comments[] = ['text' => $comment->text, 'created_at' => $comment->timestamp];
                        }
                    }

                    $medias[] = array('id' => $media->media_id,
                                      'text' => $media->caption,
                                      'username' => '',
                                      'created_at' => dateTimeUtcToLocal($media->timestamp),
                                      'like_count' => $media->like_count,
                                      'comments_count' => $media->like_count,
                                      'social_media_id' => $media->social_media_id,
                                      'tipo' => 'instagram',
                                      'comments' => $bag_comments,
                                      'link' => $media->permalink
                                    );

                }
                break;

            case 'facebook':
                $medias_temp = FbPost::with('comments')->where('client_id', $client_id)->orderBy('updated_time','DESC')->paginate(20);
                foreach ($medias_temp as $key => $media) {

                    $bag_comments = [];
                    if ($media->comments) {
                        foreach($media->comments as $comment) {
                            $bag_comments[] = ['text' => $comment->text, 'created_at' => $comment->timestamp];
                        }
                    }
                    
                    $medias[] = array('id' => $media->id,
                                      'text' => $media->message,
                                      'username' => '',
                                      'created_at' => dateTimeUtcToLocal($media->updated_time),
                                      'like_count' => $media->like_count,
                                      'comments_count' => $media->like_count,
                                      'social_media_id' => $media->social_media_id,
                                      'tipo' => 'facebook',
                                      'comments' => $bag_comments,
                                      'link' => $media->permalink_url);

                }
                break;
            
            case 'twitter':
                $medias_temp = MediaTwitter::where('client_id', $client_id)->orderBy('created_tweet_at','DESC')->paginate(20);
                foreach ($medias_temp as $key => $media) {
                    
                    $medias[] = array('id' => $media->twitter_id,
                                      'text' => $media->full_text,
                                      'username' => $media->user_name,
                                      'created_at' => dateTimeUtcToLocal($media->created_tweet_at),
                                      'like_count' => $media->favorite_count,
                                      'comments_count' => 0,
                                      'social_media_id' => $media->social_media_id,
                                      'comments' => [],
                                      'tipo' => 'twitter',
                                      'link' => '');

                }
            break;
        }

        return view('monitoramento/medias', compact('medias', 'medias_temp'));
    }
}
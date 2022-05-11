<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\User;
use App\Term;
use App\Client;
use App\Configs;
use App\Hashtag;
use App\Media;
use App\FbPost;
use App\MediaTwitter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\SocialSearchRequest;
use Illuminate\Support\Facades\Session;

class SocialSearchController extends Controller
{
    private $client_id;

    public function __construct()
    {
        $this->middleware('auth');      
        $this->client_id = session('cliente')['id'];  
        Session::put('url','social-search');
    }

    public function index(SocialSearchRequest $request)
    {
        $carbon = new Carbon();
        $term =  (trim($request->termo)) ? strtolower($request->termo) : null;
        $dt_inicial = ($request->dt_inicial) ? $carbon->createFromFormat('d/m/Y', $request->dt_inicial)->format('Y-m-d')." 00:00:00" : date("Y-m-d")." 00:00:00";
        $dt_final = ($request->dt_final) ? $carbon->createFromFormat('d/m/Y', $request->dt_final)->format('Y-m-d')." 23:59:59" : date("Y-m-d")." 23:59:59";

        $fl_instagram = (!isset($request->instagram))? false : true;
        $fl_facebook = (!isset($request->facebook))? false : true;
        $fl_twitter = (!isset($request->twitter))? false : true;

        $union = false;
        $medias = null;

        //* Dados do Facebook
        $fb_posts = DB::table('fb_posts')  
                    ->select('id')          
                    ->addSelect(DB::raw("created_at as date"))  
                    ->addSelect(DB::raw("message as text")) 
                    ->addSelect(DB::raw("'facebook' as tipo"))  
                    ->addSelect(DB::raw("'facebook' as rede")) 
                    ->addSelect(DB::raw("'username' as user")) 
                    ->addSelect(DB::raw("sentiment"))     
                    ->when($dt_inicial, function ($q) use($dt_inicial, $dt_final){
                        $q->whereBetween('created_at', [$dt_inicial, $dt_final]);
                    })     
                    ->when($term, function ($q) use($term){
                        $q->where('message','ilike','%'.$term.'%');
                    })   
                    ->where('client_id','=',$this->client_id);

        //* Dados do Instagram
        $medias_insta = DB::table('medias')  
                    ->select('id')          
                    ->addSelect(DB::raw("timestamp as date"))  
                    ->addSelect(DB::raw("caption as text")) 
                    ->addSelect(DB::raw("'instagram' as tipo"))  
                    ->addSelect(DB::raw("'instagram' as rede")) 
                    ->addSelect(DB::raw("username as user")) 
                    ->addSelect(DB::raw("sentiment"))     
                    ->when($dt_inicial, function ($q) use($dt_inicial, $dt_final){
                        $q->whereBetween('timestamp', [$dt_inicial, $dt_final]);
                    })     
                    ->when($term, function ($q) use($term){
                        $q->where('caption','ilike','%'.$term.'%');
                    })   
                    ->where('client_id','=',$this->client_id);
                    
        // Dados do Twitter
        $media_twitter = DB::table('media_twitter')  
                    ->select('id')          
                    ->addSelect(DB::raw("created_tweet_at as date"))  
                    ->addSelect(DB::raw("full_text as text")) 
                    ->addSelect(DB::raw("'twitter' as tipo"))  
                    ->addSelect(DB::raw("'twitter' as rede")) 
                    ->addSelect(DB::raw("user_name as user")) 
                    ->addSelect(DB::raw("sentiment"))   
                    ->when($dt_inicial, function ($q) use($dt_inicial, $dt_final){
                        $q->whereBetween('created_tweet_at', [$dt_inicial, $dt_final]);
                    })     
                    ->when($term, function ($q) use($term){
                        $q->where('full_text','ilike','%'.$term.'%');
                    })      
                    ->where('client_id','=',$this->client_id);

        if($fl_facebook){
            $union = true;
            $medias = ($medias) ? $medias->union($fb_posts) : $fb_posts;
        }

        if($fl_instagram){
            $union = true;
            $medias = ($medias) ? $medias->union($medias_insta) : $medias_insta;
        }

        if($fl_twitter){
            $union = true;
            $medias = ($medias) ? $medias->union($media_twitter) : $media_twitter;
        }

        if(!$union){
            $medias = $fb_posts->union($media_twitter)->union($medias_insta)->orderBy('date','DESC')->paginate(20);
        }else{
            $medias = $medias->orderBy('date','DESC')->paginate(20);
        }
       
        return view('social-search/index', compact('medias','term','term','dt_inicial','dt_final'));
    }

    public function buscar()
    {
        
    }
}
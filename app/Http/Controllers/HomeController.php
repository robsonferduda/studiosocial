<?php

namespace App\Http\Controllers;

use App\MediaMaterializedFilteredVw;
use App\MediaMaterializedRuleFilteredVw;
use DB;
use Auth;
use App\User;
use App\Term;
use App\Client;
use App\Configs;
use App\Hashtag;
use App\Media;
use App\FbPost;
use App\MediaFilteredVw;
use App\MediaRuleFilteredVw;
use App\MediaTwitter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    private $client_id;
    private $periodo_padrao;

    public function __construct()
    {
        //$this->middleware('auth', ['except' => [
            //'site'
        //]]);

        if(!Auth::user() or Auth::user()->email == 'boletim@studioclipagem.com.br')
        {

            $clienteSession = ['id' => 0, 'nome' => "Cliente não selecionado"];

        }else{

            $cliente = Client::where('id',Configs::where('key', 'cliente_padrao')->first()->value)->first();
            if($cliente)
                $clienteSession = ['id' => $cliente->id, 'nome' => $cliente->name];
            else
                $clienteSession = ['id' => 0, 'nome' => "Cliente não selecionado"];

            Session::put('cliente', session('cliente') ? session('cliente') : $clienteSession);

            $this->client_id = session('cliente')['id'];

            Session::put('url','home');

            $this->periodo_padrao = Configs::where('key', 'periodo_padrao')->first()->value;

            $this->flag_regras = Session::get('flag_regras');

            if($this->flag_regras) {
                $this->mediaModel = new MediaMaterializedRuleFilteredVw();
            } else {
                $this->mediaModel = new MediaMaterializedFilteredVw();
            }
        }

    }


    public function site()
    {
        return view('site');
    }

    public function index()
    {

        if(Auth::user())
        {

            $totais = array();
            $users = null;
            $clientes = null;
            $periodo_relatorio = null;
            $periodo_padrao = $this->periodo_padrao;
            $data_inicial = Carbon::now()->subDays($this->periodo_padrao - 1)->format('Y-m-d');
            $data_final = Carbon::now()->format('Y-m-d');

            $u = User::find(Auth::user()->id);

            $mediaModel = $this->mediaModel;

            $ig_comments_total = $mediaModel::where('tipo', 'IG_COMMENT')
                ->where('client_id', $this->client_id)
                ->whereBetween('date', [$data_inicial.' 00:00:00',$data_final.' 23:59:59'])
                ->select('id')
                ->distinct()
                ->count();

            $ig_post_total = $mediaModel::where('tipo', 'IG_POSTS')
                ->where('client_id', $this->client_id)
                ->whereBetween('date', [$data_inicial.' 00:00:00',$data_final.' 23:59:59'])
                ->select('id')
                ->distinct()
                ->count();

            $media_instagram = round(($ig_post_total + $ig_comments_total)/30, 1);

            $fb_comments_total = $mediaModel::where('tipo', 'FB_COMMENT')
                ->where('client_id', $this->client_id)
                ->whereBetween('date', [$data_inicial.' 00:00:00',$data_final.' 23:59:59'])
                ->select('id')
                ->distinct()
                ->count();

            $fb_post_total = $mediaModel::where('tipo', 'FB_POSTS')
                ->where('client_id', $this->client_id)
                ->whereBetween('date', [$data_inicial.' 00:00:00',$data_final.' 23:59:59'])
                ->select('id')
                ->distinct()
                ->count();

            $fb_page_post_total = $mediaModel::where('tipo', 'FB_PAGE_POST')
                ->where('client_id', $this->client_id)
                ->whereBetween('date', [$data_inicial.' 00:00:00',$data_final.' 23:59:59'])
                ->select('id')
                ->distinct()
                ->count();

            $fb_page_post_comment_total = $mediaModel::where('tipo', 'FB_PAGE_POST_COMMENT')
                ->where('client_id', $this->client_id)
                ->whereBetween('date', [$data_inicial.' 00:00:00',$data_final.' 23:59:59'])
                ->select('id')
                ->distinct()
                ->count();

            $media_facebook = round(($fb_post_total + $fb_page_post_total + $fb_page_post_comment_total  + $fb_comments_total)/30, 1);

            $twitter_total = $mediaModel::where('tipo', 'TWEETS')
            ->where('client_id', $this->client_id)
            ->whereBetween('date', [$data_inicial.' 00:00:00',$data_final.' 23:59:59'])
            ->select('id')
            ->count();

            $media_twitter = round(($twitter_total )/30, 1);      

            if($u->hasRole('administradores') or $u->hasRole('boletim')){

                $users = User::whereNull('client_id')->count();
                $clientes = Client::count();

                $hashtags = Hashtag::where('client_id', $this->client_id)->where('is_active',true)->orderBy('hashtag')->get();
                $terms = Term::where('client_id', $this->client_id)->where('is_active',true)->orderBy('term')->get();
            
                return view('index', compact('users','clientes','totais','hashtags','terms','periodo_relatorio','media_twitter','media_instagram','media_facebook'));

            }else{

                $periodo_relatorio = array('data_inicial' => Carbon::now()->subDays(7)->format('d/m/Y'),
                                        'data_final'   => Carbon::now()->format('d/m/Y'));

                $hashtags = Hashtag::where('client_id', $this->client_id)->where('is_active',true)->orderBy('hashtag')->get();
                $terms = Term::where('client_id', $this->client_id)->where('is_active',true)->orderBy('term')->get();

                $totais = array('total_insta' => $ig_comments_total + $ig_post_total,
                                'total_face' => $fb_comments_total + $fb_post_total + $fb_page_post_total + $fb_page_post_comment_total,
                                'total_twitter' => $twitter_total);

                return view('dashboard_cliente', compact('users','clientes','totais','hashtags','terms','periodo_relatorio','periodo_padrao'));

            }
        }else{
            return view('dashboard_nula');
        }

    }
}

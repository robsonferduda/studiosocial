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
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    private $client_id;

    public function __construct()
    {
        $this->middleware('auth');

        $cliente = Client::where('id',Configs::where('key', 'cliente_padrao')->first()->value)->first();

        $clienteSession = ['id' => $cliente->id, 'nome' => $cliente->name];

        Session::put('cliente', session('cliente') ? session('cliente') : $clienteSession);

        $this->client_id = session('cliente')['id'];
        
        Session::put('url','home');
    }

    public function index()
    {
        $totais = array();
        $users = null;
        $clientes = null;
        $periodo_relatorio = null;

        $u = User::find(Auth::user()->id);
        $media_twitter = (new MediaTwitter)->getMedia($this->client_id);

        if($u->hasRole('administradores')){

            $users = User::whereNull('client_id')->count();
            $clientes = Client::count();
            $hashtags = Hashtag::where('is_active',true)->get();
            $terms = Term::where('is_active',true)->get();

            return view('index', compact('users','clientes','totais','hashtags','terms','periodo_relatorio','media_twitter'));

        }else{
            
            $periodo_relatorio = array('data_inicial' => Carbon::now()->subDays(7)->format('d/m/Y'),
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

            return view('dashboard_cliente', compact('users','clientes','totais','hashtags','terms','periodo_relatorio','media_twitter'));
                
        }     

    }
}
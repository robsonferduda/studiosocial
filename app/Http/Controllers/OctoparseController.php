<?php

namespace App\Http\Controllers;

use App\MediaMaterializedFilteredVw;
use App\MediaMaterializedRuleFilteredVw;
use DB;
use Auth;
use Excel;
use App\User;
use App\Octoparse;
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
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Session;

use App\Exports\OcorrenciasExport;

class OctoparseController extends Controller
{
    private $client_id;
    private $periodo_padrao;

    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'site','index'
        ]]);

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

    public function index()
    {
        return view('octoparse/index');
    }

    public function importar()
    {
        $dados = Octoparse::whereBetween('uct_time', ['2024-08-12 00:00:00', '2024-08-13 23:59:59'])->get();
        $total_inserido = 0;

        foreach ($dados as $key => $dado) {
            
            $id = explode("/",$dado->tweet_website)[5];

            $media = MediaTwitter::where('twitter_id',$id)->first();

            if(!$media){

                $tweet = array('twitter_id' => $id,
                               'full_text' => $dado->tweet_content, 
                               'permalink' => $dado->tweet_website,
                               'created_tweet_at' => $dado->uct_time,
                               'user_name' => $dado->autor_name,
                               'client_id' => 34
                );

                $result = MediaTwitter::create($tweet);
                $result->hashtags()->syncWithoutDetaching(181);
                $total_inserido++;
            }

            /*

            $dados = array('full_text' => $row[3],
                                'retweet_count' => $row[6],
                                'client_id' => $request->cliente,
                                'favorite_count' => $row[5],
                                'user_name' => $row[10],
                                'user_screen_name' => $row[10],
                                'created_tweet_at' => $row[1],
                                'permalink' => $row[0]
                                );
        
                $tweet = MediaTwitter::create($dados); 

            */

        }

        Flash::success('<i class="fa fa-check"></i> Foram inseridos '.$total_inserido.' novos registros');

        return redirect('octoparse')->withInput(); 
    }
}
<?php

namespace App\Http\Controllers;

use App\MediaMaterializedFilteredVw;
use App\MediaMaterializedRuleFilteredVw;
use DB;
use Auth;
use Excel;
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
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Session;

use App\Exports\OcorrenciasExport;

class ImportarKnewinController extends Controller
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

       
    }

    public function importar()
    {
        $clientes = Client::orderBy('name')->get();

        return view('importar/importar',compact('clientes'));
    }

    public function upload(Request $request)
    {
        $arquivo = $request->file('file');
        $fileInfo = $arquivo->getClientOriginalName();
        $filename = pathinfo($fileInfo, PATHINFO_FILENAME);
        $extension = pathinfo($fileInfo, PATHINFO_EXTENSION);
        $file_name = date('Y-m-d-H-i-s').'.'.$extension;

        $path = 'importacao';
        $arquivo->move(public_path($path), $file_name);

        $dados = array('arquivo' => $file_name);

        return response()->json($dados);
    }

    public function processar(Request $request)
    {
        $total_inserido = 0;

        /*
        0 - url
        1 - data
        3 - texto
        4 - total de omentários
        5 - total de curtidas
        6 - total de compartilhamentos
        10 - usuário
        */          

        $rows = Excel::toArray(new OcorrenciasExport, public_path('importacao/'.$request->arquivo))[0];

        foreach ($rows as $key => $row) {
            
            if($row[8] == 'pt'){
                
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
                $total_inserido++;
            }
        }

        Flash::success('<i class="fa fa-check"></i> Foram inseridos '.$total_inserido.' novos registros');

        return redirect('importar')->withInput(); 
    }
}
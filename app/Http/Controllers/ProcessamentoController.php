<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\User;
use App\Term;
use App\Radios;
use App\Configs;
use App\Hashtag;
use App\Media;
use App\FbPost;
use App\MediaTwitter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProcessamentoController extends Controller
{
    public function index()
    {
        $linha = array();
        $lista = array();
        $ocorrencias = array();
        $chaves_busca = array('gás',
                                'sc gás',
                                'gás natural',      
                                'gás que você precisa',        
                                'motoristas catarinenses',       
                                'santa catarina',        
                                'governo de santa catarina',        
                                'gasto com combustível',        
                                'economia consciente',        
                                'com combustível pintura',
                                'óleo novinho');

        $chaves_busca = array('trabalho','trabalho seguro','país
        ');

        $file = fopen(storage_path("app/textos/transamerica_9.txt"), "r");

        while(!feof($file)) {

            $linha = fgets($file);
            $tempo = substr(trim($linha),11,8);
            $lista = array();

            for ($i=0; $i < count($chaves_busca); $i++) { 
                if(str_contains($linha,$chaves_busca[$i])){
                    $lista[] = $chaves_busca[$i];
                }
            }

            if(count($lista)){
                $ocorrencias[] = array('tempo' => $tempo, 'ocorrencias' => $lista);
            }
        }
        
        fclose($file);
    
        for ($i=0; $i < count($ocorrencias); $i++) { 
            echo "No instante ".$ocorrencias[$i]['tempo']." houve ".count($ocorrencias[$i]['ocorrencias'])." ocorrências de valores das chaves \"".implode('","',$ocorrencias[$i]['ocorrencias']).'"'."<br/>";
        }
    }

    public function radios()
    {
        $emissoras = Radios::all();
        return view('transcricao/emissoras', compact('emissoras'));
    }

    public function audios($emissora)
    {
        $radio = Radios::where('pasta',$emissora)->first();
        $files = \File::allFiles(storage_path('app/audios/'.$emissora));

        $lista = collect($files)->sortBy(function($file){
            return $file->getBaseName();
        });

        foreach($lista as $key => $file){

            $linha = '';
            $arq = fopen(storage_path("app/audios/".$emissora."/".$file->getFilename()), "r");
            while(!feof($arq)) {
                $linha = fgets($arq);
                if(trim($linha) != '') $tempo = substr(trim($linha),11,8);
            }
            fclose($arq);
            $lista[$key]->tempo = $tempo;
        }

        return view('transcricao/arquivos', compact('lista','radio'));
    }

    public function processar($pasta)
    {
        $radio = Radios::where('pasta',$pasta)->first();
        $resultado = array();
        return view('transcricao/resultado', compact('resultado','radio'));
    }
    
}
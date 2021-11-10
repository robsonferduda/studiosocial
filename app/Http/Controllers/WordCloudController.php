<?php

namespace App\Http\Controllers;

use App\FbPost;
use App\Media;
use App\MediaTwitter;
use Illuminate\Support\Facades\Session;
use Axisofstevil\StopWords\Filter;
use Illuminate\Support\Facades\Storage;
use voku\helper\StopWords;

class WordCloudController extends Controller
{

    private $cliente;

    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','nuvem-palavras');
        $this->cliente = Session::get('cliente');
    }

    public function render()
    {        
        return view('word-cloud/index');
    }

    public function getWords() {

        if(isset($this->cliente['id'])) {

            $file = Storage::disk('wordcloud')->get('cliente-1-wordclould.json');

            $words = json_decode($file);
            
            $words = (Array) $words;
            
            arsort($words);
    
            $words = array_slice($words, 0, 100);
    
            $word_cloud = [];
            foreach($words as $word => $qtd_times) {
                $word_cloud[$word] = $qtd_times;  
            }

        } else {
            $word_cloud = ['Cliente' => 3, 'Não' => 2, 'Selecionado' => 2];
        }
        
        echo json_encode($word_cloud);

    }
}
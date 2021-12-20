<?php

namespace App\Http\Controllers;

use App\WordsExecption;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

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

    public function remove(Request $request) 
    {        
        $word = WordsExecption::create([
            'word' => $request->word,
            'client_id' => $this->cliente['id']
        ]);       

        $process = new Process(['python3', base_path().'/studio-social-wordcloud.py']);

        $process = $process->run();

        return $word;
    }

    public function getWords() 
    {

        if(isset($this->cliente['id'])) {

            $file = Storage::disk('wordcloud')->get("cliente-{$this->cliente['id']}-wordclould.json");

            $words = json_decode($file);
            
            $words = (Array) $words;
            
            arsort($words);
    
            $words = array_slice($words, 0, 200);
    
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
<?php

namespace App\Http\Controllers;

use App\Rule;
use App\WordsExecption;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
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
        ini_set('memory_limit', '2048M');
        
        if(isset($this->cliente['id'])) {

            $rules = Rule::where('client_id', $this->cliente['id'])->get();
          
            $words = [];

            foreach($rules as $rule) {
                foreach($rule->igPosts as $post){
                    $words_post = json_decode($post->wordcloud_expression, true);

                    if(!empty($words_post)) {
                        foreach($words_post as $key => $value) {
                            $words[] = $key;
                        }
                    }                   
                }

                foreach($rule->twPosts as $post){
                    $words_post = json_decode($post->wordcloud_expression, true);

                    if(!empty($words_post)) {
                        foreach($words_post as $key => $value) {
                            $words[] = $key;
                        }
                    }                   
                }
            }

            $lista_frequencia = array_count_values($words);
            arsort($lista_frequencia);
    
            $lista_frequencia = array_slice($lista_frequencia, 0, 200);
    
        } else {
            $lista_frequencia = ['Cliente' => 3, 'Não' => 2, 'Selecionado' => 2];
        }
        
        echo json_encode($lista_frequencia);

    }
}
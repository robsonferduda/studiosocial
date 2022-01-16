<?php

namespace App\Http\Controllers;

use App\Rule;
use App\WordCloudText;
use App\WordsExecption;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laracasts\Flash\Flash;
use Symfony\Component\Process\Exception\ProcessFailedException;
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

        return $word;
    }

    public function getWords() 
    {   
        if(isset($this->cliente['id'])) {

            $words_execption = WordsExecption::where('client_id', $this->cliente['id'])->pluck('word')->toArray();

            $file = Storage::disk('wordcloud')->get("cliente-{$this->cliente['id']}-wordclould.json");
            //dd($words_execption);

            $words = json_decode($file);
            $words = (Array) $words;
            arsort($words);

            $words = array_slice($words, 0, 200);

            $word_cloud = [];
            foreach($words as $word => $qtd_times) {
                if(in_array($word, $words_execption)){
                    continue;
                }

                $word_cloud[$word] = $qtd_times;  
            }

        } else {
                $word_cloud = ['Cliente' => 3, 'Não' => 2, 'Selecionado' => 2];               
        }

        echo json_encode($word_cloud);
        
    }

    public function getWordsByRule($rule) 
    {   
        if(isset($this->cliente['id'])) {

            $rule = Rule::where('id', $rule)->first();

            $igPosts = $rule->igPosts()->pluck('caption')->toArray();
            $igComments = $rule->igComments()->pluck('text')->toArray();
            $fbPosts = $rule->fbPosts()->pluck('message')->toArray();
            $fbComments = $rule->fbComments()->pluck('text')->toArray();
            $twPosts = $rule->twPosts()->pluck('full_text')->toArray();

            $textig = $this->concatenateSanitizeText($igPosts);
            $textigc = $this->concatenateSanitizeText($igComments);
            $textfb = $this->concatenateSanitizeText($fbPosts);
            $textfbc = $this->concatenateSanitizeText($fbComments);
            $texttw = $this->concatenateSanitizeText($twPosts);

            $text = $textig.' '.$textigc.' '.$textfb.' '.$textfbc.' '.$texttw;

            $wordcloud_text = WordCloudText::create([
                'text' => $text
            ]);


            $file_name = 'wordcloud-'.strtotime(now()).'.json';

            if(isset($wordcloud_text->id)) {
                
                $word_cloud = [];

                $process = new Process(['python3', base_path().'/studio-social-wordcloud-rules.py', $wordcloud_text->id, $file_name]);
                $process->run(function ($type, $buffer) use ($file_name, &$word_cloud){
                    if (Process::ERR === $type) {
                        //echo 'ERR > '.$buffer.'<br />';
                    } else {
                        
                        if(trim($buffer) == 'END') {
                            //echo 'OUT > '.$buffer.'<br />';

                            $file = Storage::disk('wordcloud')->get($file_name);
                            //dd($words_execption);
                
                            $words = json_decode($file);
                           
                            $words = (Array) $words;
                            arsort($words);
                
                            $words = array_slice($words, 0, 200);

                            $words_execption = WordsExecption::where('client_id', $this->cliente['id'])->pluck('word')->toArray();
                                            
                            foreach($words as $word => $qtd_times) {

                                if(in_array($word, $words_execption))
                                    continue;

                                $word_cloud[$word] = $qtd_times;  
                            }

                            Storage::disk('wordcloud')->delete($file_name);
                        }
    
                    }
                });
            
            }

            

        } else {
                $word_cloud = ['Cliente' => 3, 'Não' => 2, 'Selecionado' => 2];               
        }

        echo json_encode($word_cloud);
        
    }

    public function concatenateSanitizeText(Array $textArray)
    {
        $concatenateText = '';

        foreach($textArray as $text) {
                        
            $concatenateText .= ' '.$text;
        }

        return $concatenateText;

    }


    public function excecoes()
    {
        $words_execption = WordsExecption::where('client_id', $this->cliente['id'])->get();

        return view('word-cloud/exceptions', compact('words_execption'));
    }

    public function excecaoRemove($id)
    {
        $word =  WordsExecption::where('id', $id)->where('client_id', $this->cliente['id'])->first();

        if($word->delete())
            Flash::success('<i class="fa fa-check"></i> Registro retornado com sucesso.');
        else
            Flash::error("Erro ao retornar o registro");

        return redirect('nuvem-palavras/excecoes')->withInput();
        
    }


}
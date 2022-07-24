<?php

namespace App\Http\Controllers;

use App\MediaMaterializedFilteredVw;
use App\MediaMaterializedRuleFilteredVw;
use App\Rule;
use App\WordCloudText;
use App\WordsExecption;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laracasts\Flash\Flash;
use Symfony\Component\Process\Process;


class WordCloudController extends Controller
{

    private $cliente;

    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','nuvem-palavras');
        $this->cliente = Session::get('cliente');
        $this->flag_regras = Session::get('flag_regras');

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

        if($this->cliente) {

            if($this->flag_regras) {
                $this->mediaModel = new MediaMaterializedRuleFilteredVw();
            } else {
                $this->mediaModel = new MediaMaterializedFilteredVw();
            }

            $medias = $this->mediaModel::where('client_id', $this->cliente['id'])
            ->select('text')
            ->distinct('text')
            ->pluck('text')
            ->toArray();

            $text_concatenate = '';

            foreach($medias as $text) {

                $text_concatenate .= ' '.$text;
            }

            $wordcloud_text = WordCloudText::create([
                'text' => $text_concatenate
            ]);

            $file_name = 'wordcloud-'.strtotime(now());

            if(isset($wordcloud_text->id)) {

                $word_cloud = [];

                $process = new Process(['python3', base_path().'/studio-social-wordcloud-rules.py', $wordcloud_text->id, $file_name, 'tela',  $this->cliente['id']]);

                $process->run(function ($type, $buffer) use ($file_name, &$word_cloud){
                    if (Process::ERR === $type) {
                       // echo 'ERR > '.$buffer.'<br />';
                    } else {

                        if(trim($buffer) == 'END') {
                            //echo 'OUT > '.$buffer.'<br />';

                            $file = Storage::disk('wordcloud')->get($file_name.".json");
                            //dd($words_execption);

                            $words = json_decode($file);

                            $words = (Array) $words;
                            arsort($words);

                            $words = array_slice($words, 0, 200);

                            $words_execption = WordsExecption::where('client_id',  $this->cliente['id'])->pluck('word')->toArray();

                            foreach($words as $word => $qtd_times) {

                                if(in_array($word, $words_execption))
                                    continue;

                                $word_cloud[$word] = $qtd_times;
                            }

                            Storage::disk('wordcloud')->delete($file_name.".json");
                        }

                    }
                });

            }

        } else {
                $word_cloud = ['Cliente' => 3, 'Não' => 2, 'Selecionado' => 2];
        }
        echo json_encode($word_cloud);
        
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
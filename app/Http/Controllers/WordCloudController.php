<?php

namespace App\Http\Controllers;

use App\FbPost;
use App\Media;
use App\MediaTwitter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Axisofstevil\StopWords\Filter;
use voku\helper\StopWords;

class WordCloudController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','nuvem-palavras');
    }

    public function render()
    {
        return view('word-cloud/index');
    }

    public function getWords() {
       
        ini_set('memory_limit', '512M'); 

        $text = '';

        $medias = Media::get();

        foreach($medias as $media)  {
            $text .= ' '.$this->sanitize($media->caption); 
        }   

        $posts = FbPost::get();

        foreach($posts as $post)  {
            $text .= ' '.$this->sanitize($post->message);
        }  

        $twitters = MediaTwitter::get();

        foreach($twitters as $twitter)  {
            $text .= ' '.$this->sanitize($twitter->full_text);
        } 

        $stop_words = new StopWords();
        $words = array_merge($stop_words->getStopWordsFromLanguage('pt'), $stop_words->getStopWordsFromLanguage('en'));

        $filter = new Filter();
        $filter->setWords($words);
        $filter->mergeWords(['é','nada','além','pra']);

        $characterMap = 'áàâäãÁÀÂÄÃéèêëÉÈÊËíìîïÍÌÎÏóòôöõÓÒÔÖÕúùûüÚÙÛÜçÇ#123456789';
        $words_list = str_word_count($filter->cleanText($text),1,$characterMap);
        $words_counted = array_count_values($words_list);
        arsort($words_counted);

        $words_counted = array_slice($words_counted, 0, 100);
        $word_cloud = [];
        
        foreach($words_counted as $word => $qtd_times) {
            if (!is_int($word) && strlen($word) > 1) {
                $word_cloud[$word] = $qtd_times;
            }                
        }

        echo json_encode($word_cloud);
    }

    private function sanitize($text) {

        $text =  mb_strtolower($text);

        $text = preg_replace('/\b((https?|ftp|file):\/\/|www\.)[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', ' ', $text);

        $text = preg_replace('/[^A-Za-z0-9áàâãéèêíïóôõöúçñ#\-]/', ' ', $text);

        $text = str_replace(["\n",'.','-'], '', $text);

        return $text;
    }


}
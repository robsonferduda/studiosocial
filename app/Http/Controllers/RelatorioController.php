<?php

namespace App\Http\Controllers;

use DB;
use DOMPDF;
use App\Configs;
use App\Rule;
use App\FbPost;
use App\Media;
use App\Utils;
use App\FbReaction;
use App\MediaTwitter;
use App\WordCloudText;
use App\WordsExecption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class RelatorioController extends Controller
{
    private $client_id;
    private $mensagem;
    private $periodo;
    private $data_inicial;
    private $data_final;
    private $periodo_padrao;
    private $rules;
    private $rule;
    private $rule_id;

    public function __construct()
    {
        $this->middleware('auth');
        $this->mensagem = "";
        $this->rule = null;
        $this->rule_id = null;
        $this->client_id = session('cliente')['id'];
        $this->periodo_padrao = Configs::where('key', 'periodo_padrao')->first()->value;
        $this->rules = Rule::where('client_id', $this->client_id)->orderBy('name')->get();
        Session::put('url','relatorios');
    }

    public function index()
    {
      return view('relatorios/index');
    }

    //INÍCIO Métodos do Relatório de Evolução Diária 

    public function evolucaoDiaria()
    {
      $rules = $this->rules;
      $periodo_padrao = $this->periodo_padrao;
      $periodo_relatorio = $this->retornaDataPeriodo();
      $mensagem = "Volume diário de mensagens dividido por sentimentos";

      return view('relatorios/evolucao-diaria', compact('rules','periodo_relatorio','periodo_padrao','mensagem'));
    }

    public function getEvolucaoDiaria(Request $request)
    {
        $dados = array();
        $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);
        $this->rule_id = $request->regra; 
        $dados = $this->getDadosEvolucaoDiaria();      

        return response()->json($dados);
    }

    public function getDadosEvolucaoDiaria()
    {    
        if($this->rule_id){

          $rules = Rule::when($this->rule_id > 0, function($query){
            return $query->where('id', $this->rule_id);  
          })->where('client_id', $this->client_id)->get();

          foreach($rules as $rule) {

            for ($i=0; $i < $this->periodo; $i++) { 

              $data = $this->data_inicial->addDay()->format('Y-m-d');
              $data_formatada = $this->data_inicial->format('d/m/Y');
                  
              //Total de sentimentos do Twitter
              $total_positivos = $rule->twPosts()->where('sentiment',1)->whereBetween('created_tweet_at',["{$data} 00:00:00","{$data} 23:23:59"])->count();
              $total_negativos = $rule->twPosts()->where('sentiment',-1)->whereBetween('created_tweet_at',["{$data} 00:00:00","{$data} 23:23:59"])->count();
              $total_neutros = $rule->twPosts()->where('sentiment',0)->whereBetween('created_tweet_at',["{$data} 00:00:00","{$data} 23:23:59"])->count();

              //Total de sentimentos do facebook
              $total_positivos += $rule->fbPosts()->where('sentiment',1)->whereBetween('tagged_time',["{$data} 00:00:00","{$data} 23:23:59"])->count();
              $total_negativos += $rule->fbPosts()->where('sentiment',-1)->whereBetween('tagged_time',["{$data} 00:00:00","{$data} 23:23:59"])->count();
              $total_neutros += $rule->fbPosts()->where('sentiment',0)->whereBetween('tagged_time',["{$data} 00:00:00","{$data} 23:23:59"])->count();

              $total_positivos += $rule->fbComments()->where('sentiment',1)->whereBetween('created_time',["{$data} 00:00:00","{$data} 23:23:59"])->count();
              $total_negativos += $rule->fbComments()->where('sentiment',-1)->whereBetween('created_time',["{$data} 00:00:00","{$data} 23:23:59"])->count();
              $total_neutros += $rule->fbComments()->where('sentiment',0)->whereBetween('created_time',["{$data} 00:00:00","{$data} 23:23:59"])->count();

              //Total de sentimentos do Instagram
              $total_positivos += $rule->igPosts()->where('sentiment',1)->whereBetween('timestamp',["{$data} 00:00:00","{$data} 23:23:59"])->count();
              $total_negativos += $rule->igPosts()->where('sentiment',-1)->whereBetween('timestamp',["{$data} 00:00:00","{$data} 23:23:59"])->count();
              $total_neutros += $rule->igPosts()->where('sentiment',0)->whereBetween('timestamp',["{$data} 00:00:00","{$data} 23:23:59"])->count();

              $total_positivos += $rule->igComments()->where('sentiment',1)->whereBetween('timestamp',["{$data} 00:00:00","{$data} 23:23:59"])->count();
              $total_negativos += $rule->igComments()->where('sentiment',-1)->whereBetween('timestamp',["{$data} 00:00:00","{$data} 23:23:59"])->count();
              $total_neutros += $rule->igComments()->where('sentiment',0)->whereBetween('timestamp',["{$data} 00:00:00","{$data} 23:23:59"])->count();
              
              $datas[] = $data;
              $datas_formatadas[] = $data_formatada;
              $dados_positivos[] = $total_positivos;
              $dados_negativos[] = $total_negativos;
              $dados_neutros[] = $total_neutros;
            }
          }

        }else{

          for ($i=0; $i < $this->periodo; $i++) { 

            $data = $this->data_inicial->addDay()->format('Y-m-d');
            $data_formatada = $this->data_inicial->format('d/m/Y');
  
            //Total de sentimentos do Twitter
            $total_positivos = MediaTwitter::where('client_id',$this->client_id)->where('sentiment',1)->whereBetween('created_tweet_at',[$data.' 00:00:00',$data.' 23:23:59'])->count();
            $total_negativos = MediaTwitter::where('client_id',$this->client_id)->where('sentiment',-1)->whereBetween('created_tweet_at',[$data.' 00:00:00',$data.' 23:23:59'])->count();
            $total_neutros   = MediaTwitter::where('client_id',$this->client_id)->where('sentiment',0)->whereBetween('created_tweet_at',[$data.' 00:00:00',$data.' 23:23:59'])->count();
      
            $datas[] = $data;
            $datas_formatadas[] = $data_formatada;
            $dados_positivos[] = $total_positivos;
            $dados_negativos[] = $total_negativos;
            $dados_neutros[] = $total_neutros;
          }

        }
      
        $dados = array('data' => $datas,
                      'data_formatada' => $datas_formatadas,
                      'dados_positivos' => $dados_positivos,
                      'dados_negativos' => $dados_negativos,
                      'dados_neutros' => $dados_neutros);

        return $dados;
    }

    public function evolucaoDiariaPdf(Request $request)
    {
        $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);   
        $dados = $this->getDadosEvolucaoDiaria();
        $chart = $this->getGraficoEvolucaoDiaria($dados);
        $rule = Rule::find($request->regra);
        $dt_inicial = $request->data_inicial;
        $dt_final = $request->data_final;
        $nome = "Relatório de Evolução Diária";

        $nome_arquivo = date('YmdHis').".pdf";

        $pdf = DOMPDF::loadView('relatorios/pdf/evolucao-diaria', compact('chart','dados','rule','dt_inicial','dt_final','nome'));
        return $pdf->download($nome_arquivo);
    }

    //FIM Métodos do Relatório de Evolução Diária

    //INÍCIO Métodos do Relatório de Redes Sociais

    public function evolucaoRedesSociais()
    {
      $rules = $this->rules;
      $periodo_padrao = $this->periodo_padrao;
      $periodo_relatorio = $this->retornaDataPeriodo();
      $mensagem = "Volume diário de mensagens dividido por rede social";

      return view('relatorios/evolucao-redes-sociais', compact('rules','periodo_relatorio','periodo_padrao','mensagem'));
    }

    public function getEvolucaoRedeSocial(Request $request)
    {
        $dados = array();
        $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);
        $this->rule_id = $request->regra; 
        $dados = $this->getDadosEvolucaoRedeSocial();        

        return response()->json($dados);
    }

    public function getDadosEvolucaoRedeSocial()
    {
        for ($i=0; $i < $this->periodo; $i++) { 

          $data = $this->data_inicial->addDay()->format('Y-m-d');
          $data_formatada = $this->data_inicial->format('d/m/Y');

          $datas[] = $data;
          $datas_formatadas[] = $data_formatada;

          $ig_comments_total = DB::table('ig_comments')
                              ->join('medias','medias.id','=','ig_comments.media_id')
                              ->where('medias.client_id','=', $this->client_id)
                              ->whereBetween('ig_comments.timestamp', [$data.' 00:00:00',$data.' 23:23:59'])
                              ->count();

          $fb_comments_total = DB::table('fb_comments')
                              ->join('fb_posts','fb_posts.id','=','fb_comments.post_id')
                              ->where('fb_posts.client_id','=',$this->client_id)
                              ->whereBetween('fb_comments.created_time', [$data.' 00:00:00',$data.' 23:23:59'])
                              ->count();

          $dados_twitter[] = MediaTwitter::where('client_id',$this->client_id)->whereBetween('created_tweet_at',[$data.' 00:00:00',$data.' 23:23:59'])->count();
          $dados_facebook[] = FbPost::where('client_id',$this->client_id)->whereBetween('tagged_time',[$data.' 00:00:00',$data.' 23:23:59'])->count() + $fb_comments_total;
          $dados_instagram[] = Media::where('client_id',$this->client_id)->whereBetween('timestamp',[$data.' 00:00:00',$data.' 23:23:59'])->count() + $ig_comments_total;
      }

      $dados = array('data' => $datas,
                      'data_formatada' => $datas_formatadas,
                      'dados_twitter' => $dados_twitter,
                      'dados_instagram' => $dados_instagram,
                      'dados_facebook' => $dados_facebook);

      return $dados;
    }

    public function evolucaoRedeSocialPdf(Request $request)
    {
        $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);   
        $dados = $this->getDadosEvolucaoRedeSocial();
        $chart = $this->getGraficoEvolucaoRedeSocial($dados);
        $rule = Rule::find($request->regra);
        $dt_inicial = $request->data_inicial;
        $dt_final = $request->data_final;
        $nome = "Relatório de Evolução por Rede Social";

        $nome_arquivo = date('YmdHis').".pdf";

        $pdf = DOMPDF::loadView('relatorios/pdf/evolucao-diaria', compact('chart','dados','rule','dt_inicial','dt_final','nome'));
        return $pdf->download($nome_arquivo);
    }

    //FIM Métodos do Relatório de Redes Sociais

    public function sentimentos()
    {
      $rules = $this->rules;
      $periodo_padrao = $this->periodo_padrao;
      $periodo_relatorio = $this->retornaDataPeriodo();
      $mensagem = "Volume diário de mensagens dividido por sentimentos";
      
      return view('relatorios/sentimentos', compact('rules','periodo_relatorio','periodo_padrao', 'mensagem'));
    }

    public function wordcloud()
    {
      $rules = $this->rules;
      $periodo_padrao = $this->periodo_padrao;
      $periodo_relatorio = $this->retornaDataPeriodo();
      $mensagem = "Nuvem baseada no volume de palavras";
      
      return view('relatorios/wordcloud', compact('rules','periodo_relatorio','periodo_padrao', 'mensagem'));
    }

    public function reactions()
    {
      $rules = $this->rules;
      $periodo_padrao = $this->periodo_padrao;
      $periodo_relatorio = $this->retornaDataPeriodo();
      $mensagem = "Total de reações nas postagens";

      return view('relatorios/reactions', compact('rules','periodo_relatorio','periodo_padrao' ,'mensagem'));
    }

    //INÍCIO Métodos do Relatório de Hashtags
    public function hashtags()
    {
      $rules = $this->rules;
      $periodo_padrao = $this->periodo_padrao;
      $periodo_relatorio = $this->retornaDataPeriodo();
      $mensagem = "Nuvem baseada no volume de hashtags";

      return view('relatorios/hashtags', compact('rules', 'mensagem', 'periodo_padrao','periodo_relatorio'));
    }

    public function getNuvemHashtags(Request $request)
    {
      $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);
      
      $lista_hashtags = Utils::contaOrdenaLista($this->getAllMedias());
      echo json_encode($lista_hashtags);
    } 

    public function hashtagsPdf(Request $request)
    {
        $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);   
        $dados = array();
        $rule = Rule::find($request->regra);
        $dt_inicial = $request->data_inicial;
        $dt_final = $request->data_final;
        $nome = "Relatório de Hashtags";

        $file_name = 'cliente-'.$this->client_id.'-hashtag';

        $lista_hashtags = Utils::contaOrdenaLista($this->getAllMedias());
        //Storage::disk('hashtag')->put($file_name.'.json', json_encode($lista_hashtags));

        $process = new Process(['python3', base_path().'/studio-social-hashtag.py', $this->client_id]);

        $process->run(function ($type, $buffer) use ($file_name, &$chart){
            if (Process::ERR === $type) {
              echo 'ERR > '.$buffer.'<br />';
              //$chartData = file_get_contents(Storage::disk('hashtag-img')->getAdapter()->getPathPrefix()."erro.png");  
              //$chart =  'data:image/png;base64, '.base64_encode($chartData);
            } else {   
              
                if(trim($buffer) == 'END') {                            
                    $chartData = file_get_contents(Storage::disk('hashtag-img')->getAdapter()->getPathPrefix().'cliente_'.$this->client_id."_hashtag.png");  
                    $chart =  'data:image/png;base64, '.base64_encode($chartData);

                    dd($chart);
                }
            }
        });
    
        $nome_arquivo = date('YmdHis').".pdf";

        $pdf = DOMPDF::loadView('relatorios/pdf/hashtags', compact('chart', 'dados','rule','dt_inicial','dt_final','nome'));
        return $pdf->download($nome_arquivo);
    }

    public function getAllMedias()
    {
      $medias = array();
      $lista_hastags = array();

      $dt_inicial = $this->data_inicial->format('Y-m-d');
      $dt_final = $this->data_final->format('Y-m-d');
      
      $medias_instagram = Media::where('client_id', $this->client_id)->whereBetween('timestamp',[$dt_inicial.' 00:00:00',$dt_final.' 23:23:59'])->get();
      $medias_facebook  = FbPost::where('client_id', $this->client_id)->whereBetween('tagged_time',[$dt_inicial.' 00:00:00',$dt_final.' 23:23:59'])->get();
      $medias_twitter = MediaTwitter::where('client_id',$this->client_id)->whereBetween('created_tweet_at',[$dt_inicial.' 00:00:00',$dt_final.' 23:23:59'])->get();

      foreach ($medias_instagram as $media) {
        $lista_hastags = Utils::getHashtags($media->caption, $lista_hastags);
      }

      foreach ($medias_facebook as $media) {
        $lista_hastags = Utils::getHashtags($media->message, $lista_hastags);
      }

      foreach ($medias_twitter as $media) {
        $lista_hastags = Utils::getHashtags($media->full_text, $lista_hastags);
      }

      return $lista_hastags;
    }

    //FIM Métodos do Relatório de Hashtags

    public function influenciadores()
    {
      $rules = $this->rules;
      $periodo_padrao = $this->periodo_padrao;
      $periodo_relatorio = $this->retornaDataPeriodo();
      $mensagem = "Influenciadores positivos e negativos do Twitter";
      
      return view('relatorios/influenciadores', compact('rules','periodo_padrao', 'mensagem','periodo_relatorio'));
    }

    public function getInfluenciadores(Request $request)
    {
      $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);
      $dados = $this->getDadosInfluenciadores();
      return response()->json($dados);
    }

    public function getDadosInfluenciadores()
    {
      
      $dados['positivos'] = (new MediaTwitter())->getInfluenciadoresPositivos($this->client_id, $this->data_inicial, $this->data_final);
      $dados['negativos'] = (new MediaTwitter())->getInfluenciadoresNegativos($this->client_id, $this->data_inicial, $this->data_final);

      foreach($dados['negativos'] as $key => $user){
        if($user->user_profile_image_url){
          $dados['negativos'][$key]->url_image = str_replace('normal','400x400', $user->user_profile_image_url);
        }else{
          $dados['negativos'][$key]->url_image = 'img/user.png';
        }
        $dados['negativos'][$key]->url_perfil = 'https://twitter.com/'.$user->user_name;
      }

      foreach($dados['positivos'] as $key => $user){
        if($user->user_profile_image_url){
          $dados['positivos'][$key]->url_image = str_replace('normal','400x400', $user->user_profile_image_url);
        }else{
          $dados['positivos'][$key]->url_image = '../img/user.png';
        }
        $dados['positivos'][$key]->url_perfil = 'https://twitter.com/'.$user->user_name;
      }

      return $dados;
    }

    public function influenciadoresPdf(Request $request)
    {
        $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);   
        $dados = $this->getDadosInfluenciadores();
        $rule = Rule::find($request->regra);
        $dt_inicial = $request->data_inicial;
        $dt_final = $request->data_final;
        $nome = "Relatório de Principais Influenciadores";

        $nome_arquivo = date('YmdHis').".pdf";

        $pdf = DOMPDF::loadView('relatorios/pdf/influenciadores', compact('dados','rule','dt_inicial','dt_final','nome'));
        return $pdf->download($nome_arquivo);
    }

    public function localizacao()
    {
      $rules = $this->rules;
      $periodo_padrao = $this->periodo_padrao;
      $periodo_relatorio = $this->retornaDataPeriodo();
      $mensagem = "Localização das postagens e dos usuários do Twitter";
      
      return view('relatorios/localizacao', compact('rules','periodo_padrao', 'mensagem','periodo_relatorio'));
    }

    public function gerenciador()
    {
      $rules = $this->rules;
      $periodo_padrao = $this->periodo_padrao;
      $periodo_relatorio = $this->retornaDataPeriodo();
      $mensagem = "Geração de relatórios em lote";
      
      return view('relatorios/gerenciador', compact('rules','periodo_padrao', 'mensagem','periodo_relatorio'));
    }

    public function retornaDataPeriodo()
    {
        return array('data_inicial' => Carbon::now()->subDays($this->periodo_padrao - 1)->format('d/m/Y'),
                     'data_final'   => Carbon::now()->format('d/m/Y'));
    }

    public function geraDataPeriodo($periodo, $data_inicial, $data_final)
    {
        $carbon = new Carbon();

        if($periodo == 0){

          $data_inicial = $carbon->createFromFormat('d/m/Y', $data_inicial);
          $data_final = $carbon->createFromFormat('d/m/Y', $data_final);
          
          $periodo = $data_final->diffInDays($data_inicial) + 1;
          $data_inicial = $data_inicial->subDays(1);

        }else{
          $data_inicial = Carbon::now()->subDays($periodo);
          $data_final = $carbon->createFromFormat('d/m/Y', $data_final);
        }

        $this->periodo = $periodo;
        $this->data_inicial = $data_inicial;
        $this->data_final = $data_final;
    } 

    public function getReactions(Request $request)
    {
      $dados = array();
      $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);
      $this->rule_id = $request->regra;
      $dados = $this->getDadosReactions();

      return response()->json($dados);
    }

    public function getSentimentosRede(Request $request)
    {
      $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);  
      $this->rule_id = $request->regra; 
      $sentimentos = $this->getSentimentos();   

      return response()->json($sentimentos);
    }

    public function getLocalizacao(Request $request)
    {
        $dados = array();
        $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);
        $this->rule_id = $request->regra; 
        $dados = $this->getDadosLocalizacao();      

        return response()->json($dados);
    }

    public function getDadosReactions()
    {
      return (new FbPost())->getReactions($this->client_id, $this->data_inicial, $this->data_final, $this->rule_id);
    }

    public function getSentimentos()
    {
        $sentimentos_twitter = (new MediaTwitter())->getSentimentos($this->client_id, $this->data_inicial, $this->data_final, $this->rule_id);
        $sentimentos_facebook = (new FbPost())->getSentimentos($this->client_id, $this->data_inicial, $this->data_final);
        $sentimentos_instagram = (new Media())->getSentimentos($this->client_id, $this->data_inicial, $this->data_final);

        $sentimentos['facebook'] = array('rede_social' => "Facebook",
                                        'total_positivo' => ($sentimentos_facebook) ? $sentimentos_facebook[2]->total : 0,
                                        'total_negativo' => ($sentimentos_facebook) ? $sentimentos_facebook[0]->total : 0,
                                        'total_neutro' => ($sentimentos_facebook) ? $sentimentos_facebook[1]->total : 0);

        $sentimentos['instagram'] = array('rede_social' => "Instagram",
                                          'total_positivo' => ($sentimentos_instagram and isset($sentimentos_instagram[2])) ? $sentimentos_instagram[2]->total : 0,
                                          'total_negativo' => ($sentimentos_instagram and isset($sentimentos_instagram[0])) ? $sentimentos_instagram[0]->total : 0,
                                          'total_neutro' => ($sentimentos_instagram and isset($sentimentos_instagram[1])) ? $sentimentos_instagram[1]->total : 0,);

        $sentimentos['twitter'] = array('rede_social' => "Twitter",
                                        'total_positivo' => ($sentimentos_twitter and isset($sentimentos_twitter[2])) ? $sentimentos_twitter[2]->total : 0,
                                        'total_negativo' => ($sentimentos_twitter and isset($sentimentos_twitter[0])) ? $sentimentos_twitter[0]->total : 0,
                                        'total_neutro' => ($sentimentos_twitter and isset($sentimentos_twitter[1])) ? $sentimentos_twitter[1]->total : 0);

        return $sentimentos;
    }

    public function getDadosLocalizacao()
    {
        $location_tweet = (new MediaTwitter())->getTweetLocation($this->client_id, $this->data_inicial, $this->data_final, $this->rule_id);
        $location_user = (new MediaTwitter())->getUserLocation($this->client_id, $this->data_inicial, $this->data_final, $this->rule_id);

        $locations['location_tweet'] = $location_tweet;
        $locations['location_user'] = $location_user;

        return $locations;
    }

    //Métodos chamados quando o relatório em pdf é requisitado

    public function wordcloudPdf(Request $request)
    {
        $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);   
        $this->rule_id = $request->regra;       
        $nome = "Nuvem de Palavras";

              
        $nome_arquivo = date('YmdHis').".pdf";

        $rule = Rule::find($request->regra);
        $chart = $this->getGraficoWordCloud();

        $dt_inicial = $request->data_inicial;
        $dt_final = $request->data_final;

        $pdf = DOMPDF::loadView('relatorios/pdf/wordcloud', compact('chart','rule','dt_inicial','dt_final','nome'));
        return $pdf->download($nome_arquivo);
    }

    public function getGraficoWordCloud()
    {
      $rule = $this->rule_id; 

      $rules = Rule::when(!empty($rule), function($query) use ($rule){
          return $query->where('id', $rule);  
      })->where('client_id', $this->client_id)->get();
      
      $text = '';

      foreach($rules as $rule) {

          $dt_inicial = $this->data_inicial->format('Y-m-d');
          $dt_final = $this->data_final->format('Y-m-d');

          $igPosts = $rule->igPosts()->whereBetween('timestamp', ["{$dt_inicial} 00:00:00","{$dt_final} 23:59:59"])->pluck('caption')->toArray();
          $igComments = $rule->igComments()->whereBetween('timestamp', ["{$dt_inicial} 00:00:00","{$dt_final} 23:59:59"])->pluck('text')->toArray();
          $fbPosts = $rule->fbPosts()->whereBetween('tagged_time', ["{$dt_inicial} 00:00:00","{$dt_final} 23:59:59"])->pluck('message')->toArray();
          $fbComments = $rule->fbComments()->whereBetween('created_time', ["{$dt_inicial} 00:00:00","{$dt_final} 23:59:59"])->pluck('text')->toArray();
          $twPosts = $rule->twPosts()->whereBetween('created_tweet_at', ["{$dt_inicial} 00:00:00","{$dt_final} 23:59:59"])->pluck('full_text')->toArray();
          $fbPagePost = $rule->fbPagePosts()->whereBetween('updated_time', ["{$dt_inicial} 00:00:00","{$dt_final} 23:59:59"])->pluck('message')->toArray();

          $textig = $this->concatenateSanitizeText($igPosts);
          $textigc = $this->concatenateSanitizeText($igComments);
          $textfb = $this->concatenateSanitizeText($fbPosts);
          $textfbc = $this->concatenateSanitizeText($fbComments);
          $texttw = $this->concatenateSanitizeText($twPosts);
          $textfpp = $this->concatenateSanitizeText($fbPagePost);

          $text .= ' '.$textig.' '.$textigc.' '.$textfb.' '.$textfbc.' '.$texttw.' '.$textfpp;
      }

      $wordcloud_text = WordCloudText::create([
          'text' => $text
      ]);

      $file_name = 'wordcloud-'.strtotime(now());
      $chart = null;

      if(isset($wordcloud_text->id)) {
          
          $process = new Process(['python3', base_path().'/studio-social-wordcloud-rules.py', $wordcloud_text->id, $file_name, 'imagem', $this->client_id]);

          $process->run(function ($type, $buffer) use ($file_name, &$chart){
              if (Process::ERR === $type) {
                  //echo 'ERR > '.$buffer.'<br />';
              } else {
                  
                  if(trim($buffer) == 'END') {
                      //echo 'OUT > '.$buffer.'<br />';
                              
                      $chartData = file_get_contents(Storage::disk('wordcloud')->getAdapter()->getPathPrefix().$file_name.".png"); 
                      $chart =  'data:image/png;base64, '.base64_encode($chartData);
                      
                      Storage::disk('wordcloud')->delete($file_name.".png");
                      Storage::disk('wordcloud')->delete($file_name.".json");
                  }

              }
          });
      
      } 
      return $chart;
    }
    
    public function pdf(Request $request)
    {
        $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);   
        $sentimentos = $this->getSentimentos();
        $chart = $this->getGraficoSentimentos($sentimentos);
        $rule = Rule::find($request->regra);
        $dt_inicial = $request->data_inicial;
        $dt_final = $request->data_final;
        $nome = "Relatório de Sentimentos";

        $nome_arquivo = date('YmdHis').".pdf";

        $pdf = DOMPDF::loadView('relatorios/pdf/sentimentos', compact('chart','sentimentos','rule','dt_inicial','dt_final','nome'));
        return $pdf->download($nome_arquivo);
    }

    public function reactionsPdf(Request $request)
    {
        $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);   
        $dados = $this->getDadosReactions();
        $chart = $this->getGraficoReactions($dados);
        $rule = Rule::find($request->regra);
        $dt_inicial = $request->data_inicial;
        $dt_final = $request->data_final;
        $nome = "Relatório de Reactions";

        $nome_arquivo = date('YmdHis').".pdf";

        $pdf = DOMPDF::loadView('relatorios/pdf/reactions', compact('chart','dados','rule','dt_inicial','dt_final','nome'));
        return $pdf->download($nome_arquivo);
    }

    public function localizacaoPdf(Request $request)
    {
      $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final); 
      $this->rule_id = $request->regra;  
      $dados = $this->getDadosLocalizacao();
      $rule = Rule::find($request->regra);
      $dt_inicial = $request->data_inicial;
      $dt_final = $request->data_final;
      $nome = "Relatório de Localização";

      $nome_arquivo = date('YmdHis').".pdf";

      $pdf = DOMPDF::loadView('relatorios/pdf/localizacao', compact('dados','rule','dt_inicial','dt_final','nome'));
      return $pdf->download($nome_arquivo);
    }

    //Métodos de geração da imagem do gráfico

    public function getGraficoEvolucaoDiaria($dados)
    {
      $datas = implode("','", $dados['data_formatada']);
      $positivos = implode(",", $dados['dados_positivos']);
      $negativos = implode(",", $dados['dados_negativos']);
      $neutros = implode(",", $dados['dados_neutros']);

      $chartData = "{
        type: 'bar',
        data: {
            labels: ['{$datas}'],
            datasets: [
            {
                label: ' Positivo',
                    borderColor: '#4caf50',
                    fill: true,
                    backgroundColor: '#4caf50',
                    hoverBorderColor: '#4caf50',
                    borderWidth: 8,
                    stack: '1',
                    data: [{$positivos}]
                },
                {
                    label: ' Negativo',
                    borderColor: '#f44336',
                    fill: true,
                    backgroundColor: '#f44336',
                    hoverBorderColor: '#f44336',
                    borderWidth: 8,
                    stack: '1',
                    data: [{$negativos}]
                },
                {
                    label: ' Neutro',
                    borderColor: '#ffcc33',
                    fill: true,
                    backgroundColor: '#ffcc33',
                    hoverBorderColor: '#ffcc33',
                    borderWidth: 8,
                    stack: '1',
                    data: [{$neutros}]
                }
            ]
        },
        options: {
          legend: {
            position: 'bottom',
            labels: {
              fontSize: 8,
            }
          },
          title: {
            display: true,
            text: 'Evolução Diária',
            fontSize: 12,
          },
          scales: {
            yAxes: [
              {
                ticks: {
                  suggestedMax: 10,
                  fontSize: 8,
                  beginAtZero: true,
                  fontFamily: 'Montserrat',
                },
              },
            ],
            xAxes: [
              {
                barPercentage: 0.3,
                ticks: {
                  fontFamily: 'Montserrat',
                  fontSize: 8,
                },
              },
            ],
          },
        },
      }";

      $chartURL = "https://quickchart.io/chart?&c=".urlencode($chartData);

      $chartData = file_get_contents($chartURL); 
      return 'data:image/png;base64, '.base64_encode($chartData);
    }

    public function getGraficoEvolucaoRedeSocial($dados)
    {
      $datas = implode("','", $dados['data_formatada']);
      $instagram = implode(",", $dados['dados_instagram']);
      $facebook = implode(",", $dados['dados_facebook']);
      $twitter = implode(",", $dados['dados_twitter']);

      $chartData = "{
        type: 'bar',
        data: {
            labels: ['{$datas}'],
            datasets: [
            {
                label: ' Instagram',
                borderColor: '#e91ea1',
                fill: true,
                backgroundColor: '#e91ea1',
                hoverBorderColor: '#fcc468',
                borderWidth: 8,
                stack: '1',
                data: [ $instagram ]
            },
            {
                label: ' Facebook',
                borderColor: '#3f51b5',
                fill: true,
                backgroundColor: '#3f51b5',
                hoverBorderColor: '#3f51b5',
                borderWidth: 8,
                stack: '1',
                data: [ $facebook ]
            },
            {
                label: ' Twitter',
                borderColor: '#51bcda',
                fill: true,
                backgroundColor: '#51bcda',
                hoverBorderColor: '#51bcda',
                borderWidth: 8,
                stack: '1',
                data: [ $twitter ]
            }
          ]
        },
        options: {
          legend: {
            position: 'bottom',
            labels: {
              fontSize: 8,
            }
          },
          title: {
            display: true,
            text: 'Evolução Diária',
            fontSize: 12,
          },
          scales: {
            yAxes: [
              {
                ticks: {
                  suggestedMax: 10,
                  fontSize: 8,
                  beginAtZero: true,
                  fontFamily: 'Montserrat',
                },
              },
            ],
            xAxes: [
              {
                barPercentage: 0.3,
                ticks: {
                  fontFamily: 'Montserrat',
                  fontSize: 8,
                },
              },
            ],
          },
        },
      }";

      $chartURL = "https://quickchart.io/chart?&c=".urlencode($chartData);

      $chartData = file_get_contents($chartURL); 
      return 'data:image/png;base64, '.base64_encode($chartData);
    }

    public function getGraficoSentimentos($sentimentos)
    {
      $chartData = "{
          type: 'bar',
          data: {
          labels: ['Positivo', 'Negativo', 'Neutro'],
              datasets: [{
                  label: 'Facebook',
                  backgroundColor: '#3f51b5',
                  hoverBorderColor: '#3f51b5',
                  data: [{$sentimentos['facebook']['total_positivo']}, {$sentimentos['facebook']['total_negativo']}, {$sentimentos['facebook']['total_neutro']}]
              }, {
                  label: 'Instagram',
                  backgroundColor: '#e91ea1',
                  hoverBorderColor: '#fcc468',
                  data: [{$sentimentos['instagram']['total_positivo']}, {$sentimentos['instagram']['total_negativo']}, {$sentimentos['instagram']['total_neutro']}]
              },{
                  label: 'Twitter',
                  backgroundColor: '#51bcda',
                  hoverBorderColor: '#51bcda',
                  data: [{$sentimentos['twitter']['total_positivo']}, {$sentimentos['twitter']['total_negativo']}, {$sentimentos['twitter']['total_neutro']}]
              }]
          },
          options: {
              legend: {
                position: 'bottom',
                labels: {
                  fontSize: 10,
                }
              },
              title: {
                display: true,
                text: 'Redes Sociais x Sentimentos',
                fontSize: 12,
              },
              scales: {
                yAxes: [
                  {
                    ticks: {
                      suggestedMax: 10,
                      fontSize: 10,
                      beginAtZero: true,
                      fontFamily: 'Montserrat',
                    },
                  },
                ],
                xAxes: [
                  {
                    ticks: {
                      fontFamily: 'Montserrat',
                      fontSize: 10,
                    },
                  },
                ],
              },
            },
      }";

      $chartURL = "https://quickchart.io/chart?&c=".urlencode($chartData);

      $chartData = file_get_contents($chartURL); 
      return 'data:image/png;base64, '.base64_encode($chartData);
    }

    public function getGraficoReactions($dados)
    {
      $valores = null;
      $labels = null;
      $colors = null;

      foreach ($dados as $key => $dado) {
        $valores[] = $dado->count;
        $labels[] = $dado->icon;
        $colors[] = $dado->color;
      }

      if($valores){

          $valores = implode(",", $valores);
          $labels = implode("','", $labels);
          $colors = implode("','", $colors);
      }

      $chartData = "{
                    type:'pie',
                    data:{
                      labels:['$labels'],
                      datasets:[{
                        label:'Reactions',
                        borderWidth: 0,
                        backgroundColor: ['$colors'],
                        data:[$valores]
                      }]
                    },
                    options: {
                      legend: {
                        position: 'bottom',
                        labels: {
                          fontSize: 10,
                        }
                      },
                      pieceLabel: {
                        render: 'percentage',
                        fontColor: '#fff',
                        precision: 2
                      },
                      scales: {
                       
                      },
                    }
                  }";

      $chartURL = "https://quickchart.io/chart?&c=".urlencode($chartData);

      $chartData = file_get_contents($chartURL); 
      return 'data:image/png;base64, '.base64_encode($chartData);
    }

    public function getWordCloudPeriodo(Request $request) 
    {   
        $rule =   $request->regra;

        $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);

        if(isset($this->client_id)) {

            $rules = Rule::when(!empty($rule), function($query) use ($rule){
                return $query->where('id', $rule);  
            })->where('client_id', $this->client_id)->get();
            
            $text = '';

            foreach($rules as $rule) {

                $dt_inicial = $this->data_inicial->format('Y-m-d');
                $dt_final = $this->data_final->format('Y-m-d');

                $igPosts = $rule->igPosts()->whereBetween('timestamp', ["{$dt_inicial} 00:00:00","{$dt_final} 23:59:59"])->pluck('caption')->toArray();
                $igComments = $rule->igComments()->whereBetween('timestamp', ["{$dt_inicial} 00:00:00","{$dt_final} 23:59:59"])->pluck('text')->toArray();
                $fbPosts = $rule->fbPosts()->whereBetween('tagged_time', ["{$dt_inicial} 00:00:00","{$dt_final} 23:59:59"])->pluck('message')->toArray();
                $fbComments = $rule->fbComments()->whereBetween('created_time', ["{$dt_inicial} 00:00:00","{$dt_final} 23:59:59"])->pluck('text')->toArray();
                $twPosts = $rule->twPosts()->whereBetween('created_tweet_at', ["{$dt_inicial} 00:00:00","{$dt_final} 23:59:59"])->pluck('full_text')->toArray();
                $fbPagePost = $rule->fbPagePosts()->whereBetween('updated_time', ["{$dt_inicial} 00:00:00","{$dt_final} 23:59:59"])->pluck('message')->toArray();
    
                $textig = $this->concatenateSanitizeText($igPosts);
                $textigc = $this->concatenateSanitizeText($igComments);
                $textfb = $this->concatenateSanitizeText($fbPosts);
                $textfbc = $this->concatenateSanitizeText($fbComments);
                $texttw = $this->concatenateSanitizeText($twPosts);
                $textfpp = $this->concatenateSanitizeText($fbPagePost);
    
                $text .= ' '.$textig.' '.$textigc.' '.$textfb.' '.$textfbc.' '.$texttw.' '.$textfpp;
            }

            $wordcloud_text = WordCloudText::create([
                'text' => $text
            ]);

            $file_name = 'wordcloud-'.strtotime(now());

            if(isset($wordcloud_text->id)) {
                
                $word_cloud = [];

                $process = new Process(['python3', base_path().'/studio-social-wordcloud-rules.py', $wordcloud_text->id, $file_name, 'tela', $this->client_id]);

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

                            $words_execption = WordsExecption::where('client_id', $this->client_id)->pluck('word')->toArray();
                                            
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

    public function concatenateSanitizeText(Array $textArray)
    {
        $concatenateText = '';

        foreach($textArray as $text) {          
          $concatenateText .= ' '.$text;
        }

        return $concatenateText;
    }

    public function geradorPdf(Request $request)
    {
        $this->rule_id = $request->regra;  
        $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);   
        $rule = Rule::find($request->regra);
        $dt_inicial = $request->data_inicial;
        $dt_final = $request->data_final;
        $relatorios = $request->relatorios;
        $nome = "Relatório de Redes Sociais";
        $page_break = 0;

        $dados = [];
        $charts = [];

        if(in_array('evolucao_diaria', $relatorios)){
          $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);  
          $dados['evolucao_diaria'] = $this->getDadosEvolucaoDiaria(); 
          $charts['evolucao_diaria'] = $this->getGraficoEvolucaoDiaria($dados['evolucao_diaria']);
          $page_break++;
        }

        if(in_array('evolucao_rede', $relatorios)){
          $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);  
          $dados['evolucao_rede'] = $this->getDadosEvolucaoRedeSocial(); 
          $charts['evolucao_rede'] = $this->getGraficoEvolucaoRedeSocial($dados['evolucao_rede']);
          $page_break++;
        }

        if(in_array('sentimentos', $relatorios)){
          $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);  
          $dados['sentimentos'] = $this->getSentimentos(); 
          $charts['sentimentos'] = $this->getGraficoSentimentos($dados['sentimentos']);
          $page_break++;
        }

        if(in_array('nuvem', $relatorios)){
          $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);  
          $charts['nuvem'] = $this->getGraficoWordCloud();
          $page_break++;
        }

        if(in_array('reactions', $relatorios)){
          $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);  
          $dados['reactions'] = $this->getDadosReactions();
          $charts['reactions'] = $this->getGraficoReactions($dados['reactions']);
          $page_break++;
        }

        if(in_array('hashtags', $relatorios)){
          $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);  
          $dados['hashtags'] = null;
          $charts['hashtags'] = null;
          $page_break++;
        }

        if(in_array('influenciadores', $relatorios)){
          $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);  
          $dados['influenciadores'] = $this->getDadosInfluenciadores();
          $page_break++;
        }

        if(in_array('localizacao', $relatorios)){
          $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);  
          $dados['localizacao'] = $this->getDadosLocalizacao(); 
          
          $page_break++;
        }

        $nome_arquivo = date('YmdHis').".pdf";

        $pdf = DOMPDF::loadView('relatorios/pdf/gerador', compact('dados', 'charts' ,'rule','dt_inicial','dt_final','nome','relatorios','page_break'));
        return $pdf->download($nome_arquivo);
    }
}
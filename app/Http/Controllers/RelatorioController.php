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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RelatorioController extends Controller
{
    private $client_id;
    private $mensagem;
    private $periodo;
    private $data_inicial;
    private $data_final;
    private $periodo_padrao;
    private $rules;

    public function __construct()
    {
        $this->middleware('auth');
        $this->mensagem = "";
        $this->client_id = session('cliente')['id'];
        $this->periodo_padrao = Configs::where('key', 'periodo_padrao')->first()->value;
        $this->rules = Rule::where('client_id', $this->client_id)->orderBy('name')->get();
        Session::put('url','relatorios');
    }

    public function index()
    {
      return view('relatorios/index');
    }

    public function evolucaoDiaria()
    {
      $rules = $this->rules;
      $periodo_padrao = $this->periodo_padrao;
      $periodo_relatorio = $this->retornaDataPeriodo();
      $mensagem = "Volume diário de mensagens dividido por sentimentos";

      return view('relatorios/evolucao-diaria', compact('rules','periodo_relatorio','periodo_padrao','mensagem'));
    }

    public function evolucaoRedesSociais()
    {
      $rules = $this->rules;
      $periodo_padrao = $this->periodo_padrao;
      $periodo_relatorio = $this->retornaDataPeriodo();
      $mensagem = "Volume diário de mensagens dividido por rede social";

      return view('relatorios/evolucao-redes-sociais', compact('rules','periodo_relatorio','periodo_padrao','mensagem'));
    }

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

    public function hashtags()
    {
      $rules = $this->rules;
      $periodo_padrao = $this->periodo_padrao;
      $periodo_relatorio = $this->retornaDataPeriodo();
      $mensagem = "Nuvem baseada no volume de hashtags";

      return view('relatorios/hashtags', compact('rules', 'mensagem', 'periodo_padrao','periodo_relatorio'));
    }

    public function influenciadores()
    {
      $rules = $this->rules;
      $periodo_padrao = $this->periodo_padrao;
      $periodo_relatorio = $this->retornaDataPeriodo();
      $mensagem = "Influenciadores positivos e negativos do Twitter";
      
      return view('relatorios/influenciadores', compact('rules','periodo_padrao', 'mensagem','periodo_relatorio'));
    }

    public function retornaDataPeriodo()
    {
        return array('data_inicial' => Carbon::now()->subDays($this->periodo_padrao - 1)->format('d/m/Y'),
                     'data_final'   => Carbon::now()->format('d/m/Y'));
    }

    public function getInfluenciadores(Request $request)
    {
      $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);

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

      return response()->json($dados);
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

    public function getNuvemHashtags(Request $request)
    {
      $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);
      
      $lista_hashtags = Utils::contaOrdenaLista($this->getAllMedias());
      echo json_encode($lista_hashtags);
    }    

    public function getReactions(Request $request)
    {

      $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);
      $dt_inicial = $this->data_inicial->format('Y-m-d');
      $dt_final = $this->data_final->format('Y-m-d');

      $reactions = DB::select("SELECT t3.name, t3.color, t3.icon, count(*) 
                              FROM fb_posts t1, fb_post_reaction t2, fb_reactions t3
                              WHERE t1.id = t2.post_id 
                              AND t2.reaction_id = t3.id 
                              AND t2.updated_at BETWEEN '$dt_inicial 00:00:00' AND '$dt_final 23:59:59'
                              AND t1.client_id = $this->client_id
                              GROUP BY t3.name, t3.color, t3.icon 
                              ORDER BY t3.name");

      return response()->json($reactions);
    }

    public function getSentimentosRede(Request $request)
    {

      $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);

      $sentimentos_twitter = (new MediaTwitter())->getSentimentos($this->data_inicial, $this->data_final);
      $sentimentos_facebook = (new FbPost())->getSentimentos($this->data_inicial, $this->data_final);
      $sentimentos_instagram = (new Media())->getSentimentos($this->data_inicial, $this->data_final);

      $sentimentos['facebook'] = array('rede_social' => "Facebook",
                                       'total_positivo' => ($sentimentos_facebook) ? $sentimentos_facebook[2]->total : 0,
                                       'total_negativo' => ($sentimentos_facebook) ? $sentimentos_facebook[0]->total : 0,
                                       'total_neutro' => ($sentimentos_facebook) ? $sentimentos_facebook[1]->total : 0);

      $sentimentos['instagram'] = array('rede_social' => "Instagram",
                                        'total_positivo' => ($sentimentos_instagram) ? $sentimentos_instagram[2]->total : 0,
                                        'total_negativo' => ($sentimentos_instagram) ? $sentimentos_instagram[0]->total : 0,
                                        'total_neutro' => ($sentimentos_instagram) ? $sentimentos_instagram[1]->total : 0,);

      $sentimentos['twitter'] = array('rede_social' => "Twitter",
                                      'total_positivo' => ($sentimentos_twitter) ? $sentimentos_twitter[2]->total : 0,
                                      'total_negativo' => ($sentimentos_twitter) ? $sentimentos_twitter[0]->total : 0,
                                      'total_neutro' => ($sentimentos_twitter) ? $sentimentos_twitter[1]->total : 0);
      
      return response()->json($sentimentos);
    }

    public function getSentimentosPeriodo(Request $request)
    {
        $dados = array();
        $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);

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

        $dados = array('data' => $datas,
                         'data_formatada' => $datas_formatadas,
                         'dados_positivos' => $dados_positivos,
                         'dados_negativos' => $dados_negativos,
                         'dados_neutros' => $dados_neutros);

        return response()->json($dados);

    }

    public function getRedesPeriodo(Request $request)
    {
        $dados = array();
        $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);

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

        return response()->json($dados);

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

    

    public function pdf()
    {
        $nome_arquivo = date('YmdHis').".pdf";
        
        $chartData = [
            "type" => 'horizontalBar',
              "data" => [
                "labels" => ['Coluna 1', 'Coluna 2', 'Coluna 3'],
                  "datasets" => [
                    [
                      "label" => "Dados", 
                      "data" => [100, 60, 20],
                      "backgroundColor" => ['#27ae60', '#f1c40f', '#e74c3c']
                    ], 
                  ],
                ]
            ];
        
        $chartData = json_encode($chartData);

        $chartData = "{
            type: 'bar',
            data: {
            labels: ['Positivo', 'Negativo', 'Neutro'],
                datasets: [{
                    label: 'Facebook',
                    data: [35, 52, 18]
                }, {
                    label: 'Instagram',
                    data: [63, 27, 10]
                },{
                    label: 'Twitter',
                    data: [81, 11, 80]
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
        $chart = 'data:image/png;base64, '.base64_encode($chartData);

        $pdf = DOMPDF::loadView('relatorios/pdf', compact('chart'));

        return $pdf->download($nome_arquivo);
    }
}
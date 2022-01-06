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
    private $periodo_padrao;
    private $rules;

    public function __construct()
    {
        $this->middleware('auth');
        $this->mensagem = "";
        $this->client_id = session('cliente')['id'];
        $this->periodo_padrao = Configs::where('key', 'periodo_padrao')->first()->value - 1;
        $this->rules = Rule::all();
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

      return view('relatorios/evolucao-diaria', compact('rules','periodo_relatorio','mensagem'));
    }

    public function evolucaoRedesSociais()
    {
      $rules = $this->rules;
      $periodo_padrao = $this->periodo_padrao;
      $periodo_relatorio = $this->retornaDataPeriodo();
      $mensagem = "Volume diário de mensagens dividido por rede social";

      return view('relatorios/evolucao-redes-sociais', compact('rules','periodo_relatorio','mensagem'));
    }

    public function sentimentos()
    {
      $rules = $this->rules;
      $periodo_padrao = $this->periodo_padrao;
      $periodo_relatorio = $this->retornaDataPeriodo();
      $mensagem = "Volume diário de mensagens dividido por sentimentos";
      
      return view('relatorios/sentimentos', compact('rules','periodo_relatorio','mensagem'));
    }

    public function wordcloud()
    {
      $rules = $this->rules;
      $periodo_padrao = $this->periodo_padrao;
      $periodo_relatorio = $this->retornaDataPeriodo();
      $mensagem = "Nuvem baseada no volume de palavras";
      
      return view('relatorios/wordcloud', compact('rules','periodo_relatorio','mensagem'));
    }

    public function reactions()
    {
      $rules = $this->rules;
      $periodo_padrao = $this->periodo_padrao;
      $periodo_relatorio = $this->retornaDataPeriodo();
      $mensagem = "Total de reações nas postagens";

      return view('relatorios/reactions', compact('rules','periodo_relatorio','mensagem'));
    }

    public function hashtags()
    {
      $rules = $this->rules;
      $periodo_padrao = $this->periodo_padrao;
      $periodo_relatorio = $this->retornaDataPeriodo();
      $mensagem = "Nuvem baseada no volume de hashtags";

      $lista_hashtags = Utils::contaOrdenaLista($this->getAllMedias());

      return view('relatorios/hashtags', compact('rules','lista_hashtags','mensagem','periodo_relatorio'));
    }

    public function influenciadores()
    {
      $rules = $this->rules;
      $periodo_padrao = $this->periodo_padrao;
      $periodo_relatorio = $this->retornaDataPeriodo();
      $mensagem = "Influenciadores positivos e negativos";

      
      $positivos = (new MediaTwitter())->getInfluenciadoresPositivos($this->client_id);
      $negativos = (new MediaTwitter())->getInfluenciadoresNegativos($this->client_id);

      return view('relatorios/influenciadores', compact('rules','positivos','negativos','mensagem','periodo_relatorio'));
    }

    public function retornaDataPeriodo()
    {
        return array('data_inicial' => Carbon::now()->subDays($this->periodo_padrao)->format('d/m/Y'),
                     'data_final'   => Carbon::now()->format('d/m/Y'));
    }

    public function getNuvemHashtags()
    {
      $rules = Rule::all();
      $lista_hashtags = Utils::contaOrdenaLista($this->getAllMedias());

      echo json_encode($lista_hashtags);
    }    

    public function getReactions()
    {
      $reactions = DB::select('SELECT t3.name, t3.color, t3.icon, count(*) 
                              FROM fb_posts t1, fb_post_reaction t2, fb_reactions t3
                              WHERE t1.id = t2.post_id 
                              AND t2.reaction_id = t3.id 
                              GROUP BY t3.name, t3.color, t3.icon 
                              ORDER BY t3.name');

      return response()->json($reactions);
    }

    public function getSentimentos()
    {

      $sentimentos_twitter = (new MediaTwitter())->getSentimentos();

      $sentimentos[] = array('rede_social' => "Facebook",'total_positivo' => rand(1,100),'total_negativo' => rand(1,100),'total_neutro' => rand(1,100));
      $sentimentos[] = array('rede_social' => "Instagram",'total_positivo' => rand(1,100),'total_negativo' => rand(1,100),'total_neutro' => rand(1,100));
      $sentimentos[] = array('rede_social' => "Twitter",'total_positivo' => $sentimentos_twitter[2]->total,'total_negativo' => $sentimentos_twitter[0]->total,'total_neutro' => $sentimentos_twitter[1]->total);

      return response()->json($sentimentos);
    }

    public function getSentimentosPeriodo($dias)
    {
        $data_inicial = Carbon::now()->subDays($dias);
        $dados = array();

        for ($i=0; $i < $dias; $i++) { 

            $data = $data_inicial->addDay()->format('Y-m-d');
            $data_formatada = $data_inicial->format('d/m/Y');

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

    public function getAllMedias()
    {
      $medias = array();
      $lista_hastags = array();

      $medias_instagram = Media::where('client_id', $this->client_id)->get();
      $medias_facebook  = FbPost::where('client_id', $this->client_id)->get();
      $medias_twitter = MediaTwitter::where('client_id', $this->client_id)->get();

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
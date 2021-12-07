<?php

namespace App\Http\Controllers;

use DB;
use DOMPDF;
use App\Rule;
use App\FbPost;
use App\FbReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RelatorioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','relatorios');
    }

    public function index()
    {
        $reactions = FbReaction::all();
        return view('relatorios/index', compact('reactions'));
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

    public function reactions()
    {
      $rules = Rule::all();
      return view('relatorios/reactions', compact('rules'));
    }

    public function influenciadores()
    {
      $rules = Rule::all();
      return view('relatorios/influenciadores', compact('rules'));
    }

    public function sentimentos()
    {
      return view('relatorios/sentimentos');
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
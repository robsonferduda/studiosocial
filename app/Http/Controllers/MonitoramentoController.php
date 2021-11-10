<?php

namespace App\Http\Controllers;

use App\FbPost;
use App\Media;
use App\MediaTwitter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MonitoramentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','monitoramento');
    }

    public function index()
    {
        $client_id = Session::get('cliente')['id'];
        $totais = array('total_insta' => Media::where('client_id',$client_id)->count(),
                        'total_face' => FbPost::where('client_id',$client_id)->count(),
                        'total_twitter' => MediaTwitter::where('client_id',$client_id)->count());

        return view('monitoramento/index', compact('totais'));
    }

    public function seleciona($rede)
    {
        $client_id = Session::get('cliente')['id'];


        return view('monitoramento/medias');
    }
}
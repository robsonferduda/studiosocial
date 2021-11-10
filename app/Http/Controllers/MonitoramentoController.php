<?php

namespace App\Http\Controllers;

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
        return view('monitoramento/index');
    }

    public function seleciona()
    {
        return view('monitoramento/medias');
    }
}
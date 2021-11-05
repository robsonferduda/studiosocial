<?php

namespace App\Http\Controllers;

use App\Configs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ConfiguracoesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','configuracoes');
    }

    public function index()
    {
        $configs = Configs::all();
        return view('configuracoes/index', compact('configs'));
    }
}
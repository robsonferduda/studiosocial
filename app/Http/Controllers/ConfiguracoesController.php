<?php

namespace App\Http\Controllers;

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
        return view('configuracoes/index');
    }
}
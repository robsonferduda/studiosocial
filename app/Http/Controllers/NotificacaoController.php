<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NotificacaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','notificacoes');
    }

    public function index()
    {
        return view('index');
    }
}
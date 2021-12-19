<?php

namespace App\Http\Controllers;

use App\Client;
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
        $client = Client::find(Session::get('cliente')['id']);
        return view('notificacoes/index', compact('client'));
    }
}
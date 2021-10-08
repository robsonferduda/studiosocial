<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','clientes');
    }

    public function index()
    {
        $clientes = Client::orderBy('name')->get();
        return view('clientes/index', compact('clientes'));
    }

    public function show(Client $cliente)
    {
        return view('clientes/detalhes', compact('cliente'));
    }

    public function create(Request $request)
    {
        return view('clientes/novo');
    }
}
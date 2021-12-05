<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Term;
use App\Client;
use App\Configs;
use App\Hashtag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','home');
    }

    public function index()
    {
        $id_cliente_padrao = Configs::where('key', 'cliente_padrao')->first()->value;

        if(!Session::get('cliente')){
            $cliente = Client::find($id_cliente_padrao);
            $cliente_session = array('id' => $cliente->id, 'nome' => $cliente->name);
            Session::put('cliente', $cliente_session);
        }

        $users = User::whereNull('client_id')->count();
        $clientes = Client::count();
        $hashtags = Hashtag::where('is_active',true)->get();
        $terms = Term::where('is_active',true)->get();
        
        return view('index', compact('users','clientes','terms','hashtags'));
    }
}
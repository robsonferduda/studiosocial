<?php

namespace App\Http\Controllers;

use Auth;
use App\Boletim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BoletimController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','auditoria');
    }

    public function index()
    {
        $boletins = Boletim::where('id_cliente',443)->orderBy('data','DESC')->get();
        return view('boletim/index',compact('boletins'));
    }

    public function detalhes($id)
    {
        return view('boletim/detalhes');
    }

    public function enviar($id)
    {
        //Implementação do envio de email
    }
}
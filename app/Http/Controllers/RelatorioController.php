<?php

namespace App\Http\Controllers;

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
        return view('relatorios/index');
    }
}
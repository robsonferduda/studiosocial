<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PermissaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','permissoes');
    }

    public function index()
    {
        return view('permissoes/index');
    }
}
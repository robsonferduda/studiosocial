<?php

namespace App\Http\Controllers;

use App\Configs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','regra');
    }

    public function create()
    {
       
        return view('regras/create');
    }
}
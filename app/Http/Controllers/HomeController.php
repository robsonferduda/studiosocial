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
        $users = User::whereNull('client_id')->count();
        $clientes = Client::count();
        $hashtags = Hashtag::where('is_active',true)->get();
        $terms = Term::where('is_active',true)->get();
        
        return view('index', compact('users','clientes','terms','hashtags'));
    }
}
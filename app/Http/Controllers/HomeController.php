<?php

namespace App\Http\Controllers;

use App\User;
use App\Client;
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
        return view('index', compact('users','clientes'));
    }
}
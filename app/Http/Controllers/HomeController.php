<?php

namespace App\Http\Controllers;

use App\User;
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
        $user = new User();
        return view('index', compact('user'));
    }
}
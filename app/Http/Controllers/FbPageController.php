<?php

namespace App\Http\Controllers;

use App\Client;
use App\FbAccount;
use Illuminate\Http\Request;

class FbPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($client)
    {
        $client = Client::with('user')->find($client);

        return view('pages/index', compact('client'));
    }
}
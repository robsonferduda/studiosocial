<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\User;
use App\Term;
use App\Client;
use App\Configs;
use App\Hashtag;
use App\Media;
use App\FbPost;
use App\MediaTwitter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SocialSearchController extends Controller
{
    private $client_id;

    public function __construct()
    {
        $this->middleware('auth');        
        Session::put('url','social-search');
    }

    public function index()
    {
        return view('social-search/index');
    }

    public function buscar()
    {
        
    }
}
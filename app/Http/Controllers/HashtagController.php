<?php

namespace App\Http\Controllers;

use App\Hashtag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HashtagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
    }

    public function medias($hashtag)
    {
        $hashtag = Hashtag::find($hashtag);
        return view('hashtags/medias', compact('hashtag'));
    }
}
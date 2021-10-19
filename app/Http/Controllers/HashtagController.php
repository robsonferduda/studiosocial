<?php

namespace App\Http\Controllers;

use App\Hashtag;

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
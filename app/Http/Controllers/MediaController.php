<?php

namespace App\Http\Controllers;

use App\MediaTwitter;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
    }

    public function atualizaSentimento($id, $tipo, $sentimento)
    {
        $media = MediaTwitter::where('twitter_id',$id)->first();
        $media->sentiment = $sentimento;
        $media->update();

        Flash::success('<i class="fa fa-check"></i> Sentimento da mídia atualizado com sucesso');
        return redirect()->back()->withInput();
    }
}
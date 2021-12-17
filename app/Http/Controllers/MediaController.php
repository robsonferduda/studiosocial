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
        switch ($tipo) {
            case 'tweets':
                $media = MediaTwitter::where('twitter_id',$id)->first();
                $media->sentiment = $sentimento;
                break;
            
            case 'value':
                
                break;
        }
        
        if($media->update())
            Flash::success('<i class="fa fa-check"></i> Sentimento da mídia atualizado com sucesso');
        else
            Flash::error('<i class="fa fa-check"></i> Erro ao atualizar o sentimento da mídia');
        return redirect()->back()->withInput();
    }
}
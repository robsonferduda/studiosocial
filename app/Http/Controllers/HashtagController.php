<?php

namespace App\Http\Controllers;

use App\Hashtag;
use Laracasts\Flash\Flash;
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

    public function atualizarSituacao($hashtag)
    {
        $hashtag = Hashtag::find($hashtag);
        $hashtag->is_active = !$hashtag->is_active;
        $hashtag->save();
        
        return redirect('client/hashtags/'.$hashtag->client->id);
    }

    public function medias($hashtag)
    {
        $hashtag = Hashtag::find($hashtag);
        return view('hashtags/medias', compact('hashtag'));
    }

    public function create(Request $request)
    {
        if($request->social_media and $request->hashtag)
        {
            for ($i=0; $i < count($request->social_media); $i++) { 
                
                $dados = array('hashtag' => $request->hashtag,
                               'client_id' => $request->client_id,
                               'social_media_id' => $request->social_media[$i] );

                Hashtag::create($dados);
            }
            Flash::success('<i class="fa fa-check"></i> Cadastro de hashtag realizado com sucesso');

        }else{
            Flash::info('<i class="fa fa-info"></i> Informe uma hashtag e uma Mídia Social para realizar o cadastro');
        }
        return redirect('client/hashtags/'.$request->client_id);
    }
}
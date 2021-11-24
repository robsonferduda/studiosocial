<?php

namespace App\Http\Controllers;

use App\Client;
use App\Term;
use App\SocialMedia;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TermController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
    }

    public function getTerms($client_id)
    {
        $client = Client::with(['terms' => function($query){
            $query->withCount('mediasTwitter')->withCount('medias');
        }])->find($client_id);

        $social_medias = SocialMedia::where('fl_term',true)->orderBy('name')->get();
        return view('clientes/terms', compact('client','social_medias'));
    }

    public function atualizarSituacao($term_id)
    {
        $term = Term::find($term_id);
        $term->is_active = !$term->is_active;
        $term->save();
        
        return redirect('terms/client/'.$term->client->id);
    }

    public function store(Request $request)
    {
        if($request->social_media and $request->term)
        {
            for ($i=0; $i < count($request->social_media); $i++) { 
                
                $dados = array('term' => $request->term,
                               'client_id' => $request->client_id,
                               'social_media_id' => $request->social_media[$i] );

                Term::create($dados);
            }
            Flash::success('<i class="fa fa-check"></i> Cadastro de termo realizado com sucesso');

        }else{
            Flash::info('<i class="fa fa-info"></i> Informe um termo e uma Mídia Social para realizar o cadastro');
        }
        return redirect('terms/client/'.$request->client_id);
    }

    public function destroy($id)
    {
        $term = Term::with('client')->find($id);
        
        if($term->delete())
            Flash::success('<i class="fa fa-check"></i> Termo <strong>'.$term->term.'</strong> excluído com sucesso');
        else
            Flash::error("Erro ao excluir o termo");

        return redirect('terms/client/'.$term->client->id);
    }
}
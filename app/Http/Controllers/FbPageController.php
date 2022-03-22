<?php

namespace App\Http\Controllers;

use App\Classes\FBFeed;
use App\FbPageMonitor;
use App\FbPagePost;
use App\Client;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use LDAP\Result;

class FbPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $pages = FbPageMonitor::all();

        $clients = Client::select(['id', 'name'])->get();

        return view('pages/index', compact('pages', 'clients'));
    }

    public function show($id) {
        $page = FbPageMonitor::where('id', $id)->first();

        return $page;
    }

    public function store(Request $request)
    {
        try {
            $page = FbPageMonitor::create([
                'name' => $request->name, 
                'url' => 'https://paginaexemplo1.com.br' //$request->url
            ]);

            if($page) {
                $retorno = array('flag' => true,
                'msg' => "Dados inseridos com sucesso");
            }

        } catch (\Illuminate\Database\QueryException $e) {

            $retorno = array('flag' => false,
                             'msg' => Utils::getDatabaseMessageByCode($e->getCode()));

        } catch (Exception $e) {
            
            $retorno = array('flag' => true,
                             'msg' => "Ocorreu um erro ao inserir o registro");
        }

        //(new FBFeed())->pullMedias();

        if ($retorno['flag']) {
            Flash::success($retorno['msg']);
            return redirect('facebook-paginas');
        } else {
            Flash::error($retorno['msg']);
            return redirect('facebook-paginas');
        }
    }

    public function update(Request $request)
    {   
        try {
            $page = FbPageMonitor::where('id', $request->id)->first();

            $page->update([
                'name' => $request->name,
                'url' => 'https://paginaexemplo1.com.br' //$request->url
            ]);

            if($page) {
                $retorno = array('flag' => true,
                'msg' => "Dados atualizados com sucesso");
            }

        } catch (\Illuminate\Database\QueryException $e) {

            $retorno = array('flag' => false,
                             'msg' => Utils::getDatabaseMessageByCode($e->getCode()));

        } catch (Exception $e) {
            
            $retorno = array('flag' => true,
                             'msg' => "Ocorreu um erro ao atualizar o registro");
        }

       //(new FBFeed())->pullMedias();

       if ($retorno['flag']) {
            Flash::success($retorno['msg']);
            return redirect('facebook-paginas');
        } else {
            Flash::error($retorno['msg']);
            return redirect('facebook-paginas');
        }
    }

    public function destroy($id)
    {
        $page = FbPageMonitor::where('id', $id)->first();

        if($page->delete())
            Flash::success('<i class="fa fa-check"></i> Página <strong>'.$page->name.'</strong> excluída com sucesso');
        else
            Flash::error("Erro ao excluir o registro");

        return redirect('facebook-paginas');
    }

    public function medias()
    {

        $medias_temp = FbPagePost::orderBy('updated_time','DESC')->paginate(20);
        foreach ($medias_temp as $key => $media) {
            
            // $bag_comments = [];
            // if ($media->comments) {
            //     foreach($media->comments as $comment) {
            //         $bag_comments[] = ['text' => $comment->text, 'created_at' => $comment->timestamp];
            //     }
            // }

            // $medias[] = array('id' => $media->id,
            //     'text' => $media->message,
            //     'username' => '',
            //     'created_at' => dateTimeUtcToLocal($media->updated_time),
            //     'sentiment' => '',
            //     'type_message' => 'facebook',
            //     'like_count' => '',
            //     'comments_count' => !empty($media->comment_count) ? $media->comment_count : 0,
            //     'social_media_id' => $media->social_media_id,
            //     'tipo' => 'facebook',
            //     'comments' => [],
            //     'link' => $media->permalink_url,
            //     'share_count' => '',
            //     'user_profile_image_url' => ''
            //  );

            $medias[] = array('id' => $media->id,
                'text' => "No entanto, não podemos esquecer que a hegemonia do ambiente político exige a precisão e a definição das formas de ação.",
                'username' => '',
                'created_at' => dateTimeUtcToLocal($media->updated_time),
                'sentiment' => '',
                'type_message' => 'facebook',
                'like_count' => '',
                'comments_count' => !empty($media->comment_count) ? $media->comment_count : 0,
                'social_media_id' => $media->social_media_id,
                'tipo' => 'facebook',
                'comments' => [],
                'link' => 'https://www.facebook.com/paginaexemplo',
                'share_count' => '',
                'user_profile_image_url' => ''
             );


        }

        return view('pages/medias', compact('medias', 'medias_temp'));

    }

    public function associarCliente(Request $request)
    {
        $clients = $request->client;

        $page = FbPageMonitor::where('id', $request->id)->first();

        $result = $page->clients()->sync($clients);

        Flash::success('<i class="fa fa-check"></i> Clientes da página <strong>'.$page->name.'</strong> atualizados com sucesso');

        return redirect('facebook-paginas');

    }
}
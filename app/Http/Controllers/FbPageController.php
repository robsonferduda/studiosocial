<?php

namespace App\Http\Controllers;

use App\Classes\FBFeed;
use App\Classes\FBFeedApi;
use App\Classes\FBSearchPageApi;
use App\FbPageMonitor;
use App\FbPagePost;
use App\Client;
use App\Enums\FbReaction;
use App\Utils;
use Exception;
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

    public function cadastrar()
    {
        return view('pages/cadastrar');
    }

    public function buscarPagina(Request $request)
    {

        $pages_monitor = FbPageMonitor::pluck('page_id')->toArray();

        $token_app = env('COLETA1');//getTokenApp();

        $fb_api = new FBSearchPageApi();

        $fields = $fb_api->getPageInfoFields();
                            
        $params = [      
            'q' => strtolower($request->termo),   
            'access_token' => $token_app,
            'after' => $request->after,
            'before' => $request->before,
            'limit' => 10
        ];

        $pages = $fb_api->getPages($params);

        $dados = [];
        $dados['limit_exceeded'] =  false;

        if(isset($pages['headers']['x-app-usage'][0])) {
            $x_app_usage = json_decode($pages['headers']['x-app-usage'][0]);

            if((int) $x_app_usage->call_count > 50) {
                $dados['limit_exceeded'] = true;
                echo json_encode($dados);  
                exit;
            }
        }

        $pages = $pages['body'];

        foreach ($pages['data'] as $page) {

            $page_id = $page['id'];
                                
            $params = [      
                'fields' => $fields,   
                'access_token' => $token_app                
            ];

            $infos = $fb_api->getPageInfo($page_id, $params);

            $dados['data'][] =  array(
                            'id' => $infos['id'],
                            'name' => $infos['name'],
                            'link' => $infos['link'],
                            'description' => isset($infos['description']) ? $infos['description'] : '',
                            'category' => $infos['category'],
                            'picture' => $infos['picture']['data']['url'],
                            'registered' =>  ( in_array($infos['id'], $pages_monitor) ? true : false )
                        );
        }

        if($fb_api->hasAfter($pages) || $fb_api->hasBefore($pages) ) {
            $dados['info']['after'] = $fb_api->getAfter($pages);
            $dados['info']['before'] = $fb_api->getBefore($pages);
            $dados['info']['query'] = $request->termo;
        }
        
        echo json_encode($dados);   
    
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
                'url' => $request->link,
                'page_id' => $request->id,
                'picture_url' => $request->picture
            ]);

            if($page) {
                $retorno = array('flag' => true,
                'msg' => "Dados inseridos com sucesso");
            }

        } catch (\Illuminate\Database\QueryException $e) {

            $retorno = array('flag' => false,
                             'msg' => Utils::getDatabaseMessageByCode($e->getCode()));

        } catch (Exception $e) {
            
            $retorno = array('flag' => false,
                             'msg' => "Ocorreu um erro ao inserir o registro");
        }

        if ($retorno['flag']) {
            echo json_encode($retorno);
        } else {
            echo json_encode($retorno);
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

        $medias_temp = FbPagePost::with('page')->whereHas('page')->orderBy('updated_time','DESC')->paginate(20);
        foreach ($medias_temp as $key => $media) {
            
            // $bag_comments = [];
            // if ($media->comments) {
            //     foreach($media->comments as $comment) {
            //         $bag_comments[] = ['text' => $comment->text, 'created_at' => $comment->timestamp];
            //     }p
            // }

            $likes_count = 0;
            $loves = $media->reactions()->wherePivot('reaction_id',FbReaction::LOVE)->first();                
            $likes = $media->reactions()->wherePivot('reaction_id',FbReaction::LIKE)->first();
            if(!empty($loves)) {
                $likes_count += $loves->pivot->count;
            }

            if(!empty($likes)) {
                $likes_count += $likes->pivot->count;
            }

            $medias[] = array('id' => $media->id,
                'text' => $media->message,
                'username' => $media->page->name,
                'created_at' => dateTimeUtcToLocal($media->updated_time),
                'sentiment' => '',
                'type_message' => 'facebook',
                'like_count' => $likes_count,
                'comments_count' => !empty($media->comment_count) ? $media->comment_count : 0,
                'social_media_id' => $media->social_media_id,
                'tipo' => 'facebook',
                'comments' => [],
                'link' => $media->permalink_url,
                'share_count' => !empty($media->share_count) ? $media->share_count : 0,
                'user_profile_image_url' => $media->page->picture_url
             );

        }

        return view('pages/medias', compact('medias', 'medias_temp'));

    }

    public function pullMedias()
    {
        (new FBFeed())->pullMedias();
    }

    public function associarCliente(Request $request)
    {
        $clients = $request->client;

        $page = FbPageMonitor::where('id', $request->id)->first();

        $result = $page->clients()->sync($clients);

        Flash::success('<i class="fa fa-check"></i> Clientes da página <strong>'.$page->name.'</strong> atualizados com sucesso');

        return redirect('facebook-paginas');

    }

    public function verifyPicture(){

        $pages_monitor = FbPageMonitor::get();

        foreach ($pages_monitor as $page) {
           
            $token_app = getTokenApp();

            $fb_api = new FBSearchPageApi();
                                
            $params = [      
                'access_token' => $token_app,
                'redirect' => 0,
                'fields' => 'url'
            ];

            $picture = $fb_api->getPagePicture($page->page_id, $params);

            $page->picture_url = $picture['data']['url'];
            
            $page->save();
        }

    }

    public function updateReactions() {

        $posts = FbPagePost::get();
        $fb_feed = new FBFeedApi(999);
        $token_app = env('COLETA1');//getTokenApp();

        foreach ($posts as $post) {
            $reactions = $this->getReactions($post['post_id'], $fb_feed, $token_app);

            $post->comment_count = $reactions['qtd_comments'];
            $post->share_count  = $reactions['qtd_shares'];
            $post->save();

            $reaction_buffer = [];
            foreach ($reactions['types'] as $type => $qtd) {
                if($qtd > 0) {

                    $reaction = constant('App\Enums\FbReaction::'. $type);
                    $reaction_buffer[$reaction] = ['count' => $qtd];
                               
                }                                    
            }

            if (!empty($reaction_buffer)) {                
                $post->reactions()->sync($reaction_buffer);
            }
        }
    }


    public function getReactions($post_id, $fb_feed, $access_token) {

        $params = [
            'fields' => $fb_feed->getFBReactionsFields($post_id),
            'access_token' => $access_token
        ];

        $post_reactions = $fb_feed->getFBPostReactions($post_id,$params);

        // if(empty($post_reactions['id'])){
        //     return [];
        // }

        $reactions = [
            'id' =>  isset($post_reactions['id']) ? $post_reactions['id'] : null,
            'qtd_shares' => isset($post_reactions['shares']['count']) ? $post_reactions['shares']['count'] : null,
            'qtd_comments' => isset($post_reactions['comments']['summary']['total_count']) ? $post_reactions['comments']['summary']['total_count'] : null,
            'types' => [
                'LIKE' => isset($post_reactions['LIKE']['summary']['total_count']) ? $post_reactions['LIKE']['summary']['total_count'] : null,
                'LOVE' => isset($post_reactions['LOVE']['summary']['total_count']) ? $post_reactions['LOVE']['summary']['total_count'] : null,
                'WOW' => isset($post_reactions['WOW']['summary']['total_count']) ? $post_reactions['WOW']['summary']['total_count'] : null,
                'HAHA' => isset($post_reactions['HAHA']['summary']['total_count']) ? $post_reactions['HAHA']['summary']['total_count'] : null,
                'SAD' => isset($post_reactions['SAD']['summary']['total_count']) ? $post_reactions['SAD']['summary']['total_count'] : null,
                'ANGRY' => isset($post_reactions['ANGRY']['summary']['total_count']) ? $post_reactions['ANGRY']['summary']['total_count'] : null,
                'THANKFUL' => isset($post_reactions['THANKFUL']['summary']['total_count']) ? $post_reactions['THANKFUL']['summary']['total_count'] : null
            ]
        ];

        return $reactions;
        
    }
}
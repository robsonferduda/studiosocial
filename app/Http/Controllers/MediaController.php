<?php

namespace App\Http\Controllers;

use DB;
use DOMPDF;
use Storage;
use Notification;
use Carbon\Carbon;
use App\FbPost;
use App\Media;
use App\MediaTwitter;
use App\FbPagePost;
use App\FbPagePostComment;
use App\MediaFilteredVw;
use App\MediaRuleFilteredVw;
use App\Configs;
use App\Enums\TypeMessage;
use App\Jobs\Medias as JobsMedia;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MediaController extends Controller
{
    private $client_id;
    private $periodo_padrao;
    private $flag_regras;
    private $mediaModel;
    private $periodo;
    private $data_inicial;
    private $data_final;

    const FB_POSTS = 1;
    const FB_COMMENT = 2;
    const TWEETS = 3;
    const IG_POSTS = 4;
    const IG_COMMENT = 5;
    const FB_PAGE_POST = 7;
    const FB_PAGE_POST_COMMENT = 8;

    public function __construct()
    {
        $this->middleware('auth');
        $this->client_id = session('cliente')['id'];
        $this->periodo_padrao = Configs::where('key', 'periodo_padrao')->first()->value;
        Session::put('url','monitoramento');
        $this->flag_regras = Session::get('flag_regras');

        if($this->flag_regras) {
            $this->mediaModel = new MediaRuleFilteredVw();
        } else {
            $this->mediaModel = new MediaFilteredVw();
        }
    }

    public function index()
    {

    }

    public function atualizaSentimento($id, $tipo, $sentimento)
    {
        $media = null;

        switch ($tipo) {
            case 'twitter':
                $media = MediaTwitter::where('id',$id)->first();
                break;

            case 'facebook':
                $media = FbPost::where('id',$id)->first();
                break;

            case 'facebook-page':
                $media = FbPagePost::where('id',$id)->first();
                break;

            case 'instagram':
                $media = Media::where('id',$id)->first();
                break;

            case 'facebook-page-comment':
                $media = FbPagePostComment::where('id',$id)->first();
                break;
        }

        if($media){
            $media->sentiment = $sentimento;

            if($media->update()){
                Flash::success('<i class="fa fa-check"></i> Sentimento da mídia atualizado com sucesso');
            }else{
                Flash::error('<i class="fa fa-check"></i> Erro ao atualizar o sentimento da mídia');
            }

        }else{
            Flash::warning('<i class="fa fa-exclamation"></i> Mídia não encontrada');
        }

        return redirect()->back()->withInput();
    }

    public function excluir($id, $tipo)
    {
        $media = null;

        switch ($tipo) {
            case 'twitter':
                $media = MediaTwitter::where('id',$id)->first();
                break;

            case 'facebook':
                $media = FbPost::where('id',$id)->first();
                break;
            case 'facebook-page':
                $media = FbPagePost::where('id',$id)->first();
                break;
            case 'facebook-page-comment':
                $media = FbPagePostComment::where('id',$id)->first();
                break;
            case 'instagram':
                $media = Media::where('id',$id)->first();
                break;
        }

        if($media){

            if($media->delete()){
                Flash::success('<i class="fa fa-check"></i> Mídia excluída com sucesso');
            }else{
                Flash::error('<i class="fa fa-check"></i> Erro ao excluir mídia');
            }

        }else{
            Flash::warning('<i class="fa fa-exclamation"></i> Mídia não encontrada');
        }

        return redirect()->back()->withInput();

    }

    public function relatorio(Request $request)
    {
        $nome = "Relatório de Redes Sociais";
        $dt_inicial = $request->data_inicial;
        $dt_final = $request->data_final;
        $rede = 'todos';
        $this->geraDataPeriodo($request->periodo, $request->data_inicial, $request->data_final);

        $client_id = Session::get('cliente')['id'];
        $medias = array();
        $dados = array();

        switch ($rede) {

            case 'instagram':
                $medias = $this->getMediasInstagram();
                break;

            case 'facebook':
                $medias = $this->getMediasFacebook();
                break;

            case 'twitter':
                $medias = $this->getMediasTwitter();

            break;

            case 'todos':
                $medias_i = $this->getMediasInstagram();
                $medias_t = $this->getMediasTwitter();
                $medias_f = $this->getMediasFacebook();

                $medias = array_merge($medias_i, $medias_t, $medias_f);
        }

        foreach ($medias as $key => $media) {

            switch ($media['sentiment']) {
                case -1:
                    $sentimento = '<i class="fa fa-frown-o text-danger"></i>
                                   <i class="fa fa-ban op-2"></i>
                                   <i class="fa fa-smile-o op-2"></i>';
                break;
                case 0:
                    $sentimento = '<i class="fa fa-frown-o op-2"></i>
                                    <i class="fa fa-ban text-primary"></i>
                                    <i class="fa fa-smile-o op-2"></i>';
                    break;

                case 1:
                    $sentimento = ' <i class="fa fa-frown-o op-2"></i>
                                    <i class="fa fa-ban op-2"></i>
                                    <i class="fa fa-smile-o text-success"></i>';
                    break;
                default:
                    # code...
                    break;
            }

            switch ($media['tipo']) {
                case 'instagram':
                    $tipo = '<span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"  viewBox="0 0 16 16">
                                  <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                                </svg>
                             </span>';
                    break;

                case 'facebook':
                    $tipo = '<span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                  <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                                </svg>
                             </span>';
                    break;

                case 'twitter':
                    $tipo = '<span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                  <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"/>
                                </svg>
                             </span>';
                    break;
                default:
                    # code...
                    break;
            }

            $dados[] = array('text' => $media['text'],
                             'sentimento' => $sentimento,
                             'tipo' => $tipo,
                             'username' => $media['username'],
                             'like_count' => $media['like_count'],
                             'link' => $media['link'],
                             'created_at' => $media['created_at']);

        }

        JobsMedia::dispatchNow($client_id, $nome, $dt_inicial, $dt_final, $dados);

        Flash::success('<i class="fa fa-exclamation"></i> O pedido de relatório foi encaminhado para processamento. Aguarde um email confirmando a geração do mesmo');
        return redirect()->back()->withInput();

    }

    public function geraDataPeriodo($periodo, $data_inicial, $data_final)
    {
        $carbon = new Carbon();

        if($periodo == 0){

          $data_inicial = $carbon->createFromFormat('d/m/Y', $data_inicial);
          $data_final = $carbon->createFromFormat('d/m/Y', $data_final);

          $periodo = $data_final->diffInDays($data_inicial) + 1;
          $data_inicial = $data_inicial->subDays(1);

        }else{
          $data_inicial = Carbon::now()->subDays($periodo);
          $data_final = $carbon->createFromFormat('d/m/Y', $data_final);
        }

        $this->periodo = $periodo;
        $this->data_inicial = $data_inicial;
        $this->data_final = $data_final;
    }

    function getMediasInstagram()
    {
        $medias = array();
        $medias = array();
        $client_id = Session::get('cliente')['id'];
        $medias_temp =  $this->mediaModel::where('tipo', 'IG_POSTS')->whereBetween('date', [$this->data_inicial, $this->data_final]);

        $medias_temp = $medias_temp->where('client_id', $this->client_id)
        ->select('id', 'text', 'date', 'sentiment', 'name', 'link', 'img_link', 'comment_count', 'share_count', 'like_count', 'client_id')
        ->groupBy('id', 'text', 'date', 'sentiment', 'name', 'link', 'img_link', 'comment_count', 'share_count', 'like_count', 'client_id')
        ->orderBy('date', 'DESC')->get();

        foreach($medias_temp as $media) {

            $medias[] = array(  'id' => $media->id,
                                'text' => $media->text,
                                'username' => $media->name,
                                'created_at' => ($media->date) ? dateTimeUtcToLocal($media->date) : null,
                                'sentiment' => $media->sentiment,
                                'type_message' => 'instagram',
                                'like_count' => $media->like_count,
                                'share_count' => $media->share_count,
                                'comments_count' => $media->comments_count,
                                'tipo' => 'instagram',
                                'comments' => [],
                                'link' => $media->link,
                                'user_profile_image_url' => $media->img_link
                            );
        }

        return $medias;
    }

    function getMediasFacebook()
    {
        $medias = array();
        $medias_temp = $this->mediaModel::where(function($query) {
            $query->Orwhere('tipo', 'FB_COMMENT')
            ->Orwhere('tipo', 'FB_PAGE_POST')
            ->Orwhere('tipo', 'FB_PAGE_POST_COMMENT')
            ->Orwhere('tipo', 'FB_POSTS');
        })->whereBetween('date', [$this->data_inicial, $this->data_final]);

        $medias_temp = $medias_temp->where('client_id', $this->client_id)
        ->select('id', 'text', 'date', 'sentiment', 'name', 'link', 'img_link', 'comment_count', 'share_count', 'like_count', 'client_id', 'tipo')
        ->groupBy('id', 'text', 'date', 'sentiment', 'name', 'link', 'img_link', 'comment_count', 'share_count', 'like_count', 'client_id', 'tipo')
        ->orderBy('date', 'DESC')->get();

        foreach($medias_temp as $media) {

            switch ($media->tipo) {
                case 'FB_COMMENT':
                        $tipo = '';
                    break;
                case 'FB_PAGE_POST':
                        $tipo = 'facebook-page';
                    break;
                case 'FB_PAGE_POST_COMMENT':
                        $tipo = 'facebook-page-comment';
                    break;
                case 'FB_POSTS':
                        $tipo = 'facebook';
                    break;

            }

            $medias[] = array(  'id' => $media->id,
                                'text' => $media->text,
                                'username' => $media->name,
                                'created_at' => ($media->date) ? dateTimeUtcToLocal($media->date) : null,
                                'sentiment' => $media->sentiment,
                                'type_message' => $tipo,
                                'like_count' => $media->like_count,
                                'share_count' => $media->share_count,
                                'comments_count' => $media->comments_count,
                                'tipo' => 'facebook',
                                'comments' => [],
                                'link' => $media->link,
                                'user_profile_image_url' => $media->img_link
                            );
        }

        return $medias;
    }

    function getMediasTwitter()
    {
        $medias = array();
        $medias_temp =  $this->mediaModel::where('tipo', 'TWEETS')->whereBetween('date', [$this->data_inicial, $this->data_final]);

        $medias_temp = $medias_temp->where('client_id', $this->client_id)
        ->select('id', 'text', 'date', 'sentiment', 'name', 'link', 'img_link', 'comment_count', 'share_count', 'like_count', 'client_id', 'retweet_count')
        ->groupBy('id', 'text', 'date', 'sentiment', 'name', 'link', 'img_link', 'comment_count', 'share_count', 'like_count', 'client_id', 'retweet_count')
        ->orderBy('date', 'DESC')->get();

        foreach($medias_temp as $media) {

            $medias[] = array(  'id' => $media->id,
                                'text' => $media->text,
                                'username' => $media->name,
                                'created_at' => ($media->date) ? dateTimeUtcToLocal($media->date) : null,
                                'sentiment' => $media->sentiment,
                                'type_message' => 'twitter',
                                'like_count' => $media->like_count,
                                'share_count' => $media->share_count,
                                'comments_count' => $media->comments_count,
                                'tipo' => 'twitter',
                                'retweet_count' => $media->retweet_count,
                                'comments' => [],
                                'link' => $media->link,
                                'user_profile_image_url' => $media->img_link
                            );
        }

        return $medias;
    }
}

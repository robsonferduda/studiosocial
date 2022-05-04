<?php

namespace App\Http\Controllers;

use Mail;
use App\Utils;
use App\Client;
use App\Media;
use App\MediaTwitter;
use App\Notification;
use App\NotificationClient;
use Carbon\Carbon;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\Enums\NotificationType;
use App\Notifications\NotificacaoProcessNotification;
use App\Http\Requests\NotificationRequest;
use Illuminate\Support\Facades\Session;

class NotificacaoController extends Controller
{
    private $client_id;

    public function __construct()
    {
        $this->middleware('auth');
        $this->client_id = session('cliente')['id'];
        Session::put('url','notificacoes');
    }

    public function index()
    {
        $client = Client::find(Session::get('cliente')['id']);
        $notifications = Notification::orderBy('name')->get();
        $notifications_client = NotificationClient::with('notification')->where('client_id', $this->client_id)->orderBy('created_at')->get();

        return view('notificacoes/index', compact('client','notifications','notifications_client'));
    }

    public function getDescricao($id)
    {
        $descricao = Notification::find($id)->description;
        return response()->json($descricao);
    }

    public function edit($id)
    {
        $client = Client::find(Session::get('cliente')['id']);
        $notification_client = NotificationClient::find($id);
        $notifications = Notification::orderBy('name')->get();
       
        return view('notificacoes/edit', compact('client','notifications','notification_client'));
    }

    public function store(NotificationRequest $request)
    {
        $request->merge(['dt_inicio' => date('Y-m-d', strtotime(str_replace('/', '-', $request->dt_inicio)))]);
        $request->merge(['dt_termino' => ($request->dt_termino) ? date('Y-m-d', strtotime(str_replace('/', '-', $request->dt_termino))) : null] );

        try {
            NotificationClient::create($request->all());

            $retorno = array('flag' => true,
                             'msg' => '<i class="fa fa-check"></i> Dados inseridos com sucesso');
        } catch (\Illuminate\Database\QueryException $e) {
            $retorno = array('flag' => false,
                             'msg' => Utils::getDatabaseMessageByCode($e->getCode()));
        } catch (Exception $e) {
            $retorno = array('flag' => true,
                             'msg' => '<i class="fa fa-times"></i> Ocorreu um erro ao inserir o registro');
        }

        if ($retorno['flag']) {
            Flash::success($retorno['msg']);
            return redirect('notificacoes')->withInput();
        } else {
            Flash::error($retorno['msg']);
            return redirect('notificacoes')->withInput();
        }
    }

    public function update(NotificationRequest $request, $id)
    {
        $request->merge(['dt_inicio' => date('Y-m-d', strtotime(str_replace('/', '-', $request->dt_inicio)))]);
        $request->merge(['dt_termino' => ($request->dt_termino) ? date('Y-m-d', strtotime(str_replace('/', '-', $request->dt_termino))) : null] );

        try {
            $notification_client = NotificationClient::find($id);
            $notification_client->update($request->all());

            $retorno = array('flag' => true,
                             'msg' => '<i class="fa fa-check"></i> Dados atualizados com sucesso');
        } catch (\Illuminate\Database\QueryException $e) {
            $retorno = array('flag' => false,
                             'msg' => Utils::getDatabaseMessageByCode($e->getCode()));
        } catch (Exception $e) {
            $retorno = array('flag' => true,
                             'msg' => '<i class="fa fa-times"></i> Ocorreu um erro ao atualizar o registro');
        }

        if ($retorno['flag']) {
            Flash::success($retorno['msg']);
            return redirect('notificacoes')->withInput();
        } else {
            Flash::error($retorno['msg']);
            return redirect('notificacoes')->withInput();
        }
    }

    public function destroy($id)
    {
        $notification = NotificationClient::findOrFail($id);

        if ($notification and $notification->delete()) {
            Flash::success('<i class="fa fa-check"></i> Registro excluído com sucesso');
        } else {
            Flash::error('<i class="fa fa-times"></i> Erro ao excluir registro');
        }

        return redirect('notificacoes');
    }

    public function atualizarSituacao($notification_id)
    {
        $notification = NotificationClient::findOrFail($notification_id);
        $notification->status = !$notification->status;
        $notification->save();
        
        return redirect('notificacoes');
    }

    public function verificar()
    {
        //Buscar notificações ativas, independente do cliente

        $msg = "";
        $valor_atual = 0;
        $total_post = 0;
        $flag_enviar = false;
        $postagens = array();
        $postagens_twitter = array();
        $postagens_instagram = array();
        $notificacoes_ativas = NotificationClient::where('status', true)->get();

        foreach ($notificacoes_ativas as $notification) {

            $flag_enviar = false; //Sempre inicializa o envio da mensagem como falso

            //Trata cada notificação de acordo com o tipo
            switch ($notification->notification_id) {

                //Para cada notificação, verificar a ocorrência, carregar os dados da ocorrência e encaminhar por email
                
                case NotificationType::ENGAJAMENTO:
                        
                    $titulo = "Alerta de Engajamento";
                    $msg = "";
                    $valor_atual = rand(1,10);
                    break;
                    
                case NotificationType::MENTION:
                    
                    $titulo = "Alerta de Menções";
                    $msg = "";
                    $valor_atual = rand(1,10);
                    break;
                
                case NotificationType::KEYWORDS:
                           
                    $postagens_twitter = MediaTwitter::where('client_id', $notification->client_id)
                                                    ->whereBetween('created_tweet_at', [$notification->dt_inicio,  Carbon::now()->format('Y-m-d')])
                                                    ->where('full_text', "ilike", "%{$notification->valor}%")
                                                    ->where('fl_notification',false)
                                                    ->get();

                    $total_post_twitter = count($postagens_twitter);

                    if($total_post_twitter){

                        foreach ($postagens_twitter as $key => $post) {

                            $postagens[] = array('img' => 'twitter',
                                            'msg'  => $post->full_text,
                                            'link' => 'https://twitter.com/'.$post->user_screen_name.'/status/'.$post->twitter_id);

                            $post->fl_notification = true;
                            $post->save();
                        }

                    }

                    $postagens_instagram = Media::where('client_id', $notification->client_id)
                                                ->whereBetween('timestamp', [$notification->dt_inicio,  Carbon::now()->format('Y-m-d')])
                                                ->where('caption', "ilike", "%{$notification->valor}%")
                                                ->where('fl_notification',false)
                                                ->get();

                    $total_post_instagram = count($postagens_instagram);

                    if($total_post_instagram){

                        foreach ($postagens_instagram as $key => $post) {

                            $postagens[] = array('img' => 'instagram',
                                            'msg'  => $post->caption,
                                            'link' => $post->permalink );

                            $post->fl_notification = true;
                            $post->save();
                        }
                    }

                    $total_post = $total_post_twitter + $total_post_instagram;

                    if($total_post){
                        $flag_enviar = true;
                        $titulo = "Alerta para a palavra-chave ".$notification->valor;
                        $msg = "Foram resgistradas novas postagens em relação ao monitoramento da palavra-chave '{$notification->valor}'. <br/> Total de mensagens descobertas: {$total_post}";
                    }

                    break;

                case NotificationType::HASHTAG:

                    $postagens_twitter = MediaTwitter::where('client_id', $notification->client_id)
                                                     ->whereBetween('created_tweet_at', [$notification->dt_inicio,  Carbon::now()->format('Y-m-d')])
                                                     ->where('full_text', "ilike", "%{$notification->valor}%")
                                                     ->where('fl_notification',false)
                                                     ->get();

                    $total_post_twitter = count($postagens_twitter);

                    if($total_post_twitter){

                        foreach ($postagens_twitter as $key => $post) {

                            $postagens[] = array('img' => 'twitter',
                                                 'msg'  => $post->full_text,
                                                 'link' => 'https://twitter.com/'.$post->user_screen_name.'/status/'.$post->twitter_id);

                            $post->fl_notification = true;
                            $post->save();
                        }

                    }

                    $postagens_instagram = Media::where('client_id', $notification->client_id)
                                                ->whereBetween('timestamp', [$notification->dt_inicio,  Carbon::now()->format('Y-m-d')])
                                                ->where('caption', "ilike", "%{$notification->valor}%")
                                                ->where('fl_notification',false)
                                                ->get();

                    $total_post_instagram = count($postagens_instagram);

                    if($total_post_instagram){

                        foreach ($postagens_instagram as $key => $post) {

                            $postagens[] = array('img' => 'instagram',
                                                 'msg'  => $post->caption,
                                                 'link' => $post->permalink );

                            $post->fl_notification = true;
                            $post->save();
                        }

                    }

                    $total_post = $total_post_twitter + $total_post_instagram;
                    
                    if($total_post){

                        $flag_enviar = true;

                        $titulo = "Alerta para a hashtag ".$notification->valor;
                        $msg = "Foram resgistradas novas postagens em relação ao monitoramento da hashtag '{$notification->valor}'. <br/> Total de mensagens descobertas: {$total_post}";

                    }
                    break;
            }

            $email = null;
            $data['msg'] = $msg;
            $data['postagens'] = $postagens;

            if($flag_enviar){

                $emails = Client::where('id', $notification->client_id)->first()->emails;

                if(count($emails)){

                    foreach ($emails as $key => $email) {

                        $mail_to = $email->ds_email;

                        //Enviar email
                        Mail::send('notificacoes.email', $data, function($message) use ($mail_to, $msg, $titulo) {
                            $message->to($mail_to)
                                    ->subject('Notificação de Monitoramento - '.$titulo);
                            $message->from('boletins@clipagens.com.br','Studio Social');
                        });
                    }
                }

            }
       
            $notification->valor_atual = $total_post;
            $notification->save();
        }

    }
}
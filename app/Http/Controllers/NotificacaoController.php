<?php

namespace App\Http\Controllers;

use App\Client;
use App\Notification;
use App\NotificationClient;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
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
        $notifications_client = NotificationClient::with('notification')->where('client_id', $this->client_id)->get();

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
}
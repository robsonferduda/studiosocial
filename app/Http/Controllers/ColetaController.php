<?php

namespace App\Http\Controllers;

use App\Collect;
use App\NotificationLog;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\Http\Requests\EmailRequest;
use Illuminate\Support\Facades\Session;

class ColetaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','coletas');
    }

    public function index()
    {
        $coletas = Collect::with('client')->orderBy('created_at','DESC')->paginate(10);
        $notificacoes = NotificationLog::with('client')->orderBy('created_at','DESC')->paginate(10);

        return view('coleta/index', compact('coletas','notificacoes'));
    }
}
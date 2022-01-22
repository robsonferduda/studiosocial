<?php

namespace App\Http\Controllers;

use Auth;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuditoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','auditoria');
    }

    public function index()
    {
        $audits = Audit::with('user')->whereNotNull('user_id')->orderBy('created_at','ASC')->get();
        return view('auditoria/index',compact('audits'));
    }

    public function show($id)
    {
        $auditoria = Audit::with('user')->where('id',$id)->first();
        return view('auditoria/detalhes',compact('auditoria'));
    }

}
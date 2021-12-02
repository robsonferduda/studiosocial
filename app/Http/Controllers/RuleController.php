<?php

namespace App\Http\Controllers;

use App\Http\Requests\RuleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','regra');
    }

    public function create()
    {
       
        return view('regras/create');
    }

    public function store(RuleRequest $request)
    {   
        $todas = $request->todas ? explode(',', $request->todas) : [];
        $algumas = $request->algumas ? explode(',',  $request->algumas) : [];
        $nenhuma = $request->nenhuma ? explode(',', $request->nenhuma) : [];



    }
}
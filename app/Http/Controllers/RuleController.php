<?php

namespace App\Http\Controllers;

use App\Enums\TypeRule;
use App\ExpressionRule;
use App\Http\Requests\RuleRequest;
use App\Rule;
use Illuminate\Support\Facades\Session;

class RuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','regra');

        $this->client_id = Session::get('cliente')['id'];
    }

    public function create()
    {
       
        return view('regras/create');
    }

    public function edit($id)
    {
        $rule = Rule::where('client_id', $this->client_id)->where('id', $id)->first();
    
        return view('regras/edit', compact('rule'));
    }

    public function index()
    {
        $rules = Rule::where('client_id', $this->client_id)->get();

        return view('regras/index', compact('rules'));
    }


    public function store(RuleRequest $request)
    {   
        $nome    = $request->nome;
        $todas   = $request->todas ? explode(',', $request->todas) : [];
        $algumas = $request->algumas ? explode(',',  $request->algumas) : [];
        $nenhuma = $request->nenhuma ? explode(',', $request->nenhuma) : [];

        $rule = Rule::create([
            'name' => $nome,
            'client_id' => $this->client_id
        ]);

       

        if(count($todas) > 0) {
            foreach($todas as $expression) {
                ExpressionRule::create([
                    'rule_id' => $rule->id,
                    'type_rule_id' => TypeRule::TODAS,
                    'expression' => $expression
                ]);
            }
        }

        if(count($algumas) > 0) {
            foreach($algumas as $expression) {
                ExpressionRule::create([
                    'rule_id' => $rule->id,
                    'type_rule_id' => TypeRule::ALGUMAS,
                    'expression' => $expression
                ]);
            }
        }

        if(count($nenhuma) > 0) {
            foreach($nenhuma as $expression) {
                ExpressionRule::create([
                    'rule_id' => $rule->id,
                    'type_rule_id' => TypeRule::NENHUMA,
                    'expression' => $expression
                ]);
            }
        }
    }
}
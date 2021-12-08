<?php

namespace App\Http\Controllers;

use App\Enums\TypeRule;
use App\ExpressionRule;
use App\Http\Requests\RuleRequest;
use App\Jobs\Rule as JobsRule;
use App\Rule;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\Session;
use Laracasts\Flash\Flash;

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
        try {
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
            $retorno = array('flag' => true,
                             'msg' => '<i class="fa fa-check"></i> Dados atualizados com sucesso');
        } catch (\Illuminate\Database\QueryException $e) {
            $retorno = array('flag' => false,
                             'msg' => Utils::getDatabaseMessageByCode($e->getCode()));
        } catch (Exception $e) {
            $retorno = array('flag' => true,
                             'msg' => "Ocorreu um erro ao atualizar o registro");
        }

        if ($retorno['flag']) {
            Flash::success($retorno['msg']);
            return redirect('regras')->withInput();
        } else {
            Flash::error($retorno['msg']);
            return redirect()->route('regras.edit', $rule->id)->withInput();
        }
    }

    public function update($id, RuleRequest $request)
    {   
        $rule = Rule::where('client_id', $this->client_id)->where('id', $id)->first();

        $nome    = $request->nome;
        $todas   = $request->todas ? explode(',', $request->todas) : [];
        $algumas = $request->algumas ? explode(',',  $request->algumas) : [];
        $nenhuma = $request->nenhuma ? explode(',', $request->nenhuma) : [];
        try {

            $rule->update([
                'name' => $nome
            ]);

            ExpressionRule::where('rule_id', $rule->id)->where('type_rule_id', TypeRule::TODAS)->delete();
            if(count($todas) > 0) {
                foreach($todas as $expression) {
                    ExpressionRule::create([
                        'rule_id' => $rule->id,
                        'type_rule_id' => TypeRule::TODAS,
                        'expression' => $expression
                    ]);
                }
            }

            ExpressionRule::where('rule_id', $rule->id)->where('type_rule_id', TypeRule::ALGUMAS)->delete();
            if(count($algumas) > 0) {
                foreach($algumas as $expression) {
                    ExpressionRule::create([
                        'rule_id' => $rule->id,
                        'type_rule_id' => TypeRule::ALGUMAS,
                        'expression' => $expression
                    ]);
                }
            }

            ExpressionRule::where('rule_id', $rule->id)->where('type_rule_id', TypeRule::NENHUMA)->delete();
            if(count($nenhuma) > 0) {
                foreach($nenhuma as $expression) {
                    ExpressionRule::create([
                        'rule_id' => $rule->id,
                        'type_rule_id' => TypeRule::NENHUMA,
                        'expression' => $expression
                    ]);
                }
            }

            $retorno = array('flag' => true,
                             'msg' => '<i class="fa fa-check"></i> Dados atualizados com sucesso');
        } catch (\Illuminate\Database\QueryException $e) {
            $retorno = array('flag' => false,
                             'msg' => Utils::getDatabaseMessageByCode($e->getCode()));
        } catch (Exception $e) {
            $retorno = array('flag' => true,
                             'msg' => "Ocorreu um erro ao atualizar o registro");
        }

        JobsRule::dispatch($this->client_id);

        if ($retorno['flag']) {
            Flash::success($retorno['msg']);
            return redirect('regras')->withInput();
        } else {
            Flash::error($retorno['msg']);
            return redirect()->route('regras.edit', $rule->id)->withInput();
        }
        
        
    }
}
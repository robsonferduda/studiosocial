<?php

namespace App\Http\Controllers;

use Auth;
use Mail;
use Carbon\Carbon;
use App\Boletim;
use App\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BoletimController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['detalhes','enviar','visualizar']]);
        Session::put('url','boletins');
    }

    public function index(Request $request)
    {
        $carbon = new Carbon();
        $clientes = Cliente::where('ativo','y')->orderBy('nome')->get();
        if($request->data){ $data = $carbon->createFromFormat('d/m/Y', $request->data)->format('Y-m-d'); } else { $data = date('Y-m-d'); }

        $query = Boletim::query();

        $query->select('app_boletins.id','data','titulo','nome','status_envio');

        $query->join('app_clientes', 'app_clientes.id' , '=', 'app_boletins.id_cliente')->orderBy('nome','ASC');

        $query->when(request('cliente'), function ($q) {
            return $q->where('id_cliente', request('cliente'));
        });

        $query->where('data', $data);
        $boletins = $query->get();

        return view('boletim/index',compact('boletins','clientes'));
    }

    public function detalhes($id)
    {   
        $boletim = Boletim::where('id', $id)->first();
        $dados = $this->getDadosBoletim($id);    
        
        if(Auth::user()){

            return view('boletim/detalhes', compact('boletim', 'dados'));

        }else{

            return redirect('boletim/'.$id.'/visualizar');

        }
    }

    public function visualizar($id)
    {   
        $boletim = Boletim::where('id', $id)->first();
        $dados = $this->getDadosBoletim($id);   
            
        return view('boletim/visualizar', compact('boletim', 'dados'));
    }

    public function outlook($id)
    {   
        $boletim = Boletim::where('id', $id)->first();
        $dados = $this->getDadosBoletim($id);    
            
        return view('boletim/outlook', compact('boletim', 'dados'));
    }

    public function enviar($id)
    {
        $lista_email = array();
        $boletim = Boletim::where('id', $id)->first();
        $emails = $boletim->cliente->listaemails;

        $lista = explode(",",$emails);

        //$lista_email[] = 'robsonferduda@gmail.com';

        for ($i=0; $i < count($lista); $i++) { 
            $lista_email[] = trim($lista[$i]);
        }

        return view('boletim/lista-envio', compact('boletim', 'lista_email'));
    }

    public function enviarLista(Request $request)
    {
        $boletim = Boletim::where('id', $request->id)->first();
        $dados = $this->getDadosBoletim($request->id);   
        $logs = array();
        
        $data = array("dados"=> $dados, "boletim" => $boletim);
        $emails = $request->emails;

        for ($i=0; $i < count($emails); $i++) { 

            try{
                $nail_status = Mail::send('boletim.outlook', $data, function($message) use ($emails, $i) {
                $message->to($emails[$i])
                ->subject('Boletim de Clipagens');
                    $message->from('boletins@clipagens.com.br','Studio Clipagem');
                });
                $msg = "Email enviado com sucesso";
                $tipo = "success";
            }
            catch (\Swift_TransportException $e) {
                $msg = "Erro ao enviar para o endereço especificado";
                $tipo = "error";
            }

            $logs[] = array('email' => $emails[$i],'tipo' => $tipo, 'msg' => $msg);
        }

        $boletim->status_envio = 'enviado';
        $boletim->save();

        return view('boletim/resumo', compact('boletim', 'logs'));
    }
 
    public function getDadosBoletim($id)
    {
        $tipo = null;

        $boletim = Boletim::where('id', $id)->first();

        $conteudo = explode(",",$boletim->conteudo);

        if(!empty($boletim->tipo)) {
           
            $tipo = $boletim->tipo;

            switch ($tipo) {
                case 'tv': $idsTV[] = $boletim->conteudo; break;
                case 'radio': $idsRadio[] = $boletim->conteudo; break;
                case 'jornal': $idsJornal[] = $boletim->conteudo; break;
                case 'web': $idsWeb[] = $boletim->conteudo; break;
            }
    
        } else {

            $conteudo = str_replace('&quote;', '"', $boletim->conteudo);
            $conteudo = json_decode($conteudo, true);

            foreach($conteudo as $tipo => $value) {
                switch ($tipo) {
                    case 'tv': $idsTV[] = implode(',', $value); break;
                    case 'radio': $idsRadio[] = implode(',', $value); break;
                    case 'jornal': $idsJornal[] = implode(',', $value); break;
                    case 'web': $idsWeb[] = implode(',', $value); break;
                }
            }
        }

        $sql = array();

        if(!empty($idsTV)){

            $idsTVIn = trim(implode(",",$idsTV));
            $idsTVIn = ($idsTVIn) ? $idsTVIn : 0;

            $sql[] = "( SELECT 
                                tv.id as id,
                                CONCAT('','') as titulo, 
                                tv.data as data,
                                tv.segundos_totais as segundos, 
                                tv.sinopse as sinopse, 
                                tv.uf as uf, 
                                CONCAT('','') as link, 
                                tv.status as status, 
                                '' as printurl,
                                cidade.titulo as cidade_titulo, 
                                veiculo.titulo as INFO1,
                                parte.titulo as INFO2, 
                                parte.hora as INFOHORA, 
                                CONCAT('tv','') as clipagem,
                                area.titulo as area,
                                area.ordem as ordem
                        FROM app_tv as tv 
                                LEFT JOIN app_tv_emissora as veiculo ON veiculo.id = tv.id_emissora
                                LEFT JOIN app_tv_programa as parte ON parte.id = tv.id_programa 
                                LEFT JOIN app_cidades as cidade ON cidade.id = tv.id_cidade 
                                LEFT JOIN app_areasmodalidade as area ON (tv.id_area = area.id)
                        WHERE 
                            tv.id IN (".$idsTVIn.") 
                    )";
        }

        if(!empty($idsRadio)){

            $idsRadioIn = trim(implode(",",$idsRadio));
            $idsRadioIn = ($idsRadioIn) ? $idsRadioIn : 0;

            $sql[] = "(SELECT 
                            radio.id as id,
                            CONCAT('','') as titulo, 
                            radio.data as data, 
                            radio.segundos_totais as segundos, 
                            radio.sinopse as sinopse, 
                            radio.uf as uf, 
                            radio.link as link, 
                            radio.status as status, 
                            '' as printurl,
                            cidade.titulo as cidade_titulo, 
                            veiculo.titulo as INFO1,
                            parte.titulo as INFO2, 
                            parte.hora as INFOHORA, 
                            CONCAT('radio','') as clipagem,
                            area.titulo as area,
                            area.ordem as ordem      
                        FROM app_radio as radio 
                            LEFT JOIN app_radio_emissora as veiculo ON veiculo.id = radio.id_emissora
                            LEFT JOIN app_radio_programa as parte ON parte.id = radio.id_programa 
                            LEFT JOIN app_cidades as cidade ON cidade.id = radio.id_cidade 
                            LEFT JOIN app_areasmodalidade as area ON (radio.id_area = area.id)
                            WHERE radio.id IN (".$idsRadioIn.") 
                )";
        }

        if(!empty($idsJornal)){

            $idsJornalIn = trim(implode(",",$idsJornal));
            $idsJornalIn = ($idsJornalIn) ? $idsJornalIn : 0;

            $sql[] = "(SELECT
                            jornal.id as id, 
                            jornal.titulo as titulo, 
                            jornal.data_clipping as data, 
                            '' as segundos,
                            jornal.sinopse as sinopse, 
                            jornal.uf as uf, 
                            CONCAT('','') as link, 
                            jornal.status as status, 
                            jornal.printurl as printurl,
                            cidade.titulo as cidade_titulo, 
                            veiculo.titulo as INFO1,
                            parte.titulo as INFO2,
                            ''  as INFOHORA,
                            CONCAT('jornal','') as clipagem,
                            area.titulo as area,
                            area.ordem as ordem  
                        FROM app_jornal as jornal 
                            LEFT JOIN app_jornal_impresso as veiculo ON veiculo.id = jornal.id_jornalimpresso
                            LEFT JOIN app_jornal_secao as parte ON parte.id = jornal.id_secao 
                            LEFT JOIN app_cidades as cidade ON cidade.id = jornal.id_cidade 
                            LEFT JOIN app_areasmodalidade as area ON (jornal.id_area = area.id)
                        WHERE jornal.id IN (".$idsJornalIn.") 
                )";
        }

        if(!empty($idsWeb)){

            $idsWebIn = trim(implode(",",$idsWeb));
            $idsWebIn = ($idsWebIn) ? $idsWebIn : 0;

            $sql[] = "(SELECT 
                            web.id as id, 
                            web.titulo as titulo, 
                            web.data_clipping as data, 
                            '' as segundos,
                            web.sinopse as sinopse, 
                            web.uf as uf, 
                            web.link as link, 
                            web.status as status, 
                            web.printurl as printurl, 
                            cidade.titulo as cidade_titulo, 
                            veiculo.titulo as INFO1,
                            parte.titulo as INFO2, 
                            ''  as INFOHORA,
                            CONCAT('web','') as clipagem,
                            area.titulo as area,
                            area.ordem as ordem      
                        FROM app_web as web 
                        LEFT JOIN app_web_sites as veiculo ON veiculo.id = web.id_site
                        LEFT JOIN app_web_secao as parte ON parte.id = web.id_secao 
                        LEFT JOIN app_cidades as cidade ON cidade.id = web.id_cidade 
                        LEFT JOIN app_areasmodalidade as area ON (web.id_area = area.id)
                        WHERE web.id IN (".$idsWebIn.") 
                )";
        }
                
        $sql = implode(" UNION DISTINCT ",$sql);				
        $sql .= " ORDER BY ordem ASC, clipagem DESC, data DESC";
        
        $dados = DB::connection('mysql')->select($sql);

        foreach($dados as $key => $noticia){

            /*

            if($noticia->clipagem == 'web' or $noticia->clipagem == 'jornal'){

                if(file_exists(env('FILE_URL').$noticia->clipagem.'/arquivo'.$noticia->id.'_1.jpeg')){
                    $url = env('FILE_URL').$noticia->clipagem.'/arquivo'.$noticia->id.'_1.jpeg';
                }else{
                    $url = env('FILE_URL').$noticia->clipagem.'/arquivo'.$noticia->id.'_1.jpeg';
                }

                $dados[$key]->url = $url;    
            } 

            */
            
            if($noticia->clipagem == 'web' or $noticia->clipagem == 'jornal'){

                $url = env('FILE_URL').$noticia->clipagem.'/arquivo'.$noticia->id.'_1.jpg';
                $header_response = @get_headers($url, 1);

                if(strpos( $header_response[0], "404" ) !== false or strpos( $header_response[0], "301" ) !== false){
                    $url = env('FILE_URL').$noticia->clipagem.'/arquivo'.$noticia->id.'_1.jpeg';
                } 

                $dados[$key]->url = $url;    
            }      
        }        

        return $dados;
    }
}
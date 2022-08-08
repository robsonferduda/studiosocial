<?php

namespace App\Http\Controllers;

use App\MediaMaterializedFilteredVw;
use App\MediaMaterializedRuleFilteredVw;
use Mail;
use Auth;
use App\User;
use App\Term;
use App\Client;
use App\Configs;
use App\Hashtag;
use App\Media;
use App\FbPost;
use App\MediaFilteredVw;
use App\MediaRuleFilteredVw;
use App\MediaTwitter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SiteController extends Controller
{
    public function __construct()
    {

    }

    public function email(Request $request)
    {
        $emails = ['robsonferduda@gmail.com','contato@studioclipagem.com.br','rafael01costa@gmail.com'];

        $data = array('nome' => $request->name,
                      'telefone' => $request->subject,
                      'email' => $request->email,
                      'msg' => $request->message);

        Mail::send('email/contato', $data, function($message) use($emails) {
            $message->to($emails)->subject('Contato Comercial');
            $message->from('boletins@clipagens.com.br','Studiosocial - Contato');
        });

        if(count(Mail::failures()) > 0) {
            return response()->json(['error' => 'invalid'], 400);
        }else{
            echo "OK";
        }
    }
}
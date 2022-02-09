<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('/politica-de-privacidade', function () { return view('politica-de-privacidade'); });
Route::get('/termos-de-servico', function () { return view('termos-de-servico'); });

Auth::routes();

Route::get('auditoria','AuditoriaController@index');
Route::get('auditoria/detalhes/{id}','AuditoriaController@show');

Route::get('boletins','BoletimController@index');
Route::get('boletim/{id}','BoletimController@detalhes');
Route::get('boletim/{id}/enviar','BoletimController@enviar');
Route::get('boletim/{id}/outlook','BoletimController@outlook');
Route::get('boletim/{id}/visualizar','BoletimController@visualizar');
Route::post('boletim/enviar/lista','BoletimController@enviarLista');

Route::get('configuracoes','ConfiguracoesController@index');
Route::post('configuracoes/cliente/selecionar','ConfiguracoesController@selecionarCliente');
Route::post('configuracoes/periodo/selecionar','ConfiguracoesController@selecionarPeriodo');

Route::get('permissoes','PermissaoController@index');
Route::get('permissoes/{id}/users','PermissaoController@users');
Route::get('permissoes/{id}/perfis','PermissaoController@perfis');
Route::get('perfis','RoleController@index');

Route::get('relatorios','RelatorioController@index');
Route::get('relatorios/midias/evolucao-diaria','RelatorioController@evolucaoDiaria');
Route::get('relatorios/midias/evolucao-redes-sociais','RelatorioController@evolucaoRedesSociais');
Route::get('relatorios/gerenciador','RelatorioController@gerenciador');
Route::get('relatorios/reactions','RelatorioController@reactions');
Route::get('relatorios/sentimentos','RelatorioController@sentimentos');
Route::get('relatorios/hashtags','RelatorioController@hashtags');
Route::get('relatorios/influenciadores','RelatorioController@influenciadores');
Route::get('relatorios/wordcloud','RelatorioController@wordcloud');
Route::get('relatorios/localizacao','RelatorioController@localizacao');

Route::post('relatorios/dados/medias/evolucao-diaria','RelatorioController@getEvolucaoDiaria');
Route::post('relatorios/dados/medias/evolucao-redes','RelatorioController@getEvolucaoRedeSocial');
Route::post('relatorios/dados/influenciadores','RelatorioController@getInfluenciadores');
Route::post('relatorios/dados/reactions','RelatorioController@getReactions');
Route::post('relatorios/dados/sentimentos/rede','RelatorioController@getSentimentosRede');
Route::post('relatorios/dados/wordcloud', 'RelatorioController@getWordCloudPeriodo');
Route::post('relatorios/dados/localizacao', 'RelatorioController@getLocalizacao');

Route::post('relatorios/pdf/evolucao-diaria','RelatorioController@evolucaoDiariaPdf');
Route::post('relatorios/pdf/wordcloud','RelatorioController@wordcloudPdf');
Route::post('relatorios/pdf/evolucao-redes-cociais','RelatorioController@evolucaoRedeSocialPdf');
Route::post('relatorios/pdf/sentimentos/rede','RelatorioController@pdf');
Route::post('relatorios/pdf/reactions','RelatorioController@reactionsPdf');
Route::post('relatorios/pdf/hashtags','RelatorioController@hashtagsPdf');
Route::post('relatorios/pdf/influenciadores','RelatorioController@influenciadoresPdf');
Route::post('relatorios/pdf/localizacao','RelatorioController@localizacaoPdf');
Route::post('relatorios/pdf/gerador','RelatorioController@geradorPdf');

Route::get('pdf','RelatorioController@pdf');

Route::get('perfil','UserController@perfil');
Route::get('usuarios','UserController@index');
Route::get('clientes','ClientController@index');
Route::get('cliente/get/json','ClientController@json');
Route::get('clientes','ClientController@index')->name('clientes.index');
Route::get('client/accounts/facebook/{cliente}','ClientController@getFacebookAccounts');
Route::get('client/hashtags/{cliente}','ClientController@getHashtags');
Route::post('cliente/selecionar','ClientController@selecionar');

Route::get('terms/client/{cliente}','TermController@getTerms');
Route::get('term/situacao/{term}','TermController@atualizarSituacao');
Route::get('term/{term_id}/medias','TermController@medias');

Route::get('hashtag/situacao/{hashtag}','HashtagController@atualizarSituacao');
Route::get('hashtag/medias/{hashtag}','HashtagController@medias');
Route::post('hashtag/create','HashtagController@create');

Route::get('role/permissions/{role}','RoleController@permissions');
Route::post('role/permission/{role}','RoleController@addPermission');

Route::resource('client', 'ClientController');
Route::resource('hashtag', 'HashtagController');
Route::resource('notification', 'NotificacaoController');
Route::resource('usuario', 'UserController');
Route::resource('regras', 'RuleController');
Route::resource('role', 'RoleController');
Route::resource('term', 'TermController');


Route::get('login/facebook/client/{client}', 'FacebookController@redirectToProvider');
Route::get('login/facebook/callback', 'FacebookController@handleProviderCallback');

Route::get('media/{media_id}/tipo/{tipo}/sentimento/{sentimento}/atualizar','MediaController@atualizaSentimento');
Route::get('media/{media_id}/tipo/{tipo}/excluir','MediaController@excluir');

Route::get('monitoramento','MonitoramentoController@index');
Route::get('monitoramento/medias/historico/{dias}','MonitoramentoController@getHistorico');
Route::get('monitoramento/media/{rede}','MonitoramentoController@seleciona');

Route::get('notificacoes','NotificacaoController@index');
Route::get('notificacoes/{id}/situacao','NotificacaoController@atualizarSituacao');
Route::get('notificacoes/{id}/descricao','NotificacaoController@getDescricao');

Route::post('check/token', 'TokenController@checkFacebookToken');

Route::get('ig-webhook', 'IGWebhookController@urlValidade');
Route::post('ig-webhook', 'IGWebhookController@receive');

Route::get('fb-webhook', 'FBWebhookController@urlValidade');
Route::post('fb-webhook', 'FBWebhookController@receive');

Route::get('/test-api', 'TestApiController@test');

Route::get('twitter', 'TwitterController@index');
Route::get('twitter/postagens/user/{user}/sentimento/{sentimento}', 'TwitterController@getTweetByUserAndSentiment');

Route::get('nuvem-palavras', 'WordCloudController@render');
Route::get('nuvem-palavras/excecoes', 'WordCloudController@excecoes');
Route::get('nuvem-palavras/words', 'WordCloudController@getWords');
Route::post('nuvem-palavras/hashtags', 'RelatorioController@getNuvemHashtags');
Route::post('nuvem-palavras/remove', 'WordCloudController@remove');
Route::delete('nuvem-palavras/excecao/remove/{id}', 'WordCloudController@excecaoRemove')->name('excecao.remove');



Route::post('account/collect/mention', 'AccountController@isToCollectMention');

Route::get('cliente/{client}/facebook/paginas','FbPageController@index');

Route::resource('facebook/pagina', 'FbPageControllerController');
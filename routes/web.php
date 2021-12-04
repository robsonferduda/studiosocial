<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('/politica-de-privacidade', function () { return view('politica-de-privacidade'); });
Route::get('/termos-de-servico', function () { return view('termos-de-servico'); });

Auth::routes();

Route::get('configuracoes','ConfiguracoesController@index');
Route::get('notificacoes','NotificacaoController@index');
Route::get('permissoes','PermissaoController@index');

Route::get('relatorios','RelatorioController@index');
Route::get('relatorios/reactions','RelatorioController@reactions');
Route::get('relatorios/sentimentos','RelatorioController@sentimentos');
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

Route::resource('client', 'ClientController');
Route::resource('hashtag', 'HashtagController');
Route::resource('usuario', 'UserController');
Route::resource('term', 'TermController');

Route::get('login/facebook/client/{client}', 'FacebookController@redirectToProvider');
Route::get('login/facebook/callback', 'FacebookController@handleProviderCallback');

Route::get('monitoramento','MonitoramentoController@index');
Route::get('monitoramento/media/{rede}','MonitoramentoController@seleciona');

Route::post('check/token', 'TokenController@checkFacebookToken');

Route::get('ig-webhook', 'IGWebhookController@urlValidade');
Route::post('ig-webhook', 'IGWebhookController@receive');

Route::get('fb-webhook', 'FBWebhookController@urlValidade');
Route::post('fb-webhook', 'FBWebhookController@receive');

Route::get('/test-api', 'TestApiController@test');

Route::get('twitter', 'TwitterController@index');

Route::get('nuvem-palavras', 'WordCloudController@render');
Route::get('nuvem-palavras/words', 'WordCloudController@getWords');

Route::post('account/collect/mention', 'AccountController@isToCollectMention');
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');

Auth::routes();

Route::get('configuracoes','ConfiguracoesController@index');
Route::get('monitoramento','MonitoramentoController@index');
Route::get('notificacoes','NotificacaoController@index');
Route::get('permissoes','PermissaoController@index');
Route::get('relatorios','RelatorioController@index');

Route::get('perfil','UserController@perfil');
Route::get('usuarios','UserController@index');
Route::get('clientes','ClientController@index');
Route::get('client/accounts/facebook/{cliente}','ClientController@getFacebookAccounts');
Route::get('client/hashtags/{cliente}','ClientController@getHashtags');
Route::get('hashtag/medias/{hashtag}','HashtagController@medias');

Route::resource('client', 'ClientController');
Route::resource('usuario', 'UserController');

Route::get('login/facebook', 'FacebookController@redirectToProvider');
Route::get('login/facebook/callback', 'FacebookController@handleProviderCallback');

Route::get('check/token/{token}', 'TokenController@checkFacebookToken');
Route::get('webhook', 'WebhookController@receive');

Route::get('/test-api', 'TestApiController@test');




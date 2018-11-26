<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Redirecionar rota raiz para área administrativa */
Route::get('/', function () {
    return redirect()->route('admin');
});

/* Rotas de autenticação */
Route::get('/entrar', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/entrar', 'Auth\LoginController@login');
Route::get('/sair', 'Auth\LoginController@logout')->name('logout');
Route::post('/senha/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('/senha/recuperar', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('/senha/recuperar', 'Auth\ResetPasswordController@reset');
Route::get('/senha/recuperar/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

/* Rotas da área administrativa */
Route::get('/admin', 'HomeController@index')->name('admin');

/* Rotas do CRUD de avaliadores */
Route::get('/admin/redacoes/avaliadores', 'CorrectorController@index')->name('corrector.index');
Route::get('/admin/redacoes/avaliadores/adicionar', 'CorrectorController@create')->name('corrector.create');
Route::post('/admin/redacoes/avaliadores/adicionar', 'CorrectorController@store')->name('corrector.store');
Route::get('/admin/redacoes/avaliadores/{id}/ver', 'CorrectorController@show')->name('corrector.show');
Route::get('/admin/redacoes/avaliadores/{id}/modificar', 'CorrectorController@edit')->name('corrector.edit');
Route::put('/admin/redacoes/avaliadores/{id}/modificar', 'CorrectorController@update')->name('corrector.update');
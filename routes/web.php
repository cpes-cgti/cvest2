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
Route::get('/admin', function () {
    return redirect()->route('admin');
});
Route::get('/home', function () {
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
Route::get('/senha/alterar', 'HomeController@change_password')->name('password.change');
Route::put('/senha/alterar', 'HomeController@update_password');

/* Rotas da área administrativa */
Route::get('/admin/resumo', 'HomeController@index')->name('admin');
Route::get('/admin/importar', 'RedactionController@import')->name('redaction.import');
Route::post('/admin/importar', 'RedactionController@process_import')->name('redaction.process_import');
Route::get('/admin/selecionar', 'RedactionController@for_correction')->name('redaction.for_correction');
Route::post('/admin/selecionar', 'RedactionController@process_for_correction')->name('redaction.process_for_correction');
Route::get('/admin/distribuir_redacoes', 'RedactionController@allocate')->name('redaction.allocate');
Route::post('/admin/distribuir_redacoes', 'RedactionController@process_allocate')->name('redaction.process_allocate');
Route::get('/admin/corrigir_redacoes', 'RedactionController@rate_lots')->name('redaction.rate_lots');
Route::get('/admin/corrigir_redacoes/{lot}', 'RedactionController@rate_lot')->name('redaction.rate_lot');
Route::get('/admin/corrigir_redacoes/{lot}/{id}', 'RedactionController@rate')->name('redaction.rate');
Route::post('/admin/corrigir_redacoes/{lot}/{id}', 'RedactionController@rate_save')->name('redaction.rate_save');

/* Rotas do CRUD de avaliadores */
Route::get('/admin/avaliadores', 'CorrectorController@index')->name('corrector.index');
Route::get('/admin/avaliadores/adicionar', 'CorrectorController@create')->name('corrector.create');
Route::post('/admin/avaliadores/adicionar', 'CorrectorController@store')->name('corrector.store');
Route::get('/admin/avaliadores/exibir/{id}', 'CorrectorController@show')->name('corrector.show');
Route::get('/admin/avaliadores/modificar/{id}', 'CorrectorController@edit')->name('corrector.edit');
Route::put('/admin/avaliadores/modificar/{id}', 'CorrectorController@update')->name('corrector.update');
Route::delete('/admin/avaliadores/remover/{id}', 'CorrectorController@destroy')->name('corrector.destroy');

/* Rotas do CRUD de redações */
Route::get('/admin/redacoes/datatables', 'RedactionController@datatables')->name('redaction.datatables');
Route::get('/admin/redacoes', 'RedactionController@index')->name('redaction.index');
Route::get('/admin/redacoes/exibir/{id}', 'RedactionController@show')->name('redaction.show');
Route::get('/admin/redacoes/detalhes/{id}', 'RedactionController@details')->name('redaction.details');
Route::get('/admin/redacoes/exibir_completo/{id}', 'RedactionController@show_admin')->name('redaction.show_admin');
Route::delete('/admin/redacoes/remover_lote/{id}', 'RedactionController@lot_destroy')->name('redaction.lot_destroy');
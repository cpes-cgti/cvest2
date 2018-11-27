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
Route::get('/teste', function () {
    $files = Storage::files();
    /*  dd($files); */
    $file = Storage::get('18203837.JPG');
    $img = Image::make($file);
    $corte_vertical = 0;
    $corte_horizontal = 710;
    $x = $corte_vertical;
    $y = $corte_horizontal;
    $largura = $img->width() - $corte_vertical;
    $altura =  $img->height() - $corte_horizontal;
    $img->crop($largura,  $altura, $x, $y);
    $img_data = $img->encode('data-url');
    return view('redactions.image', compact('img_data'));
    $response = Response::make($img->encode('jpeg'));
    $response->header('Content-Type', 'image/jpeg');
    return $response;
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
Route::get('/admin/redacoes/importar', 'RedactionController@import')->name('redaction.import');
Route::post('/admin/redacoes/importar', 'RedactionController@process_import')->name('redaction.process_import');

/* Rotas do CRUD de avaliadores */
Route::get('/admin/redacoes/avaliadores', 'CorrectorController@index')->name('corrector.index');
Route::get('/admin/redacoes/avaliadores/adicionar', 'CorrectorController@create')->name('corrector.create');
Route::post('/admin/redacoes/avaliadores/adicionar', 'CorrectorController@store')->name('corrector.store');
Route::get('/admin/redacoes/avaliadores/{id}/exibir', 'CorrectorController@show')->name('corrector.show');
Route::get('/admin/redacoes/avaliadores/{id}/modificar', 'CorrectorController@edit')->name('corrector.edit');
Route::put('/admin/redacoes/avaliadores/{id}/modificar', 'CorrectorController@update')->name('corrector.update');
Route::delete('/admin/redacoes/avaliadores/{id}/remover', 'CorrectorController@destroy')->name('corrector.destroy');
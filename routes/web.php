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

Route::get('/', function () {
    return view('principal');
});

Route::get('/Empresa', 'EmpresaController@index');
Route::post('/Empresa/store', 'EmpresaController@store');
Route::put('/Empresa/update', 'EmpresaController@update');
Route::put('/Empresa/desactivate', 'EmpresaController@desactivate');
Route::put('/Empresa/activate', 'EmpresaController@activate');

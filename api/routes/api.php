<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//TOKEN
Route::get('token','BienvenidoController@getToken');
// CRUD DE EMPRESA
/*Route::get('empresas', 'empresaController@inicio')->middleware('token');
Route::get('empresaMostrar', 'empresaController@mostrarEmpresa')->middleware('token');
Route::get('tablaEmpresa', 'empresaController@tablaEmpresa')->middleware('token');
Route::get('empresas/{id}', 'empresaController@mostrar')->middleware('token');
Route::post('empresas', 'empresaController@registrar')->middleware('token');
Route::post('empresas/{id}', 'empresaController@actualizar')->middleware('token');
Route::delete('empresa/{id}', 'empresaController@eliminar')->middleware('token');
Route::patch('empresa/{id}', 'empresaController@cambiarEstado')->middleware('token');
// *********************************************************
*/
//CRUD DE ENTIDAD BANCARIA
Route::get('entidadesBancarias', 'entidadBancariaController@inicio')->middleware('token');
Route::get('entidadMostrar', 'entidadBancariaController@entidadMostrar')->middleware('token');
Route::get('tablaEntidadBancaria', 'entidadBancariaController@tablaEntidadBancaria')->middleware('token');
Route::get('entidadesBancarias/{id}', 'entidadBancariaController@mostrar')->middleware('token');
Route::post('entidadesBancarias', 'entidadBancariaController@registrar')->middleware('token');
Route::post('entidadesBancarias/{id}', 'entidadBancariaController@actualizar')->middleware('token');
Route::patch('entidadBancaria/{id}', 'entidadBancariaController@cambiarEstado')->middleware('token');
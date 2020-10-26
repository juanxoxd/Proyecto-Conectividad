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
Route::get('empresas', 'EmpresaController@listar')->middleware('token');
Route::get('empresas/{id}', 'EmpresaController@leer')->middleware('token');
Route::post('empresas', 'EmpresaController@registrar')->middleware('token');
Route::post('empresas/{id}', 'EmpresaController@actualizar')->middleware('token');
Route::patch('empresa/{id}', 'EmpresaController@cambiarVigencia')->middleware('token');
// *********************************************************

//**************************CRUD MEDIO DE PAGO ***************************
Route::get('mediosdepago','MedioPagoController@listar')->middleware('token');
Route::get('mediodepago/{id}', 'MedioPagoController@leer')->middleware('token');
Route::post('mediosdepago','MedioPagoController@registrar')->middleware('token');
Route::put('mediosdepago/{id}','MedioPagoController@actualizar')->middleware('token');
Route::patch('mediosdepago/{id}','MedioPagoController@cambiarVigencia')->middleware('token');

// CRUD DE CUENTA BANCARIA

Route::get('cuentasbancarias','CuentaBancariaController@listar')->middleware('token');
Route::post('cuentasbancarias','CuentaBancariaController@registrar')->middleware('token');
Route::put('cuentasbancarias/{id}','CuentaBancariaController@actualizar')->middleware('token');
Route::patch('cuentasbancarias/{id}','CuentaBancariaController@cambiarVigencia')->middleware('token');

// CRUD USUARIO
Route::get('usuariosperfil', 'UsuarioController@listarPorPerfil')->middleware('token');
Route::get('usuariospersonal', 'UsuarioController@listarPorPersonal')->middleware('token');
Route::get('usuarioslocal', 'UsuarioController@listarPorLocal')->middleware('token');
Route::get('usuarios', 'UsuarioController@listar')->middleware('token');
Route::get('usuarios/{id}', 'UsuarioController@leer')->middleware('token');
Route::post('usuarios', 'UsuarioController@registrar')->middleware('token');
Route::put('usuarios/{id}', 'UsuarioController@actualizar')->middleware('token');
Route::patch('usuario/{id}', 'UsuarioController@cambiarVigencia')->middleware('token');

//****** PERSONAL */
Route::get('personal/{id}', 'PersonalController@leer')->middleware('token');
Route::post('personal/{txt}/{op}', 'PersonalController@listar')->middleware('token');
Route::post('personal', 'PersonalController@registrar')->middleware('token');
Route::put('personal/{id}', 'PersonalController@actualizar')->middleware('token');
Route::patch('personal/{id}', 'PersonalController@cambiarVigencia')->middleware('token');

//*******DOCUMENTO PERSONAL */
Route::get('documentopersonal', 'DocumentoPersonalController@listar')->middleware('token');
Route::get('documentopersonalvigentes', 'DocumentoPersonalController@listarVigente')->middleware('token');
Route::get('documentopersonal/{id}', 'DocumentoPersonalController@leer')->middleware('token');
Route::post('documentopersonal', 'DocumentoPersonalController@registrar')->middleware('token');
Route::put('documentopersonal/{id}', 'DocumentoPersonalController@actualizar')->middleware('token');
Route::patch('documentopersonal/{id}', 'DocumentoPersonalController@cambiarVigencia')->middleware('token');

// CRUD DE SISTEMA DE PENSIONES
Route::get('sistemasdepensiones', 'SistemaPensionController@listar')->middleware('token');
Route::get('sistemasdepensiones/{id}', 'SistemaPensionController@leer')->middleware('token');
Route::post('sistemasdepensiones', 'SistemaPensionController@registrar')->middleware('token');
Route::put('sistemasdepensiones/{id}', 'SistemaPensionController@actualizar')->middleware('token');
Route::patch('sistemadepension/{id}', 'SistemaPensionController@cambiarVigencia')->middleware('token');

Route::get('sistemadepensionesvigentes', 'SistemaPensionController@listarVigente')->middleware('token');


//**************************CRUD MEDIO DE PAGO ***************************
Route::get('entidadesbancarias','EntidadBancariaController@listar')->middleware('token');
Route::get('entidadesbancarias/{id}', 'EntidadBancariaController@leer')->middleware('token');
Route::post('entidadesbancarias','EntidadBancariaController@registrar')->middleware('token');
Route::put('entidadesbancarias/{id}','EntidadBancariaController@actualizar')->middleware('token');
Route::patch('entidadesbancarias/{id}','EntidadBancariaController@cambiarVigencia')->middleware('token');




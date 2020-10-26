<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\CuentaBancaria;
use DB;

class CuentaBancariaController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function listar()
    {

        /*
        SELECT cb.nombre,ef.siglas,cb.numeroCuenta,cb.CCI
from cuentabancaria cb
inner join entidadbancaria ef on ef.codigo = cb.codigoEntidadBancaria
        */
        $cuentas = DB::table('cuentabancaria as cb')->select('cb.Codigo','ef.Siglas','cb.CCI','cb.NumeroCuenta','cb.CodigoEntidadBancaria','cb.TipoMoneda','cb.Nombre','cb.Vigencia')
                                    ->join('entidadBancaria as ef','ef.Codigo','=','cb.CodigoEntidadBancaria')
                                    ->get();
        return response()->json($cuentas);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registrar(Request $request)
    {

        $cuenta =  new CuentaBancaria();
        $cuenta->CodigoEmpresa = $request->codEmpresa;
        $cuenta->CodigoEntidadBancaria = $request->codEntidad;
        $cuenta->CCI = $request->cci;
        $cuenta->NumeroCuenta = $request->numCuenta;
        $cuenta->TipoMoneda = $request->tipoMoneda;
        $cuenta->Nombre = $request->nombre;
        $cuenta->save();
        return response()->json($cuenta,202);

    }

    
    public function actualizar(Request $request, $id)
    {

        $cuenta = CuentaBancaria::findOrFail($id);
        $cuenta->CodigoEmpresa = $request->codEmpresa;
        $cuenta->CodigoEntidadBancaria = $request->codEntidad;
        $cuenta->CCI = $request->cci;
        $cuenta->NumeroCuenta = $request->numCuenta;
        $cuenta->TipoMoneda = $request->tipoMoneda;
        $cuenta->Nombre = $request->nombre;
        $cuenta->save();
        return response()->json($cuenta,202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cambiarVigencia($id)
    {
        $cuenta = CuentaBancaria::findOrFail($id);
        $cuenta->Vigencia = $cuenta->Vigencia == 1 ? 0 : 1;
        $cuenta->save();
       return response()->json($cuenta,202);
    }
}
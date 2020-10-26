<?php

namespace App\Http\Controllers;

use App\Personal;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonalController extends Controller
{
    public function registrar(Request $request)
    {

        $personal = new personal();

        $personal->Nombres = $request->get('Nombres');
        $personal->ApellidoPaterno = $request->get('ApellidoPaterno');
        $personal->ApellidoMaterno = $request->get('ApellidoMaterno');
        $personal->CodigoDocumentoPersonal = $request->get('Documento');
        $personal->NumeroDocumento = $request->get('NumeroDocumento');
        $personal->Direccion = $request->get('Direccion');
        $personal->Telefono = $request->get('Telefono');
        $personal->Correo = $request->get('Correo');
        $personal->Celular = $request->get('Celular');
        $personal->CodigoSistemaPensiones = $request->get('Siglas');
        $personal->Vigencia = 1;
        $personal->save();

        return response()->json($personal, 201);
    }

    public function actualizar(Request $request, $id)
    {
        $personal = personal::findOrFail($id);
        $personal->Nombres = $request->get('Nombres');
        $personal->ApellidoPaterno = $request->get('ApellidoPaterno');
        $personal->ApellidoMaterno = $request->get('ApellidoMaterno');
        $personal->CodigoDocumentoPersonal = $request->get('Documento');
        $personal->NumeroDocumento = $request->get('NumeroDocumento');
        $personal->Direccion = $request->get('Direccion');
        $personal->Telefono = $request->get('Telefono');
        $personal->Correo = $request->get('Correo');
        $personal->Celular = $request->get('Celular');
        $personal->CodigoSistemaPensiones = $request->get('Siglas');

        $personal->save();
        return response()->json($personal, 200);
    }

    public function cambiarVigencia($id)
    {
        $personal = Personal::findOrFail($id);
        $personal->Vigencia = !$personal->Vigencia;
        $personal->save();

        return response()->json($personal, 200);
    }

    public function listar($txt, $op)
    {
        if ($op == "Nombres") {

            $sql = 'SELECT @i:=@i+1 as contador, pe.Codigo, pe.Vigencia, pe.Nombres, pe.ApellidoPaterno, pe.Codigo, pe.ApellidoMaterno, doc.Nombre as Documento, pe.NumeroDocumento, si.Siglas, pe.Direccion, pe.Telefono,pe.Correo, pe.Celular FROM personal as pe
            INNER JOIN documentopersonal as doc on doc.Codigo = pe.CodigoDocumentoPersonal
            INNER JOIN sistemapensiones as si on si.Codigo = pe.CodigoSistemaPensiones
            CROSS JOIN (SELECT @i := 0) r
            WHERE pe.Nombres LIKE "%'.$txt.'%" or pe.ApellidoPaterno LIKE "%'.$txt.'%" or pe.ApellidoMaterno LIKE "%'.$txt.'%"';
        }

        if ($op == "S.N.P") {

            $sql = 'SELECT @i:=@i+1 as contador, pe.Codigo, pe.Vigencia, pe.Nombres, pe.ApellidoPaterno, pe.Codigo, pe.ApellidoMaterno, doc.Nombre as Documento, pe.NumeroDocumento, si.Siglas, pe.Direccion, pe.Telefono,pe.Correo, pe.Celular FROM personal as pe
            INNER JOIN documentopersonal as doc on doc.Codigo = pe.CodigoDocumentoPersonal
            INNER JOIN sistemapensiones as si on si.Codigo = pe.CodigoSistemaPensiones
            CROSS JOIN (SELECT @i := 0) r
            WHERE  si.Codigo = '.$txt.' ';

        }

        $personal = DB::select($sql);

        return response()->json($personal, 200);
    }

    public function leer($id)
    {
        return personal::find($id);
    }

    /*public function listarTipoDoc()
    {
        $sql = 'SELECT Nombre, Tipo, CantidadMaxima FROM documentopersonal
        WHERE vigencia = 1';

        $tipoDoc = DB::select($sql);

        return response()->json($tipoDoc, 200);
    }*/

    /*public function tablaPersonal()
    {
        $sql = 'SELECT pe.Vigencia, pe.Nombres, pe.ApellidoPaterno, pe.Codigo, pe.ApellidoMaterno, doc.Nombre as Documento, pe.NumeroDocumento, si.Siglas, pe.Direccion, pe.Telefono,pe.Correo, pe.Celular FROM personal as pe
        INNER JOIN documentopersonal as doc on doc.Codigo = pe.CodigoDocumentoPersonal
        INNER JOIN sistemapensiones as si on si.Codigo = pe.CodigoSistemaPensiones
        ';
        
        $personal = DB::select($sql);

        return response()->json($personal, 200);
    }*/


    /*public function listapersonal()
    {
                
        $sql = 'SELECT pe.Codigo, pe.Vigencia, pe.Nombres, pe.ApellidoPaterno, pe.Codigo, pe.ApellidoMaterno, doc.Nombre as Documento, pe.NumeroDocumento, si.Siglas, pe.Direccion, pe.Telefono,pe.Correo, pe.Celular FROM personal as pe
                INNER JOIN documentopersonal as doc on doc.Codigo = pe.CodigoDocumentoPersonal
                INNER JOIN sistemapensiones as si on si.Codigo = pe.CodigoSistemaPensiones
                ';
        $personal = DB::select($sql);

        return response()->json($personal, 200);
    }*/
}


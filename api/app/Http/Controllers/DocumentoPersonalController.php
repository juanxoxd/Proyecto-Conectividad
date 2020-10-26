<?php

namespace App\Http\Controllers;

use App\DocumentoPersonal;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentoPersonalController extends Controller
{
    public function listar()
    {
        $sql = 'SELECT @i:=@i+1 as contador, Nombre, CodigoSUNAT, Codigo, CantidadMaxima, CantidadMinima, Vigencia FROM documentopersonal CROSS JOIN (SELECT @i := 0) r';

        $documentopersonal = DB::select($sql);

        return response()->json($documentopersonal, 200);
    }

    public function listarVigente()
    {
        $sql = 'SELECT Codigo, Nombre, CantidadMinima, CantidadMaxima FROM documentopersonal
        WHERE Vigencia = 1';
        $doc = DB::select($sql);

        return response()->json($doc, 200);
    }

    public function registrar(Request $request)
    {
        $documentopersonal = new documentopersonal();

        $documentopersonal->Nombre = $request->get('Nombre');
        $documentopersonal->CodigoSUNAT = $request->get('CodigoSUNAT');
        $documentopersonal->CantidadMinima = $request->get('CantidadMinima');
        $documentopersonal->CantidadMaxima = $request->get('CantidadMaxima');
        $documentopersonal->Tipo = $request->get('Tipo');
        $documentopersonal->Vigencia = 1;

        $documentopersonal->save();

        return response()->json($documentopersonal, 201);
    }

    public function actualizar(Request $request, $id)
    {
        $documentopersonal = documentopersonal::findOrFail($id);
        $documentopersonal->Nombre = $request->get('Nombre');
        $documentopersonal->CodigoSUNAT = $request->get('CodigoSUNAT');
        $documentopersonal->CantidadMinima = $request->get('CantidadMinima');
        $documentopersonal->CantidadMaxima = $request->get('CantidadMaxima');
        $documentopersonal->Tipo = $request->get('Tipo');

        $documentopersonal->save();
        return response()->json($documentopersonal, 200);
    }

    public function cambiarVigencia($id)
    {
        $documentopersonal = Documentopersonal::findOrFail($id);
        $documentopersonal->Vigencia = !$documentopersonal->Vigencia;
        $documentopersonal->save();

        return response()->json($documentopersonal, 200);
    }

    public function leer($id)
    {
        return documentopersonal::find($id);
    }
}
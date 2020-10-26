<?php

namespace App\Http\Controllers;

use App\EntidadBancaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EntidadBancariaController extends Controller
{
    public function listar()
    {
        $entidadBancaria = DB::table('entidadbancaria as eb')
            ->select('eb.Codigo','eb.RazonSocial', 'eb.Siglas','eb.Vigencia')
            ->get();
        return response()->json($entidadBancaria, 200);
    }

    public function leer($id)
    {
        return EntidadBancaria::find($id);
    }

    public function registrar(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'RazonSocial' => 'required|max:90',
            'Siglas' => 'required|unique:EntidadBancaria|max:15',
        ], [
            'unique' => ':attribute ya se encuentra registrado',
            'required' => ':attribute es obligatorio',
            'max' => ':attribute llegó al limite de letras'
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion->errors()->first(), 400);
        }

        $entidadBancaria = new EntidadBancaria();

        $entidadBancaria->RazonSocial = $request->get('RazonSocial');
        $entidadBancaria->Siglas = $request->get('Siglas');

        $entidadBancaria->save();
        return response()->json($entidadBancaria, 201);
    }

    public function actualizar(Request $request, $id)
    {
        $validacion = Validator::make($request->all(), [
            'RazonSocial' => 'required|max:90',
            'Siglas' => 'required|unique:EntidadBancaria|max:15',
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion, 400);
        }
        $entidadBancaria = EntidadBancaria::findOrFail($id);
        $entidadBancaria->RazonSocial = $request->get('RazonSocial');
        $entidadBancaria->Siglas = $request->get('Siglas');
        $entidadBancaria->save();
        return response()->json($entidadBancaria, 200);
    }

    public function cambiarVigencia($id)
    {
        $entidadBancaria = EntidadBancaria::findOrFail($id);
        $entidadBancaria->Vigencia = !$entidadBancaria->Vigencia;
        $entidadBancaria->save();

        return response()->json($entidadBancaria, 200);
    }

}

<?php

namespace App\Http\Controllers;

use App\entidadBancaria;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntidadBancariaController extends Controller
{
    public function inicio()
    {
        $entidadBancaria = DB::table('entidadBancaria as eB')
            ->select('eB.RazonSocial', 'eB.Siglas')
            ->where('eB.Vigencia', '=', 1)
            ->get();
        return response()->json($entidadBancaria, 200);
    }
    public function mostrarentidadesbancarias()
    {
        return entidadBancaria::all();
    }

    public function tablaentidadbancaria()
    {
        $entidadBancaria = entidadBancaria::all('Codigo', 'RazonSocial', 'Siglas','Vigencia');
        $count  = 1;
        foreach ($entidadBancaria  as $aux) {
            $aux->indice = $count;
            $count ++;
        }
        return $entidadBancaria;        
    }

    public function mostrar($id)
    {
        return entidadBancaria::find($id);
    }
    
    public function registrar(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'RazonSocial' => 'required|max:100',
            'Siglas' => 'required|unique:entidadBancaria|max:10',           
        ], [
            'unique' => ':attribute ya se encuentra registrado',
            'required' => ':attribute es obligatorio',
            'max' => ':attribute llego al lìmite de letras.'
        ]);

        if ($validacion->fails()) {
            return response()->json($validacion->errors()->first(), 400);
        }
        $entidadBancaria = new entidadBancaria();
        $entidadBancaria->RazonSocial = $request->get('RazonSocial');
        $entidadBancaria->Siglas = $request->get('Siglas');
        $entidadBancaria->Vigencia = 1;
        $entidadBancaria->save();
        return response()->json($entidadBancaria, 201);
    }

    public function actualizar(Request $request, $id)
    {
        $validacion = Validator::make($request->all(), [
            'RazonSocial' => 'required|max:100',
            'Siglas' => 'required|max:10',
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion, 400);
        }
        $entidadBancaria = entidadBancaria::findOrFail($id);
        $entidadBancaria->RazonSocial = $request->get('RazonSocial');
        $entidadBancaria->Siglas = $request->get('Siglas');
        $entidadBancaria->save();
        return response()->json($entidadBancaria, 200);
    }
    public function entidadVigencia($id)
    {
        $empresa = entidadBancaria::findOrFail($id);
        $empresa->Vigencia = !$empresa->Vigencia;
        $empresa->save();

        return response()->json($empresa, 200);
    }
    
}

<?php

namespace App\Http\Controllers;

use App\MedioPago;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedioPagoController extends Controller
{
    public function listar()
    {
        $medioPago = DB::table('mediopago as mp')
            ->select('mp.Nombre', 'mp.CodigoSUNAT', 'mp.Codigo','mp.Vigencia')
            ->get();
        return response()->json($medioPago, 200);
    }

    public function leer($id)
    {
        return MedioPago::find($id);
    }

    public function registrar(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'Nombre' => 'required|max:50',
            'CodigoSUNAT' => 'required|unique:medioPago|max:5',
        ], [
            'unique' => ':attribute ya se encuentra registrado',
            'required' => ':attribute es obligatorio',
            'max' => ':attribute llego al limite de letras'
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion->errors()->first(), 400);
        }

        $medioPago = new medioPago();

        $medioPago->Nombre = $request->get('Nombre');
        $medioPago->CodigoSUNAT = $request->get('CodigoSUNAT');

        $medioPago->save();
        return response()->json($medioPago, 201);
    }

    public function actualizar(Request $request, $id)
    {
        $validacion = Validator::make($request->all(), [
            'Nombre' => 'required|max:50',
            'CodigoSUNAT' => 'required|unique:medioPago|max:5',
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion, 400);
        }

        $medioPago = medioPago::findOrFail($id);
        $medioPago->Nombre = $request->get('Nombre');
        $medioPago->CodigoSUNAT = $request->get('CodigoSUNAT');
        $medioPago->save();
        return response()->json($medioPago, 200);
    }

    public function cambiarVigencia($id)
    {
        $medioPago = medioPago::findOrFail($id);
        $medioPago->Vigencia = !$medioPago->Vigencia;
        $medioPago->save();

        return response()->json($medioPago, 200);
    }

}

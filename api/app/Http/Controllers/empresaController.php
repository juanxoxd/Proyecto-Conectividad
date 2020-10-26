<?php

namespace App\Http\Controllers;

use App\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmpresaController extends Controller
{
    public function listar()
    {
        return Empresa::all('Codigo', 'RUC', 'RazonSocial', 'Correo', 'Vigencia');
    }

    public function leer($id)
    {
        return Empresa::find($id);
    }

    public function registrar(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'RazonSocial' => 'required|max:100',
            'RUC' => 'required|unique:empresa|max:11',
            'Correo' => 'required|max:100',
        ], [
            'unique' => ':attribute ya se encuentra registrado',
            'required' => ':attribute es obligatorio',
            'max' => ':attribute llego al limite de letras'
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion->errors()->first(), 400);
        }
        $empresa = new Empresa();

        $empresa->RazonSocial = $request->get('RazonSocial');
        $empresa->RUC = $request->get('RUC');
        $empresa->Facebook = $request->get('Facebook');
        $empresa->Instagram = $request->get('Instagram');
        $empresa->Youtube = $request->get('Youtube');
        $empresa->Whatsapp = $request->get('Whatsapp');
        $empresa->Correo = $request->get('Correo');
        if ($request->hasFile('Logo')) {
            $file = $request->file('Logo');
            $filename =  $request->get("RUC") . "." . $file->getClientOriginalExtension();
            $path = public_path() . '/logos/';
            $file->move($path, $filename);
            $empresa->Logo = $filename;
        }
        $empresa->save();


        return response()->json($empresa, 201);
    }

    public function actualizar(Request $request, $id)
    {
        $validacion = Validator::make($request->all(), [
            'RazonSocial' => 'required|max:100',
            'RUC' => 'required|max:11',
            'Facebook' => 'max:100',
            'Instagram' => 'max:100',
            'Youtube' => 'max:100',
            'Whatsapp' => 'max:100',
            'Correo' => 'required|max:100',
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion, 400);
        }
        $empresa = Empresa::findOrFail($id);
        $empresa->RazonSocial = $request->get('RazonSocial');
        $empresa->RUC = $request->get('RUC');
        $empresa->Facebook = $request->get('Facebook');
        $empresa->Instagram = $request->get('Instagram');
        $empresa->Youtube = $request->get('Youtube');
        $empresa->Whatsapp = $request->get('Whatsapp');
        $empresa->Correo = $request->get('Correo');
        if ($request->hasFile('Logo')) {
            $file = $request->file('Logo');
            $filename =  strtoupper($request->get("RUC")) . "." . $file->getClientOriginalExtension();
            $path = public_path() . '/logos/';
            $file->move($path, $filename);
            $empresa->Logo = $filename;
        }
        $empresa->save();
        return response()->json($empresa, 200);
    }

    public function cambiarVigencia($id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->Vigencia = !$empresa->Vigencia;
        $empresa->save();

        return response()->json($empresa, 200);
    }
}
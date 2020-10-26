<?php

namespace App\Http\Controllers;

use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    public function listar()
    {
        $usuario = DB::table('usuario as u')
            ->join('local as l', 'l.Codigo', '=', 'u.CodigoLocal')
            ->join('perfil as p', 'p.Codigo', '=', 'u.CodigoPerfil')
            ->select('U.Codigo', 'u.Nombre', 'u.Clave', 'l.Nombre as Local', 'p.Nombre as Perfil', 'u.Vigencia')
            ->get();

        return response()->json($usuario, 200);
    }

    public function listarPorPerfil()
    {
        $respuesta = [];
        $perfil = DB::table('perfil as p')
            ->select('p.Codigo', 'p.Nombre')
            ->get();
        foreach ($perfil as $p) {
            array_push($respuesta, array("value" => $p->Codigo, "text" => $p->Nombre));
        }
        return response()->json($respuesta, 200);
    }

    public function listarPorPersonal()
    {
        $respuesta = [];
        $personal = DB::table('personal as p')
            ->select('p.Codigo', 'p.Nombres', 'p.ApellidoPaterno', 'p.ApellidoMaterno')
            ->get();
        foreach ($personal as $p) {
            array_push($respuesta, array("value" => $p->Codigo, "text" => $p->Nombres . " " . $p->ApellidoPaterno . " " . $p->ApellidoMaterno));
        }
        return response()->json($respuesta, 200);
    }

    public function listarPorLocal()
    {
        $respuesta = [];
        $local = DB::table('local as l')
            ->select('l.Codigo', 'l.Nombre')
            ->get();
        foreach ($local as $l) {
            array_push($respuesta, array("value" => $l->Codigo, "text" => $l->Nombre));
        }
        return response()->json($respuesta, 200);
    }


    public function leer($id)
    {
        return Usuario::find($id);
    }

    public function registrar(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'CodigoPersonal' => 'required',
            'CodigoPerfil' => 'required',
            'Nombre' => 'required|max:100',
            'Clave' => 'required|max:255',
            'CodigoLocal' => 'required'
        ], [
            'required' => ':attribute es obligatorio',
            'max' => ':attribute llego al limite de letras'
        ]);

        if ($validacion->fails()) {
            return response()->json($validacion->errors()->first(), 400);
        }

        $usuario = new Usuario();
        $usuario->CodigoPersonal = $request->get('CodigoPersonal');
        $usuario->CodigoPerfil = $request->get('CodigoPerfil');
        $usuario->Nombre = $request->get('Nombre');
        $usuario->Clave = Hash::make($request->get('Clave'));
        $usuario->CodigoLocal = $request->get('CodigoLocal');
        $usuario->save();

        return response()->json($usuario, 201);
    }

    public function actualizar(Request $request, $id)
    {
        // luego lo pones
        $clave = DB::table('usuario as u')
            ->select('u.Clave')
            ->where('u.Codigo', '=', $id)
            ->get()
            ->first();

        if ($request->get('ComprobarClave') != '') {
            $respuesta = Hash::check($request->get('ComprobarClave'), $clave->Clave);
            if ($respuesta == false) {
                return response()->json(array("msg" => "Contrase;a no coincide"), 501); //el msg de aqui no sirve por ahora 
            }
        }
        $usuario = Usuario::findOrFail($id);
        $usuario->Nombre = $request->get('Nombre');
        if ($request->get('Clave') != '') {
            $usuario->Clave = Hash::make($request->get('Clave'));
        }
        $usuario->save();

        return response()->json($usuario, 200);
    }


    public function cambiarVigencia($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->Vigencia = !$usuario->Vigencia;
        $usuario->save();

        return response()->json($usuario, 200);
    }
}
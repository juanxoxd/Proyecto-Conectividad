<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Empresa;

class EmpresaController extends Controller
{
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        DB::beginTransaction();
        try {
            $empresa = new Empresa();
            $empresa->razonSocial = $request->razonSocial;
            $empresa->ruc = $request->ruc;
            $empresa->urlFacebook = $request->urlFacebook;
            $empresa->urlInstagram = $request->urlInstagram;
            $empresa->correo = $request->correo;
            $empresa->logo = $request->logo;
            $empresa->vigencia = $request->vigencia;
            $empresa->save();
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
}

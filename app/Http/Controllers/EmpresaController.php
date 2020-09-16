<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests\EmpresaStoreRequest;
use App\Empresa;
class EmpresaController extends Controller
{
    public function store(EmpresaStoreRequest $request)
    {
        if(!$request->ajax()) return redirect('/');
        DB::beginTransaction();
        try {
            $empresa = new Empresa();
            $empresa->razonSocial = $request->razonSocial;
            $empresa->ruc = $request->ruc;
            $empresa->urlFacebook = $request->urlFacebook;
            $empresa->urlInstagram = $request->urlInstagram;
            $empresa->urlWhatsApp = $request->urlWhatsApp;
            $empresa->correo = $request->correo;
            $empresa->logo = $request->logo;
            $empresa->save();
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $textoBuscar = $request-> textoBuscar;
        $criterio = $request -> criterio;
        $validate = false;
        if($criterio != 'Seleccione'){
            $validate = true;
        }
        if($validate){
            $empresa = Empresa::where($criterio, 'like', '%'.$textoBuscar.'%')->orderBy('idEmpresa', 'desc')->paginate(5);
        }else{
            $empresa = Empresa::where('ruc', 'like', '%'.$textoBuscar.'%')
            ->orwhere('razonSocial', 'like', '%'.$textoBuscar.'%')
            ->orderBy('idEmpresa', 'desc')->paginate(5);
        }
        return [
            'pagination' => [
                'total'             => $empresa->total(), //total
                'current_page'      => $empresa->currentPage(), //pagina Actual
                'per_page'          => $empresa->perPage(), //por p{agina}
                'last_page'         => $empresa->lastPage(), //ultima
                'from'              => $empresa->firstItem(), // primer elemento
                'to'                => $empresa->lastItem(), //u}ltimo item
            ], 'empresa' => $empresa
        ];
    }
    public function update(Request $request)
    {
        //Buscamos la empresa por id, luego asignamos los datos, y guardamos

        DB::beginTransaction();
        try {
            $empresa= Empresa::findOrFail($request->idEmpresa);
            $empresa->razonSocial = $request->razonSocial;
            $empresa->ruc = $request->ruc;
            $empresa->urlFacebook = $request->urlFacebook;
            $empresa->urlInstagram = $request->urlInstagram;
            $empresa->urlWhatsApp = $request->urlWhatsApp;
            $empresa->correo = $request->correo;
            $empresa->logo = $request->logo;
            $empresa->save();
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
    public function activate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $empresa = Empresa::findOrFail($request->idEmpresa);//accedemos a cada una de las propiedades
        $empresa->vigencia = '1';
        $empresa->save();
    }
    public function desactivate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $empresa = Empresa::findOrFail($request->idEmpresa);//accedemos a cada una de las propiedades
        $empresa->vigencia = '0';
        $empresa->save();
    }
}

<?php

namespace App\Http\Controllers\biblioteca;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\modelos\catalogos\centroInformacionModel;
use DB;

class bibliotecaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {

        return view('catalogos.biblioteca.centroInformacion');
    }

    public function listaBiblioteca($clasificacion, $titulo)
    {

        if ($clasificacion == 0 && $titulo == 0) {
            $info = centroInformacionModel::orderBy('created_at', 'ASC')->get();
        } else if ($clasificacion != 0 && $titulo == 0) {
            $info = centroInformacionModel::where('CLASIFICACION', $clasificacion)
                ->orderBy('created_at', 'ASC')
                ->get();
        }
        return response()->json($info);
    }


    public function listaBibliotecaText($clasificacion, $titulo)
    {

        $info = centroInformacionModel::where('TITULO', 'LIKE', '%' . $titulo . '%')
            ->orWhere('DESCRIPCION', 'LIKE', '%' . $titulo . '%')
            ->orderBy('created_at', 'ASC')
            ->get();
        return response()->json($info);
    }

    public function consultaLibro($id)
    {
        $libro = centroInformacionModel::where('ID_CENTRO_INFORMACION', $id)->get();
        return response()->json($libro);
    }


    public function bibliotecapdf($documento_id)
    {
        $documento = centroInformacionModel::findOrFail($documento_id);
        return Storage::response($documento->RUTA_DOCUMENTO);
    }


    public function eliminarLibro($id)
    {
        $documento = centroInformacionModel::findOrFail($id);
        $documento->delete();

        return response()->json('Registro eliminado con exito');

    }

    public function store(Request $request)
    {
        try {


            if ($request->ID_CENTRO_INFORMACION == 0){

                // AUTO_INCREMENT
                DB::statement('ALTER TABLE centroInformacion AUTO_INCREMENT=1;');
                $documento = centroInformacionModel::create($request->all());
    
                if ($request->file('DOCUMENTO')) {
    
                    $request['RUTA_DOCUMENTO'] = $request->file('DOCUMENTO')->storeAs('biblioteca', str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->file('DOCUMENTO')->getclientoriginalname()));
    
    
                    $documento->update($request->all());
                }

            }else{

                $documento = centroInformacionModel::findOrFail($request['ID_CENTRO_INFORMACION']);

                if ($request->file('DOCUMENTO')) {

                    // Eliminar DOC anterior
                    if (Storage::exists($documento->RUTA_DOCUMENTO)) {
                        Storage::delete($documento->RUTA_DOCUMENTO);
                    }

                    $request['RUTA_DOCUMENTO'] = $request->file('DOCUMENTO')->storeAs('biblioteca', str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->file('DOCUMENTO')->getclientoriginalname()));
                }

                $documento->update($request->all());

            }

            return response()->json($documento);
        } catch (Exception $e) {

            return response()->json('Error al guardar informacion');
        }
    }
}

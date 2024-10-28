<?php

namespace App\Http\Controllers\biblioteca;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\modelos\catalogos\centroInformacionModel;
use DB;

class bibliotecaController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }


    public function index(){

        return view('catalogos.biblioteca.centroInformacion');
    }

    public function listaBiblioteca(){

        $info = centroInformacionModel::OrderBy('created_at', 'ASC')->get();
        return response()->json($info);

    }

    public function bibliotecapdf($documento_id)
    {
        $documento = centroInformacionModel::findOrFail($documento_id);
        return Storage::response($documento->RUTA_DOCUMENTO);
    }


    public function store(Request $request){
        try {
           
            // AUTO_INCREMENT
            DB::statement('ALTER TABLE centroInformacion AUTO_INCREMENT=1;');
            $documento = centroInformacionModel::create($request->all());

            if ($request->file('DOCUMENTO')) {

                $request['RUTA_DOCUMENTO'] = $request->file('DOCUMENTO')->storeAs('biblioteca', str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->file('DOCUMENTO')->getclientoriginalname()));


                $documento->update($request->all());
            }

            return response()->json($documento);

        } catch (Exception $e) {

            return response()->json('Error al guardar informacion');
        }
    }

}

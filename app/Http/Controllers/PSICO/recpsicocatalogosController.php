<?php

namespace App\Http\Controllers\PSICO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\modelos\reconocimientopsico\catalogosguiaspsicoModel;


use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Support\Facades\Storage;

class recpsicocatalogosController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('catalogos.psico.recpsicocatalogos');
    }

        /**
     * Display the specified resource.
     *
     * @param  int  $num_catalogo
     * @return \Illuminate\Http\Response
     */
    public function tablaCatalogoGuia($num_catalogo)
    {
        switch (($num_catalogo + 0)) {
            case 1:
                $lista = DB::select('SELECT
                                        ID_GUIAPREGUNTA,
                                        TIPOGUIA,
                                        PREGUNTA_ID,
                                        PREGUNTA_NOMBRE,
                                        PREGUNTA_EXPLICACION
                                    FROM
                                        catalogoguiaspsico
                                    WHERE 
                                        TIPOGUIA = 1');

                $COUNT = 0;
                foreach ($lista as $key => $value) {
                    $COUNT++;
                    $value->COUNT = $COUNT;

                    $value->boton_editar = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_registro_guia();"><i class="fa fa-pencil"></i></button>';
                }
            break;
            case 2:
                $lista = DB::select('SELECT
                                        PREGUNTA_NOMBRE,
                                        PREGUNTA_EXPLICACION
                                    FROM
                                        catalogoguiaspsico
                                    WHERE 
                                        TIPOGUIA = 2');

                $COUNT = 0;
                foreach ($lista as $key => $value) {
                    $COUNT++;
                    $value->COUNT = $COUNT;

                    $value->boton_editar = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_registro_guia();"><i class="fa fa-pencil"></i></button>';
                }
            break;
            case 3:
                $lista = DB::select('SELECT
                                        PREGUNTA_NOMBRE,
                                        PREGUNTA_EXPLICACION
                                    FROM
                                        catalogoguiaspsico
                                    WHERE 
                                        TIPOGUIA = 3');

                $COUNT = 0;
                foreach ($lista as $key => $value) {
                    $COUNT++;
                    $value->COUNT = $COUNT;

                    $value->boton_editar = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_registro_guia();"><i class="fa fa-pencil"></i></button>';
                }
            break;
        }

        // Respuesta
        $catalogo['data']  = $lista;
        return response()->json($catalogo);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            switch (($request['catalogo'] + 0)) {
               
                case 1:
                    if ($request['ID_GUIAPREGUNTA'] == 0) {

                    } else {
                        $catalogo = catalogosguiaspsicoModel::findOrFail($request['ID_GUIAPREGUNTA']);
                        $catalogo->update($request->all());
                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
            }
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


}

<?php

namespace App\Http\Controllers\PSICO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//librerias
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Support\Facades\Storage;

//modelos
use App\modelos\reconocimientopsico\catcargos_psicoModel;
use App\modelos\reconocimientopsico\catconclusiones_psicoModel;
use App\modelos\reconocimientopsico\catdefiniciones_psicoModel;
use App\modelos\reconocimientopsico\catintroducciones_psicoModel;
use App\modelos\reconocimientopsico\catrecomendaciones_psicoModel;

class recpsicocatalogosrecController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('catalogos.psico.recpsicocatalogosrec');
    }

          /**
     * Display the specified resource.
     *
     * @param  int  $num_catalogo
     * @return \Illuminate\Http\Response
     */
    public function tablaCatalogoRec($num_catalogo)
    {
        switch (($num_catalogo + 0)) {
            case 1:
                $lista = catcargos_psicoModel::all();
                foreach ($lista as $key => $value) {
                    $value['CARGO'] = $value->CARGO;
                    $value['ID_CARGO_INFORME'] = $value->ID_CARGO_INFORME;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_cargoInforme();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_CARGO_INFORME . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_CARGO_INFORME . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
            break;
            case 2:
                $lista = catintroducciones_psicoModel::all();
                foreach ($lista as $key => $value) {
                    $value['INTRODUCCION'] = $value->INTRODUCCION;
                    $value['ID_INTRODUCCION_INFORME'] = $value->ID_INTRODUCCION_INFORME;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_introduccionInforme();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_INTRODUCCION_INFORME . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_INTRODUCCION_INFORME . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
            break;
            case 3:
                $lista = catdefiniciones_psicoModel::all();
                foreach ($lista as $key => $value) {
                    $value['CONCEPTO'] = $value->CONCEPTO;
                    $value['ID_DEFINICION_INFORME'] = $value->ID_DEFINICION_INFORME;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_definicionInforme();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_DEFINICION_INFORME . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_DEFINICION_INFORME . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
            break;
            case 4:
                $lista = catrecomendaciones_psicoModel::all();
                foreach ($lista as $key => $value) {
                    if ($value->NIVEL == 1) {
                        $value['NIVEL'] = 'Primer nivel';
                    } elseif ($value->NIVEL == 2) {
                        $value['NIVEL'] = 'Segundo nivel';
                    } elseif ($value->NIVEL == 3) {
                        $value['NIVEL'] = 'Tercer nivel';
                    }
                    $value['ID_RECOMENDACION_INFORME'] = $value->ID_RECOMENDACION_INFORME;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_recomendacionInforme();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_RECOMENDACION_INFORME . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_RECOMENDACION_INFORME . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
            break;
            case 5:
                $lista = catconclusiones_psicoModel::all();
                foreach ($lista as $key => $value) {
                    $value['NOMBRE'] = $value->NOMBRE;
                    $value['ID_CONCLUSION_INFORME'] = $value->ID_CONCLUSION_INFORME;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_conclusionInforme();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_CONCLUSION_INFORME . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_CONCLUSION_INFORME . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
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
                    if ($request['ID_CARGO_INFORME'] == 0) {
                        DB::statement('ALTER TABLE psicocat_cargos AUTO_INCREMENT=1');

                        $request["ACTIVO"] = 1;
                        $catalogo = catcargos_psicoModel::create($request->all());

                        $dato["msj"] = 'Información guardada correctamente';
                    } else {

                        $catalogo = catcargos_psicoModel::findOrFail($request['ID_CARGO_INFORME']);
                        $catalogo->update($request->all());

                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
                case 2:
                    if ($request['ID_INTRODUCCION_INFORME'] == 0) {
                        DB::statement('ALTER TABLE psicocat_introducciones AUTO_INCREMENT=1');

                        $request["ACTIVO"] = 1;
                        $catalogo = catintroducciones_psicoModel::create($request->all());

                        $dato["msj"] = 'Información guardada correctamente';
                    } else {

                        $catalogo = catintroducciones_psicoModel::findOrFail($request['ID_INTRODUCCION_INFORME']);
                        $catalogo->update($request->all());

                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
                case 3:
                    if ($request['ID_DEFINICION_INFORME'] == 0) {
                        DB::statement('ALTER TABLE psicocat_definiciones AUTO_INCREMENT=1');

                        $request["ACTIVO"] = 1;
                        $catalogo = catdefiniciones_psicoModel::create($request->all());

                        $dato["msj"] = 'Información guardada correctamente';
                    } else {

                        $catalogo = catdefiniciones_psicoModel::findOrFail($request['ID_DEFINICION_INFORME']);
                        $catalogo->update($request->all());

                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
                case 4:
                    if ($request['ID_RECOMENDACION_INFORME'] == 0) {
                        DB::statement('ALTER TABLE psicocat_recomendaciones AUTO_INCREMENT=1');

                        $request["ACTIVO"] = 1;
                        $catalogo = catrecomendaciones_psicoModel::create($request->all());

                        $dato["msj"] = 'Información guardada correctamente';
                    } else {

                        $catalogo = catrecomendaciones_psicoModel::findOrFail($request['ID_RECOMENDACION_INFORME']);
                        $catalogo->update($request->all());

                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
                case 5:
                    if ($request['ID_CONCLUSION_INFORME'] == 0) {
                        DB::statement('ALTER TABLE psicocat_conclusiones AUTO_INCREMENT=1');

                        $request["ACTIVO"] = 1;
                        $catalogo = catconclusiones_psicoModel::create($request->all());

                        $dato["msj"] = 'Información guardada correctamente';
                    } else {

                        $catalogo = catconclusiones_psicoModel::findOrFail($request['ID_CONCLUSION_INFORME']);
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

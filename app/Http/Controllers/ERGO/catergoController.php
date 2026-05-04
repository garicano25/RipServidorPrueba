<?php

namespace App\Http\Controllers\ERGO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Image;
use Carbon\Carbon;
use DateTime;
use DB;
use Artisan;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use ZipArchive;

/// MODEL 

use App\modelos\reconocimientoergo\catergo_regimencontractualModel;
use App\modelos\reconocimientoergo\catergo_jornada;
use App\modelos\reconocimientoergo\catergo_turnoModel;
use App\modelos\reconocimientoergo\catergo_introduccionModel;
use App\modelos\reconocimientoergo\catergo_definicionesModel;
use App\modelos\reconocimientoergo\catergo_recomendacionesModel;
use App\modelos\reconocimientoergo\catergo_conclusionModel;


class catergoController extends Controller
{



    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Reportes,ApoyoTecnico,Financiero,Cadista,Externo');
        // $this->middleware('roles:Superusuario,Administrador,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,ApoyoTecnico,Reportes');

        // $this->middleware('asignacionUser')->only('store');
    }



    public function index()
    { //vista RECONOCIMIENTO SENSORIAL

       

        return view('catalogos.ergo.catalogo.recergocatalogo');
    }



    public function ergoconsultacatalogo($num_catalogo)
    {
        switch (($num_catalogo + 0)) {
          

            case 1:
                $lista = catergo_regimencontractualModel::all();

                foreach ($lista as $key => $value) {
                    $value['ID_REGIMEN_CONTRACTUAL'] = $value->ID_REGIMEN_CONTRACTUAL;
                    $value['NOMBRE_REGIMEN_CONTRACTUAL'] = $value->NOMBRE_REGIMEN_CONTRACTUAL;
                    $value['ACTIVO'] = $value->ACTIVO;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_cat_regioncontractual();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_REGIMEN_CONTRACTUAL . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_REGIMEN_CONTRACTUAL . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;

            case 2:
                $lista = catergo_jornada::all();

                foreach ($lista as $key => $value) {
                    $value['ID_JORNADA'] = $value->ID_JORNADA;
                    $value['NOMBRE_JORNADA'] = $value->NOMBRE_JORNADA;
                    $value['ACTIVO'] = $value->ACTIVO;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_cat_jornada();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_JORNADA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_JORNADA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;

            case 3:
                $lista = catergo_turnoModel::all();

                foreach ($lista as $key => $value) {
                    $value['ID_TURNO'] = $value->ID_TURNO;
                    $value['NOMBRE_TURNO'] = $value->NOMBRE_TURNO;
                    $value['ACTIVO'] = $value->ACTIVO;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_cat_turno();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_TURNO . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_TURNO . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;

            case 4:
                $lista = catergo_introduccionModel::all();

                foreach ($lista as $key => $value) {
                    $value['ID_INTRODUCCION'] = $value->ID_INTRODUCCION;
                    $value['NOMBRE_INTRODUCCION'] = $value->NOMBRE_INTRODUCCION;
                    $value['ACTIVO'] = $value->ACTIVO;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_cat_introduccion();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_INTRODUCCION . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_INTRODUCCION . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;

            case 5:
                $lista = catergo_definicionesModel::all();

                foreach ($lista as $key => $value) {
                    $value['ID_DEFINICIONES'] = $value->ID_DEFINICIONES;
                    $value['CONCEPTO_DEFINICION'] = $value->CONCEPTO_DEFINICION;
                    $value['DESCRIPCION_DEFINICION'] = $value->DESCRIPCION_DEFINICION;
                    $value['FUENTE_DEFINICION'] = $value->FUENTE_DEFINICION;
                    $value['USO_DEFINICIONES'] = $value->USO_DEFINICIONES;
                    
                    $value['ACTIVO'] = $value->ACTIVO;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_cat_definiciones();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_DEFINICIONES . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_DEFINICIONES . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 6:
                $lista = catergo_recomendacionesModel::all();

                foreach ($lista as $key => $value) {
                    $value['ID_RECOMENDACIONES'] = $value->ID_RECOMENDACIONES;
                    $value['TIPO_RECOMENDACIONES'] = $value->TIPO_RECOMENDACIONES;
                    $value['DESCRIPCION_RECOMENDACIONES'] = $value->DESCRIPCION_RECOMENDACIONES;

                    $value['USO_RECOMENDACIONES'] = $value->USO_RECOMENDACIONES;

                    

                    $value['ACTIVO'] = $value->ACTIVO;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_cat_recomendaciones();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_RECOMENDACIONES . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_RECOMENDACIONES . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;


            case 7:
                $lista = catergo_conclusionModel::all();

                foreach ($lista as $key => $value) {
                    $value['ID_CONCLUSION'] = $value->ID_CONCLUSION;
                    $value['NOMBRE_CONCLUSION'] = $value->NOMBRE_CONCLUSION;
                    $value['ACTIVO'] = $value->ACTIVO;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_cat_conclusion();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_CONCLUSION . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_CONCLUSION . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
          
            case 13:
                $lista = catpartexpuestaeppModel::all();

                foreach ($lista as $key => $value) {
                    $value['ID_PARTE_EXPUESTO'] = $value->ID_PARTE_EXPUESTO;
                    $value['NOMBRE_PARTE'] = $value->NOMBRE_PARTE;

                    $value['ACTIVO'] = $value->ACTIVO;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_cat_partesexpuesta();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_PARTE_EXPUESTO . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_PARTE_EXPUESTO . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
        }

        // Respuesta
        $catalogo['data']  = $lista;
        return response()->json($catalogo);
    }



    public function ergocatalogodesactiva($catalogo, $registro, $estado)
    {
        try {
            switch (($catalogo + 0)) {
                case 1:
                    $tabla = catergo_regimencontractualModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 2:
                    $tabla = catergo_jornada::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 3:
                    $tabla = catergo_turnoModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 4:
                    $tabla = catergo_introduccionModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 5:
                    $tabla = catergo_definicionesModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 6:
                    $tabla = catergo_recomendacionesModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 7:
                    $tabla = catergo_conclusionModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
            }

            if ($estado == 0) {
                // Mensaje
                $dato["msj"] = 'Registro desactivado correctamente';
            } else {
                // Mensaje
                $dato["msj"] = 'Registro activado correctamente';
            }

            // Respuesta
            return response()->json($dato);
        } catch (Exception $e) {
            // Respuesta
            $dato["msj"] = 'Error al modificar la información ' . $e->getMessage();
            return response()->json($dato);
        }
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
                    if ($request['ID_REGIMEN_CONTRACTUAL'] == 0) {
                        $catalogo = catergo_regimencontractualModel::create($request->all());
                    } else {
                        $catalogo = catergo_regimencontractualModel::findOrFail($request['ID_REGIMEN_CONTRACTUAL']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 2:
                    if ($request['ID_JORNADA'] == 0) {
                        $catalogo = catergo_jornada::create($request->all());
                    } else {
                        $catalogo = catergo_jornada::findOrFail($request['ID_JORNADA']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 3:
                    if ($request['ID_TURNO'] == 0) {
                        $catalogo = catergo_turnoModel::create($request->all());
                    } else {
                        $catalogo = catergo_turnoModel::findOrFail($request['ID_TURNO']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 4:
                    if ($request['ID_INTRODUCCION'] == 0) {
                        $catalogo = catergo_introduccionModel::create($request->all());
                    } else {
                        $catalogo = catergo_introduccionModel::findOrFail($request['ID_INTRODUCCION']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 5:
                    if ($request['ID_DEFINICIONES'] == 0) {
                        $catalogo = catergo_definicionesModel::create($request->all());
                    } else {
                        $catalogo = catergo_definicionesModel::findOrFail($request['ID_DEFINICIONES']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 6:
                    if ($request['ID_RECOMENDACIONES'] == 0) {
                        $catalogo = catergo_recomendacionesModel::create($request->all());
                    } else {
                        $catalogo = catergo_recomendacionesModel::findOrFail($request['ID_RECOMENDACIONES']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 7:
                    if ($request['ID_CONCLUSION'] == 0) {
                        $catalogo = catergo_conclusionModel::create($request->all());
                    } else {
                        $catalogo = catergo_conclusionModel::findOrFail($request['ID_CONCLUSION']);
                        $catalogo->update($request->all());
                    }
                    break;

            

              
            }

            $dato["code"] = 1;
            $dato["msj"] = 'Información guardada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            return response()->json('Error al guardar informacion');
        }
    }



}

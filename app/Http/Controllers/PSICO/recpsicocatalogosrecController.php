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
      //
      public function __construct()
      {
          $this->middleware('auth');
          // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,ApoyoTecnico,Reportes,Externo');
          $this->middleware('roles:Superusuario,Administrador,Coordinador,Psicólogo');
      }
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
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(4, ' . $value->ID_CARGO_INFORME . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(4, ' . $value->ID_CARGO_INFORME . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
            break;
            case 2:
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
            case 3:
                $lista = catrecomendaciones_psicoModel::all();
                foreach ($lista as $key => $value) {
                    if ($value->CATEGORIA == 1) {
                        $value['CATEGORIA'] = 'Acontecimientos traumáticos severos';
                    } elseif ($value->CATEGORIA == 2) {
                        $value['CATEGORIA'] = 'Ambiente de trabajo';
                    } elseif ($value->CATEGORIA == 3) {
                        $value['CATEGORIA'] = 'Factores propios de la actividad';
                    } elseif ($value->CATEGORIA == 4) {
                        $value['CATEGORIA'] = 'Organización del tiempo de trabajo';
                    } elseif ($value->CATEGORIA == 5) {
                        $value['CATEGORIA'] = 'Liderazgo y relaciones en el trabajo';
                    } elseif ($value->CATEGORIA == 6) {
                        $value['CATEGORIA'] = 'Entorno organizacional';
                    }

                    if ($value->NIVELRIESGO == 1) {
                        $value['NIVELRIESGO'] = 'Riesgo muy alto';
                    } elseif ($value->NIVELRIESGO == 2) {
                        $value['NIVELRIESGO'] = 'Riesgo alto';
                    } elseif ($value->NIVELRIESGO == 3) {
                        $value['NIVELRIESGO'] = 'Riesgo medio';
                    } elseif ($value->NIVELRIESGO == 4) {
                        $value['NIVELRIESGO'] = 'Riesgo bajo';
                    } elseif ($value->NIVELRIESGO == 5) {
                        $value['NIVELRIESGO'] = 'Riesgo nulo';
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
            case 4:
                $lista = catconclusiones_psicoModel::all();
                foreach ($lista as $key => $value) {
                    if ($value->DOMINIO == 1) {
                        $value['DOMINIO'] = 'Acontecimientos traumáticos severos';
                    } elseif ($value->DOMINIO == 2) {
                        $value['DOMINIO'] = 'Ambiente de trabajo';
                    } elseif ($value->DOMINIO == 3) {
                        $value['DOMINIO'] = 'Factores propios de la actividad';
                    } elseif ($value->DOMINIO == 4) {
                        $value['DOMINIO'] = 'Organización del tiempo de trabajo';
                    } elseif ($value->DOMINIO == 5) {
                        $value['DOMINIO'] = 'Liderazgo y relaciones en el trabajo';
                    } elseif ($value->DOMINIO == 6) {
                        $value['DOMINIO'] = 'Entorno organizacional';
                    } elseif ($value->DOMINIO == 7) {
                        $value['DOMINIO'] = 'Condiciones del ambiente de trabajo';
                    } elseif ($value->DOMINIO == 8) {
                        $value['DOMINIO'] = 'Carga de trabajo';
                    } elseif ($value->DOMINIO == 9) {
                        $value['DOMINIO'] = 'Falta de control sobre el trabajo';
                    } elseif ($value->DOMINIO == 10) {
                        $value['DOMINIO'] = 'Jornada de trabajo';
                    } elseif ($value->CATEGORIA == 11) {
                        $value['DOMINIO'] = 'Interferencia trabajo-familia';
                    } elseif ($value->DOMINIO == 12) {
                        $value['DOMINIO'] = 'Liderazgo';
                    } elseif ($value->DOMINIO == 13) {
                        $value['DOMINIO'] = 'Relaciones en el trabajo';
                    }elseif ($value->DOMINIO == 14) {
                        $value['DOMINIO'] = 'Violencia';
                    }elseif ($value->DOMINIO == 15) {
                        $value['DOMINIO'] = 'Reconocimiento del desempeño';
                    }elseif ($value->DOMINIO == 16) {
                        $value['DOMINIO'] = 'Insuficiente sentido de pertenencia e inestabilidad';
                    }
                    

                    if ($value->NIVEL == 1) {
                        $value['NIVEL'] = 'Riesgo muy alto';
                    } elseif ($value->NIVEL == 2) {
                        $value['NIVEL'] = 'Riesgo alto';
                    } elseif ($value->NIVEL == 3) {
                        $value['NIVEL'] = 'Riesgo medio';
                    } elseif ($value->NIVEL == 4) {
                        $value['NIVEL'] = 'Riesgo bajo';
                    } elseif ($value->NIVEL == 5) {
                        $value['NIVEL'] = 'Riesgo nulo';
                    }elseif ($value->NIVEL == 6) {
                        $value['NIVEL'] = 'Acontecimiento traumático severo';
                    } elseif ($value->NIVEL == 7) {
                        $value['NIVEL'] = 'No existe acontecimiento traumático severo';
                    }
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
                case 3:
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
                case 4:
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

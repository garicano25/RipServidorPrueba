<?php

namespace App\Http\Controllers\recsensorialquimicos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorialquimicos\catsustanciaModel;
use App\modelos\recsensorialquimicos\catsustanciacomponenteModel;
use App\modelos\recsensorialquimicos\catestadofisicosustanciaModel;
use App\modelos\recsensorialquimicos\catunidadmedidasustaciaModel;
use App\modelos\recsensorialquimicos\catvolatilidadModel;
use App\modelos\recsensorialquimicos\catviaingresoorganismoModel;
use App\modelos\recsensorialquimicos\catcategoriapeligrosaludModel;
use App\modelos\recsensorialquimicos\catgradoriesgosaludModel;
use App\modelos\recsensorialquimicos\catSustanciasQuimicasModel;
use App\modelos\recsensorialquimicos\catHojaSeguridadSustanciaQuimicaModel;
use App\modelos\recsensorialquimicos\sustanciaQuimicaEntidadModel;
use App\modelos\recsensorialquimicos\catUnidadMedidaModel;
use App\modelos\recsensorialquimicos\catConnotacionModel;
use App\modelos\recsensorialquimicos\catEntidadesModel;




use DB;
use Illuminate\Support\Facades\Storage;


use App\Services\PayUService\Exception;

class recsensorialquimicoscatalogosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo');
        $this->middleware('roles:Superusuario,Administrador');
    }








    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // catalogos
        $catestadofisicosustancia = catestadofisicosustanciaModel::where('catestadofisicosustancia_activo', 1)->get();
        $catunidadmedidasustacia = catunidadmedidasustaciaModel::where('catunidadmedidasustacia_activo', 1)->get();
        $catvolatilidad = catvolatilidadModel::where('catvolatilidad_activo', 1)->get();
        $catviaingresoorganismo = catviaingresoorganismoModel::where('catviaingresoorganismo_activo', 1)->get();
        $catcategoriapeligrosalud = catcategoriapeligrosaludModel::where('catcategoriapeligrosalud_activo', 1)->get();
        $catgradoriesgosalud = catgradoriesgosaludModel::where('catgradoriesgosalud_activo', 1)->orderby('catgradoriesgosalud_ponderacion', 'ASC')->get();
        $catSustanciasQuimicas = catSustanciasQuimicasModel::where('ACTIVO', 1)->get();
        $catEntidades = catEntidadesModel::where('ACTIVO', 1)->get();



        // vista
        return view('catalogos.recsensorialquimicos.recsensorialquimicos_catalogos', compact('catestadofisicosustancia', 'catunidadmedidasustacia', 'catvolatilidad', 'catviaingresoorganismo', 'catcategoriapeligrosalud', 'catgradoriesgosalud', 'catSustanciasQuimicas', 'catEntidades'));
    }


    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function quimicoscatestadofisico()
    {
        try {
            $catestadofisicosustancia = catestadofisicosustanciaModel::where('catestadofisicosustancia_activo', 1)->get();

            $opciones = '<option value=""></option>
                        <option value="ND">ND</option>';

            foreach ($catestadofisicosustancia as $key => $value) {
                $opciones .= '<option value="' . $value->id . '">' . $value->catestadofisicosustancia_estado . '</option>';
            }

            // Respuesta
            $dato['catestadofisico']  = $opciones;
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['data'] = 0;
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $catsustancia_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialquimicoscatalogopdf($catsustancia_id)
    {
        try {
            $pdf = catsustanciaModel::findOrFail($catsustancia_id);
            return Storage::response($pdf->catsustancia_hojaseguridadpdf);
        } catch (Exception $e) {
            // Respuesta
            return 'Error al consultar PDF, intentelo de nuevo';
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $num_catalogo
     * @return \Illuminate\Http\Response
     */
    public function recsensorialquimicoscatalogostabla($num_catalogo)
    {
        $numero_registro = 1;
        try {
            switch (($num_catalogo + 0)) {
                case 0: //CATALOGO DE SUSTANCIAS QUIMICAS
                    $catalogo = DB::select('SELECT cat.ID_SUSTANCIA_QUIMICA,
                                                cat.SUSTANCIA_QUIMICA,
                                                cat.ALTERACION_EFECTO,
                                                cat.PM, 
                                                cat.NUM_CAS,
                                                cat.ACTIVO,
                                                cat.TIPO_CLASIFICACION,
                                                cat.CATEGORIA_PELIGRO_ID,
                                                cat.GRADO_RIESGO_ID,
                                                cat.CLASIFICACION_RIESGO,
                                                cat.VIA_INGRESO, 
                                                COUNT(sus.ID_SUSTANCIA_QUIMICA_ENTIDAD) as TOTAL
                                                FROM catsustancias_quimicas as cat
                                                LEFT JOIN sustanciaQuimicaEntidad sus 
                                                        ON sus.SUSTANCIA_QUIMICA_ID = cat.ID_SUSTANCIA_QUIMICA
                                                        AND cat.ACTIVO = 1
                                                GROUP BY cat.ID_SUSTANCIA_QUIMICA,
                                                cat.SUSTANCIA_QUIMICA,
                                                cat.ALTERACION_EFECTO,
                                                cat.PM, 
                                                cat.NUM_CAS,
                                                cat.ACTIVO,
                                                cat.TIPO_CLASIFICACION,
                                                cat.CATEGORIA_PELIGRO_ID,
                                                cat.GRADO_RIESGO_ID,
                                                cat.CLASIFICACION_RIESGO,
                                                cat.VIA_INGRESO
                                                ');

                    // crear campos NOMBRE Y ESTADO
                    foreach ($catalogo as $key => $value) {
                        // Registro
                        $value->numero_registro = $numero_registro;
                        $numero_registro += 1;



                        $value->total_registro = '<span class="badge badge-success p-2" style="font-size: 15px">' . $value->TOTAL . '</span>';


                        $value->boton_ver = '<button type="button" class="btn btn-info btn-circle VER" onclick="ver_sustancia_quimico();"><i class="fa fa-eye" aria-hidden="true"></i></button>';

                        // Checkbox estado
                        if ($value->ACTIVO == 1) {
                            $value->CheckboxEstado = '<div class="switch"><label><input type="checkbox" checked onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->ID_SUSTANCIA_QUIMICA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        } else {
                            $value->CheckboxEstado = '<div class="switch"><label><input type="checkbox" onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->ID_SUSTANCIA_QUIMICA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        }

                        // Valida perfil
                        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo');
                        if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Reconocimiento', 'CoordinadorHI'])) {
                            $value->perfil = 1;
                            $value->boton_editar = '<button type="button" class="btn btn-danger btn-circle" onclick="selecciona_sustancia_quimico();"><i class="fa fa-pencil"></i></button>';
                        } else {
                            $value->perfil = 0;
                            $value->boton_editar = '<button type="button" class="btn btn-secondary btn-circle" ><i class="fa fa-ban" aria-hidden="true"></i></button>';
                        }
                    }


                    break;
                case 1: // CATALOGO SUSTANCIAS
                    // $lista = catsustanciaModel::all();
                    // $catalogo = catsustanciaModel::with([ 'catestadofisicosustancia', 'catvolatilidad', 'catviaingresoorganismo', 'catcategoriapeligrosalud', 'catgradoriesgosalud'])
                    //                             // ->where('recsensorial_id', $recsensorial_id)
                    //                             ->orderBy('id', 'ASC')
                    //                             ->get();


                    $catalogo = DB::select('SELECT hoja.id , 
                                                    hoja.catsustancia_nombre, 
                                                    hoja.catsustancia_fabricante,
                                                    hoja.catsustancia_hojaseguridadpdf, 
                                                    hoja.catsustancia_activo,
                                                    IFNULL(GROUP_CONCAT(CONCAT("<li>", sus.SUSTANCIA_QUIMICA) SEPARATOR "</li>"), "Sin componente") AS sustancias_quimicas,
                                                    estado.catestadofisicosustancia_estado,
                                                    vol.catvolatilidad_tipo,
                                                    ingreso.catviaingresoorganismo_viaingreso,
                                                    peligro.id AS peligroID,
                                                    grado.id AS gradoID,
                                                    hoja.catestadofisicosustancia_id,
                                                    hoja.catvolatilidad_id,
                                                    hoja.catviaingresoorganismo_id,
                                                    hoja.catcategoriapeligrosalud_id,
                                                    hoja.catgradoriesgosalud_id,
                                                    hoja.catsustancia_solidoTipo,
                                                    hoja.catTipoClasificacion,
                                                    hoja.catClasificacionRiesgo,
                                                    hoja.catsustancia_puntoEbullicion,
                                                    hoja.catTemOperacion
                                                FROM catsustancia hoja
                                                LEFT JOIN catHojasSeguridad_SustanciasQuimicas relacion ON relacion.HOJA_SEGURIDAD_ID = hoja.id
                                                LEFT JOIN catsustancias_quimicas sus ON sus.ID_SUSTANCIA_QUIMICA = relacion.SUSTANCIA_QUIMICA_ID
                                                LEFT JOIN catestadofisicosustancia estado ON estado.id = hoja.catestadofisicosustancia_id
                                                LEFT JOIN catvolatilidad vol ON vol.id = hoja.catvolatilidad_id
                                                LEFT JOIN catviaingresoorganismo ingreso ON ingreso.id = hoja.catviaingresoorganismo_id
                                                LEFT JOIN catcategoriapeligrosalud peligro ON peligro.id = hoja.catcategoriapeligrosalud_id
                                                LEFT JOIN catgradoriesgosalud grado ON grado.id = hoja.catgradoriesgosalud_id
                                                GROUP BY hoja.id, 
                                                        hoja.catsustancia_nombre, 
                                                    hoja.catsustancia_fabricante,
                                                    hoja.catsustancia_hojaseguridadpdf, 
                                                    hoja.catsustancia_activo,
                                                    estado.catestadofisicosustancia_estado,
                                                    vol.catvolatilidad_tipo,
                                                    ingreso.catviaingresoorganismo_viaingreso,
                                                    peligro.id,
                                                    grado.id,
                                                    hoja.catestadofisicosustancia_id,
                                                    hoja.catvolatilidad_id,
                                                    hoja.catviaingresoorganismo_id,
                                                    hoja.catcategoriapeligrosalud_id,
                                                    hoja.catgradoriesgosalud_id,
                                                    hoja.catsustancia_solidoTipo,
                                                    hoja.catTipoClasificacion,
                                                    hoja.catClasificacionRiesgo,
                                                    hoja.catsustancia_puntoEbullicion,
                                                    hoja.catTemOperacion
                                                ORDER BY hoja.id,
                                                    hoja.catsustancia_nombre, 
                                                    hoja.catsustancia_fabricante,
                                                    hoja.catsustancia_hojaseguridadpdf, 
                                                    hoja.catsustancia_activo,
                                                    estado.catestadofisicosustancia_estado,
                                                    vol.catvolatilidad_tipo,
                                                    ingreso.catviaingresoorganismo_viaingreso,
                                                    peligro.id,
                                                    grado.id,
                                                    hoja.catestadofisicosustancia_id,
                                                    hoja.catvolatilidad_id,
                                                    hoja.catviaingresoorganismo_id,
                                                    hoja.catcategoriapeligrosalud_id,
                                                    hoja.catgradoriesgosalud_id,
                                                    hoja.catsustancia_solidoTipo,
                                                    hoja.catTipoClasificacion,
                                                    hoja.catClasificacionRiesgo,
                                                    hoja.catsustancia_puntoEbullicion,
                                                    hoja.catTemOperacion');

                    // crear campos NOMBRE Y ESTADO
                    foreach ($catalogo as $key => $value) {
                        // Registro
                        $value->numero_registro = $numero_registro;
                        $numero_registro += 1;

                        // // componentes
                        // $componentes = DB::select('SELECT sus.SUSTANCIA_QUIMICA
                        //                             FROM catHojasSeguridad_SustanciasQuimicas relacion
                        //                             LEFT JOIN catsustancia hoja ON hoja.id = relacion.HOJA_SEGURIDAD_ID
                        //                             LEFT JOIN catsustancias_quimicas sus ON sus.ID_SUSTANCIA_QUIMICA = relacion.SUSTANCIA_QUIMICA_ID
                        //                             WHERE relacion.HOJA_SEGURIDAD_ID = 674 ');


                        // $lista = "";
                        //     foreach ($componentes as $key => $val) {
                        //         $lista .= "<li>" . $val->SUSTANCIA_QUIMICA . "</li>";
                        //     }
                        // $value['componentes'] = $componentes;

                        // campo PDF
                        if ($value->catsustancia_hojaseguridadpdf) {
                            $value->boton_pdf = '<button type="button" class="btn btn-info btn-circle" onclick="mostrar_pdf();"><i class="fa fa-file-pdf-o"></i></button>';
                        } else {
                            $value->boton_pdf = '<button type="button" class="btn btn-secondary btn-circle" onclick="mostrar_pdf();"><i class="fa fa-warning"></i></button>';
                        }

                        $value->boton_editar = '<button type="button" class="btn btn-danger btn-circle" onclick="selecciona_sustancia();"><i class="fa fa-pencil"></i></button>';

                        // Checkbox estado
                        if ($value->catsustancia_activo == 1) {
                            $value->CheckboxEstado = '<div class="switch"><label><input type="checkbox" checked onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        } else {
                            $value->CheckboxEstado = '<div class="switch"><label><input type="checkbox" onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        }

                        // Valida perfil
                        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo');
                        if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Reconocimiento', 'CoordinadorHI'])) {
                            $value->perfil = 1;
                        } else {
                            $value->perfil = 0;
                        }
                    }
                    break;
                case 2: // CATALOGO ESTADO FISICO
                    $catalogo = catestadofisicosustanciaModel::orderBy('id', 'ASC')->get();

                    // crear campos NOMBRE Y ESTADO
                    foreach ($catalogo as $key => $value) {
                        // Registro
                        $value['numero_registro'] = $numero_registro;
                        $numero_registro += 1;

                        $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="selecciona_catestadofisico();"><i class="fa fa-pencil"></i></button>';

                        // Checkbox estado
                        if ($value->catestadofisicosustancia_activo == 1) {
                            $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        } else {
                            $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        }
                    }
                    break;
                case 3: // CATALOGO VOLATILIDAD
                    $catalogo = catvolatilidadModel::orderBy('id', 'ASC')->get();

                    // crear campos NOMBRE Y ESTADO
                    foreach ($catalogo as $key => $value) {
                        // Registro
                        $value['numero_registro'] = $numero_registro;
                        $numero_registro += 1;

                        $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="selecciona_catvolatilidad();"><i class="fa fa-pencil"></i></button>';

                        // Checkbox estado
                        if ($value->catvolatilidad_activo == 1) {
                            $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        } else {
                            $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        }
                    }
                    break;
                case 4: // CATALOGO VIA DE INGRESO AL ORGANISMO
                    $catalogo = catviaingresoorganismoModel::orderBy('id', 'ASC')->get();

                    // crear campos NOMBRE Y ESTADO
                    foreach ($catalogo as $key => $value) {
                        // Registro
                        $value['numero_registro'] = $numero_registro;
                        $numero_registro += 1;

                        $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="selecciona_catviaingreso();"><i class="fa fa-pencil"></i></button>';

                        // Checkbox estado
                        if ($value->catviaingresoorganismo_activo == 1) {
                            $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        } else {
                            $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        }
                    }
                    break;
                case 5: // CATALOGO CATEGORIA DE PELIGRP
                    $catalogo = catcategoriapeligrosaludModel::orderBy('id', 'ASC')->get();

                    // crear campos NOMBRE Y ESTADO
                    foreach ($catalogo as $key => $value) {
                        $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="selecciona_catpeligro();"><i class="fa fa-pencil"></i></button>';

                        // Checkbox estado
                        if ($value->catcategoriapeligrosalud_activo == 1) {
                            $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        } else {
                            $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        }
                    }
                    break;
                case 6: // CATALOGO GRADO DE RIESGO
                    $catalogo = catgradoriesgosaludModel::orderBy('id', 'ASC')->get();

                    // crear campos NOMBRE Y ESTADO
                    foreach ($catalogo as $key => $value) {
                        $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="selecciona_catgradoriesgo();"><i class="fa fa-pencil"></i></button>';

                        // Checkbox estado
                        if ($value->catgradoriesgosalud_activo == 1) {
                            $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        } else {
                            $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        }
                    }
                    break;

                case 8: // CATALOGO UNIDAD DE MEDIDA
                    $catalogo = catUnidadMedidaModel::orderBy('ID_UNIDAD_MEDIDA', 'ASC')->get();

                    // crear campos NOMBRE Y ESTADO
                    foreach ($catalogo as $key => $value) {
                        // Registro
                        $value['numero_registro'] = $numero_registro;
                        $numero_registro += 1;

                        $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="selecciona_catvolatilidad();"><i class="fa fa-pencil"></i></button>';

                        // Checkbox estado
                        if ($value->ACTIVO == 1) {
                            $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->ID_UNIDAD_MEDIDA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        } else {
                            $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->ID_UNIDAD_MEDIDA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        }
                    }
                    break;
                case 9: // CATALOGO DE CONNOTACIONES
                    $catalogo = DB::select('SELECT e.ENTIDAD, c.ABREVIATURA, c.DESCRIPCION, c.ACTIVO, c.ID_CONNOTACION, e.ID_ENTIDAD
                                            FROM catConnotaciones c
                                            LEFT JOIN catEntidades e ON e.ID_ENTIDAD = c.ENTIDAD_ID
                                            WHERE c.ACTIVO = 1');

                    // crear campos NOMBRE Y ESTADO
                    foreach ($catalogo as $key => $value) {


                        $value->boton_editar = '<button type="button" class="btn btn-danger btn-circle" onclick="selecciona_catConnotacion();"><i class="fa fa-pencil"></i></button>';

                        // Checkbox estado
                        if ($value->ACTIVO == 1) {
                            $value->CheckboxEstado = '<div class="switch"><label><input type="checkbox" checked onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->ID_CONNOTACION . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        } else {
                            $value->CheckboxEstado = '<div class="switch"><label><input type="checkbox" onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->ID_CONNOTACION . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        }
                    }
                    break;
                case 10: // CATALOGO DE ENTIDADES
                    $catalogo = catEntidadesModel::orderBy('ID_ENTIDAD', 'ASC')->get();

                    // crear campos NOMBRE Y ESTADO
                    foreach ($catalogo as $key => $value) {


                        $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="selecciona_catEntidades();"><i class="fa fa-pencil"></i></button>';

                        // Checkbox estado
                        if ($value->ACTIVO == 1) {
                            $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->ID_ENTIDAD . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        } else {
                            $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="cambia_estado_registro(' . $num_catalogo . ', ' . $value->ID_ENTIDAD . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        }
                    }
                    break;
            }

            // Respuesta
            $dato['data']  = $catalogo;
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['data'] = 0;
            return response()->json($dato);
        }
    }


    public function tablasustanciasEntidad($SUSTANCIA_QUIMICA_ID)
    {
        try {
            $catalogo = DB::select('SELECT e.ENTIDAD, e.DESCRIPCION, s.*
                                FROM sustanciaQuimicaEntidad s
                                LEFT JOIN catEntidades e ON e.ID_ENTIDAD = s.ENTIDAD_ID
                                LEFT JOIN catsustancias_quimicas q ON q.ID_SUSTANCIA_QUIMICA = s.SUSTANCIA_QUIMICA_ID
                                WHERE q.ID_SUSTANCIA_QUIMICA = ?', [$SUSTANCIA_QUIMICA_ID]);

            // crear campos NOMBRE Y ESTADO
            foreach ($catalogo as $key => $value) {
                $connotacionesText = '';

                $connotacionesArray = json_decode($value->CONNOTACION);

                // Comprueba si json_decode devuelve un array
                if (is_array($connotacionesArray)) {
                    $len = count($connotacionesArray);
                    $num = 1;
                    foreach ($connotacionesArray as $val) {
                        $conn = DB::select('SELECT ABREVIATURA
                                        FROM catConnotaciones
                                        WHERE ID_CONNOTACION = ?', [intval($val)]);

                        if ($num == $len) {
                            $connotacionesText .= $conn[0]->ABREVIATURA;
                        } else {
                            $connotacionesText .= $conn[0]->ABREVIATURA . ', ';
                        }

                        $num++;
                    }
                } else {
                    // Manejo en caso de que $value->CONNOTACION no sea un JSON válido o sea null
                    $connotacionesText = 'N/A';
                }

                $value->listaConnotaciones = $connotacionesText;

                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Reconocimiento', 'CoordinadorHI'])) {
                    $value->acciones = '<button type="button" class="btn btn-warning btn-circle EDITAR" onclick="seleccionar_sustanciaQuimicaEntidad();"><i class="fa fa-pencil"></i></button> <button type="button" class="btn btn-danger btn-circle ELIMINAR" onclick="eliminar_sustanciaQuimicaEntidad();"><i class="fa fa-trash"></i></button>';
                } else {
                    $value->acciones = '<button type="button" class="btn btn-secondary btn-circle" ><i class="fa fa-ban" aria-hidden="true"></i></button>';
                }
            }

            // Respuesta
            $dato['data']  = $catalogo;
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['data'] = 0;
            return response()->json($dato);
        }
    }



    public function listaConnotaciones($ID_ENTIDAD)
    {
        try {


            $opciones_select = '<option value="">&nbsp;</option>';

            $connotaciones = catConnotacionModel::where('ENTIDAD_ID', $ID_ENTIDAD)->get();

            foreach ($connotaciones as $key => $value) {

                $opciones_select .= '<option value="' . $value->ID_CONNOTACION . '"  data-descripcion="'.$value->DESCRIPCION.'">' . $value->ABREVIATURA . '</option>';
                
            }

            // // respuesta
            $dato['opciones'] = $opciones_select;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);


        } catch (Exception $e) {

            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['opciones'] = $opciones_select;
            return response()->json($dato);
        }
    }



    public function mostarConnotacionesSelccionadas($ID_ENTIDAD, $ID_SUSTANCIA_ENTIDAD)
    {
        try {


            $opciones_select = '<option value="">&nbsp;</option>';

            $connotaciones = catConnotacionModel::where('ENTIDAD_ID', $ID_ENTIDAD)->get();
            $connotacioneSelect = sustanciaQuimicaEntidadModel::where('ID_SUSTANCIA_QUIMICA_ENTIDAD', $ID_SUSTANCIA_ENTIDAD)->get();


            foreach ($connotaciones as $key => $value) {

                $opciones_select .= '<option value="' . $value->ID_CONNOTACION . '"  data-descripcion="' . $value->DESCRIPCION . '">' . $value->ABREVIATURA . '</option>';
            }

            // // respuesta
            $dato['opciones'] = $opciones_select;
            $dato['connotaciones'] = $connotacioneSelect[0]->CONNOTACION;

            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {

            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['opciones'] = $opciones_select;
            return response()->json($dato);
        }
    }


    public function inforCartaEntidades($ID_SUSTANCIA_QUIMICA)
    {
        try {

            $entidades = DB::select('SELECT *
                                FROM sustanciaQuimicaEntidad s
                                WHERE SUSTANCIA_QUIMICA_ID = ?', [$ID_SUSTANCIA_QUIMICA]);


            foreach ($entidades as $key => $value) {
                $connotacionesText = '';

                $connotacionesArray = json_decode($value->CONNOTACION);

                // Comprueba si json_decode devuelve un array
                if (is_array($connotacionesArray)) {
                    $len = count($connotacionesArray);
                    $num = 1;
                    foreach ($connotacionesArray as $val) {
                        $conn = DB::select('SELECT ABREVIATURA
                                        FROM catConnotaciones
                                        WHERE ID_CONNOTACION = ?', [intval($val)]);

                        if ($num == $len) {
                            $connotacionesText .= $conn[0]->ABREVIATURA;
                        } else {
                            $connotacionesText .= $conn[0]->ABREVIATURA . ', ';
                        }

                        $num++;
                    }
                } else {
                    // Manejo en caso de que $value->CONNOTACION no sea un JSON válido o sea null
                    $connotacionesText = 'N/A';
                }

                $value->listaConnotaciones = $connotacionesText;

            }
           


            // Respuesta
            $dato['data']  = $entidades;
            return response()->json($dato);
        } catch (Exception $e) {

            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['data'] = 0;
            return response()->json($dato);
        }
    }

    public function catSustanciaQuimicaEntidadEliminar($ID_SUSTANCIA_QUIMICA_ENTIDAD)
    {
        try {
            sustanciaQuimicaEntidadModel::where('ID_SUSTANCIA_QUIMICA_ENTIDAD', $ID_SUSTANCIA_QUIMICA_ENTIDAD)->delete();

            // respuesta
            $dato["msj"] = 'Información eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $num_catalogo
     * @param  int  $registro_id
     * @param  int  $estado_checkbox
     * @return \Illuminate\Http\Response
     */
    public function recsensorialquimicoscataloestado($num_catalogo, $registro_id, $estado_checkbox)
    {
        try {
            switch (($num_catalogo + 0)) {
                case 0:
                    $estado = catSustanciasQuimicasModel::findOrFail($registro_id);
                    $estado->update(['ACTIVO' => $estado_checkbox]);
                    break;
                case 1:
                    $estado = catsustanciaModel::findOrFail($registro_id);
                    $estado->update(['catsustancia_activo' => $estado_checkbox]);
                    break;
                case 2:
                    $estado = catestadofisicosustanciaModel::findOrFail($registro_id);
                    $estado->update(['catestadofisicosustancia_activo' => $estado_checkbox]);
                    break;
                case 3:
                    $estado = catvolatilidadModel::findOrFail($registro_id);
                    $estado->update(['catvolatilidad_activo' => $estado_checkbox]);
                    break;
                case 4:
                    $estado = catviaingresoorganismoModel::findOrFail($registro_id);
                    $estado->update(['catviaingresoorganismo_activo' => $estado_checkbox]);
                    break;
                case 5:
                    $estado = catcategoriapeligrosaludModel::findOrFail($registro_id);
                    $estado->update(['catcategoriapeligrosalud_activo' => $estado_checkbox]);
                    break;
                case 6:
                    $estado = catgradoriesgosaludModel::findOrFail($registro_id);
                    $estado->update(['catgradoriesgosalud_activo' => $estado_checkbox]);
                    break;
                case 8:
                    $estado = catUnidadMedidaModel::findOrFail($registro_id);
                    $estado->update(['ACTIVO' => $estado_checkbox]);
                    break;
                case 9:
                    $estado = catConnotacionModel::findOrFail($registro_id);
                    $estado->update(['ACTIVO' => $estado_checkbox]);
                    break;
                case 10:
                    $estado = catEntidadesModel::findOrFail($registro_id);
                    $estado->update(['ACTIVO' => $estado_checkbox]);
                    break;
            }

            // Respuesta
            $dato["msj"] = 'Dato modificado correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            // Respuesta
            $dato["msj"] = 'Error al modificar la información ' . $e->getMessage();
            return response()->json($dato);
            // return $e->getMessage();
        }
    }



    public function sustanciasHojasSeguridad($id)
    {
        try {

            $opciones = DB::select('SELECT hoja.SUSTANCIA_QUIMICA_ID, hoja.OPERADOR, hoja.PORCENTAJE, sus.SUSTANCIA_QUIMICA, 
                                            sus.NUM_CAS, hoja.TEM_EBULLICION, hoja.ESTADO_FISICO, hoja.VOLATILIDAD
                                    FROM catHojasSeguridad_SustanciasQuimicas hoja
                                    LEFT JOIN catsustancias_quimicas sus ON hoja.SUSTANCIA_QUIMICA_ID = sus.ID_SUSTANCIA_QUIMICA
                                    WHERE hoja.HOJA_SEGURIDAD_ID = ? AND hoja.ACTIVO = 1', [$id]);

            // // respuesta
            $dato['opciones'] = $opciones;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['opciones'] = "No encontradas";
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
            // $catalogo;
            switch (($request['catalogo'] + 0)) {
                case 0:
                    if ($request['ID_SUSTANCIA_QUIMICA'] == 0) {
                        // $sql = DB::select('ALTER TABLE catestadofisicosustancia AUTO_INCREMENT=1');
                        $catalogo = catSustanciasQuimicasModel::create($request->all());
                    } else {
                        $catalogo = catSustanciasQuimicasModel::findOrFail($request['ID_SUSTANCIA_QUIMICA']);
                        $catalogo->update($request->all());
                    }

                    break;
                case 1:
                    if ($request['sustancia_id'] == 0) {
                        // AUTO_INCREMENT

                        $repetidas = DB::select('SELECT COUNT(*) AS REPETICIONES
                                                    FROM catsustancia 
                                                    WHERE catsustancia_nombre = ? ', [$request['catsustancia_nombre']]);


                        if ($repetidas[0]->REPETICIONES <= 0) {


                            DB::statement('ALTER TABLE catsustancia AUTO_INCREMENT=1');
                            DB::statement('ALTER TABLE catHojasSeguridad_SustanciasQuimicas AUTO_INCREMENT=1');

                            $catalogo = catsustanciaModel::create($request->all());

                            // guardar componentes
                            $porcentajes = json_decode($request->porcentajes, true);
                            foreach ($porcentajes as $key => $value) {

                                $sustancia = catHojaSeguridadSustanciaQuimicaModel::create([
                                    'HOJA_SEGURIDAD_ID' => $catalogo->id,
                                    'SUSTANCIA_QUIMICA_ID' => $value['SUSTANCIA_QUIMICA_ID'],
                                    'OPERADOR' => $value['OPERADOR'],
                                    'PORCENTAJE' => $value['PORCENTAJE'],
                                    'TEM_EBULLICION' => $value['TEM_EBULLICION'],
                                    'VOLATILIDAD' => $value['VOLATILIDAD'],
                                    'ESTADO_FISICO' => $value['ESTADO_FISICO']
                                ]);
                            }
                        } else {

                            $dato["code"] = 0;
                            $dato["msj"] = 'Ya existe una sustancia con el mismo nombre';
                            return response()->json($dato);
                        }
                    } else {

                        $catalogo = catsustanciaModel::findOrFail($request['sustancia_id']);
                        $catalogo->update($request->all());
                        $componenteseliminar = catHojaSeguridadSustanciaQuimicaModel::where('HOJA_SEGURIDAD_ID', $request['sustancia_id'])->delete(); //eliminar componentes anteriores

                        // AUTO_INCREMENT
                        DB::statement('ALTER TABLE catHojasSeguridad_SustanciasQuimicas AUTO_INCREMENT=1');

                        // guardar componentes
                        $porcentajes = json_decode($request->porcentajes, true);
                        foreach ($porcentajes as $key => $value) {

                            $sustancia = catHojaSeguridadSustanciaQuimicaModel::create(['HOJA_SEGURIDAD_ID' => $catalogo->id,
                                'SUSTANCIA_QUIMICA_ID' => $value['SUSTANCIA_QUIMICA_ID'],
                                'OPERADOR' => $value['OPERADOR'],
                                'PORCENTAJE' => $value['PORCENTAJE'],
                                'TEM_EBULLICION' => $value['TEM_EBULLICION'],
                                'VOLATILIDAD' => $value['VOLATILIDAD'],
                                'ESTADO_FISICO' => $value['ESTADO_FISICO']
                            ]);
                        }
                    }

                    // si envia archivo PDF
                    if ($request->file('hojaseguridadpdf')) {
                        $extension = $request->file('hojaseguridadpdf')->getClientOriginalExtension();
                        $request['catsustancia_hojaseguridadpdf'] = $request->file('hojaseguridadpdf')->storeAs('catalogos/catsustancias', 'hoja_de_seguridad_' . $catalogo->id . '.' . $extension);
                        $catalogo->update($request->all());
                    }
                    break;
                case 2:
                    if ($request['id'] == 0) {
                        // $sql = DB::select('ALTER TABLE catestadofisicosustancia AUTO_INCREMENT=1');
                        $catalogo = catestadofisicosustanciaModel::create($request->all());
                    } else {
                        $catalogo = catestadofisicosustanciaModel::findOrFail($request['id']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 3:
                    if ($request['id'] == 0) {
                        // $sql = DB::select('ALTER TABLE catvolatilidad AUTO_INCREMENT=1');
                        $catalogo = catvolatilidadModel::create($request->all());
                    } else {
                        $catalogo = catvolatilidadModel::findOrFail($request['id']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 4:
                    if ($request['id'] == 0) {
                        // $sql = DB::select('ALTER TABLE catviaingresoorganismo AUTO_INCREMENT=1');
                        $catalogo = catviaingresoorganismoModel::create($request->all());
                    } else {
                        $catalogo = catviaingresoorganismoModel::findOrFail($request['id']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 5:
                    if ($request['id'] == 0) {
                        // $sql = DB::select('ALTER TABLE catcategoriapeligrosalud AUTO_INCREMENT=1');
                        $catalogo = catcategoriapeligrosaludModel::create($request->all());
                    } else {
                        $catalogo = catcategoriapeligrosaludModel::findOrFail($request['id']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 6:
                    if ($request['id'] == 0) {
                        // $sql = DB::select('ALTER TABLE catgradoriesgosalud AUTO_INCREMENT=1');
                        $catalogo = catgradoriesgosaludModel::create($request->all());
                    } else {
                        $catalogo = catgradoriesgosaludModel::findOrFail($request['id']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 7:
                    if ($request['ID_SUSTANCIA_QUIMICA_ENTIDAD'] == 0) {
                        // $sql = DB::select('ALTER TABLE catestadofisicosustancia AUTO_INCREMENT=1');
                        $request['CONNOTACION'] = isset($request['CONNOTACIONES']) ? $request['CONNOTACIONES'] : null;  
                        $catalogo = sustanciaQuimicaEntidadModel::create($request->all());

                        //Actualizamos las pruebas del reconocimiento sensorial
                        if ($request->JSON_NUEVOS_BEIS && !empty($request->JSON_NUEVOS_BEIS)) {

                            $request['JSON_BEIS'] = $request->JSON_NUEVOS_BEIS;
                            $catalogo->update($request->all());
                        }
                    } else {
                        $catalogo = sustanciaQuimicaEntidadModel::findOrFail($request['ID_SUSTANCIA_QUIMICA_ENTIDAD']);


                        // Verificar y ajustar el valor de CONNOTACION
                        if (!isset($request['CONNOTACIONES']) || empty($request['CONNOTACIONES']) || is_null($request['CONNOTACIONES']) || $request['CONNOTACIONES'] == '') {
                            $request['CONNOTACION'] = null; 
                        }else{
                             $request['CONNOTACION'] = $request['CONNOTACIONES'];
                        }

                        // Actualizar el registro
                        $catalogo->update($request->all());

                        //Actualizamos las pruebas del reconocimiento sensorial
                        if ($request->JSON_NUEVOS_BEIS && !empty($request->JSON_NUEVOS_BEIS)) {

                            $request['JSON_BEIS'] = $request->JSON_NUEVOS_BEIS;
                            $catalogo->update($request->all());
                        }
                    }

                    break;
                case 8:

                    if ($request['ID_UNIDAD_MEDIDA'] == 0) {
                        // $sql = DB::select('ALTER TABLE catvolatilidad AUTO_INCREMENT=1');
                        $catalogo = catUnidadMedidaModel::create($request->all());
                    } else {
                        $catalogo = catUnidadMedidaModel::findOrFail($request['ID_UNIDAD_MEDIDA']);
                        $catalogo->update($request->all());
                    }
                    break;

                case 9:

                    if ($request['ID_CONNOTACION'] == 0) {
                        // $sql = DB::select('ALTER TABLE catvolatilidad AUTO_INCREMENT=1');

                        $cadena_mayusculas = mb_strtoupper($request['ABREVIATURA'], 'UTF-8');
                        $sql = DB::select('SELECT COUNT(*) IGUAL
                                                FROM catConnotaciones
                                                WHERE UPPER(ABREVIATURA) = ? AND ENTIDAD_ID = ?', [$cadena_mayusculas, $request['ENTIDAD_ID']]);

                        
                        if($sql[0]->IGUAL != 0) {

                            $dato["code"] = 2;
                            $dato["msj"] = 'Al parecer ya existe una Connotación con la misma Abreviatura ';
                            return response()->json($dato);

                        }else{

                            $catalogo = catConnotacionModel::create($request->all());
                        }

                    } else {

                        $cadena_mayusculas = mb_strtoupper($request['ABREVIATURA'], 'UTF-8');
                        $sql = DB::select('SELECT COUNT(*) IGUAL
                                            FROM catConnotaciones
                                            WHERE UPPER(ABREVIATURA) = ? AND ENTIDAD_ID = ?', [$cadena_mayusculas, $request['ENTIDAD_ID']]);

                        if ($sql[0]->IGUAL != 0) {

                            $dato["code"] = 2;
                            $dato["msj"] = 'Al parecer ya existe una Connotación con la misma Abreviatura ';
                            return response()->json($dato);

                        } else {

                            $catalogo = catConnotacionModel::findOrFail($request['ID_CONNOTACION']);
                            $catalogo->update($request->all());
                        }
                    }
                    break;
                case 10:

                    if ($request['ID_ENTIDAD'] == 0) {
                        // $sql = DB::select('ALTER TABLE catvolatilidad AUTO_INCREMENT=1');
                        $catalogo = catEntidadesModel::create($request->all());
                    } else {

                        $catalogo = catEntidadesModel::findOrFail($request['ID_ENTIDAD']);
                        $catalogo->update($request->all());
                    }
                    break;
            }

            // Respuesta
            $dato["code"] = 1;
            $dato["msj"] = 'información guardada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            return response()->json('Error al guardar informacion');
        }
    }
}

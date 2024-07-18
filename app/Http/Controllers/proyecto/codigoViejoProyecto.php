<?php

namespace App\Http\Controllers\proyecto;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTime;
use DB;

// plugins PDF
use Barryvdh\DomPDF\Facade as PDF;
use PDFMerger;

// Modelos
use App\modelos\proyecto\proyectoModel;
use App\modelos\proyecto\proyectoproveedoresfisicosModel;
use App\modelos\proyecto\proyectoproveedoresquimicosModel;
use App\modelos\recsensorial\recsensorialModel;

//Modelos catalogos
use App\modelos\recsensorial\catregionModel;
use App\modelos\recsensorial\catsubdireccionModel;
use App\modelos\recsensorial\catgerenciaModel;
use App\modelos\recsensorial\catactivoModel;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class proyectoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Reportes,ApoyoTecnico,Financiero,Cadista,Externo');
        // $this->middleware('roles:Superusuario,Administrador,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,ApoyoTecnico,Reportes');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $catregion = catregionModel::where('catregion_activo', 1)->get();
        $catsubdireccion = catsubdireccionModel::where('catsubdireccion_activo', 1)->orderBy('catsubdireccion_nombre', 'ASC')->get();
        $catgerencia = catgerenciaModel::where('catgerencia_activo', 1)->orderBy('catgerencia_nombre', 'ASC')->get();
        $catactivo = catactivoModel::where('catactivo_activo', 1)->orderBy('catactivo_nombre', 'ASC')->get();

        return view('catalogos.proyecto.proyecto', compact('catregion', 'catsubdireccion', 'catgerencia', 'catactivo'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function proyectotabla()
    {
        try {
            // TIPO DE USUARIO
            if (auth()->user()->hasRoles(['CoordinadorRN', 'CoordinadorRS', 'CoordinadorRM'])) {
                $region = '';

                if (auth()->user()->hasRoles(['CoordinadorRN'])) {
                    // $region = 'NORTE';
                    $region = ' proyecto.catregion_id = 1';
                } else if (auth()->user()->hasRoles(['CoordinadorRS'])) {
                    // $region = 'SUR';
                    $region = ' proyecto.catregion_id = 2';
                } else {
                    // $region = 'MARINA';
                    $region = ' proyecto.catregion_id = 3';
                }

                // Reconocimientos segun la region
                $proyectos_coordinador = collect(DB::select('SELECT
                                                                proyecto.id,
                                                                proyecto.proyecto_folio,
                                                                proyecto.recsensorial_id,
                                                                catregion.catregion_nombre 
                                                            FROM
                                                                proyecto
                                                                LEFT JOIN recsensorial ON proyecto.recsensorial_id = recsensorial.id
                                                                LEFT JOIN catregion ON recsensorial.catregion_id = catregion.id
                                                            WHERE' . $region));

                $lista_proyectos = array(0);
                foreach ($proyectos_coordinador as $key => $value) {
                    $lista_proyectos[] = $value->id;
                }

                // $proyectos = proyectoModel::with(['recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'recsensorial.catcontrato', 'recsensorial.catregion', 'recsensorial.catgerencia', 'recsensorial.catactivo'])
                $proyectos = proyectoModel::with(['recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'recsensorial.cliente', 'recsensorial.catregion', 'recsensorial.catgerencia', 'recsensorial.catactivo'])
                    ->whereIn('id', $lista_proyectos) // ->whereIn('id', [1, 2, 3, 8, 22])
                    ->orderBy('id', 'ASC')
                    ->get();
            } else if (auth()->user()->hasRoles(['Psicólogo', 'Ergónomo', 'ApoyoTecnico'])) {
                $proyectos_signatario = collect(DB::select('SELECT
                                                                proyectosignatariosactual.proyecto_id,
                                                                signatario.signatario_Nombre 
                                                            FROM
                                                                proyectosignatariosactual
                                                                LEFT JOIN proyecto ON proyectosignatariosactual.proyecto_id = proyecto.id
                                                                LEFT JOIN signatario ON proyectosignatariosactual.signatario_id = signatario.id
                                                            WHERE
                                                                proyecto.proyecto_concluido = 0
                                                                AND signatario.signatario_Eliminado = 0
                                                                AND signatario.signatario_Nombre LIKE "%' . auth()->user()->name . ' ' . auth()->user()->empleado->empleado_apellidopaterno . ' ' . auth()->user()->empleado->empleado_apellidomaterno . '%"
                                                            GROUP BY
                                                                proyectosignatariosactual.proyecto_id,    
                                                                signatario.signatario_Nombre'));

                $lista_proyectos = array(0);
                foreach ($proyectos_signatario as $key => $value) {
                    $lista_proyectos[] = $value->proyecto_id;
                }

                // $proyectos = proyectoModel::with(['recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'recsensorial.catcontrato', 'recsensorial.catregion', 'recsensorial.catgerencia', 'recsensorial.catactivo'])
                $proyectos = proyectoModel::with(['recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'recsensorial.cliente', 'recsensorial.catregion', 'recsensorial.catgerencia', 'recsensorial.catactivo'])
                    ->whereIn('id', $lista_proyectos) // ->whereIn('id', [1, 2, 3, 8, 22])
                    ->orderBy('id', 'ASC')
                    ->get();
            } else {
                // $proyectos = proyectoModel::all();
                // $proyectos = proyectoModel::with(['recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'recsensorial.catcontrato', 'recsensorial.catregion', 'recsensorial.catgerencia', 'recsensorial.catactivo'])
                $proyectos = proyectoModel::with(['recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'recsensorial.cliente', 'recsensorial.catregion', 'recsensorial.catgerencia', 'recsensorial.catactivo'])
                    // ->whereIn('id', [118])
                    ->orderBy('id', 'ASC')
                    ->get();
            }


            // Formaterar filas
            $numero_registro = 0;
            foreach ($proyectos as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                $value->instalacion_y_direccion = '<span style="color: #999999;">' . $value->proyecto_clienteinstalacion . '</span><br>' . $value->proyecto_clientedireccionservicio;
                // $value->recsensorial_folios = $value->recsensorial->recsensorial_foliofisico."<br>".$value->recsensorial->recsensorial_folioquimico;

                // formatear fecha
                if ($value->proyecto_fechainicio) {
                    // $value->proyecto_fechainicio = Carbon::createFromFormat('Y-m-d', $value->proyecto_fechainicio)->format('d-m-Y');
                    // $value->proyecto_fechafin = Carbon::createFromFormat('Y-m-d', $value->proyecto_fechafin)->format('d-m-Y');
                    $value->inicio_y_fin = $value->proyecto_fechainicio . "<br>" . $value->proyecto_fechafin;

                    // $value->proyecto_fechaelaboracion_texto = Carbon::createFromFormat('Y-m-d', $value->proyecto_fechaelaboracion)->format('d-m-Y');
                }

                // Si hay recsensorial
                if ($value->recsensorial_id) {
                    // Folios RECSENSORIAL asignado
                    if (($value->recsensorial->recsensorial_alcancefisico + 0) > 0 && ($value->recsensorial->recsensorial_alcancefisico + 0) > 0) {
                        $value->recsensorial_folios = $value->recsensorial->recsensorial_foliofisico . "<br>" . $value->recsensorial->recsensorial_folioquimico;
                    } else if (($value->recsensorial->recsensorial_alcancefisico + 0) > 0) {
                        $value->recsensorial_folios = $value->recsensorial->recsensorial_foliofisico;
                    } else {
                        $value->recsensorial_folios = $value->recsensorial->recsensorial_folioquimico;
                    }
                } else {
                    $value->recsensorial_folios = 'Sin asignar<br><i class="fa fa-ban text-danger"></i>';
                }

                $value->proyecto_fechacreacion = Carbon::createFromFormat('Y-m-d H:i:s', $value->created_at)->format('Y-m-d - h:i:s a');

                // Valida perfil
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto'])) {
                    $value->perfil = 1;
                } else if (auth()->user()->hasRoles(['Psicólogo', 'Ergónomo', 'CoordinadorPsicosocial', 'CoordinadorErgonómico', 'CoordinadorRN', 'CoordinadorRS', 'CoordinadorRM', 'CoordinadorHI', 'ApoyoTecnico'])) {
                    $value->perfil = 2;
                } else if (auth()->user()->hasRoles(['Compras'])) {
                    $value->perfil = 3;
                } else {
                    $value->perfil = 0;
                }

                // // Valida perfil
                // if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'CoordinadorHI']))
                // {
                //     $value->perfil = 1;
                // }
                // else if (auth()->user()->hasRoles(['CoordinadorPsicosocial', 'CoordinadorErgonómico', 'CoordinadorRN', 'CoordinadorRS', 'CoordinadorRM', 'Compras']))
                // {
                //     $value->perfil = 2;
                // }
                // else
                // {
                //     $value->perfil = 0;
                // }

                // BOTON MOSTRAR [Proyecto Bloqueado]
                if (($value->proyecto_concluido + 0) == 0) //Desbloqueado
                {
                    $value->boton_mostrar = '<button type="button" class="btn btn-info btn-circle" style="padding: 0px;"><i class="fa fa-eye fa-2x"></i></button>';
                } else {
                    $value->boton_mostrar = '<button type="button" class="btn btn-secondary btn-circle" style="padding: 0px; border: 1px #999999 solid!important;" data-toggle="tooltip" title="Solo lectura"><i class="fa fa-eye-slash fa-2x"></i></button>';
                }
            }

            // respuesta
            $dato['data'] = $proyectos;
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['data'] = 0;
            return response()->json($dato);
        }
    }




    public function proyectosTotales()
    {
        try {

            $proyectos = DB::select('SELECT COUNT(*) AS PROYECTOS
                                    FROM proyecto
                                    WHERE proyecto_eliminado = 0');


            // // respuesta
            $dato['NUM_PROYECTO'] = $proyectos[0]->PROYECTOS;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['opciones'] = $opciones_select;
            return response()->json($dato);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoselectrecsensorial($recsensorial_id)
    {
        try {
            $opciones_select = '<option value="">&nbsp;</option>';
            $recsensorial = DB::select('SELECT
                                            TABLA.id,
                                            TABLA.recsensorial_alcancefisico,
                                            TABLA.recsensorial_alcancequimico,
                                            TABLA.texto_select,
                                            IF(TABLA.recsensorial_quimicovalidado = 0, "(Químicos sin validar)", "") AS quimicovalidado_texto,
                                            IF(TABLA.recsensorial_quimicovalidado = 0, "color: #F00;", "color: #007bff; font-weight: bold;") AS style_color,
                                            IF(TABLA.recsensorial_quimicovalidado = 0, "red", "blue") AS option_color,
                                            TABLA.cargado
                                        FROM
                                            (
                                                SELECT
                                                    recsensorial.id,
                                                    recsensorial.recsensorial_alcancefisico,
                                                    recsensorial.recsensorial_alcancequimico,
                                                    (
                                                        CASE
                                                            WHEN ((recsensorial.recsensorial_alcancefisico > 0) && (recsensorial.recsensorial_alcancequimico > 0)) THEN CONCAT(recsensorial.recsensorial_instalacion, " - [", recsensorial.recsensorial_foliofisico, "] y [", recsensorial.recsensorial_folioquimico,"]")
                                                            WHEN (recsensorial.recsensorial_alcancefisico > 0) THEN CONCAT(recsensorial.recsensorial_instalacion, " [", recsensorial.recsensorial_foliofisico, "]")
                                                            WHEN (recsensorial.recsensorial_alcancequimico > 0) THEN CONCAT(recsensorial.recsensorial_instalacion, " [", recsensorial.recsensorial_folioquimico, "]")
                                                            ELSE "Error"
                                                        END
                                                    ) AS texto_select,
                                                    (
                                                        CASE
                                                            WHEN (recsensorial.recsensorial_alcancequimico = 1) THEN IF(recsensorial.recsensorial_quimicovalidado > 0, 1, 0)
                                                            ELSE 1
                                                        END
                                                    ) AS recsensorial_quimicovalidado,
                                                    IFNULL((
                                                        SELECT
                                                            IF(proyecto.recsensorial_id, 1, 0)
                                                        FROM
                                                            proyecto
                                                        WHERE
                                                            proyecto.recsensorial_id = recsensorial.id
                                                    ), 0) AS cargado
                                                FROM
                                                    recsensorial
                                                WHERE
                                                    recsensorial.recsensorial_eliminado = 0
                                            ) AS TABLA
                                        WHERE
                                           TABLA.cargado = 0 || TABLA.id = ' . $recsensorial_id);

            foreach ($recsensorial as $key => $value) {
                if ($value->id == $recsensorial_id) {
                    $opciones_select .= '<option value="' . $value->id . '" style="' . $value->style_color . '" selected><b>' . $value->texto_select . ' ' . $value->quimicovalidado_texto . '</b></option>';
                } else {
                    $opciones_select .= '<option value="' . $value->id . '" style="' . $value->style_color . '"><b>' . $value->texto_select . ' ' . $value->quimicovalidado_texto . '</b></option>';
                }
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



    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function proyectorecsensorial($recsensorial_id)
    {
        try {
            $numero_registro = 0;
            $error = 0;
            $filas_fisicos = '<tr><td colspan="4" style="text-align: center;">El reconocimiento aún no se ha evaluado</td></tr>';
            $filas_quimicos = '<tr><td colspan="4" style="text-align: center;">El reconocimiento aún no se ha evaluado</td></tr>';

            DB::statement("SET lc_time_names = 'es_MX';");
            $recsensorial = DB::select('SELECT
                                        recsensorial.id,
                                        recsensorial.recsensorial_alcancefisico,
                                        recsensorial.recsensorial_alcancequimico,
                                        IFNULL(recsensorial.recsensorial_foliofisico, "N/A") AS recsensorial_foliofisico,
                                        IFNULL(recsensorial.recsensorial_folioquimico, "N/A") AS recsensorial_folioquimico,

                                        recsensorial.cliente_id,
                                        recsensorial.recsensorial_tipocliente,
                                        cliente.cliente_RazonSocial,
                                        cliente.cliente_NombreComercial,
                                        cliente.cliente_DomicilioFiscal,
                                        cliente.cliente_LineaNegocios,
                                        cliente.cliente_GiroComercial,
                                        cliente.cliente_Rfc,
                                        cliente.cliente_CiudadPais,
                                        cliente.cliente_Pais,
                                        cliente.cliente_RepresentanteLegal,
                                        cliente.cliente_PaginaWeb,
                                        -- cliente.cliente_NombreContacto,
                                        -- cliente.cliente_CargoContacto,
                                        -- cliente.cliente_CorreoContacto,
                                        -- cliente.cliente_TelefonoContacto,
                                        -- cliente.cliente_CelularContacto,
                                        -- cliente.cliente_numerocontrato,
                                        -- cliente.cliente_descripcioncontrato,
                                        -- cliente.cliente_fechainicio,
                                        -- cliente.cliente_fechafin,

                                        recsensorial.catregion_id,
                                        recsensorial.catsubdireccion_id,
                                        recsensorial.catgerencia_id,
                                        recsensorial.catactivo_id,
                                        recsensorial.recsensorial_empresa,
                                        recsensorial.recsensorial_codigopostal,
                                        recsensorial.recsensorial_rfc,
                                        recsensorial.recsensorial_representantelegal,
                                        recsensorial.recsensorial_representanteseguridad,
                                        recsensorial.recsensorial_instalacion,
                                        recsensorial.recsensorial_direccion,
                                        recsensorial.recsensorial_coordenadas,
                                        DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%d-%m-%Y") AS recsensorial_fechainicio,
                                        DATE_FORMAT(recsensorial.recsensorial_fechafin, "%d-%m-%Y") AS recsensorial_fechafin,
                                        recsensorial.recsensorial_actividadprincipal,
                                        recsensorial.recsensorial_descripcionproceso,
                                        recsensorial.recsensorial_obscategorias,
                                        recsensorial.recsensorial_elabora1,
                                        recsensorial.recsensorial_elabora2,
                                        recsensorial.recsensorial_quimicovalidado,
                                        DATE_FORMAT(recsensorial.recsensorial_quimicofechavalidacion, "%d-%m-%Y") AS recsensorial_quimicofechavalidacion,
                                        recsensorial.recsensorial_quimicopersonavalida,
                                        recsensorial.recsensorial_pdfvalidaquimicos,
                                        recsensorial.recsensorial_fotoplano,
                                        recsensorial.recsensorial_fotoubicacion,
                                        recsensorial.recsensorial_fotoinstalacion,
                                        recsensorial.recsensorial_eliminado 
                                    FROM
                                        recsensorial
                                        LEFT JOIN cliente ON recsensorial.cliente_id = cliente.id
                                    WHERE
                                        recsensorial.id = ?', [$recsensorial_id]);









            // Resumen de fisicos
            if (($recsensorial[0]->recsensorial_alcancefisico + 0) != 0) {
                if (($recsensorial[0]->recsensorial_alcancefisico + 0) == 1) { //Reconocimiento de FISICOS completo

                    $fisicos = DB::select('SELECT
                                                recsensorialpruebas.recsensorial_id,
                                                cat_prueba.catPrueba_Tipo,
                                                TABLA1.catprueba_id,
                                                IF(IFNULL(TABLA1.agente_subnombre, "") = "", cat_prueba.catPrueba_Nombre, CONCAT(cat_prueba.catPrueba_Nombre, " (", TABLA1.agente_subnombre, ")")) AS catPrueba_Nombre,
                                                cat_prueba.catPrueba_Nombre AS agente_nombre,
                                                TABLA1.agente_subnombre AS agente_subnombre,
                                                TABLA1.totalregistros,
                                                TABLA1.totalpuntos,
                                                (
                                                    IF((SELECT recsensorial_tipocliente FROM recsensorial WHERE recsensorial.id = ' . $recsensorial_id . ' LIMIT 1) = 1, -- Si el cliente es Pemex
                                                        IF(TABLA1.catprueba_id != 17,
                                                            IF(TABLA1.catprueba_id != 16,
                                                                CASE
                                                                    WHEN TABLA1.totalregistros = 0 THEN "Sin evaluar"
                                                                    WHEN TABLA1.totalpuntos >= 151 THEN "Extra grande"
                                                                    WHEN TABLA1.totalpuntos >= 81 THEN "Grande"
                                                                    WHEN TABLA1.totalpuntos >= 41 THEN "Mediana"
                                                                    WHEN TABLA1.totalpuntos >= 21 THEN "Chica"
                                                                    ELSE "Extra chica"
                                                                END
                                                                ,
                                                                CASE
                                                                    WHEN TABLA1.totalregistros = 0 THEN "Sin evaluar"
                                                                    WHEN TABLA1.totalpuntos >= 50 THEN "Extra grande"
                                                                    WHEN TABLA1.totalpuntos >= 31 THEN "Grande"
                                                                    WHEN TABLA1.totalpuntos >= 11 THEN "Mediana"
                                                                    WHEN TABLA1.totalpuntos >= 5 THEN "Chica"
                                                                    WHEN TABLA1.totalpuntos >= 2 THEN "Extra chica"
                                                                    ELSE "N/A"
                                                                END
                                                            )
                                                            ,
                                                                "N/A"
                                                        )
                                                        ,
                                                        IF(TABLA1.catprueba_id != 17,
                                                            IF(TABLA1.catprueba_id != 16,
                                                                CASE
                                                                    WHEN TABLA1.totalregistros = 0 THEN "Sin evaluar"
                                                                    -- WHEN TABLA1.totalpuntos >= 151 THEN "Extra grande"
                                                                    WHEN TABLA1.totalpuntos >= 81 THEN "Grande"
                                                                    WHEN TABLA1.totalpuntos >= 41 THEN "Mediana"
                                                                    WHEN TABLA1.totalpuntos >= 21 THEN "Chica"
                                                                    ELSE "Extra chica"
                                                                END
                                                                ,
                                                                CASE
                                                                    WHEN TABLA1.totalregistros = 0 THEN "Sin evaluar"
                                                                    WHEN TABLA1.totalpuntos >= 50 THEN "Extra grande"
                                                                    WHEN TABLA1.totalpuntos >= 31 THEN "Grande"
                                                                    WHEN TABLA1.totalpuntos >= 11 THEN "Mediana"
                                                                    WHEN TABLA1.totalpuntos >= 5 THEN "Chica"
                                                                    WHEN TABLA1.totalpuntos >= 2 THEN "Extra chica"
                                                                    ELSE "N/A"
                                                                END
                                                            )
                                                            ,
                                                                "N/A"
                                                        )
                                                    )
                                                ) AS tipoinstalacion,
                                                (
                                                    IF((SELECT recsensorial_tipocliente FROM recsensorial WHERE recsensorial.id = ' . $recsensorial_id . ' LIMIT 1) = 1, -- Si el cliente es Pemex
                                                        IF(TABLA1.catprueba_id != 17,
                                                            IF(TABLA1.catprueba_id != 16,
                                                                CASE
                                                                    WHEN TABLA1.totalregistros = 0 THEN "0"
                                                                    WHEN TABLA1.totalpuntos >= 151 THEN "XG"
                                                                    WHEN TABLA1.totalpuntos >= 81 THEN "G"
                                                                    WHEN TABLA1.totalpuntos >= 41 THEN "M"
                                                                    WHEN TABLA1.totalpuntos >= 21 THEN "CH"
                                                                    ELSE "XC"
                                                                END
                                                                ,
                                                                CASE
                                                                    WHEN TABLA1.totalregistros = 0 THEN "0"
                                                                    WHEN TABLA1.totalpuntos >= 50 THEN "XG"
                                                                    WHEN TABLA1.totalpuntos >= 31 THEN "G"
                                                                    WHEN TABLA1.totalpuntos >= 11 THEN "M"
                                                                    WHEN TABLA1.totalpuntos >= 5 THEN "CH"
                                                                    WHEN TABLA1.totalpuntos >= 2 THEN "XC"
                                                                    ELSE "N/A"
                                                                END
                                                            )
                                                            ,
                                                                "N/A"
                                                        )
                                                        ,
                                                        IF(TABLA1.catprueba_id != 17,
                                                            IF(TABLA1.catprueba_id != 16,
                                                                CASE
                                                                    WHEN TABLA1.totalregistros = 0 THEN "0"
                                                                    -- WHEN TABLA1.totalpuntos >= 151 THEN "XG"
                                                                    WHEN TABLA1.totalpuntos >= 81 THEN "G"
                                                                    WHEN TABLA1.totalpuntos >= 41 THEN "M"
                                                                    WHEN TABLA1.totalpuntos >= 21 THEN "CH"
                                                                    ELSE "XC"
                                                                END
                                                                ,
                                                                CASE
                                                                    WHEN TABLA1.totalregistros = 0 THEN "0"
                                                                    WHEN TABLA1.totalpuntos >= 50 THEN "XG"
                                                                    WHEN TABLA1.totalpuntos >= 31 THEN "G"
                                                                    WHEN TABLA1.totalpuntos >= 11 THEN "M"
                                                                    WHEN TABLA1.totalpuntos >= 5 THEN "CH"
                                                                    WHEN TABLA1.totalpuntos >= 2 THEN "XC"
                                                                    ELSE "N/A"
                                                                END
                                                            )
                                                            ,
                                                                "N/A"
                                                        )
                                                    )
                                                ) AS tipoinstalacion_abreviacion,
                                                (
                                                    IF((SELECT recsensorial_tipocliente FROM recsensorial WHERE recsensorial.id = ' . $recsensorial_id . ' LIMIT 1) = 1, -- Si el cliente es Pemex
                                                        IF(TABLA1.catprueba_id != 17,
                                                            IF(TABLA1.catprueba_id != 16,
                                                                CASE
                                                                    WHEN TABLA1.totalregistros = 0 THEN "#999999"
                                                                    WHEN TABLA1.totalpuntos >= 151 THEN "#DF0101"
                                                                    WHEN TABLA1.totalpuntos >= 81 THEN "#FF8000"
                                                                    WHEN TABLA1.totalpuntos >= 41 THEN "#FFD700"
                                                                    WHEN TABLA1.totalpuntos >= 21 THEN "#5FB404"
                                                                    ELSE "#0080FF"
                                                                END
                                                                ,
                                                                CASE
                                                                    WHEN TABLA1.totalregistros = 0 THEN "#999999"
                                                                    WHEN TABLA1.totalpuntos >= 50 THEN "#DF0101"
                                                                    WHEN TABLA1.totalpuntos >= 31 THEN "#FF8000"
                                                                    WHEN TABLA1.totalpuntos >= 11 THEN "#FFD700"
                                                                    WHEN TABLA1.totalpuntos >= 5 THEN "#5FB404"
                                                                    WHEN TABLA1.totalpuntos >= 2 THEN "#0080FF"
                                                                    ELSE "#999999"
                                                                END
                                                            )
                                                            ,
                                                            "#999999"
                                                        )
                                                        ,
                                                        IF(TABLA1.catprueba_id != 17,
                                                            IF(TABLA1.catprueba_id != 16,
                                                                CASE
                                                                    WHEN TABLA1.totalregistros = 0 THEN "#999999"
                                                                    -- WHEN TABLA1.totalpuntos >= 151 THEN "#DF0101"
                                                                    WHEN TABLA1.totalpuntos >= 81 THEN "#FF8000"
                                                                    WHEN TABLA1.totalpuntos >= 41 THEN "#FFD700"
                                                                    WHEN TABLA1.totalpuntos >= 21 THEN "#5FB404"
                                                                    ELSE "#0080FF"
                                                                END
                                                                ,
                                                                CASE
                                                                    WHEN TABLA1.totalregistros = 0 THEN "#999999"
                                                                    WHEN TABLA1.totalpuntos >= 50 THEN "#DF0101"
                                                                    WHEN TABLA1.totalpuntos >= 31 THEN "#FF8000"
                                                                    WHEN TABLA1.totalpuntos >= 11 THEN "#FFD700"
                                                                    WHEN TABLA1.totalpuntos >= 5 THEN "#5FB404"
                                                                    WHEN TABLA1.totalpuntos >= 2 THEN "#0080FF"
                                                                    ELSE "#999999"
                                                                END
                                                            )
                                                            ,
                                                            "#999999"
                                                        )
                                                    )
                                                ) AS tipoinstalacion_color
                                            FROM
                                                (
                                                    (
                                                        SELECT
                                                            TABLARUIDO.catprueba_id,
                                                            NULL AS agente_subnombre,
                                                            SUM(TABLARUIDO.totalregistros) AS totalregistros,
                                                            SUM(TABLARUIDO.totalpuntos) AS totalpuntos
                                                        FROM
                                                            (
                                                                (
                                                                    -- SELECT
                                                                    --     1 AS catprueba_id,
                                                                    --     NULL AS agente_subnombre,
                                                                    --     COUNT(parametroruidosonometria.recsensorial_id) AS totalregistros,
                                                                    --     SUM(parametroruidosonometria.parametroruidosonometria_puntos) AS totalpuntos 
                                                                    -- FROM
                                                                    --     parametroruidosonometria
                                                                    -- WHERE
                                                                    --     parametroruidosonometria.recsensorial_id = ' . $recsensorial_id . '
                                                                    -- GROUP BY
                                                                    --     parametroruidosonometria.recsensorial_id,
                                                                    --     parametroruidosonometria.parametroruidosonometria_puntos


                                                                    SELECT
                                                                        1 AS catprueba_id,
                                                                        NULL AS agente_subnombre,
                                                                        COUNT(tablaresumen.recsensorialarea_id) AS totalregistros,
                                                                        SUM(tablaresumen.puntos) AS totalpuntos
                                                                    FROM
                                                                        (
                                                                            SELECT
                                                                                tabla.recsensorial_id,
                                                                                tabla.recsensorialarea_id,
                                                                                IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                                                                IFNULL((
                                                                                    SELECT
                                                                                        CONCAT("● ", REPLACE(GROUP_CONCAT(recsensorialcategoria.recsensorialcategoria_nombrecategoria), ",", "<br/>● "))
                                                                                    FROM
                                                                                        parametroruidosonometriacategorias
                                                                                        LEFT JOIN recsensorialcategoria ON parametroruidosonometriacategorias.recsensorialcategoria_id = recsensorialcategoria.id 
                                                                                    WHERE
                                                                                        parametroruidosonometriacategorias.recsensorialarea_id = tabla.recsensorialarea_id
                                                                                ), "-") AS categorias,
                                                                                tabla.parametroruidosonometria_puntos AS puntos,
                                                                                MIN(tabla.medicion) AS min,
                                                                                MAX(tabla.medicion) AS max,
                                                                                (MAX(tabla.medicion) - MIN(tabla.medicion)) AS diferencia,
                                                                                (
                                                                                    CASE
                                                                                        WHEN (COUNT(tabla.medicion) = 0) THEN 1
                                                                                        WHEN (MAX(tabla.medicion) > 80 OR MIN(tabla.medicion) > 80) THEN 1
                                                                                        ELSE 0
                                                                                    END
                                                                                ) AS aplicaevaluacion,
                                                                                IFNULL((
                                                                                    REPLACE(GROUP_CONCAT(tabla.medicion_texto ORDER BY tabla.medicion_texto ASC), ",", "<br/>")
                                                                                ), "NP") AS mediciones,
                                                                                (
                                                                                    CASE
                                                                                        WHEN (COUNT(tabla.medicion) = 0) THEN "Se evalua"
                                                                                        WHEN (MAX(tabla.medicion) > 80 OR MIN(tabla.medicion) > 80) THEN 
                                                                                            CASE
                                                                                                WHEN ((MAX(tabla.medicion) - MIN(tabla.medicion)) > 5) THEN (CONCAT("Se evalua<br/>Ruido inestable<br/>±", (MAX(tabla.medicion) - MIN(tabla.medicion)), " dB"))
                                                                                                ELSE (CONCAT("Se evalua<br/>Ruido Estable<br/>±", (MAX(tabla.medicion) - MIN(tabla.medicion)), " dB"))
                                                                                            END
                                                                                        ELSE "No se evalua<br/>≤80 dB"
                                                                                    END
                                                                                ) AS resultado
                                                                            FROM
                                                                                (
                                                                                    (
                                                                                        SELECT
                                                                                            parametroruidosonometria.recsensorial_id, 
                                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_medmax = 0, NULL, parametroruidosonometria.parametroruidosonometria_medmax) AS medicion,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_medmax = 0, NULL, CONCAT("Max: ", parametroruidosonometria.parametroruidosonometria_medmax, " dB")) AS medicion_texto
                                                                                        FROM
                                                                                            parametroruidosonometria
                                                                                    )
                                                                                    UNION ALL
                                                                                    (
                                                                                        SELECT
                                                                                            parametroruidosonometria.recsensorial_id, 
                                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_medmin = 0, NULL, parametroruidosonometria.parametroruidosonometria_medmin) AS medicion,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_medmin = 0, NULL, CONCAT("Min: ", parametroruidosonometria.parametroruidosonometria_medmin, " dB")) AS medicion_texto
                                                                                        FROM
                                                                                            parametroruidosonometria
                                                                                    )
                                                                                    UNION ALL
                                                                                    (
                                                                                        SELECT
                                                                                            parametroruidosonometria.recsensorial_id, 
                                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                                            parametroruidosonometria.parametroruidosonometria_puntos,                       
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med1 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med1) AS medicion,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med1 = 0, NULL, CONCAT("Med01: ", parametroruidosonometria.parametroruidosonometria_med1, " dB")) AS medicion_texto
                                                                                        FROM
                                                                                            parametroruidosonometria
                                                                                    )
                                                                                    UNION ALL
                                                                                    (
                                                                                        SELECT
                                                                                            parametroruidosonometria.recsensorial_id, 
                                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                                            parametroruidosonometria.parametroruidosonometria_puntos,               
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med2 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med2) AS medicion,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med2 = 0, NULL, CONCAT("Med02: ", parametroruidosonometria.parametroruidosonometria_med2, " dB")) AS medicion_texto
                                                                                        FROM
                                                                                            parametroruidosonometria
                                                                                    )
                                                                                    UNION ALL
                                                                                    (
                                                                                        SELECT
                                                                                            parametroruidosonometria.recsensorial_id, 
                                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med3 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med3) AS medicion,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med3 = 0, NULL, CONCAT("Med03: ", parametroruidosonometria.parametroruidosonometria_med3, " dB")) AS medicion_texto
                                                                                        FROM
                                                                                            parametroruidosonometria
                                                                                    )
                                                                                    UNION ALL
                                                                                    (
                                                                                        SELECT
                                                                                            parametroruidosonometria.recsensorial_id, 
                                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med4 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med4) AS medicion,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med4 = 0, NULL, CONCAT("Med04: ", parametroruidosonometria.parametroruidosonometria_med4, " dB")) AS medicion_texto
                                                                                        FROM
                                                                                            parametroruidosonometria
                                                                                    )
                                                                                    UNION ALL
                                                                                    (
                                                                                        SELECT
                                                                                            parametroruidosonometria.recsensorial_id, 
                                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med5 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med5) AS medicion,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med5 = 0, NULL, CONCAT("Med05: ", parametroruidosonometria.parametroruidosonometria_med5, " dB")) AS medicion_texto
                                                                                        FROM
                                                                                            parametroruidosonometria
                                                                                    )
                                                                                    UNION ALL
                                                                                    (
                                                                                        SELECT
                                                                                            parametroruidosonometria.recsensorial_id, 
                                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med6 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med6) AS medicion,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med6 = 0, NULL, CONCAT("Med06: ", parametroruidosonometria.parametroruidosonometria_med6, " dB")) AS medicion_texto
                                                                                        FROM
                                                                                            parametroruidosonometria
                                                                                    )
                                                                                    UNION ALL
                                                                                    (
                                                                                        SELECT
                                                                                            parametroruidosonometria.recsensorial_id, 
                                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med7 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med7) AS medicion,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med7 = 0, NULL, CONCAT("Med07: ", parametroruidosonometria.parametroruidosonometria_med7, " dB")) AS medicion_texto
                                                                                        FROM
                                                                                            parametroruidosonometria
                                                                                    )
                                                                                    UNION ALL
                                                                                    (
                                                                                        SELECT
                                                                                            parametroruidosonometria.recsensorial_id, 
                                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med8 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med8) AS medicion,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med8 = 0, NULL, CONCAT("Med08: ", parametroruidosonometria.parametroruidosonometria_med8, " dB")) AS medicion_texto
                                                                                        FROM
                                                                                            parametroruidosonometria
                                                                                    )
                                                                                    UNION ALL
                                                                                    (
                                                                                        SELECT
                                                                                            parametroruidosonometria.recsensorial_id, 
                                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med9 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med9) AS medicion,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med9 = 0, NULL, CONCAT("Med09: ", parametroruidosonometria.parametroruidosonometria_med9, " dB")) AS medicion_texto
                                                                                        FROM
                                                                                            parametroruidosonometria
                                                                                    )
                                                                                    UNION ALL
                                                                                    (
                                                                                        SELECT
                                                                                            parametroruidosonometria.recsensorial_id, 
                                                                                            parametroruidosonometria.recsensorialarea_id, 
                                                                                            parametroruidosonometria.parametroruidosonometria_puntos,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med10 = 0, NULL, parametroruidosonometria.parametroruidosonometria_med10) AS medicion,
                                                                                            IF(parametroruidosonometria.parametroruidosonometria_med10 = 0, NULL, CONCAT("Med10: ", parametroruidosonometria.parametroruidosonometria_med10, " dB")) AS medicion_texto
                                                                                        FROM
                                                                                            parametroruidosonometria
                                                                                    )
                                                                                ) AS tabla
                                                                                LEFT JOIN recsensorialarea ON tabla.recsensorialarea_id = recsensorialarea.id
                                                                            WHERE
                                                                                tabla.recsensorial_id = ' . $recsensorial_id . ' 
                                                                            GROUP BY
                                                                                tabla.recsensorial_id,
                                                                                tabla.recsensorialarea_id,
                                                                                parametroruidosonometria_puntos
                                                                            ORDER BY
                                                                                tabla.recsensorialarea_id ASC
                                                                        ) AS tablaresumen
                                                                    WHERE
                                                                        tablaresumen.aplicaevaluacion = 1
                                                                )
                                                                UNION ALL
                                                                (
                                                                    SELECT
                                                                        1 AS catprueba_id,
                                                                        NULL AS agente_subnombre,
                                                                        COUNT(parametroruidodosimetria.recsensorial_id) AS totalregistros,
                                                                        SUM(parametroruidodosimetria.parametroruidodosimetria_dosis) AS totalpuntos 
                                                                    FROM
                                                                        parametroruidodosimetria
                                                                    WHERE
                                                                        parametroruidodosimetria.recsensorial_id = ' . $recsensorial_id . '
                                                                    GROUP BY
                                                                        parametroruidodosimetria.recsensorial_id,
                                                                        parametroruidodosimetria.parametroruidodosimetria_dosis
                                                                )
                                                            ) AS TABLARUIDO
                                                        GROUP BY
                                                            TABLARUIDO.catprueba_id
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            2 AS catprueba_id,
                                                            NULL AS agente_subnombre,
                                                            COUNT(parametrovibracion.recsensorial_id) AS totalregistros,
                                                            SUM(parametrovibracion.parametrovibracion_puntovce + parametrovibracion.parametrovibracion_puntoves) AS totalpuntos  
                                                        FROM
                                                            parametrovibracion
                                                        WHERE
                                                            parametrovibracion.recsensorial_id = ' . $recsensorial_id . '
                                                        GROUP BY
                                                            parametrovibracion.recsensorial_id
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            3 AS catprueba_id,
                                                            NULL AS agente_subnombre,
                                                            COUNT( parametrotemperatura.recsensorial_id ) AS totalregistros,
                                                            SUM( parametrotemperatura.parametrotemperatura_puntote + parametrotemperatura.parametrotemperatura_puntota ) AS totalpuntos 
                                                        FROM
                                                            parametrotemperatura 
                                                        WHERE
                                                            parametrotemperatura.recsensorial_id = ' . $recsensorial_id . ' 
                                                        GROUP BY
                                                            parametrotemperatura.recsensorial_id
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            4 AS catprueba_id,
                                                            NULL AS agente_subnombre,
                                                            COUNT( parametroiluminacion.recsensorial_id ) AS totalregistros,
                                                            SUM( parametroiluminacion.parametroiluminacion_puntos ) AS totalpuntos 
                                                        FROM
                                                            parametroiluminacion 
                                                        WHERE
                                                            parametroiluminacion.recsensorial_id = ' . $recsensorial_id . ' 
                                                        GROUP BY
                                                            parametroiluminacion.recsensorial_id
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            5 AS catprueba_id,
                                                            NULL AS agente_subnombre,
                                                            COUNT( parametroradiacionionizante.recsensorial_id ) AS totalregistros,
                                                            SUM( parametroradiacionionizante.parametroradiacionionizante_puntos ) AS totalpuntos 
                                                        FROM
                                                            parametroradiacionionizante 
                                                        WHERE
                                                            parametroradiacionionizante.recsensorial_id = ' . $recsensorial_id . ' 
                                                        GROUP BY
                                                            parametroradiacionionizante.recsensorial_id
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            6 AS catprueba_id,
                                                            NULL AS agente_subnombre,
                                                            COUNT( parametroradiacionnoionizante.recsensorial_id ) AS totalregistros,
                                                            SUM( parametroradiacionnoionizante.parametroradiacionnoionizante_puntos ) AS totalpuntos 
                                                        FROM
                                                            parametroradiacionnoionizante 
                                                        WHERE
                                                            parametroradiacionnoionizante.recsensorial_id = ' . $recsensorial_id . ' 
                                                        GROUP BY
                                                            parametroradiacionnoionizante.recsensorial_id
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            7 AS catprueba_id,
                                                            NULL AS agente_subnombre,
                                                            COUNT( parametroprecionesambientales.recsensorial_id ) AS totalregistros,
                                                            SUM( parametroprecionesambientales.parametroprecionesambientales_puntos ) AS totalpuntos 
                                                        FROM
                                                            parametroprecionesambientales 
                                                        WHERE
                                                            parametroprecionesambientales.recsensorial_id = ' . $recsensorial_id . ' 
                                                        GROUP BY
                                                            parametroprecionesambientales.recsensorial_id
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            8 AS catprueba_id,
                                                            NULL AS agente_subnombre,
                                                            COUNT( parametrocalidadaire.recsensorial_id ) AS totalregistros,
                                                            SUM( parametrocalidadaire.parametrocalidadaire_puntos ) AS totalpuntos 
                                                        FROM
                                                            parametrocalidadaire 
                                                        WHERE
                                                            parametrocalidadaire.recsensorial_id = ' . $recsensorial_id . ' 
                                                        GROUP BY
                                                            parametrocalidadaire.recsensorial_id
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            9 AS catprueba_id,
                                                            "Fisicoquímico" AS agente_subnombre,
                                                            Count( parametroagua.recsensorial_id ) AS totalregistros,
                                                            Sum( parametroagua.parametroagua_puntos ) AS totalpuntos
                                                        FROM
                                                            parametroagua
                                                            INNER JOIN catparametroaguacaracteristica ON parametroagua.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id 
                                                        WHERE
                                                            parametroagua.recsensorial_id = ' . $recsensorial_id . ' AND catparametroaguacaracteristica.catparametroaguacaracteristica_tipo = "Fisicoquímico"
                                                        GROUP BY
                                                            parametroagua.recsensorial_id
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            9 AS catprueba_id,
                                                            "Microbiológico" AS agente_subnombre,
                                                            Count( parametroagua.recsensorial_id ) AS totalregistros,
                                                            Sum( parametroagua.parametroagua_puntos ) AS totalpuntos
                                                        FROM
                                                            parametroagua
                                                            LEFT JOIN catparametroaguacaracteristica ON parametroagua.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id 
                                                        WHERE
                                                            parametroagua.recsensorial_id = ' . $recsensorial_id . ' AND catparametroaguacaracteristica.catparametroaguacaracteristica_tipo = "Microbiológico"
                                                        GROUP BY
                                                            parametroagua.recsensorial_id
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            10 AS catprueba_id,
                                                            "Fisicoquímico" AS agente_subnombre,
                                                            Count( parametrohielo.recsensorial_id ) AS totalregistros,
                                                            Sum( parametrohielo.parametrohielo_puntos ) AS totalpuntos
                                                        FROM
                                                            parametrohielo
                                                            INNER JOIN catparametrohielocaracteristica ON parametrohielo.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id 
                                                        WHERE
                                                            parametrohielo.recsensorial_id = ' . $recsensorial_id . ' AND catparametrohielocaracteristica.catparametrohielocaracteristica_tipo = "Fisicoquímico"
                                                        GROUP BY
                                                            parametrohielo.recsensorial_id
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            10 AS catprueba_id,
                                                            "Microbiológico" AS agente_subnombre,
                                                            Count( parametrohielo.recsensorial_id ) AS totalregistros,
                                                            Sum( parametrohielo.parametrohielo_puntos ) AS totalpuntos
                                                        FROM
                                                            parametrohielo
                                                            INNER JOIN catparametrohielocaracteristica ON parametrohielo.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id 
                                                        WHERE
                                                            parametrohielo.recsensorial_id = ' . $recsensorial_id . ' AND catparametrohielocaracteristica.catparametrohielocaracteristica_tipo = "Microbiológico"
                                                        GROUP BY
                                                            parametrohielo.recsensorial_id
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            11 AS catprueba_id,
                                                            NULL AS agente_subnombre,
                                                            COUNT( parametroalimento.recsensorial_id ) AS totalregistros,
                                                            SUM( parametroalimento.parametroalimento_puntos ) AS totalpuntos 
                                                        FROM
                                                            parametroalimento 
                                                        WHERE
                                                            parametroalimento.recsensorial_id = ' . $recsensorial_id . ' 
                                                        GROUP BY
                                                            parametroalimento.recsensorial_id
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            12 AS catprueba_id,
                                                            NULL AS agente_subnombre,
                                                            COUNT( parametrosuperficie.recsensorial_id ) AS totalregistros,
                                                            SUM( parametrosuperficie.parametrosuperficie_puntos ) AS totalpuntos 
                                                        FROM
                                                            parametrosuperficie 
                                                        WHERE
                                                            parametrosuperficie.recsensorial_id = ' . $recsensorial_id . ' 
                                                        GROUP BY
                                                            parametrosuperficie.recsensorial_id
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            13 AS catprueba_id,
                                                            NULL AS agente_subnombre,
                                                            TABLAERGONOMIA.recsensorial_id,
                                                            SUM(TABLAERGONOMIA.totalpuntos) AS totalpuntos
                                                        FROM
                                                            (
                                                                SELECT
                                                                    parametroergonomia.recsensorial_id,
                                                                    parametroergonomia.id,
                                                                    IF((parametroergonomia.parametroergonomia_movimientorepetitivo + parametroergonomia.parametroergonomia_posturamantenida + parametroergonomia.parametroergonomia_posturaforzada + parametroergonomia.parametroergonomia_cargamanual +parametroergonomia.parametroergonomia_fuerza) > 1, 1, 0) AS totalpuntos
                                                                FROM
                                                                    parametroergonomia
                                                                WHERE
                                                                    parametroergonomia.recsensorial_id = ' . $recsensorial_id . '
                                                                GROUP BY
                                                                    parametroergonomia.recsensorial_id,
                                                                    parametroergonomia.id,
                                                                    parametroergonomia.parametroergonomia_movimientorepetitivo,
                                                                    parametroergonomia.parametroergonomia_posturamantenida,
                                                                    parametroergonomia.parametroergonomia_posturaforzada,
                                                                    parametroergonomia.parametroergonomia_cargamanual,
                                                                    parametroergonomia.parametroergonomia_fuerza
                                                            ) AS TABLAERGONOMIA
                                                        WHERE
                                                            TABLAERGONOMIA.totalpuntos > 0
                                                        GROUP BY
                                                            TABLAERGONOMIA.recsensorial_id
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            14 AS catprueba_id,
                                                            NULL AS agente_subnombre,
                                                            COUNT( parametropsicosocial.recsensorial_id ) AS totalregistros,
                                                            SUM( parametropsicosocial.parametropsicosocial_nopersonas ) AS totalpuntos 
                                                        FROM
                                                            parametropsicosocial 
                                                        WHERE
                                                            parametropsicosocial.recsensorial_id = ' . $recsensorial_id . ' 
                                                        GROUP BY
                                                            parametropsicosocial.recsensorial_id
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            TABLASERVICIOPERSONAL.catprueba_id,
                                                            TABLASERVICIOPERSONAL.agente_subnombre,
                                                            TABLASERVICIOPERSONAL.totalregistros,
                                                            TABLASERVICIOPERSONAL.totalpuntos
                                                        FROM
                                                            (
                                                                SELECT
                                                                    16 AS catprueba_id,
                                                                    NULL AS agente_subnombre,
                                                                    COUNT( parametroserviciopersonal.recsensorial_id ) AS totalregistros,
                                                                    SUM( parametroserviciopersonal.parametroserviciopersonal_puntos ) AS totalpuntos 
                                                                FROM
                                                                    parametroserviciopersonal 
                                                                WHERE
                                                                    parametroserviciopersonal.recsensorial_id = ' . $recsensorial_id . ' 
                                                                GROUP BY
                                                                    parametroserviciopersonal.recsensorial_id
                                                            ) AS TABLASERVICIOPERSONAL
                                                        WHERE
                                                            TABLASERVICIOPERSONAL.totalpuntos > 1
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            17 AS catprueba_id,
                                                            "Tipo 1" AS agente_subnombre,
                                                            COUNT(parametromapariesgo.parametromapariesgo_tipo1) AS totalregistros,
                                                            SUM(parametromapariesgo.parametromapariesgo_tipo1) AS totalpuntos
                                                        FROM
                                                            parametromapariesgo
                                                        WHERE
                                                            parametromapariesgo.recsensorial_id = ' . $recsensorial_id . '
                                                        GROUP BY
                                                            parametromapariesgo.recsensorial_id
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            17 AS catprueba_id,
                                                            "Tipo 2" AS agente_subnombre,
                                                            COUNT(parametromapariesgo.parametromapariesgo_tipo2) AS totalregistros,
                                                            SUM(parametromapariesgo.parametromapariesgo_tipo2) AS totalpuntos
                                                        FROM
                                                            parametromapariesgo
                                                        WHERE
                                                            parametromapariesgo.recsensorial_id = ' . $recsensorial_id . '
                                                        GROUP BY
                                                            parametromapariesgo.recsensorial_id
                                                    )
                                                ) TABLA1
                                                LEFT JOIN recsensorialpruebas ON TABLA1.catprueba_id = recsensorialpruebas.catprueba_id
                                                LEFT JOIN cat_prueba ON recsensorialpruebas.catprueba_id = cat_prueba.id
                                            WHERE
                                                recsensorialpruebas.recsensorial_id = ' . $recsensorial_id . '
                                                AND recsensorialpruebas.catprueba_id != 15
                                                AND TABLA1.totalpuntos > 0
                                            GROUP BY
                                                recsensorialpruebas.recsensorial_id,
                                                cat_prueba.catPrueba_Tipo,
                                                TABLA1.catprueba_id,
                                                cat_prueba.catPrueba_Nombre,
                                                TABLA1.agente_subnombre,
                                                TABLA1.totalregistros,
                                                TABLA1.totalpuntos
                                            ORDER BY
                                                TABLA1.totalpuntos DESC,
                                                cat_prueba.catPrueba_Nombre ASC');
                } else  //Reconocimiento de FISICOS puntos cliente
                {
                    $fisicos = DB::select('SELECT
                                                recsensorialagentescliente.recsensorial_id,
                                                recsensorialagentescliente.agentescliente_nombre AS catPrueba_Nombre,
                                                recsensorialagentescliente.agentescliente_puntos AS totalpuntos,
                                                (
                                                    IF((SELECT recsensorial_tipocliente FROM recsensorial WHERE recsensorial.id = ' . $recsensorial_id . ' LIMIT 1) = 1, -- Si el cliente es Pemex
                                                        IF(recsensorialagentescliente.agentescliente_agenteid != 17,
                                                            IF(recsensorialagentescliente.agentescliente_agenteid != 16,
                                                                CASE
                                                                    WHEN recsensorialagentescliente.agentescliente_puntos >= 151 THEN "Extra grande"
                                                                    WHEN recsensorialagentescliente.agentescliente_puntos >= 81 THEN "Grande"
                                                                    WHEN recsensorialagentescliente.agentescliente_puntos >= 41 THEN "Mediana"
                                                                    WHEN recsensorialagentescliente.agentescliente_puntos >= 21 THEN "Chica"
                                                                    ELSE "Extra chica"
                                                                END
                                                            ,
                                                                CASE
                                                                    WHEN recsensorialagentescliente.agentescliente_puntos >= 50 THEN "Extra grande"
                                                                    WHEN recsensorialagentescliente.agentescliente_puntos >= 31 THEN "Grande"
                                                                    WHEN recsensorialagentescliente.agentescliente_puntos >= 11 THEN "Mediana"
                                                                    WHEN recsensorialagentescliente.agentescliente_puntos >= 5 THEN "Chica"
                                                                    WHEN recsensorialagentescliente.agentescliente_puntos >= 2 THEN "Extra chica"
                                                                    ELSE "N/A"
                                                                END
                                                            )
                                                        ,
                                                            "N/A"
                                                        )
                                                    ,
                                                        IF(recsensorialagentescliente.agentescliente_agenteid != 17,
                                                            IF(recsensorialagentescliente.agentescliente_agenteid != 16,
                                                                CASE
                                                                    -- WHEN recsensorialagentescliente.agentescliente_puntos >= 151 THEN "Extra grande"
                                                                    WHEN recsensorialagentescliente.agentescliente_puntos >= 81 THEN "Grande"
                                                                    WHEN recsensorialagentescliente.agentescliente_puntos >= 41 THEN "Mediana"
                                                                    WHEN recsensorialagentescliente.agentescliente_puntos >= 21 THEN "Chica"
                                                                    ELSE "Extra chica"
                                                                END
                                                            ,
                                                                CASE
                                                                    WHEN recsensorialagentescliente.agentescliente_puntos >= 50 THEN "Extra grande"
                                                                    WHEN recsensorialagentescliente.agentescliente_puntos >= 31 THEN "Grande"
                                                                    WHEN recsensorialagentescliente.agentescliente_puntos >= 11 THEN "Mediana"
                                                                    WHEN recsensorialagentescliente.agentescliente_puntos >= 5 THEN "Chica"
                                                                    WHEN recsensorialagentescliente.agentescliente_puntos >= 2 THEN "Extra chica"
                                                                    ELSE "N/A"
                                                                END
                                                            )
                                                        ,
                                                            "N/A"
                                                        )
                                                    )
                                                ) AS tipoinstalacion
                                            FROM
                                                recsensorialagentescliente
                                            WHERE
                                                recsensorialagentescliente.recsensorial_id = ' . $recsensorial_id . '
                                                AND recsensorialagentescliente.agentescliente_agenteid != 15
                                            ORDER BY
                                                recsensorialagentescliente.agentescliente_puntos DESC');
                }

                // Dibujar filas
                if (count($fisicos) > 0) {
                    $numero_registro = 0;
                    $filas_fisicos = '';

                    // Formatear filas Fisicos
                    foreach ($fisicos as $key => $value) {
                        $numero_registro += 1;
                        $filas_fisicos .= '<tr>
                                                <td>' . $numero_registro . '</td>
                                                <td>' . $value->catPrueba_Nombre . '</td>
                                                <td>' . $value->totalpuntos . '</td>
                                                <td>' . $value->tipoinstalacion . '</td>
                                            </tr>';
                    }
                }
            } else {
                $filas_fisicos = '<tr><td colspan="4" style="text-align: center;">No hay datos que mostrar</td></tr>';
            }



            // Resumen de quimicos
            if (($recsensorial[0]->recsensorial_alcancequimico + 0) > 0) {
                if (($recsensorial[0]->recsensorial_alcancequimico + 0) == 1) //Reconocimiento de QUIMICOS completo
                {
                    //Valida el resumen de quimicos (Sin uso, ya que no importa si esta o no validado el reconocimiento)
                    // if (($recsensorial[0]->recsensorial_quimicovalidado + 0) == 1) {
                    $quimicos = DB::select('SELECT
                                                    TABLA6.componente,
                                                    COUNT(TABLA6.componente) AS tot_registros,
                                                    IF(MAX(TABLA6.MUESTREO_PPT) > 0, SUM(TABLA6.TOTAL_MUESTREOS), "ND") AS MUESTREO_PPT,
                                                    IF(MAX(TABLA6.MUESTREO_CT) > 0, SUM(TABLA6.TOTAL_MUESTREOS), "ND") AS MUESTREO_CT,
                                                    SUM(TABLA6.TOTAL_MUESTREOS) AS TOTAL_MUESTREOS,
                                                    (
                                                        IF((SELECT recsensorial_tipocliente FROM recsensorial WHERE recsensorial.id = ' . $recsensorial_id . ' LIMIT 1) = 1, -- Si el cliente es Pemex
                                                            CASE
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 151 THEN "Extra grande"
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 81 THEN "Grande"
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 41 THEN "Mediana"
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 21 THEN "Chica"
                                                                ELSE "Extra chica"
                                                            END
                                                        ,
                                                            CASE
                                                                -- WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 151 THEN "Extra grande"
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 81 THEN "Grande"
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 41 THEN "Mediana"
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 21 THEN "Chica"
                                                                ELSE "Extra chica"
                                                            END
                                                        )
                                                    ) AS tipoinstalacion,
                                                    (
                                                        IF((SELECT recsensorial_tipocliente FROM recsensorial WHERE recsensorial.id = ' . $recsensorial_id . ' LIMIT 1) = 1, -- Si el cliente es Pemex
                                                            CASE
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 151 THEN "XG"
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 81 THEN "G"
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 41 THEN "M"
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 21 THEN "CH"
                                                                ELSE "XC"
                                                            END
                                                        ,
                                                            CASE
                                                                -- WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 151 THEN "XG"
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 81 THEN "G"
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 41 THEN "M"
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 21 THEN "CH"
                                                                ELSE "XC"
                                                            END
                                                        )
                                                    ) AS tipoinstalacion_abreviacion,
                                                    (
                                                        IF((SELECT recsensorial_tipocliente FROM recsensorial WHERE recsensorial.id = ' . $recsensorial_id . ' LIMIT 1) = 1, -- Si el cliente es Pemex
                                                            CASE
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 151 THEN "#DF0101"
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 81 THEN "#FF8000"
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 41 THEN "#FFD700"
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 21 THEN "#5FB404"
                                                                ELSE "#0080FF"
                                                            END
                                                        ,
                                                            CASE
                                                                -- WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 151 THEN "#DF0101"
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 81 THEN "#FF8000"
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 41 THEN "#FFD700"
                                                                WHEN SUM(TABLA6.TOTAL_MUESTREOS) >= 21 THEN "#5FB404"
                                                                ELSE "#0080FF"
                                                            END
                                                        )
                                                    ) AS tipoinstalacion_color
                                                FROM
                                                    (
                                                        SELECT
                                                            -- *,
                                                            -- TABLA5.area_id,
                                                            -- TABLA5.area_nombre,
                                                            TABLA5.categoria_id,
                                                            TABLA5.categoria_nombre,
                                                            -- TABLA5.id,
                                                            -- TABLA5.sustancia_nombre,
                                                            TABLA5.componente,
                                                            -- IF(TABLA5.MUESTREO_PPT > 0, TABLA5.TOTAL_MUESTREOS, "ND") AS MUESTREO_PPT,
                                                            -- IF(TABLA5.MUESTREO_CT > 0, TABLA5.TOTAL_MUESTREOS, "ND") AS MUESTREO_CT,
                                                            IF(TABLA5.MUESTREO_PPT > 0, 1, 0) AS MUESTREO_PPT,
                                                            IF(TABLA5.MUESTREO_CT > 0, 1, 0) AS MUESTREO_CT,
                                                            -- TABLA5.TOTAL_MUESTREOS
                                                            SUM(TABLA5.TOTAL_MUESTREOS) AS TOTAL_MUESTREOS
                                                        FROM
                                                                (
                                                                    SELECT
                                                                        -- *,
                                                                        TABLA4.area_id,
                                                                        TABLA4.area_nombre,
                                                                        TABLA4.categoria_id,
                                                                        TABLA4.categoria_nombre,
                                                                        TABLA4.id,
                                                                        TABLA4.sustancia_nombre,
                                                                        TABLA4.componente,
                                                                        -- (TABLA4.componente_ponderacion_cantidad + TABLA4.ponderacion_riesgo + TABLA4.ponderacion_volatilidad) AS COMPONENTE_PONDERACION_TOTAL,       
                                                                        TABLA4.TOTAL2,
                                                                        TABLA4.PRIORIDAD2,
                                                                        TABLA4.NUMERO_MUESTREOS,
                                                                        TABLA4.MUESTREO_PPT,
                                                                        TABLA4.MUESTREO_CT,
                                                                        TABLA4.TOTAL_MUESTREOS
                                                                    FROM
                                                                        (
                                                                            SELECT
                                                                                -- *,
                                                                                TABLA3.area_id,
                                                                                TABLA3.area_nombre,
                                                                                TABLA3.categoria_id,
                                                                                TABLA3.categoria_nombre,
                                                                                TABLA3.id,
                                                                                TABLA3.sustancia_nombre,
                                                                                -- datos ponderacion componente
                                                                                TABLA3.sustancia_cantidad,
                                                                                TABLA3.Umedida_ID,
                                                                                TABLA3.ponderacion_cantidad,
                                                                                TABLA3.ponderacion_riesgo,
                                                                                TABLA3.ponderacion_volatilidad,
                                                                                REPLACE(TABLA3.componente, "ˏ", ",") AS componente,
                                                                                TABLA3.cancerigeno,
                                                                                TABLA3.componente_porcentaje_real,
                                                                                TABLA3.componente_porcentaje,
                                                                                TABLA3.componente_cantidad,
                                                                                (
                                                                                    CASE
                                                                                        WHEN TABLA3.componente_cantidad = 0 THEN 0
                                                                                        WHEN TABLA3.cancerigeno = 1 THEN 5 -- cancerigeno
                                                                                        ELSE 
                                                                                            CASE
                                                                                                WHEN (TABLA3.Umedida_ID = 3 || TABLA3.Umedida_ID = 6) THEN -- M3 ó toneladas
                                                                                                    CASE
                                                                                                        WHEN TABLA3.componente_cantidad >= 1 THEN 4
                                                                                                        ELSE
                                                                                                            CASE
                                                                                                                WHEN (TABLA3.componente_cantidad * 1000) >= 1 THEN
                                                                                                                    CASE
                                                                                                                        WHEN (TABLA3.componente_cantidad * 1000) > 250 THEN 3
                                                                                                                        ELSE 2
                                                                                                                    END
                                                                                                                ELSE 1
                                                                                                            END
                                                                                                    END
                                                                                                WHEN (TABLA3.Umedida_ID = 2 || TABLA3.Umedida_ID = 5) THEN -- Litros ó Kilos
                                                                                                    CASE
                                                                                                        WHEN TABLA3.componente_cantidad >= 1 THEN
                                                                                                            CASE
                                                                                                                WHEN TABLA3.componente_cantidad > 250 THEN 3
                                                                                                                ELSE 2
                                                                                                            END
                                                                                                        ELSE 1
                                                                                                    END
                                                                                                ELSE 1
                                                                                            END
                                                                                    END
                                                                                ) AS componente_ponderacion_cantidad,
                                                                                -- / datos ponderacion componente
                                                                                TABLA3.TOTAL2,
                                                                                TABLA3.PRIORIDAD2,
                                                                                TABLA3.NUMERO_MUESTREOS,
                                                                                IF(TABLA3.catsustanciacomponente_exposicionppt > 0, TABLA3.NUMERO_MUESTREOS, "ND") AS MUESTREO_PPT,
                                                                                IF(TABLA3.catsustanciacomponente_exposicionctop > 0, TABLA3.NUMERO_MUESTREOS, "ND") AS MUESTREO_CT,
                                                                                (IF(TABLA3.catsustanciacomponente_exposicionppt > 0, TABLA3.NUMERO_MUESTREOS, IF(TABLA3.catsustanciacomponente_exposicionctop > 0, TABLA3.NUMERO_MUESTREOS, 0))) AS TOTAL_MUESTREOS
                                                                            FROM
                                                                                (
                                                                                    SELECT
                                                                                        -- *,
                                                                                        TABLA2.area_id,
                                                                                        IFNULL(area_nombre, "Sin dato") AS area_nombre,
                                                                                        TABLA2.categoria_id,
                                                                                        -- IFNULL(CONCAT(categoria_geh, ".- ", categoria_nombre, " (", categoria_funcion, ")"), "Sin dato") AS categoria_nombre,
                                                                                        IFNULL(CONCAT(categoria_geh, ".- ", categoria_nombre), "Sin dato") AS categoria_nombre,
                                                                                        TABLA2.id,
                                                                                        IFNULL(sustancia_nombre, "Sin dato") AS sustancia_nombre,
                                                                                        -- datos ponderacion componente
                                                                                        TABLA2.sustancia_cantidad,
                                                                                        TABLA2.Umedida_ID,
                                                                                        TABLA2.ponderacion_cantidad,
                                                                                        TABLA2.ponderacion_riesgo,
                                                                                        TABLA2.ponderacion_volatilidad,
                                                                                        catsustanciacomponente.catsustanciacomponente_nombre AS componente,
                                                                                        TABLA2.cancerigeno,
                                                                                        IFNULL(catsustanciacomponente.catsustanciacomponente_porcentaje, 0) AS componente_porcentaje_real,
                                                                                        IF(IFNULL(catsustanciacomponente.catsustanciacomponente_porcentaje, 0) >= 1, ROUND((catsustanciacomponente.catsustanciacomponente_porcentaje / 100), 4), 0) AS componente_porcentaje,
                                                                                        ROUND((IFNULL(TABLA2.sustancia_cantidad, 0) * IF(IFNULL(catsustanciacomponente.catsustanciacomponente_porcentaje, 0) >= 1, ROUND((catsustanciacomponente.catsustanciacomponente_porcentaje / 100), 4), 0)), 1) AS componente_cantidad,
                                                                                        -- / datos ponderacion componente
                                                                                        catsustanciacomponente.catsustanciacomponente_exposicionppt,
                                                                                        catsustanciacomponente.catsustanciacomponente_exposicionctop,
                                                                                        (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) AS TOTAL2,
                                                                                        (
                                                                                            CASE
                                                                                                WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 13 THEN "Muy alta"
                                                                                                WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 9 THEN "Alta"
                                                                                                WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 4 THEN "Moderada"
                                                                                                ELSE "Baja"
                                                                                            END
                                                                                        ) AS PRIORIDAD2,
                                                                                        (
                                                                                            CASE
                                                                                                WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 13 THEN
                                                                                                    CASE
                                                                                                        WHEN tot_trabajadores > 100 THEN 20
                                                                                                        WHEN tot_trabajadores >= 51 THEN 15
                                                                                                        WHEN tot_trabajadores >= 26 THEN 8
                                                                                                        WHEN tot_trabajadores >= 16 THEN 5
                                                                                                        WHEN tot_trabajadores >= 9 THEN 3
                                                                                                        WHEN tot_trabajadores >= 3 THEN 2
                                                                                                        ELSE 1
                                                                                                    END
                                                                                                ELSE 
                                                                                                    CASE
                                                                                                        WHEN tot_trabajadores > 100 THEN 10
                                                                                                        WHEN tot_trabajadores >= 51 THEN 7
                                                                                                        WHEN tot_trabajadores >= 31 THEN 5
                                                                                                        WHEN tot_trabajadores >= 21 THEN 4
                                                                                                        WHEN tot_trabajadores >= 11 THEN 3
                                                                                                        WHEN tot_trabajadores >= 6 THEN 2
                                                                                                        ELSE 1
                                                                                                    END
                                                                                            END
                                                                                        ) AS NUMERO_MUESTREOS
                                                                                    FROM
                                                                                        (
                                                                                            SELECT
                                                                                                *,
                                                                                                (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) AS TOTAL,
                                                                                                (
                                                                                                    CASE
                                                                                                        WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 12 THEN "Muy alta"
                                                                                                        WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 10 THEN "Alta"
                                                                                                        WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 8 THEN "Moderada"
                                                                                                        WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 5 THEN "Baja"
                                                                                                        ELSE "Muy baja"
                                                                                                    END
                                                                                                ) AS PRIORIDAD
                                                                                            FROM
                                                                                                (
                                                                                                    SELECT
                                                                                                        recsensorialquimicosinventario.recsensorial_id,
                                                                                                        recsensorialquimicosinventario.id,
                                                                                                        recsensorialquimicosinventario.recsensorialarea_id AS area_id,
                                                                                                        recsensorialarea.recsensorialarea_nombre AS area_nombre,
                                                                                                        recsensorialquimicosinventario.recsensorialcategoria_id AS categoria_id,
                                                                                                        recsensorialcategoria.recsensorialcategoria_nombrecategoria AS categoria_nombre,
                                                                                                        recsensorialcategoria.recsensorialcategoria_funcioncategoria AS categoria_funcion,
                                                                                                        recsensorialareacategorias.recsensorialareacategorias_actividad AS categoria_actividad,
                                                                                                        recsensorialareacategorias.recsensorialareacategorias_geh AS categoria_geh,
                                                                                                        recsensorialcategoria.recsensorialcategoria_horasjornada AS horas_jornada,
                                                                                                        recsensorialareacategorias.recsensorialareacategorias_total AS tot_trabajadores,
                                                                                                        IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) AS tiempo_expo,
                                                                                                        IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0) AS frecuencia_expo,
                                                                                                        recsensorialcategoria.recsensorialcategoria_horarioentrada AS horario_entrada,
                                                                                                        recsensorialcategoria.recsensorialcategoria_horariosalida AS horario_salida,
                                                                                                        recsensorialquimicosinventario.catsustancia_id AS sustancia_id,
                                                                                                        catsustancia.catsustancia_nombre AS sustancia_nombre,
                                                                                                        recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad AS sustancia_cantidad,
                                                                                                        recsensorialquimicosinventario.catunidadmedidasustacia_id AS Umedida_ID,
                                                                                                        catunidadmedidasustacia.catunidadmedidasustacia_descripcion AS Umedida,
                                                                                                        catunidadmedidasustacia.catunidadmedidasustacia_abreviacion AS UmedidaAbrev,
                                                                                                        catunidadmedidasustacia.catunidadmedidasustacia_ponderacion AS PONDERACION_ORIGINAL,
                                                                                                        IFNULL((
                                                                                                            SELECT
                                                                                                                COUNT(catsustanciacomponente.catsustanciacomponente_connotacion)
                                                                                                            FROM
                                                                                                                catsustanciacomponente 
                                                                                                            WHERE
                                                                                                                catsustanciacomponente.catsustancia_id = recsensorialquimicosinventario.catsustancia_id
                                                                                                                AND (catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A1%" OR 
                                                                                                                catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A2%" OR 
                                                                                                                catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Teratogenica%" OR 
                                                                                                                catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Mutagenica%")
                                                                                                        ), 0) AS cancerigeno,
                                                                                                        (
                                                                                                            CASE
                                                                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 6 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 4 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 3 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                                                WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 1 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                                                ELSE 0
                                                                                                            END
                                                                                                        ) AS ponderacion_cantidad,
                                                                                                        catgradoriesgosalud.catgradoriesgosalud_ponderacion AS ponderacion_riesgo,
                                                                                                        catvolatilidad.catvolatilidad_ponderacion AS ponderacion_volatilidad,
                                                                                                        catviaingresoorganismo.catviaingresoorganismo_ponderacion AS tot_ingresoorganismo,
                                                                                                        (
                                                                                                            CASE
                                                                                                                WHEN recsensorialareacategorias.recsensorialareacategorias_total > 100 THEN 8
                                                                                                                WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 25 THEN 4
                                                                                                                WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 5 THEN 2
                                                                                                                ELSE 1
                                                                                                            END
                                                                                                        ) AS tot_personalexposicion,
                                                                                                        (
                                                                                                            CASE
                                                                                                                WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 7 THEN 8
                                                                                                                WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 3 THEN 4
                                                                                                                WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 1 THEN 2
                                                                                                                ELSE 1
                                                                                                            END
                                                                                                        ) AS tot_tiempoexposicion,
                                                                                                        (IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0)) AS suma_tiempoexposicion
                                                                                                        -- ,catsustancia.catestadofisicosustancia_id,
                                                                                                        -- catestadofisicosustancia.catestadofisicosustancia_estado,
                                                                                                        -- catsustancia.catvolatilidad_id,
                                                                                                        -- catvolatilidad.catvolatilidad_tipo,
                                                                                                        -- catsustancia.catviaingresoorganismo_id,
                                                                                                        -- catviaingresoorganismo.catviaingresoorganismo_viaingreso,
                                                                                                        -- catsustancia.catcategoriapeligrosalud_id,
                                                                                                        -- catcategoriapeligrosalud.id,
                                                                                                        -- catcategoriapeligrosalud.catcategoriapeligrosalud_codigo,
                                                                                                        -- catsustancia.catgradoriesgosalud_id,
                                                                                                        -- catgradoriesgosalud.catgradoriesgosalud_clasificacion
                                                                                                    FROM
                                                                                                        recsensorialquimicosinventario
                                                                                                        LEFT JOIN recsensorialarea ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialarea.id
                                                                                                        LEFT JOIN recsensorialareacategorias ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialareacategorias.recsensorialarea_id 
                                                                                                        AND recsensorialquimicosinventario.recsensorialcategoria_id = recsensorialareacategorias.recsensorialcategoria_id
                                                                                                        LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                                                                                        LEFT JOIN catsustancia ON recsensorialquimicosinventario.catsustancia_id = catsustancia.id
                                                                                                        LEFT JOIN catunidadmedidasustacia ON recsensorialquimicosinventario.catunidadmedidasustacia_id = catunidadmedidasustacia.id
                                                                                                        LEFT JOIN catestadofisicosustancia ON catsustancia.catestadofisicosustancia_id = catestadofisicosustancia.id
                                                                                                        LEFT JOIN catvolatilidad ON catsustancia.catvolatilidad_id = catvolatilidad.id
                                                                                                        LEFT JOIN catviaingresoorganismo ON catsustancia.catviaingresoorganismo_id = catviaingresoorganismo.id
                                                                                                        LEFT JOIN catcategoriapeligrosalud ON catsustancia.catcategoriapeligrosalud_id = catcategoriapeligrosalud.id
                                                                                                        LEFT JOIN catgradoriesgosalud ON catsustancia.catgradoriesgosalud_id = catgradoriesgosalud.id 
                                                                                                    WHERE
                                                                                                        recsensorialquimicosinventario.recsensorial_id = ' . $recsensorial_id . ' 
                                                                                                    GROUP BY
                                                                                                        recsensorialquimicosinventario.recsensorial_id,
                                                                                                        recsensorialquimicosinventario.id,
                                                                                                        recsensorialquimicosinventario.recsensorialarea_id,
                                                                                                        recsensorialarea.recsensorialarea_nombre,
                                                                                                        recsensorialquimicosinventario.recsensorialcategoria_id,
                                                                                                        recsensorialcategoria.recsensorialcategoria_nombrecategoria,
                                                                                                        recsensorialcategoria.recsensorialcategoria_funcioncategoria,
                                                                                                        recsensorialareacategorias.recsensorialareacategorias_actividad,
                                                                                                        recsensorialareacategorias.recsensorialareacategorias_geh,
                                                                                                        recsensorialcategoria.recsensorialcategoria_horasjornada,
                                                                                                        recsensorialareacategorias.recsensorialareacategorias_total,
                                                                                                        recsensorialcategoria.recsensorialcategoria_horarioentrada,
                                                                                                        recsensorialcategoria.recsensorialcategoria_horariosalida,
                                                                                                        recsensorialquimicosinventario.catsustancia_id,
                                                                                                        catsustancia.catsustancia_nombre,
                                                                                                        recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad,
                                                                                                        recsensorialquimicosinventario.catunidadmedidasustacia_id,
                                                                                                        recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo,
                                                                                                        recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo,
                                                                                                        catunidadmedidasustacia.catunidadmedidasustacia_descripcion,
                                                                                                        catunidadmedidasustacia.catunidadmedidasustacia_abreviacion,
                                                                                                        catunidadmedidasustacia.catunidadmedidasustacia_ponderacion,
                                                                                                        catgradoriesgosalud.catgradoriesgosalud_ponderacion,
                                                                                                        catvolatilidad.catvolatilidad_ponderacion,
                                                                                                        catviaingresoorganismo.catviaingresoorganismo_ponderacion
                                                                                                ) AS TABLA1
                                                                                            ORDER BY
                                                                                                area_id ASC,
                                                                                                categoria_id ASC,
                                                                                                TOTAL DESC
                                                                                        ) AS TABLA2
                                                                                        RIGHT JOIN catsustanciacomponente ON catsustanciacomponente.catsustancia_id = sustancia_id
                                                                                    WHERE
                                                                                        TOTAL >= 8
                                                                                    ORDER BY
                                                                                        TABLA2.area_id ASC,
                                                                                        TABLA2.categoria_id ASC,
                                                                                        TABLA2.id ASC
                                                                                ) AS TABLA3
                                                                            WHERE
                                                                                TOTAL2 >= 4
                                                                            GROUP BY
                                                                                TABLA3.area_id,
                                                                                TABLA3.area_nombre,
                                                                                TABLA3.categoria_id,
                                                                                TABLA3.categoria_nombre,
                                                                                TABLA3.id,
                                                                                TABLA3.sustancia_nombre,
                                                                                -- datos ponderacion componente
                                                                                TABLA3.sustancia_cantidad,
                                                                                TABLA3.Umedida_ID,
                                                                                TABLA3.ponderacion_cantidad,
                                                                                TABLA3.ponderacion_riesgo,
                                                                                TABLA3.ponderacion_volatilidad,
                                                                                TABLA3.componente,
                                                                                TABLA3.cancerigeno,
                                                                                TABLA3.componente_porcentaje_real,
                                                                                TABLA3.componente_porcentaje,
                                                                                TABLA3.componente_cantidad,
                                                                                -- / datos ponderacion componente
                                                                                TABLA3.TOTAL2,
                                                                                TABLA3.PRIORIDAD2,
                                                                                TABLA3.NUMERO_MUESTREOS,
                                                                                TABLA3.catsustanciacomponente_exposicionppt,
                                                                                TABLA3.catsustanciacomponente_exposicionctop
                                                                            ORDER BY
                                                                                TABLA3.area_id ASC,
                                                                                TABLA3.categoria_id ASC,
                                                                                TABLA3.id ASC
                                                                        ) AS TABLA4
                                                                    WHERE
                                                                        TABLA4.TOTAL_MUESTREOS > 0
                                                                        -- AND (TABLA4.componente_ponderacion_cantidad + TABLA4.ponderacion_riesgo + TABLA4.ponderacion_volatilidad) >= 8
                                                                ) AS TABLA5
                                                        GROUP BY
                                                            -- TABLA5.area_id,
                                                            -- TABLA5.area_nombre,
                                                            TABLA5.categoria_id,
                                                            TABLA5.categoria_nombre,
                                                            -- TABLA5.id,
                                                            -- TABLA5.sustancia_nombre,
                                                            TABLA5.componente,
                                                            TABLA5.MUESTREO_PPT,
                                                            TABLA5.MUESTREO_CT,
                                                            TABLA5.TOTAL_MUESTREOS
                                                        ORDER BY
                                                            -- TABLA5.area_id ASC,
                                                            TABLA5.categoria_id ASC,
                                                            -- TABLA5.id ASC,
                                                            TABLA5.componente ASC
                                                    ) AS TABLA6
                                                GROUP BY
                                                    TABLA6.componente
                                                ORDER BY
                                                    SUM(TABLA6.TOTAL_MUESTREOS) DESC,
                                                    TABLA6.componente ASC');

                    // Dibujar filas
                    if (count($quimicos) > 0) {
                        $numero_registro = 0;
                        $filas_quimicos = '';

                        // Formatear filas Quimicos
                        foreach ($quimicos as $key => $value) {
                            $numero_registro += 1;
                            $filas_quimicos .= '<tr>
                                                        <td>' . $numero_registro . '</td>
                                                        <td>' . $value->componente . '</td>
                                                        <td>' . $value->TOTAL_MUESTREOS . '</td>
                                                        <td>' . $value->tipoinstalacion . '</td>
                                                    </tr>';
                        }
                    }
                    // } else {
                    //     $filas_quimicos = '<tr><td colspan="4" style="text-align: center; color: #F00; font-size: 18px!important;">Aún no se ha validado el Reconocimiento Sensorial de Químicos</td></tr>';
                    //     $error = 1;
                    // }
                } else //Reconocimiento de QUIMICOS puntos cliente
                {
                    $quimicos = DB::select('SELECT
                                                recsensorialagentescliente.recsensorial_id,
                                                recsensorialagentescliente.agentescliente_nombre AS componente,
                                                recsensorialagentescliente.agentescliente_puntos AS TOTAL_MUESTREOS,
                                                (
                                                    IF((SELECT recsensorial_tipocliente FROM recsensorial WHERE recsensorial.id = ' . $recsensorial_id . ' LIMIT 1) = 1, -- Si el cliente es Pemex
                                                        CASE
                                                            WHEN recsensorialagentescliente.agentescliente_puntos >= 151 THEN "Extra grande"
                                                            WHEN recsensorialagentescliente.agentescliente_puntos >= 81 THEN "Grande"
                                                            WHEN recsensorialagentescliente.agentescliente_puntos >= 41 THEN "Mediana"
                                                            WHEN recsensorialagentescliente.agentescliente_puntos >= 21 THEN "Chica"
                                                            ELSE "Extra chica"
                                                        END
                                                    ,
                                                        CASE
                                                            -- WHEN recsensorialagentescliente.agentescliente_puntos >= 151 THEN "Extra grande"
                                                            WHEN recsensorialagentescliente.agentescliente_puntos >= 81 THEN "Grande"
                                                            WHEN recsensorialagentescliente.agentescliente_puntos >= 41 THEN "Mediana"
                                                            WHEN recsensorialagentescliente.agentescliente_puntos >= 21 THEN "Chica"
                                                            ELSE "Extra chica"
                                                        END
                                                    )
                                                ) AS tipoinstalacion
                                            FROM
                                                recsensorialagentescliente
                                            WHERE
                                                recsensorialagentescliente.recsensorial_id = ' . $recsensorial_id . '
                                                AND recsensorialagentescliente.agentescliente_agenteid = 15
                                            ORDER BY
                                                recsensorialagentescliente.agentescliente_puntos DESC');

                    // Dibujar filas
                    if (count($quimicos) > 0) {
                        $numero_registro = 0;
                        $filas_quimicos = '';

                        // Formatear filas Quimicos
                        foreach ($quimicos as $key => $value) {
                            $numero_registro += 1;
                            $filas_quimicos .= '<tr>
                                                    <td>' . $numero_registro . '</td>
                                                    <td>' . $value->componente . '</td>
                                                    <td>' . $value->TOTAL_MUESTREOS . '</td>
                                                    <td>' . $value->tipoinstalacion . '</td>
                                                </tr>';
                        }
                    }
                }
            } else {
                $filas_quimicos = '<tr><td colspan="4" style="text-align: center;">No hay datos que mostrar</td></tr>';
            }

            // respuesta
            $dato['recsensorial'] = $recsensorial[0];
            $dato['fisicos_resumen'] = '<tr><td colspan="4" style="text-align: center;">No hay datos que mostrar</td></tr>';
            $dato['quimicos_resumen'] = $filas_quimicos;
            $dato['error'] = $error;
            $dato["msj"] = 'Datos consultados correctamente';

            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['opciones'] = $opciones_select;
            return response()->json($dato);
        }
    }



    /**
     * Display a listing of the resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoobservacionesproveedores($proyecto_id)
    {
        try {
            $observacionessignatarios = DB::select('SELECT
                                                        proyectoproveedores.proyecto_id,
                                                        proyectoproveedores.proveedor_id,
                                                        proveedor.proveedor_NombreComercial,
                                                        IFNULL((
                                                            SELECT
                                                                proyectosignatariosobservacion.proyectosignatariosobservacion 
                                                            FROM
                                                                proyectosignatariosobservacion
                                                                LEFT JOIN proveedor ON proyectosignatariosobservacion.proveedor_id = proveedor.id
                                                            WHERE
                                                                proyectosignatariosobservacion.proyecto_id = proyectoproveedores.proyecto_id
                                                                AND proyectosignatariosobservacion.proveedor_id = proyectoproveedores.proveedor_id
                                                            LIMIT 1
                                                        ), "Sin observación") AS observacionsignatarios 
                                                    FROM
                                                        proyectoproveedores
                                                        LEFT JOIN proveedor ON proyectoproveedores.proveedor_id = proveedor.id
                                                    WHERE
                                                        proyectoproveedores.proyecto_id = ' . $proyecto_id . '
                                                    GROUP BY
                                                        proyectoproveedores.proyecto_id,
                                                        proyectoproveedores.proveedor_id,
                                                        proveedor.proveedor_NombreComercial
                                                    ORDER BY
                                                        proveedor.proveedor_NombreComercial ASC');

            $obs_signatarios = '';
            foreach ($observacionessignatarios as $key => $value) {
                $obs_signatarios .= '<tr>
                                        <td>' . $value->proveedor_NombreComercial . '</td>
                                        <td style="text-align: justify;">' . $value->observacionsignatarios . '</td>
                                    </tr>';
            }

            //============================================

            $observacionesequipos = DB::select('SELECT
                                                    proyectoproveedores.proyecto_id,
                                                    proyectoproveedores.proveedor_id,
                                                    proveedor.proveedor_NombreComercial,
                                                    IFNULL((
                                                        SELECT
                                                            proyectoequiposobservacion.proyectoequiposobservacion 
                                                        FROM
                                                            proyectoequiposobservacion
                                                            LEFT JOIN proveedor ON proyectoequiposobservacion.proveedor_id = proveedor.id
                                                        WHERE
                                                            proyectoequiposobservacion.proyecto_id = proyectoproveedores.proyecto_id
                                                            AND proyectoequiposobservacion.proveedor_id = proyectoproveedores.proveedor_id
                                                        LIMIT 1
                                                    ), "Sin observación") AS observacionequipos
                                                FROM
                                                    proyectoproveedores
                                                    LEFT JOIN proveedor ON proyectoproveedores.proveedor_id = proveedor.id
                                                WHERE
                                                    proyectoproveedores.proyecto_id = ' . $proyecto_id . '
                                                GROUP BY
                                                    proyectoproveedores.proyecto_id,
                                                    proyectoproveedores.proveedor_id,
                                                    proveedor.proveedor_NombreComercial
                                                ORDER BY
                                                    proveedor.proveedor_NombreComercial ASC');

            $obs_equipos = '';
            foreach ($observacionesequipos as $key => $value) {
                $obs_equipos .= '<tr>
                                    <td>' . $value->proveedor_NombreComercial . '</td>
                                    <td style="text-align: justify;">' . $value->observacionequipos . '</td>
                                </tr>';
            }

            // respuesta
            $dato['obs_signatarios'] = $obs_signatarios;
            $dato['obs_equipos'] = $obs_equipos;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['obs_signatarios'] = 0;
            $dato['obs_equipos'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
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
            // dd($request->all());

            // formatear fecha
            // $request['proyecto_fechainicio'] = Carbon::createFromFormat('d-m-Y', $request['proyecto_fechainicio'])->format('Y-m-d');
            // $request['proyecto_fechafin'] = Carbon::createFromFormat('d-m-Y', $request['proyecto_fechafin'])->format('Y-m-d');
            // $request['proyecto_fechaelaboracion'] = Carbon::createFromFormat('d-m-Y', $request['proyecto_fechaelaboracion'])->format('Y-m-d');

            if (($request->proyecto_id + 0) == 0) //NUEVO PROYECTO
            {

                // acccion
                $request['proyecto_puntosrealesactivo'] = 1;
                $request['proyecto_bitacoraactivo'] = 1;
                $request['proyecto_concluido'] = 0;
                $request['proyecto_eliminado'] = 0;
                DB::statement('ALTER TABLE proyecto AUTO_INCREMENT=1');
                $proyectoo = proyectoModel::create($request->all());

                // Folios siguientes
                $ano = (date('y')) + 0;
                $proyecto_folio = "";
                $folio = DB::select('SELECT
                                        (COUNT(proyecto.proyecto_folio)+1) AS nuevo_folio_proyecto
                                    FROM
                                        proyecto
                                    WHERE
                                        DATE_FORMAT(proyecto.created_at, "%Y") = DATE_FORMAT(CURDATE(), "%Y")');

                // folio proyecto
                switch ($folio[0]->nuevo_folio_proyecto) {
                    case ($folio[0]->nuevo_folio_proyecto < 10):
                        $proyecto_folio = "RIPPJ-" . $ano . "-00" . $folio[0]->nuevo_folio_proyecto;
                        break;
                    case ($folio[0]->nuevo_folio_proyecto < 100):
                        $proyecto_folio = "RIPPJ-" . $ano . "-0" . $folio[0]->nuevo_folio_proyecto;
                        break;
                    default:
                        $proyecto_folio = "RIPPJ-" . $ano . "-" . $folio[0]->nuevo_folio_proyecto;
                        break;
                }

                // actualizar folio
                $proyectoo->update([
                    'proyecto_folio' => $proyecto_folio
                ]);

                // $proyecto["proyecto_fechacreacion"] = Carbon::createFromFormat('Y-m-d H:i:s', $proyecto['created_at'])->format('Y-m-d H:i:s');

                // // asignamos el nuevo ID PROYECTO
                // $request['proyecto_id'] = $proyecto["id"];

                // // validar si se creo un proyecto nuevo
                // $dato['proyecto'] = $proyecto;


                $proyecto = proyectoModel::with(['recsensorial', 'recsensorial.cliente', 'recsensorial.catregion', 'recsensorial.catgerencia', 'recsensorial.catactivo'])->findOrFail($proyectoo->id);
            } else //PROYECTO EDITADO
            {
                // Consulta proyecto
                $proyecto = proyectoModel::findOrFail($request->proyecto_id);


                // Actualizar y consultar datos del proyecto
                $proyecto->update($request->all());
                $proyecto = proyectoModel::with(['recsensorial', 'recsensorial.cliente', 'recsensorial.catregion', 'recsensorial.catgerencia', 'recsensorial.catactivo'])->findOrFail($request->proyecto_id);


                if ($proyecto->recsensorial_id) // VALIDAR SI HAY RECONOCIMIENTO Y SI ES EL MISMO
                {
                    if ($proyecto->recsensorial_id == $request->recsensorial_id) // MISMO RECONOCIMIENTO
                    {
                        // Reconocimiento seleccionado
                        $recsensorial = recsensorialModel::findOrFail($proyecto->recsensorial_id);

                        // Modificar
                        $recsensorial->update([
                            'recsensorial_fisicosimprimirbloqueado' => 1, 'recsensorial_quimicosimprimirbloqueado' => 1, 'recsensorial_bloqueado' => 1
                        ]);
                    } else // CAMBIO DE RECONOCIMIENTO
                    {
                        // Reconocimiento anterior
                        $recsensorial = recsensorialModel::findOrFail($proyecto->recsensorial_id);

                        // Modificar
                        $recsensorial->update([
                            'recsensorial_fisicosimprimirbloqueado' => 0, 'recsensorial_quimicosimprimirbloqueado' => 0, 'recsensorial_bloqueado' => 0
                        ]);

                        // Reconocimiento actual
                        $recsensorial = recsensorialModel::findOrFail($request->recsensorial_id);

                        // Modificar
                        $recsensorial->update([
                            'recsensorial_fisicosimprimirbloqueado' => 1, 'recsensorial_quimicosimprimirbloqueado' => 1, 'recsensorial_bloqueado' => 1
                        ]);
                    }
                }


                // mensaje
                $dato["msj"] = 'Informacion modificada correctamente';
            }

            // respuesta
            $dato['proyecto'] = $proyecto;
            return response()->json($dato);
        } catch (\Exception $e) {
            $dato["msj"] = 'Error en el controlador: ' . $e->getMessage();
            $dato['proyecto'] = 0;
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @param  int  $proyecto_estado
     * @return \Illuminate\Http\Response
     */
    public function proyectobloqueo($proyecto_id, $proyecto_estado)
    {
        try {
            // Proyecto
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            // Valida estado
            if (($proyecto_estado + 0) == 0) {
                $proyecto->proyecto_concluido = 1;
                $dato["msj"] = 'Proyecto bloqueado correctamente';
            } else {
                $proyecto->proyecto_concluido = 0;
                $dato["msj"] = 'Proyecto desbloqueado correctamente';
            }

            // Guardar cambios
            $proyecto->save();

            // Respuesta
            $dato["proyecto"] = $proyecto;
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["proyecto"] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }
}

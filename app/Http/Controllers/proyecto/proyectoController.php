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
use App\modelos\proyecto\serviciosProyectoModel;
use App\modelos\proyecto\estructuraProyectosModel;
use App\modelos\proyecto\ProyectoUsuariosModel;
use App\modelos\clientes\clientecontratoModel;

//Modelos catalogos
use App\modelos\recsensorial\catregionModel;
use App\modelos\recsensorial\catsubdireccionModel;
use App\modelos\recsensorial\catgerenciaModel;
use App\modelos\recsensorial\catactivoModel;
use App\modelos\catalogos\Cat_pruebaModel;


//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class proyectoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Reportes,ApoyoTecnico,Financiero,Cadista,Externo');
        // $this->middleware('roles:Superusuario,Administrador,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,ApoyoTecnico,Reportes');

        // $this->middleware('asignacionUser')->only('store');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = DB::select('SELECT u.id, CONCAT(e.empleado_nombre," ",e.empleado_apellidopaterno," ", e.empleado_apellidomaterno) as NOMBRE, u.email as CORREO
            FROM usuario u 
            LEFT JOIN empleado e ON e.id = u.empleado_id
            WHERE u.usuario_activo = 1');

        $catregion = catregionModel::where('catregion_activo', 1)->get();
        $catsubdireccion = catsubdireccionModel::where('catsubdireccion_activo', 1)->orderBy('catsubdireccion_nombre', 'ASC')->get();
        $catgerencia = catgerenciaModel::where('catgerencia_activo', 1)->orderBy('catgerencia_nombre', 'ASC')->get();
        $catactivo = catactivoModel::where('catactivo_activo', 1)->orderBy('catactivo_nombre', 'ASC')->get();
        // CATALOGO PRUEBAS
        $catpruebas = Cat_pruebaModel::where('catPrueba_Activo', 1)->OrderBy('catPrueba_Nombre', 'ASC')->get();

        return view('catalogos.proyecto.proyecto', compact('catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'usuarios', 'catpruebas'));
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
                                                            WHERE proyecto.proyectoInterno = 0 AND ' . $region));

                $lista_proyectos = array(0);
                foreach ($proyectos_coordinador as $key => $value) {
                    $lista_proyectos[] = $value->id;
                }

                // $proyectos = proyectoModel::with(['recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'recsensorial.catcontrato', 'recsensorial.catregion', 'recsensorial.catgerencia', 'recsensorial.catactivo'])
                $proyectos = proyectoModel::with(['recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'recsensorial.cliente', 'recsensorial.catregion', 'recsensorial.catgerencia', 'recsensorial.catactivo'])
                    ->where('proyectoInterno', 0)
                    ->whereIn('id', $lista_proyectos) // ->whereIn('id', [1, 2, 3, 8, 22])
                    ->orderBy('id', 'ASC')
                    ->get();
            } else if (auth()->user()->hasRoles(['ApoyoTecnico'])) {
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
                                                                AND proyecto.proyectoInterno = 0
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
                    ->where('proyectoInterno', 0)
                    ->whereIn('id', $lista_proyectos) // ->whereIn('id', [1, 2, 3, 8, 22])
                    ->orderBy('id', 'ASC')
                    ->get();
            } else {

                $proyectos = proyectoModel::with([
                    'recsensorial',
                    'catregion',
                    'catsubdireccion',
                    'catgerencia',
                    'catactivo',
                    'recsensorial.cliente',
                    'recsensorial.catregion',
                    'recsensorial.catgerencia',
                    'recsensorial.catactivo'
                ])
                    ->leftJoin('serviciosProyecto', 'proyecto.id', '=', 'serviciosProyecto.PROYECTO_ID')
                    ->where('proyectoInterno', 0)
                    ->orderBy('proyecto.proyecto_folio', 'ASC')
                    ->get([
                        'proyecto.*',
                        'serviciosProyecto.*'
                    ]);
            }


            // Formaterar filas
            $numero_registro = 0;
            foreach ($proyectos as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                $value->instalacion_y_direccion = '<span style="color: #999999;">' . $value->proyecto_clienteinstalacion . '</span><br>' . $value->proyecto_clientedireccionservicio;


                // formatear fecha
                if ($value->proyecto_fechainicio) {

                    $value->inicio_y_fin = $value->proyecto_fechainicio . "<br>" . $value->proyecto_fechafin;
                }

                // Si hay recsensorial (SE COMENTO YA QUE NO SIEMPRE UN PROYECTO PUEDE TENER UN RECONOCIMIENTO PORQUE EXISTEN MAS SERVICIOS)
                // if ($value->recsensorial_id) {
                //     // Folios RECSENSORIAL asignado
                //     if (($value->recsensorial->recsensorial_alcancefisico + 0) > 0 && ($value->recsensorial->recsensorial_alcancefisico + 0) > 0) {
                //         $value->recsensorial_folios = $value->recsensorial->recsensorial_foliofisico . "<br>" . $value->recsensorial->recsensorial_folioquimico;
                //     } else if (($value->recsensorial->recsensorial_alcancefisico + 0) > 0) {
                //         $value->recsensorial_folios = $value->recsensorial->recsensorial_foliofisico;
                //     } else {
                //         $value->recsensorial_folios = $value->recsensorial->recsensorial_folioquimico;
                //     }
                // } else {
                //     $value->recsensorial_folios = 'Sin asignar<br><i class="fa fa-ban text-danger"></i>';
                // }

                //MOSTRAMOS LOS SERVICIOS QUE TIENE EL PROYECTOS
                if ((is_null($value->HI) && is_null($value->ERGO) && is_null($value->PSICO) && is_null($value->SEGURIDAD)) || ($value->HI == 0 && $value->ERGO == 0 && $value->PSICO == 0 && $value->SEGURIDAD == 0)) {

                    $value->servicios = '<span class="badge badge-pill badge-danger p-2" style="font-size:12px">Sin servicios</span>';
                } else {

                    $hi = $value->HI == 1 ? '<li>Higiene Industrial</li>' : '';

                    $ergo = $value->ERGO == 1 ? '<li>Ergonómico</li>' : '';

                    $psico = $value->PSICO == 1 ? '<li>Psicosocial</li>' : '';

                    $seguridad = $value->SEGURIDAD == 1 ? '<li>Seguridad Industrial</li>' : '';

                    $value->servicios = $hi . $ergo . $psico . $seguridad;
                }



                if (isset($value->created_at) && $value->created_at) {
                    $value->proyecto_fechacreacion = Carbon::createFromFormat('Y-m-d H:i:s', $value->created_at)->format('Y-m-d - h:i:s a');
                } else {
                    $value->proyecto_fechacreacion = 'Fecha no disponible';
                }

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


    public function proyectotablaInternos()
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
                                                            WHERE proyecto.proyectoInterno = 1 AND ' . $region));

                $lista_proyectos = array(0);
                foreach ($proyectos_coordinador as $key => $value) {
                    $lista_proyectos[] = $value->id;
                }


                $proyectos = proyectoModel::with(['recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'recsensorial.cliente', 'recsensorial.catregion', 'recsensorial.catgerencia', 'recsensorial.catactivo'])
                    ->where('proyectoInterno', 1)
                    ->whereIn('id', $lista_proyectos)
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
                                                                AND proyecto.proyectoInterno = 1
                                                                AND signatario.signatario_Nombre LIKE "%' . auth()->user()->name . ' ' . auth()->user()->empleado->empleado_apellidopaterno . ' ' . auth()->user()->empleado->empleado_apellidomaterno . '%"
                                                            GROUP BY
                                                                proyectosignatariosactual.proyecto_id,    
                                                                signatario.signatario_Nombre'));

                $lista_proyectos = array(0);
                foreach ($proyectos_signatario as $key => $value) {
                    $lista_proyectos[] = $value->proyecto_id;
                }

                $proyectos = proyectoModel::with(['recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'recsensorial.cliente', 'recsensorial.catregion', 'recsensorial.catgerencia', 'recsensorial.catactivo'])
                    ->where('proyectoInterno', 1)
                    ->whereIn('id', $lista_proyectos) // ->whereIn('id', [1, 2, 3, 8, 22])
                    ->orderBy('id', 'ASC')
                    ->get();
            } else {
                $proyectos = proyectoModel::with([
                    'recsensorial',
                    'catregion',
                    'catsubdireccion',
                    'catgerencia',
                    'catactivo',
                    'recsensorial.cliente',
                    'recsensorial.catregion',
                    'recsensorial.catgerencia',
                    'recsensorial.catactivo'
                ])
                    ->leftJoin('serviciosProyecto', 'proyecto.id', '=', 'serviciosProyecto.PROYECTO_ID')
                    ->where('proyectoInterno', 1)
                    ->orderBy('proyecto.id', 'ASC')
                    ->get([
                        'proyecto.*',
                        'serviciosProyecto.*'
                    ]);
            }


            // Formaterar filas
            $numero_registro = 0;
            foreach ($proyectos as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                $value->instalacion_y_direccion = '<span style="color: #999999;">' . $value->proyecto_clienteinstalacion . '</span><br>' . $value->proyecto_clientedireccionservicio;

                // formatear fecha
                if ($value->proyecto_fechainicio) {
                    $value->inicio_y_fin = $value->proyecto_fechainicio . "<br>" . $value->proyecto_fechafin;
                }

                // Si hay recsensorial (SE COMENTO YA QUE NO SIEMPRE UN PROYECTO PUEDE TENER UN RECONOCIMIENTO PORQUE EXISTEN MAS SERVICIOS)
                // if ($value->recsensorial_id) {
                //     // Folios RECSENSORIAL asignado
                //     if (($value->recsensorial->recsensorial_alcancefisico + 0) > 0 && ($value->recsensorial->recsensorial_alcancefisico + 0) > 0) {
                //         $value->recsensorial_folios = $value->recsensorial->recsensorial_foliofisico . "<br>" . $value->recsensorial->recsensorial_folioquimico;
                //     } else if (($value->recsensorial->recsensorial_alcancefisico + 0) > 0) {
                //         $value->recsensorial_folios = $value->recsensorial->recsensorial_foliofisico;
                //     } else {
                //         $value->recsensorial_folios = $value->recsensorial->recsensorial_folioquimico;
                //     }
                // } else {
                //     $value->recsensorial_folios = 'Sin asignar<br><i class="fa fa-ban text-danger"></i>';
                // }

                //MOSTRAMOS LOS SERVICIOS QUE TIENE EL PROYECTOS
                if ((is_null($value->HI) && is_null($value->ERGO) && is_null($value->PSICO) && is_null($value->SEGURIDAD)) || ($value->HI == 0 && $value->ERGO == 0 && $value->PSICO == 0 && $value->SEGURIDAD == 0)) {

                    $value->servicios = '<span class="badge badge-pill badge-danger p-2" style="font-size:12px">Sin servicios</span>';
                } else {

                    $hi = $value->HI == 1 ? '<li>Higiene Industrial</li>' : '';

                    $ergo = $value->ERGO == 1 ? '<li>Ergonómico</li>' : '';

                    $psico = $value->PSICO == 1 ? '<li>Psicosocial</li>' : '';

                    $seguridad = $value->SEGURIDAD == 1 ? '<li>Seguridad Industrial</li>' : '';

                    $value->servicios = $hi . $ergo . $psico . $seguridad;
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


    public function proyectoUsuarios($ID_PROYECTO)
    {
        try {

            $usuarios = DB::select('SELECT p.ACTIVO,
                                            DATE_FORMAT(p.created_at,"%Y-%m-%d") as fecha,
                                            p.PROYECTO_ID,
                                            p.ID_PROYECTO_USUARIO,
                                            p.USUARIO_ID,
                                            p.SERVICIO_HI,
                                            p.SERVICIO_PSICO,
                                            p.SERVICIO_ERGO,
                                            CONCAT(e.empleado_nombre," ",e.empleado_apellidopaterno," ", e.empleado_apellidomaterno) as nombre
                                    FROM proyectoUsuarios p
                                    LEFT JOIN usuario u ON u.id = p.USUARIO_ID
                                    LEFT JOIN empleado e ON e.id = u.empleado_id
                                    WHERE p.PROYECTO_ID = ?', [$ID_PROYECTO]);




            // crear campos NOMBRE Y ESTADO
            $count = 1;
            foreach ($usuarios as $key => $value) {


                $value->count = $count;

                $hi = $value->SERVICIO_HI == 1 ? '<li>Higiene Industrial </li>' : '';
                $pscio = $value->SERVICIO_PSICO == 1 ? '<li> FR. Psicosocial </li>' : '';
                $ergo = $value->SERVICIO_ERGO == 1 ? '<li> FR. Ergonimico </li>' : '';
                

                $value->servicios = $hi . $pscio . $ergo;

                // Checkbox estado
                if ($value->ACTIVO == 1) {
                    $value->CheckboxEstado = '<div class="switch"><label><input type="checkbox" checked onclick="cambia_estado_usuario(' . $value->ID_PROYECTO_USUARIO . ',' . $value->ACTIVO . ');"><span class="lever switch-col-light-blue"></span></label></div>';
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle editar" id="editarUsuario_' . $value->ID_PROYECTO_USUARIO . '"><i class="fa fa-pencil"></i></button>';

                    $value->estado = '<div class="text-success"><i class="fa fa-eercast fw" aria-hidden="true"></i> Usuario activo</div>';
                } else {
                    $value->CheckboxEstado = '<div class="switch"><label><input type="checkbox" onclick="cambia_estado_usuario(' . $value->ID_PROYECTO_USUARIO . ', ' . $value->ACTIVO . ');"><span class="lever switch-col-light-blue"></span></label></div>';
                    $value->boton_editar = '<button type="button" class="btn btn-secondary btn-circle" id="editarUsuario_' . $value->ID_PROYECTO_USUARIO . '"><i class="fa fa-ban"></i></button>';

                    $value->estado = '<div class="text-danger"><i class="fa fa-eercast fw" aria-hidden="true"></i> Usuario bloqueado</div>';
                }

                $count++;
            }



            // // respuesta
            $dato['data'] = $usuarios;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['data'] = '';
            return response()->json($dato);
        }
    }

    public function actualizarEstadoUsuario($ID, $ACTIVO)
    {
        try {

            $estado = ProyectoUsuariosModel::findOrFail($ID);
            $estadoVal = $ACTIVO == 1 ? 0 : 1;
            $estado->update(['ACTIVO' => $estadoVal]);

            // // respuesta
            $dato['data'] = $estado;
            $dato["msj"] = 'Datos consultados correctamente';

            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['data'] = '';
            return response()->json($dato);
        }
    }


    public function proyectoServicios($ID_PROYECTO)
    {
        try {

            $info = DB::select('SELECT *
                                FROM serviciosProyecto
                                WHERE PROYECTO_ID = ?', [$ID_PROYECTO]);

            // // respuesta
            $dato['data'] = $info;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['data'] = '';
            return response()->json($dato);
        }
    }


    public function proyectosTotales()
    {
        try {

            $proyectos = DB::select('SELECT COUNT(*) AS PROYECTOS
                                    FROM proyecto
                                    WHERE proyecto_eliminado = 0
                                    AND proyectoInterno = 0
                                    UNION ALL
                                    SELECT COUNT(*) AS PROYECTOS
                                    FROM proyecto
                                    WHERE proyecto_eliminado = 0
                                    AND proyectoInterno = 1');


            // // respuesta
            $dato['NUM_PROYECTO'] = $proyectos[0]->PROYECTOS;
            $dato['NUM_PROYECTO_INTERNOS'] = $proyectos[1]->PROYECTOS;

            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['opciones'] = $opciones_select;
            return response()->json($dato);
        }
    }

    public function proyectoContactos()
    {
        try {
            $opciones_select = '<option value="">&nbsp;</option>';
            $contratos = DB::select('SELECT CONTACTOS_JSON
                                    FROM proveedor
                                    WHERE proveedor_Rfc = "RIP1706223K9" ');


            $contratos_json = $contratos[0]->CONTACTOS_JSON;
            $contactos = json_decode($contratos_json, true);



            foreach ($contactos as $contacto) {

                $opciones_select .= '<option value="' . $contacto['PROVEEDOR_NOMBRE_CONTACTO'] . '"  data-correo=" ' . $contacto['PROVEEDOR_CORREO_CONTACTO'] . ' "  data-telefono=" ' . $contacto['PROVEEDOR_TELEFONO_CONTACTO'] . ' " data-celular=" ' . $contacto['PROVEEDOR_CELULAR_CONTACTO'] . ' ">' . $contacto['PROVEEDOR_NOMBRE_CONTACTO']  . ' </option>';
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


    public function proyectoselectcliente($cliente_id)
    {
        try {
            $opciones_select = '<option value="">&nbsp;</option>';
            $contratos = DB::select('SELECT cliente_Rfc RFC, cliente_NombreComercial NOMBRE, id ID_CLIENTE
                                    FROM cliente
                                    WHERE cliente_Eliminado = 0');

            foreach ($contratos as $key => $value) {
                if ($value->ID_CLIENTE == $cliente_id) {

                    $opciones_select .= '<option value="' . $value->ID_CLIENTE . '"  selected>' . $value->NOMBRE . ' [' . $value->RFC . ']' . '</option>';
                } else {

                    $opciones_select .= '<option value="' . $value->ID_CLIENTE . '"  >' . $value->NOMBRE . ' [' . $value->RFC . ']' . ' </option>';
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


    public function proyectoEstructuta($ID)
    {
        try {

            $estructura = DB::select('SELECT 
                                    e.ID_ETIQUETA,
                                    e.NOMBRE_ETIQUETA,
                                    o.ID_OPCIONES_ETIQUETAS,
                                    o.NOMBRE_OPCIONES,
                                    es.NIVEL,
                                    es.ETIQUETA_ID,
                                    IF(es.OPCION_ID = o.ID_OPCIONES_ETIQUETAS ,1,0) SELECCIONADA
                            FROM cat_etiquetas e
                            LEFT JOIN catetiquetas_opciones o ON o.ETIQUETA_ID = e.ID_ETIQUETA
                            LEFT JOIN estructuraProyectos es ON e.ID_ETIQUETA = es.ETIQUETA_ID
                            WHERE e.ACTIVO = 1 AND o.ACTIVO = 1
                                            AND es.PROYECTO_ID = ?
                            ORDER BY 
                                    es.NIVEL', [$ID]);


            $etiquetas = DB::select('SELECT 
                                    e.ID_ETIQUETA,
                                    e.NOMBRE_ETIQUETA,
                                    es.NIVEL
                                FROM cat_etiquetas e
                                LEFT JOIN catetiquetas_opciones o ON o.ETIQUETA_ID = e.ID_ETIQUETA
                                LEFT JOIN estructuraProyectos es ON e.ID_ETIQUETA = es.ETIQUETA_ID
                                WHERE e.ACTIVO = 1 AND o.ACTIVO = 1 AND es.PROYECTO_ID = ?
                                GROUP BY e.ID_ETIQUETA, e.NOMBRE_ETIQUETA,
                                                es.NIVEL
                                ORDER BY es.NIVEL', [$ID]);



            $arregloOpciones = [];
            $dataOpciones = [];
            if (!empty($estructura)) {
                foreach ($estructura as $value) {
                    $posicion = $value->NIVEL;

                    if (!isset($arregloOpciones[$posicion])) {
                        $arregloOpciones[$posicion] = '<option value="">&nbsp;</option>';
                    }

                    if ($value->SELECCIONADA == 1) {
                        $arregloOpciones[$posicion] .= '<option value="' . $value->ID_OPCIONES_ETIQUETAS . '" selected>' . $value->NOMBRE_OPCIONES . '</option>';
                    } else {
                        $arregloOpciones[$posicion] .= '<option value="' . $value->ID_OPCIONES_ETIQUETAS . '">' . $value->NOMBRE_OPCIONES . '</option>';
                    }
                }

                foreach ($arregloOpciones as $opciones_select) {
                    array_push($dataOpciones, $opciones_select);
                }
            }


            //respuesta
            $dato['estructura'] = $dataOpciones;
            $dato['etiquetas'] = $etiquetas;

            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['data'] = '';
            return response()->json($dato);
        }
    }


    public function proyectocliente($cliente_id)
    {
        try {
            $html = '';
            $info = DB::select(
                'SELECT cli.id ID_CLIENTE,
                        cli.cliente_RazonSocial RAZON_SOCIAL,
                        cli.cliente_NombreComercial NOMBRE_COMERCIAL,
                        cli.cliente_Rfc RFC,
                        cli.cliente_GiroComercial GIRO_COMERCIAL
                    FROM cliente cli
                    WHERE cli.id  = ?',
                [$cliente_id]
            );



            $estructura = DB::select('SELECT 
            	                IFNULL(c.requiere_estructuraCliente,0) REQUIERE_ESTRUCTURA,
                                e.ID_ETIQUETA,
                                e.NOMBRE_ETIQUETA,
                                o.ID_OPCIONES_ETIQUETAS,
                                o.NOMBRE_OPCIONES,
                                es.NIVEL,
                                es.ETIQUETA_ID,
                                IF(es.OPCIONES_ID = o.ID_OPCIONES_ETIQUETAS ,1,0) SELECCIONADA
                            FROM cat_etiquetas e
                            LEFT JOIN catetiquetas_opciones o ON o.ETIQUETA_ID = e.ID_ETIQUETA
                            LEFT JOIN estructuraclientes es ON e.ID_ETIQUETA = es.ETIQUETA_ID
                            LEFT JOIN cliente c ON c.id = es.CLIENTES_ID
                            WHERE e.ACTIVO = 1 AND o.ACTIVO = 1
                                    AND es.CLIENTES_ID = ?
                            ORDER BY 
                                es.NIVEL', [$cliente_id]);


            $etiquetas = DB::select('SELECT 
                                    e.ID_ETIQUETA,
                                    e.NOMBRE_ETIQUETA,
                                    es.NIVEL
                            FROM cat_etiquetas e
                            LEFT JOIN catetiquetas_opciones o ON o.ETIQUETA_ID = e.ID_ETIQUETA
                            LEFT JOIN estructuraclientes es ON e.ID_ETIQUETA = es.ETIQUETA_ID
                            LEFT JOIN cliente c ON c.id = es.CLIENTES_ID
                            WHERE e.ACTIVO = 1 AND o.ACTIVO = 1
                                            AND es.CLIENTES_ID = ?
                            GROUP BY e.ID_ETIQUETA, e.NOMBRE_ETIQUETA,
                                    es.NIVEL
                            ORDER BY es.NIVEL', [$cliente_id]);



            $arregloOpciones = [];
            $dataOpciones = [];
            if (!empty($estructura) && $estructura[0]->REQUIERE_ESTRUCTURA == 'Si') {
                foreach ($estructura as $value) {
                    $posicion = $value->NIVEL;

                    if (!isset($arregloOpciones[$posicion])) {
                        $arregloOpciones[$posicion] = '<option value="">&nbsp;</option>';
                    }

                    if ($value->SELECCIONADA == 1) {
                        $arregloOpciones[$posicion] .= '<option value="' . $value->ID_OPCIONES_ETIQUETAS . '" selected>' . $value->NOMBRE_OPCIONES . '</option>';
                    } else {
                        $arregloOpciones[$posicion] .= '<option value="' . $value->ID_OPCIONES_ETIQUETAS . '">' . $value->NOMBRE_OPCIONES . '</option>';
                    }
                }

                foreach ($arregloOpciones as $opciones_select) {
                    array_push($dataOpciones, $opciones_select);
                }
            }



            // // respuesta
            $dato['data'] = $info;
            $dato['estructura'] = $dataOpciones;
            $dato['etiquetas'] = $etiquetas;

            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['data'] = '';
            return response()->json($dato);
        }
    }

    public function proyectoselectcontrato($id_contrato, $tipo)
    {
        try {
            $contratos = DB::select('SELECT cc.DESCRIPCION_CONTRATO, cc.NUMERO_CONTRATO, cc.ID_CONTRATO, cc.CLIENTE_ID,
                                            IF(cc.FECHA_FIN < DATE(NOW()), "VENCIDO", "ACTIVO") AS STATUS_CONTRATO,
                                            IF(con.id IS NULL,"SIN_CONVENIO" ,IF(con.clienteconvenio_vigencia < DATE(NOW()), "CON_CONVENIO_VENCIDO", "CON_CONVENIO_ACTIVO")) AS STATUS_CONVENIO
                                        FROM contratos_clientes as cc 
                                        LEFT JOIN contratos_convenios as con ON con.CONTRATO_ID = cc.ID_CONTRATO
                                        WHERE ACTIVO = 1
                                        AND CONCLUIDO = 0
                                        AND TIPO_SERVICIO = ?', [$tipo]);
            if (count($contratos) > 0) {
                $opciones_select = '<option value="">&nbsp;</option>';

                foreach ($contratos as $key => $value) {
                    if ($value->ID_CONTRATO == $id_contrato) {

                        $opciones_select .= '<option value="' . $value->ID_CONTRATO . '"  selected>' . $value->DESCRIPCION_CONTRATO . ' [' . $value->NUMERO_CONTRATO . ']' . '</option>';
                    } else if ($value->STATUS_CONTRATO == 'VENCIDO' && $value->STATUS_CONVENIO == 'SIN_CONVENIO') {

                        $opciones_select .= '<option value="' . $value->ID_CONTRATO . '" disabled>' . $value->DESCRIPCION_CONTRATO . ' [' . $value->NUMERO_CONTRATO . ']' . ' (Vencido y sin convenio)</option>';
                    } else if ($value->STATUS_CONTRATO == 'VENCIDO' && $value->STATUS_CONVENIO != 'SIN_CONVENIO') {

                        if ($value->STATUS_CONVENIO == 'CON_CONVENIO_VENCIDO') {

                            $opciones_select .= '<option value="' . $value->ID_CONTRATO . '" disabled>' . $value->DESCRIPCION_CONTRATO . ' [' . $value->NUMERO_CONTRATO . ']' . ' (Vencido con convenio expirado)</option>';
                        } else {
                            $opciones_select .= '<option value="' . $value->ID_CONTRATO . '" >' . $value->DESCRIPCION_CONTRATO . ' [' . $value->NUMERO_CONTRATO . ']' . ' (Vencido con convenio activo)</option>';
                        }
                    } else {

                        $opciones_select .= '<option value="' . $value->ID_CONTRATO . '"  >' . $value->DESCRIPCION_CONTRATO . ' [' . $value->STATUS_CONTRATO . ']' . ' (Activo)</option>';
                    }
                }
            } else {
                $opciones_select = '<option value="">&nbsp;</option>';
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


    public function proyectocontrato($contrato_id)
    {
        try {

            $info = DB::select(
                'SELECT con.ID_CONTRATO,
                                cli.cliente_RazonSocial RAZON_SOCIAL,
                                cli.cliente_NombreComercial NOMBRE_COMERCIAL,
                                cli.cliente_Rfc RFC,
                                cli.cliente_GiroComercial GIRO_COMERCIAL,
                                con.NOMBRE_CONTACTO,
                                con.TELEFONO_CONTACTO,
                                con.CELULAR_CONTACTO,
                                con.CORREO_CONTACTO,
                                con.DESCRIPCION_CONTRATO
                            FROM contratos_clientes con
                            LEFT JOIN cliente cli ON cli.id = con.CLIENTE_ID 
                            WHERE con.ID_CONTRATO = ?',
                [$contrato_id]
            );


            $estructura = DB::select('SELECT 
                                    IFNULL(c.requiere_estructuraCliente,0) REQUIERE_ESTRUCTURA,
                                        e.ID_ETIQUETA,
                                        e.NOMBRE_ETIQUETA,
                                        o.ID_OPCIONES_ETIQUETAS,
                                        o.NOMBRE_OPCIONES,
                                        es.NIVEL,
                                        es.ETIQUETA_ID,
                                        IF(es.OPCIONES_ID = o.ID_OPCIONES_ETIQUETAS ,1,0) SELECCIONADA
                                FROM cat_etiquetas e
                                LEFT JOIN catetiquetas_opciones o ON o.ETIQUETA_ID = e.ID_ETIQUETA
                                LEFT JOIN estructuraclientes es ON e.ID_ETIQUETA = es.ETIQUETA_ID
                                LEFT JOIN contratos_clientes cc ON cc.CLIENTE_ID = es.CLIENTES_ID
                                LEFT JOIN cliente c ON c.id = cc.CLIENTE_ID
                                WHERE e.ACTIVO = 1 AND o.ACTIVO = 1 AND cc.ID_CONTRATO = ? 
                                ORDER BY es.NIVEL', [$contrato_id]);


            $etiquetas = DB::select('SELECT 
                                    e.ID_ETIQUETA,
                                    e.NOMBRE_ETIQUETA,
                                    es.NIVEL
                                FROM cat_etiquetas e
                                LEFT JOIN catetiquetas_opciones o ON o.ETIQUETA_ID = e.ID_ETIQUETA
                                LEFT JOIN estructuraclientes es ON e.ID_ETIQUETA = es.ETIQUETA_ID
                                LEFT JOIN contratos_clientes cc ON cc.CLIENTE_ID = es.CLIENTES_ID
                                LEFT JOIN cliente c ON c.id = cc.CLIENTE_ID
                                WHERE e.ACTIVO = 1 AND o.ACTIVO = 1 AND cc.ID_CONTRATO = ?
                                GROUP BY e.ID_ETIQUETA, e.NOMBRE_ETIQUETA, es.NIVEL
                                ORDER BY es.NIVEL', [$contrato_id]);



            $arregloOpciones = [];
            $dataOpciones = [];
            if (!empty($estructura) && $estructura[0]->REQUIERE_ESTRUCTURA == 'Si') {
                foreach ($estructura as $value) {
                    $posicion = $value->NIVEL;

                    if (!isset($arregloOpciones[$posicion])) {
                        $arregloOpciones[$posicion] = '<option value="">&nbsp;</option>';
                    }

                    if ($value->SELECCIONADA == 1) {
                        $arregloOpciones[$posicion] .= '<option value="' . $value->ID_OPCIONES_ETIQUETAS . '" selected>' . $value->NOMBRE_OPCIONES . '</option>';
                    } else {
                        $arregloOpciones[$posicion] .= '<option value="' . $value->ID_OPCIONES_ETIQUETAS . '">' . $value->NOMBRE_OPCIONES . '</option>';
                    }
                }

                foreach ($arregloOpciones as $opciones_select) {
                    array_push($dataOpciones, $opciones_select);
                }
            }


            // // respuesta
            $dato['data'] = $info;
            $dato['estructura'] = $dataOpciones;
            $dato['etiquetas'] = $etiquetas;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['data'] = '';
            return response()->json($dato);
        }
    }


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
            $filas_quimicos_cliente = '<tr><td colspan="4" style="text-align: center;">El reconocimiento no tiene solicitud por el cliente</td></tr>';


            DB::statement("SET lc_time_names = 'es_MX';");
            $recsensorial = DB::select('SELECT recsensorial.id,
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
                                        recsensorial.recsensorial_eliminado, 
                                        IFNULL(recursos.PETICION_CLIENTE, 0) PETICION_CLIENTE
                                    FROM
                                        recsensorial
                                        LEFT JOIN cliente ON recsensorial.cliente_id = cliente.id
                                        LEFT JOIN recsensorial_recursos_informes recursos ON recursos.RECSENSORIAL_ID = recsensorial.id
                                    WHERE
                                        recsensorial.id = ?', [$recsensorial_id]);



            // Resumen de fisicos
            if (($recsensorial[0]->recsensorial_alcancefisico + 0) != 0) {
                if (($recsensorial[0]->recsensorial_alcancefisico + 0) == 1) { //Reconocimiento de FISICOS completo

                    $fisicos = DB::select('CALL sp_obtener_volumetria_fisico_proyecto_b(?)', [$recsensorial_id]);
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
                    $quimicos = DB::select('CALL sp_obtener_puntos_finales_b(?)', [$recsensorial_id]);



                    // Dibujar filas
                    if (count($quimicos) > 0) {
                        $numero_registro = 0;
                        $filas_quimicos = '';

                        // Formatear filas Quimicos
                        foreach ($quimicos as $key => $value) {
                            $numero_registro += 1;
                            $filas_quimicos .= '<tr>
                                                        <td>' . $numero_registro . '</td>
                                                        <td>' . $value->PRODUCTO_COMPONENTE . '</td>
                                                        <td>' . $value->TOTAL_MUESTREO . '</td>
                                                    </tr>';
                        }
                    }

                    // } else {
                    //     $filas_quimicos = '<tr><td colspan="4" style="text-align: center; color: #F00; font-size: 18px!important;">Aún no se ha validado el Reconocimiento Sensorial de Químicos</td></tr>';
                    //     $error = 1;
                    // }

                } else //Reconocimiento de QUIMICOS puntos cliente
                {
                    $quimicos = DB::select('SELECT recsensorialagentescliente.recsensorial_id,
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
                                                recsensorialagentescliente.agentescliente_puntos, recsensorialagentescliente.recsensorial_id,
                                                recsensorialagentescliente.agentescliente_nombre DESC');

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
                                                </tr>';
                        }
                    }
                }
            } else {
                $filas_quimicos = '<tr><td colspan="4" style="text-align: center;">No hay datos que mostrar</td></tr>';
            }


            //RECONOCIMIENTO QUIMICO COMPLETO (PUNTOS ADICIONALES POR EL CLIENTE)
            if (($recsensorial[0]->PETICION_CLIENTE + 0) == 1) {


                $quimicos_cliente = DB::select(' SELECT 
                                                    sus.SUSTANCIA_QUIMICA PRODUCTO_COMPONENTE,
                                                    SUM(cliente.PUNTOS) TOTAL_MUESTREO,
                                                    COUNT(cliente.ID_TABLA_INFORME_CLIENTE) AS TOTAL_REGISTROS
                                                FROM recsensorial_tablaClientes_informes cliente
                                                LEFT JOIN catsustancias_quimicas sus ON sus.ID_SUSTANCIA_QUIMICA = cliente.SUSTANCIA_ID
                                                WHERE cliente.RECONOCIMIENTO_ID = ?
                                                GROUP BY sus.SUSTANCIA_QUIMICA
                                                ORDER BY PRODUCTO_COMPONENTE', [$recsensorial_id]);


                // Dibujar filas
                if (count($quimicos_cliente) > 0) {
                    $numero_registro = 0;
                    $filas_quimicos_cliente = '';

                    // Formatear filas Quimicos
                    foreach ($quimicos_cliente as $key => $value) {
                        $numero_registro += 1;
                        $filas_quimicos_cliente .= '<tr>
                                                            <td>' . $numero_registro . '</td>
                                                            <td>' . $value->PRODUCTO_COMPONENTE . '</td>
                                                            <td>' . $value->TOTAL_MUESTREO . '</td>
                                                        </tr>';
                    }
                }
            }





            // respuesta
            $dato['recsensorial'] = $recsensorial[0];
            $dato['fisicos_resumen'] = $filas_fisicos;
            $dato['quimicos_resumen'] = $filas_quimicos;
            $dato['quimicos_resumen_cliente'] = $filas_quimicos_cliente;
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
            $observacionessignatarios = DB::select('SELECT proyectoproveedores.proyecto_id,
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
                                                    proyectoproveedores.proyecto_id = ?
                                                GROUP BY
                                                    proyectoproveedores.proyecto_id,
                                                    proyectoproveedores.proveedor_id,
                                                    proveedor.proveedor_NombreComercial,
                                                    observacionsignatarios
                                                ORDER BY
                                                    proveedor.proveedor_NombreComercial ASC', [$proyecto_id]);

            $obs_signatarios = '';
            foreach ($observacionessignatarios as $key => $value) {
                $obs_signatarios .= '<tr>
                                        <td>' . $value->proveedor_NombreComercial . '</td>
                                        <td style="text-align: justify;">' . $value->observacionsignatarios . '</td>
                                    </tr>';
            }

            //============================================

            $observacionesequipos = DB::select('SELECT proyectoproveedores.proyecto_id,
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
                                                    proyectoproveedores.proyecto_id = ?
                                                GROUP BY
                                                    proyectoproveedores.proyecto_id,
                                                    proyectoproveedores.proveedor_id,
                                                    proveedor.proveedor_NombreComercial,
                                                    observacionequipos
                                                ORDER BY
                                                    proveedor.proveedor_NombreComercial ASC,
                                                    proyectoproveedores.proyecto_id,
                                                    proyectoproveedores.proveedor_id', [$proyecto_id]);

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


   

    public function store(Request $request)
    {
        try {
         
            if ($request->api == 1) {

                if (($request->proyecto_id + 0) == 0) //NUEVO PROYECTO
                {

                    // acccion
                    $request['proyecto_puntosrealesactivo'] = 1;
                    $request['proyecto_bitacoraactivo'] = 1;
                    $request['proyecto_concluido'] = 0;
                    $request['proyecto_eliminado'] = 0;
                    $request['solicitudOS'] = 0;

                    DB::statement('ALTER TABLE proyecto AUTO_INCREMENT=1');
                    $proyectoo = proyectoModel::create($request->all());


                    // Folios siguientes
                    $ano = (date('y')) + 0;
                    $proyecto_folio = "";


                    // folio proyecto
                    if (intval($request->proyectoInterno) == 0) { //ASIGNAMOS UN FOLIO NORMAL

                        //Buscamos los proyectos Internos
                        $folio = DB::select('SELECT
                                            (COUNT(proyecto.proyecto_folio)+42) AS nuevo_folio_proyecto
                                        FROM
                                            proyecto
                                        WHERE
                                            proyecto.proyectoInterno = 0
                                            AND DATE_FORMAT(proyecto.created_at, "%Y") = DATE_FORMAT(CURDATE(), "%Y")');


                        switch ($folio[0]->nuevo_folio_proyecto) {
                            case ($folio[0]->nuevo_folio_proyecto < 10):
                                $proyecto_folio = "RES-PJ-" . $ano . "-00" . $folio[0]->nuevo_folio_proyecto;
                                break;
                            case ($folio[0]->nuevo_folio_proyecto < 100):
                                $proyecto_folio = "RES-PJ-" . $ano . "-0" . $folio[0]->nuevo_folio_proyecto;
                                break;
                            default:
                                $proyecto_folio = "RES-PJ-" . $ano . "-" . $folio[0]->nuevo_folio_proyecto;
                                break;
                        }
                    } else { // ASIGNAMOS UN FOLIO INTERNO 


                        $folio = DB::select('SELECT
                                            (COUNT(proyecto.proyecto_folio)+42) AS nuevo_folio_proyecto
                                        FROM
                                            proyecto
                                        WHERE
                                            proyecto.proyectoInterno = 1
                                            AND DATE_FORMAT(proyecto.created_at, "%Y") = DATE_FORMAT(CURDATE(), "%Y")');

                        switch ($folio[0]->nuevo_folio_proyecto) {
                            case ($folio[0]->nuevo_folio_proyecto < 10):
                                $proyecto_folio = "RES-PI-" . $ano . "-00" . $folio[0]->nuevo_folio_proyecto;
                                break;
                            case ($folio[0]->nuevo_folio_proyecto < 100):
                                $proyecto_folio = "RES-PI-" . $ano . "-0" . $folio[0]->nuevo_folio_proyecto;
                                break;
                            default:
                                $proyecto_folio = "RES-PI-" . $ano . "-" . $folio[0]->nuevo_folio_proyecto;
                                break;
                        }
                    }

                    // actualizar folio
                    $proyectoo->update([
                        'proyecto_folio' => $proyecto_folio
                    ]);


                    //GUARDAMOS LOS TIPOS DEL
                    if ($request['HI'] || $request['ERGO'] || $request['PSICO'] || $request['SEGURIDAD']) {

                        serviciosProyectoModel::create([
                            'PROYECTO_ID' => $proyectoo->id,
                            'HI' => isset($request['HI']) ? $request['HI'] : 0,
                            'HI_PROGRAMA' => isset($request['HI_PROGRAMA']) ? $request['HI_PROGRAMA'] : 0,
                            'HI_RECONOCIMIENTO' => isset($request['HI_RECONOCIMIENTO']) ? $request['HI_RECONOCIMIENTO'] : 0,
                            'HI_EJECUCION' => isset($request['HI_EJECUCION']) ? $request['HI_EJECUCION'] : 0,
                            'HI_INFORME' => isset($request['HI_INFORME']) ? $request['HI_INFORME'] : 0,
                            'ERGO' => isset($request['ERGO']) ? $request['ERGO'] : 0,
                            'ERGO_PROGRAMA' => isset($request['ERGO_PROGRAMA']) ? $request['ERGO_PROGRAMA'] : 0,
                            'ERGO_RECONOCIMIENTO' => isset($request['ERGO_RECONOCIMIENTO']) ? $request['ERGO_RECONOCIMIENTO'] : 0,
                            'ERGO_EJECUCION' => isset($request['ERGO_EJECUCION']) ? $request['ERGO_EJECUCION'] : 0,
                            'ERGO_INFORME' => isset($request['ERGO_INFORME']) ? $request['ERGO_INFORME'] : 0,
                            'PSICO' => isset($request['PSICO']) ? $request['PSICO'] : 0,
                            'PSICO_PROGRAMA' => isset($request['PSICO_PROGRAMA']) ? $request['PSICO_PROGRAMA'] : 0,
                            'PSICO_RECONOCIMIENTO' => isset($request['PSICO_RECONOCIMIENTO']) ? $request['PSICO_RECONOCIMIENTO'] : 0,
                            'PSICO_EJECUCION' => isset($request['PSICO_EJECUCION']) ? $request['PSICO_EJECUCION'] : 0,
                            'PSICO_INFORME' => isset($request['PSICO_INFORME']) ? $request['PSICO_INFORME'] : 0,
                            'SEGURIDAD' => isset($request['SEGURIDAD']) ? $request['PSICO'] : 0,
                            'SEGURIDAD_PROGRAMA' => isset($request['SEGURIDAD_PROGRAMA']) ? $request['SEGURIDAD_PROGRAMA'] : 0,
                            'SEGURIDAD_RECONOCIMIENTO' => isset($request['SEGURIDAD_RECONOCIMIENTO']) ? $request['SEGURIDAD_RECONOCIMIENTO'] : 0,
                            'SEGURIDAD_EJECUCION' => isset($request['SEGURIDAD_EJECUCION']) ? $request['SEGURIDAD_EJECUCION'] : 0,
                            'SEGURIDAD_INFORME' => isset($request['SEGURIDAD_INFORME']) ? $request['SEGURIDAD_INFORME'] : 0
                        ]);
                    }


                    //GUARDAMOS LA ESTRUCTURA DEL PROYECTO
                    if ($request['TIENE_ESTRUCTURA'] == 1) {
                        foreach ($request->ETIQUETA_ID as $key => $value) {

                            estructuraProyectosModel::create([
                                'PROYECTO_ID' => $proyectoo->id,
                                'ETIQUETA_ID' => $value,
                                'OPCION_ID' => $request->OPCION_ID[$key],
                                'NIVEL' => $request->NIVEL[$key]

                            ]);
                        }
                    }



                    $proyecto = proyectoModel::with(['recsensorial', 'recsensorial.cliente', 'recsensorial.catregion', 'recsensorial.catgerencia', 'recsensorial.catactivo'])->findOrFail($proyectoo->id);


                } else //PROYECTO EDITADO
                {
                    // Consulta proyecto
                    $proyecto = proyectoModel::findOrFail($request->proyecto_id);

                    // Actualizar y consultar datos del proyecto
                    $proyecto->update($request->all());

                    //VALIDAMOS SI EL PROYECTO TIENE UN RECONOCIMIENTO VINCULADO EN ESE CASO VALIDAMOS LOS DATOS DEL PROYECTO Y LOS EDITAMOS PARA EL RECONOCIMIENTO
                    if (!is_null($proyecto->recsensorial_id)) {

                        // Reconocimiento seleccionado
                        $recsensorial = recsensorialModel::findOrFail($proyecto->recsensorial_id);
                        $contrato = clientecontratoModel::findOrFail($proyecto->contrato_id);
                        $descripcion_contrato = is_null($contrato->NUMERO_CONTRATO) ? $contrato->DESCRIPCION_CONTRATO : '[ ' . $contrato->NUMERO_CONTRATO . ' ]' . $contrato->DESCRIPCION_CONTRATO;


                        // Modificar dependiendo si la informacion cargada en el reconocimiento es para el cliente seleccionado o no
                        if ($recsensorial->informe_del_cliente == 1) {

                            $recsensorial->update([
                                'cliente_id' => $contrato->CLIENTE_ID,
                                'contrato_id' => $proyecto->contrato_id,
                                'descripcion_contrato' => $descripcion_contrato,
                                'descripcion_cliente' => $proyecto->proyecto_clienterazonsocial,
                                'recsensorial_empresa' => $proyecto->proyecto_clienterazonsocial,
                                'recsensorial_rfc' => $proyecto->proyecto_clienterfc,
                                'recsensorial_instalacion' => $proyecto->proyecto_clienteinstalacion,
                                'recsensorial_direccion' => $proyecto->proyecto_clientedireccionservicio,
                                'recsensorial_representanteseguridad' => $proyecto->proyecto_clientepersonadirigido

                            ]);
                        } else {

                            $recsensorial->update([
                                'cliente_id' => $contrato->CLIENTE_ID,
                                'contrato_id' => $proyecto->contrato_id,
                                'descripcion_contrato' => $descripcion_contrato,
                                'descripcion_cliente' => $proyecto->proyecto_clienterazonsocial
                            ]);
                        }
                    }


                    // if ($proyecto->recsensorial_id) // VALIDAR SI HAY RECONOCIMIENTO Y SI ES EL MISMO (YA EL RECONOCIMIENTO NO SE VINCULA EN PROYECTO SI NO EN RECONOCIMINETO)
                    // {
                    //     if ($proyecto->recsensorial_id == $request->recsensorial_id) // MISMO RECONOCIMIENTO
                    //     {
                    //         // Reconocimiento seleccionado
                    //         $recsensorial = recsensorialModel::findOrFail($proyecto->recsensorial_id);

                    //         // Modificar
                    //         $recsensorial->update([
                    //             'recsensorial_fisicosimprimirbloqueado' => 0, 'recsensorial_quimicosimprimirbloqueado' => 0, 'recsensorial_bloqueado' => 0, 'proyecto_id' => $proyecto->proyecto_folio
                    //         ]);
                    //     } else // CAMBIO DE RECONOCIMIENTO
                    //     {
                    //         // Reconocimiento anterior
                    //         $recsensorial = recsensorialModel::findOrFail($proyecto->recsensorial_id);

                    //         // Modificar
                    //         $recsensorial->update([
                    //             'recsensorial_fisicosimprimirbloqueado' => 0, 'recsensorial_quimicosimprimirbloqueado' => 0, 'recsensorial_bloqueado' => 0, 'proyecto_id' => null,
                    //         ]);

                    //         // Reconocimiento actual
                    //         $recsensorial = recsensorialModel::findOrFail($request->recsensorial_id);

                    //         // Modificar
                    //         $recsensorial->update([
                    //             'recsensorial_fisicosimprimirbloqueado' => 0, 'recsensorial_quimicosimprimirbloqueado' => 0, 'recsensorial_bloqueado' => 0, 'proyecto_id' => $proyecto->proyecto_folio
                    //         ]);
                    //     }
                    // }


                    //Actualizamos los servicios del proyecto
                    $eliminar_columnas = serviciosProyectoModel::where('PROYECTO_ID', $request->proyecto_id)->delete();
                    if ($request['HI'] || $request['ERGO'] || $request['PSICO'] || $request['SEGURIDAD']) {

                        serviciosProyectoModel::create([
                            'PROYECTO_ID' => $proyecto->id,
                            'HI' => isset($request['HI']) ? $request['HI'] : 0,
                            'HI_PROGRAMA' => isset($request['HI_PROGRAMA']) ? $request['HI_PROGRAMA'] : 0,
                            'HI_RECONOCIMIENTO' => isset($request['HI_RECONOCIMIENTO']) ? $request['HI_RECONOCIMIENTO'] : 0,
                            'HI_EJECUCION' => isset($request['HI_EJECUCION']) ? $request['HI_EJECUCION'] : 0,
                            'HI_INFORME' => isset($request['HI_INFORME']) ? $request['HI_INFORME'] : 0,
                            'ERGO' => isset($request['ERGO']) ? $request['ERGO'] : 0,
                            'ERGO_PROGRAMA' => isset($request['ERGO_PROGRAMA']) ? $request['ERGO_PROGRAMA'] : 0,
                            'ERGO_RECONOCIMIENTO' => isset($request['ERGO_RECONOCIMIENTO']) ? $request['ERGO_RECONOCIMIENTO'] : 0,
                            'ERGO_EJECUCION' => isset($request['ERGO_EJECUCION']) ? $request['ERGO_EJECUCION'] : 0,
                            'ERGO_INFORME' => isset($request['ERGO_INFORME']) ? $request['ERGO_INFORME'] : 0,
                            'PSICO' => isset($request['PSICO']) ? $request['PSICO'] : 0,
                            'PSICO_PROGRAMA' => isset($request['PSICO_PROGRAMA']) ? $request['PSICO_PROGRAMA'] : 0,
                            'PSICO_RECONOCIMIENTO' => isset($request['PSICO_RECONOCIMIENTO']) ? $request['PSICO_RECONOCIMIENTO'] : 0,
                            'PSICO_EJECUCION' => isset($request['PSICO_EJECUCION']) ? $request['PSICO_EJECUCION'] : 0,
                            'PSICO_INFORME' => isset($request['PSICO_INFORME']) ? $request['PSICO_INFORME'] : 0,
                            'SEGURIDAD' => isset($request['SEGURIDAD']) ? $request['SEGURIDAD'] : 0,
                            'SEGURIDAD_PROGRAMA' => isset($request['SEGURIDAD_PROGRAMA']) ? $request['SEGURIDAD_PROGRAMA'] : 0,
                            'SEGURIDAD_RECONOCIMIENTO' => isset($request['SEGURIDAD_RECONOCIMIENTO']) ? $request['SEGURIDAD_RECONOCIMIENTO'] : 0,
                            'SEGURIDAD_EJECUCION' => isset($request['SEGURIDAD_EJECUCION']) ? $request['SEGURIDAD_EJECUCION'] : 0,
                            'SEGURIDAD_INFORME' => isset($request['SEGURIDAD_INFORME']) ? $request['SEGURIDAD_INFORME'] : 0
                        ]);
                    }


                    //Actualizamos la estructura del proyecto
                    $eliminar_columnass = estructuraProyectosModel::where('PROYECTO_ID', $request->proyecto_id)->delete();
                    if ($request['TIENE_ESTRUCTURA'] == 1) {
                        foreach ($request->ETIQUETA_ID as $key => $value) {

                            estructuraProyectosModel::create([
                                'PROYECTO_ID' => $proyecto->id,
                                'ETIQUETA_ID' => $value,
                                'OPCION_ID' => $request->OPCION_ID[$key],
                                'NIVEL' => $request->NIVEL[$key]

                            ]);
                        }
                    }


                    $proyecto = proyectoModel::with(['recsensorial', 'recsensorial.cliente', 'recsensorial.catregion', 'recsensorial.catgerencia', 'recsensorial.catactivo'])->findOrFail($request->proyecto_id);
                    // mensaje
                    $dato["msj"] = 'Informacion modificada correctamente';
                }
            
            // ===================== Asignacion de usuarios =========================
            } elseif ($request->api == 2){

                $total = DB::select('SELECT COUNT(ID_PROYECTO_USUARIO) AS ASIGNADO
                                    FROM proyectoUsuarios
                                    WHERE USUARIO_ID = ?
                                    AND PROYECTO_ID = ?', [$request->USUARIO_ID, $request->PROYECTO_ID]);

                if ($request->ID_PROYECTO_USUARIO == 0) {

                    if ($total[0]->ASIGNADO == 0) {

                        $proyecto = ProyectoUsuariosModel::create($request->all());
                    } else {

                        return response()->json('El usuario ya esta asignado al proyecto', 500);
                    }
                } else {

                    $proyecto = ProyectoUsuariosModel::findOrFail($request->ID_PROYECTO_USUARIO);
                    $request['SERVICIO_HI'] = isset($request['SERVICIO_HI']) ? $request['SERVICIO_HI'] : 0;
                    $request['SERVICIO_PSICO'] = isset($request['SERVICIO_PSICO']) ? $request['SERVICIO_PSICO'] : 0;
                    $request['SERVICIO_ERGO'] = isset($request['SERVICIO_ERGO']) ? $request['SERVICIO_ERGO'] : 0;
                    $proyecto->update($request->all());
                }

                //Realizamos la asignacion de usuarios
                return response()->json($proyecto);
            

            // ================== Clonacion de proyectos =================================
            }elseif($request->api == 3){

                // acccion
                $request['proyecto_puntosrealesactivo'] = 1;
                $request['proyecto_bitacoraactivo'] = 1;
                $request['proyecto_concluido'] = 0;
                $request['proyecto_eliminado'] = 0;
                $request['solicitudOS'] = 0;
                $request['proyectoInterno'] = 0;
                $request['recsensorial_id'] = null;
                $request['reconocimiento_psico_id'] = null;
                $request['proyecto_folio'] = null;

                //Al proyecto interno le actualizacimos el campo de proyecto_clonado para que se deshabilite
                $proyecto_clonado = proyectoModel::findOrFail($request->proyecto_id);
                $proyecto_clonado->update(['proyecto_clonado' => 1]);





                DB::statement('ALTER TABLE proyecto AUTO_INCREMENT=1');
                $proyectoo = proyectoModel::create($request->all());


                $ano = (date('y')) + 0;
                $proyecto_folio = "";

                //Buscamos los proyectos 
                $folio = DB::select('SELECT
                                (COUNT(proyecto.proyecto_folio)+42) AS nuevo_folio_proyecto
                            FROM
                                proyecto
                            WHERE
                                proyecto.proyectoInterno = 0
                                AND DATE_FORMAT(proyecto.created_at, "%Y") = DATE_FORMAT(CURDATE(), "%Y")');


                switch (($folio[0]->nuevo_folio_proyecto + 2)) {
                    case ($folio[0]->nuevo_folio_proyecto < 10):
                        $proyecto_folio = "RES-PJ-" . $ano . "-00" . $folio[0]->nuevo_folio_proyecto;
                        break;
                    case ($folio[0]->nuevo_folio_proyecto < 100):
                        $proyecto_folio = "RES-PJ-" . $ano . "-0" . $folio[0]->nuevo_folio_proyecto;
                        break;
                    default:
                        $proyecto_folio = "RES-PJ-" . $ano . "-" . $folio[0]->nuevo_folio_proyecto;
                        break;
                }

                $proyectoo->update([
                    'proyecto_folio' => $proyecto_folio
                ]);

                //GUARDAMOS LOS TIPOS DEL
                if ($request['HI']) {

                    serviciosProyectoModel::create([
                        'PROYECTO_ID' => $proyectoo->id,
                        'HI' => isset($request['HI']) ? $request['HI'] : 0,
                        'HI_PROGRAMA' => isset($request['HI_PROGRAMA']) ? $request['HI_PROGRAMA'] : 0,
                        'HI_RECONOCIMIENTO' => isset($request['HI_RECONOCIMIENTO']) ? $request['HI_RECONOCIMIENTO'] : 0,
                        'HI_EJECUCION' => isset($request['HI_EJECUCION']) ? $request['HI_EJECUCION'] : 0,
                        'HI_INFORME' => isset($request['HI_INFORME']) ? $request['HI_INFORME'] : 0,
                        'ERGO' => isset($request['ERGO']) ? $request['ERGO'] : 0,
                        'ERGO_PROGRAMA' => isset($request['ERGO_PROGRAMA']) ? $request['ERGO_PROGRAMA'] : 0,
                        'ERGO_RECONOCIMIENTO' => isset($request['ERGO_RECONOCIMIENTO']) ? $request['ERGO_RECONOCIMIENTO'] : 0,
                        'ERGO_EJECUCION' => isset($request['ERGO_EJECUCION']) ? $request['ERGO_EJECUCION'] : 0,
                        'ERGO_INFORME' => isset($request['ERGO_INFORME']) ? $request['ERGO_INFORME'] : 0,
                        'PSICO' => isset($request['PSICO']) ? $request['PSICO'] : 0,
                        'PSICO_PROGRAMA' => isset($request['PSICO_PROGRAMA']) ? $request['PSICO_PROGRAMA'] : 0,
                        'PSICO_RECONOCIMIENTO' => isset($request['PSICO_RECONOCIMIENTO']) ? $request['PSICO_RECONOCIMIENTO'] : 0,
                        'PSICO_EJECUCION' => isset($request['PSICO_EJECUCION']) ? $request['PSICO_EJECUCION'] : 0,
                        'PSICO_INFORME' => isset($request['PSICO_INFORME']) ? $request['PSICO_INFORME'] : 0,
                        'SEGURIDAD' => isset($request['SEGURIDAD']) ? $request['PSICO'] : 0,
                        'SEGURIDAD_PROGRAMA' => isset($request['SEGURIDAD_PROGRAMA']) ? $request['SEGURIDAD_PROGRAMA'] : 0,
                        'SEGURIDAD_RECONOCIMIENTO' => isset($request['SEGURIDAD_RECONOCIMIENTO']) ? $request['SEGURIDAD_RECONOCIMIENTO'] : 0,
                        'SEGURIDAD_EJECUCION' => isset($request['SEGURIDAD_EJECUCION']) ? $request['SEGURIDAD_EJECUCION'] : 0,
                        'SEGURIDAD_INFORME' => isset($request['SEGURIDAD_INFORME']) ? $request['SEGURIDAD_INFORME'] : 0
                    ]);
                }


                //GUARDAMOS LA ESTRUCTURA DEL PROYECTO
                if ($request['TIENE_ESTRUCTURA'] == 1) {
                    foreach ($request->ETIQUETA_ID as $key => $value) {

                        estructuraProyectosModel::create([
                            'PROYECTO_ID' => $proyectoo->id,
                            'ETIQUETA_ID' => $value,
                            'OPCION_ID' => $request->OPCION_ID[$key],
                            'NIVEL' => $request->NIVEL[$key]

                        ]);
                    }
                }


                // DB::select('CALL clonacionProyectoInterno(?)', [$proyectoo->id]);

                $dato["msj"] = 'Proyecto clonado';
                return response()->json($dato);


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


    public function proyectoSolicitarOS($proyecto_id, $valor)
    {
        try {
            // Proyecto
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            // Guardar cambios
            $proyecto->solicitudOS = intval($valor);
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

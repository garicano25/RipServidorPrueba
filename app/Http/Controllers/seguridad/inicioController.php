<?php

namespace App\Http\Controllers\seguridad;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
// use Artisan;
use DB;

// MODELOS
use App\User;


//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');


class inicioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Reportes,ApoyoTecnico,Financiero,Cadista,Externo');
        // $this->middleware('Superusuario,Administrador');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Borarr cache // use Artisan;
        // Artisan::call('route:cache');
        // Artisan::call('config:cache');
        // Artisan::call('route:clear');
        // Artisan::call('config:clear');
        // Artisan::call('cache:clear');
        // Artisan::call('view:clear');


        // $usuarionombre = json_encode( auth()->user()->name );
        // return view('principal.index', compact('usuarionombre'));
        // return view('principal.index');


        if (auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador']))
        {
            return view('principal.modulos');
            // return view('principal.index');

        }
        else if (auth()->user()->hasRoles(['Externo']))
        {
            return redirect()->route('externo.index');
        }
        else if (auth()->user()->hasRoles(['Operativo HI']))
        {
            // return redirect()->route('proyectos.index');
            return view('principal.modulos');

        }
        else if (auth()->user()->hasRoles(['Almacén']))
        {
            // return redirect()->route('recsensorial.index');
            return view('principal.modulos');

        }
        else if (auth()->user()->hasRoles(['Proveedor']))
        {
            // return redirect()->route('proveedor.index');
            return view('principal.modulos');

        } else if (auth()->user()->hasRoles(['Compras'])) {
            // return redirect()->route('proveedor.index');
            return view('principal.modulos');
        }
        else
        {
            return redirect()->route('usuario.index');
        }
    }

    public function tablero(){
        return view('principal.index');
        
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function graficas()
    {
        try
        {
            $proyectos = DB::select('SELECT
                                        DATE_FORMAT(proyecto.proyecto_fechainicio, "%Y") AS anio,
                                        COUNT(proyecto.id) AS total
                                    FROM
                                        proyecto
                                    WHERE
                                        DATE_FORMAT(proyecto.proyecto_fechainicio, "%Y") != ""
                                    GROUP BY
                                        DATE_FORMAT(proyecto.proyecto_fechainicio, "%Y")
                                    ORDER BY
                                        DATE_FORMAT(proyecto.proyecto_fechainicio, "%Y") ASC');


            if (count($proyectos) > 0)
            {
                foreach ($proyectos as $key => $value)
                {
                    $dato['proyectos_periodo_chartData'][] = array(
                                                                  'periodo' => $value->anio
                                                                , 'total' => $value->total
                                                                , 'color' => "#1e88e5" //Azul
                                                            );
                }
            }
            else
            {
                $dato['proyectos_periodo_chartData'][] = array(
                                                              'periodo' => date('Y')
                                                            , 'total' => 0
                                                            , 'color' => "#1e88e5" //Azul
                                                        );
            }


            //=====================================================================


            $recsensorial = DB::select('SELECT
                                            DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%Y") AS anio,
                                            COUNT(recsensorial.id) AS total
                                        FROM
                                            recsensorial
                                        WHERE
                                            DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%Y") != ""
                                        GROUP BY
                                            DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%Y")
                                        ORDER BY
                                            DATE_FORMAT(recsensorial.recsensorial_fechainicio, "%Y") ASC');


            if (count($recsensorial) > 0)
            {
                foreach ($recsensorial as $key => $value)
                {
                    $dato['reconocimientos_periodo_chartData'][] = array(
                                                                          'periodo' => $value->anio
                                                                        , 'total' => $value->total
                                                                        , 'color' => "#7460ee" //Morado
                                                                    );
                }
            }
            else
            {
                $dato['reconocimientos_periodo_chartData'][] = array(
                                                                      'periodo' => date('Y')
                                                                    , 'total' => 0
                                                                    , 'color' => "#7460ee" //Morado
                                                                );
            }


            //=====================================================================


            $proyectos_detalles = DB::select('SELECT
                                                --  TABLA2.id, 
                                                --  TABLA2.proyecto_folio, 
                                                --  TABLA2.proyecto_fechainicio, 
                                                --  TABLA2.proyecto_fechafin, 
                                                --  TABLA2.proyecto_totaldias, 
                                                --  TABLA2.proyecto_fechaentrega, 
                                                --  TABLA2.prorroga, 
                                                --  TABLA2.prorroga_fecha_max, 
                                                --  TABLA2.proyecto_entregado, 
                                                --  TABLA2.proyecto_fechaentregado,
                                                --  TABLA2.proyecto_estado
                                                    DATE_FORMAT(TABLA2.proyecto_fechainicio, "%Y") AS periodo,
                                                    COUNT(TABLA2.id) AS total,
                                                    SUM(IF(TABLA2.proyecto_estado = "Activo", 1, 0)) AS activos,
                                                    SUM(IF(TABLA2.proyecto_estado = "Reprogramado", 1, 0)) AS reprogramados,
                                                    SUM(IF(TABLA2.proyecto_estado = "Penalizado", 1, 0)) AS penalizados,
                                                    SUM(IF(TABLA2.proyecto_estado = "Concluido", 1, 0)) AS concluidos
                                                FROM
                                                    (
                                                        SELECT
                                                            TABLA1.id, 
                                                            TABLA1.proyecto_folio, 
                                                            TABLA1.proyecto_fechainicio, 
                                                            TABLA1.proyecto_fechafin, 
                                                            TABLA1.proyecto_totaldias, 
                                                            TABLA1.proyecto_fechaentrega, 
                                                            TABLA1.prorroga, 
                                                            TABLA1.prorroga_fecha_max, 
                                                            TABLA1.proyecto_entregado, 
                                                            TABLA1.proyecto_fechaentregado,
                                                            (
                                                                CASE
                                                                    WHEN TABLA1.proyecto_entregado = 1 THEN
                                                                        CASE
                                                                            WHEN TABLA1.prorroga = 1 THEN
                                                                                CASE
                                                                                    WHEN TABLA1.proyecto_fechaentregado > TABLA1.prorroga_fecha_max THEN "Penalizado"
                                                                                    ELSE "Reprogramado"
                                                                                END
                                                                            ELSE 
                                                                                CASE
                                                                                    WHEN TABLA1.proyecto_fechaentregado > TABLA1.proyecto_fechaentrega THEN "Penalizado"
                                                                                    ELSE "Concluido"
                                                                                END
                                                                        END
                                                                    ELSE
                                                                        CASE
                                                                            WHEN TABLA1.prorroga = 1 THEN
                                                                                CASE
                                                                                    WHEN CURDATE() > TABLA1.prorroga_fecha_max THEN "Penalizado"
                                                                                    ELSE "Reprogramado"
                                                                                END
                                                                            ELSE
                                                                                CASE
                                                                                    WHEN CURDATE() > TABLA1.proyecto_fechaentrega THEN "Penalizado"
                                                                                    ELSE "Activo"
                                                                                END
                                                                        END
                                                                END
                                                            ) AS proyecto_estado    
                                                        FROM
                                                            (
                                                                SELECT
                                                                    proyecto.id, 
                                                                    proyecto.proyecto_folio, 
                                                                    proyecto.proyecto_fechainicio, 
                                                                    proyecto.proyecto_fechafin, 
                                                                    proyecto.proyecto_totaldias, 
                                                                    proyecto.proyecto_fechaentrega, 
                                                                    IFNULL((
                                                                        SELECT
                                                                            COUNT(proyectoprorrogas.id)
                                                                        FROM
                                                                            proyectoprorrogas
                                                                        WHERE
                                                                            proyectoprorrogas.proyecto_id = proyecto.id
                                                                    ), 0) AS prorroga,
                                                                    IFNULL((
                                                                        SELECT
                                                                            MAX(proyectoprorrogas.proyectoprorrogas_fechafin)
                                                                        FROM
                                                                            proyectoprorrogas
                                                                        WHERE
                                                                            proyectoprorrogas.proyecto_id = proyecto.id
                                                                    ), NULL) AS prorroga_fecha_max,
                                                                    proyecto.proyecto_entregado, 
                                                                    proyecto.proyecto_fechaentregado
                                                                FROM
                                                                    proyecto
                                                                WHERE
                                                                    proyecto.proyecto_fechaentrega != ""
                                                                ORDER BY
                                                                    DATE_FORMAT(proyecto.proyecto_fechainicio, "%Y") ASC
                                                            ) AS TABLA1
                                                    ) AS TABLA2
                                                GROUP BY
                                                    DATE_FORMAT(TABLA2.proyecto_fechainicio, "%Y")');


            $dato['grafica_pastel_total_proyectos'] = 0;
            if (count($proyectos_detalles) > 0)
            {
                foreach ($proyectos_detalles as $key => $value)
                {
                    $dato['grafica_proyectos_detalles'][] = array(
                                                                  "periodo" => $value->periodo
                                                                , "activos" => $value->activos
                                                                , "reprogramados" => $value->reprogramados
                                                                , "penalizados" => $value->penalizados
                                                                , "concluidos" => $value->concluidos
                                                            );


                    if ($value->periodo == date('Y'))
                    {
                        $dato['grafica_pastel'][] = array(
                                                          'estado' => 'Activos'
                                                        , 'total' => $value->activos
                                                        , 'color' => "#26c6da" //Azul 
                                                    );


                        $dato['grafica_pastel'][] = array(
                                                          'estado' => 'Reprogramados'
                                                        , 'total' => $value->reprogramados
                                                        , 'color' => "#FCD202" //Amarillo 
                                                    );


                        $dato['grafica_pastel'][] = array(
                                                          'estado' => 'Penalizados'
                                                        , 'total' => $value->penalizados
                                                        , 'color' => "#fc4b6c" //Rojo 
                                                    );


                        $dato['grafica_pastel'][] = array(
                                                          'estado' => 'Concluidos'
                                                        , 'total' => $value->concluidos
                                                        , 'color' => "#04D215" //Verde 
                                                    );


                        $dato['grafica_pastel_total_proyectos'] = (($value->activos+0) + ($value->reprogramados+0) + ($value->penalizados+0) + ($value->concluidos+0));
                    }
                }
            }
            else
            {
                $dato['grafica_proyectos_detalles'][] = array(
                                                              "periodo" => date('Y')
                                                            , "activos" => 0
                                                            , "reprogramados" => 0
                                                            , "penalizados" => 0
                                                            , "concluidos" => 0
                                                        );


                $dato['grafica_pastel'][] = array(
                                                  'estado' => 'Sin datos'
                                                , 'total' => 1
                                                , 'color' => "#BBBBBB" 
                                            );
            }


            //=====================================================================


            $proyectos_tabla = DB::select('SELECT
                                                TABLA1.id, 
                                                TABLA1.proyecto_folio,
                                                TABLA1.proyecto_clienteinstalacion, 
                                                TABLA1.proyecto_fechainicio, 
                                                TABLA1.proyecto_fechafin, 
                                                TABLA1.proyecto_totaldias, 
                                                TABLA1.proyecto_fechaentrega, 
                                                TABLA1.prorroga, 
                                                TABLA1.prorroga_fecha_max, 
                                                TABLA1.proyecto_entregado, 
                                                TABLA1.proyecto_fechaentregado,
                                                (
                                                    CASE
                                                        WHEN TABLA1.proyecto_entregado = 1 THEN
                                                            CASE
                                                                WHEN TABLA1.prorroga = 1 THEN
                                                                    CASE
                                                                        WHEN TABLA1.proyecto_fechaentregado > TABLA1.prorroga_fecha_max THEN "Penalizado"
                                                                        ELSE "Reprogramado"
                                                                    END
                                                                ELSE 
                                                                    CASE
                                                                        WHEN TABLA1.proyecto_fechaentregado > TABLA1.proyecto_fechaentrega THEN "Penalizado"
                                                                        ELSE "Concluido"
                                                                    END
                                                            END
                                                        ELSE
                                                            CASE
                                                                WHEN TABLA1.prorroga = 1 THEN
                                                                    CASE
                                                                        WHEN CURDATE() > TABLA1.prorroga_fecha_max THEN "Penalizado"
                                                                        ELSE "Reprogramado"
                                                                    END
                                                                ELSE
                                                                    CASE
                                                                        WHEN CURDATE() > TABLA1.proyecto_fechaentrega THEN "Penalizado"
                                                                        ELSE "Activo"
                                                                    END
                                                            END
                                                    END
                                                ) AS proyecto_estado    
                                            FROM
                                                (
                                                    SELECT
                                                        proyecto.id, 
                                                        proyecto.proyecto_folio, 
                                                        proyecto.proyecto_clienteinstalacion,
                                                        proyecto.proyecto_fechainicio, 
                                                        proyecto.proyecto_fechafin, 
                                                        proyecto.proyecto_totaldias, 
                                                        proyecto.proyecto_fechaentrega, 
                                                        IFNULL((
                                                            SELECT
                                                                COUNT(proyectoprorrogas.id)
                                                            FROM
                                                                proyectoprorrogas
                                                            WHERE
                                                                proyectoprorrogas.proyecto_id = proyecto.id
                                                        ), 0) AS prorroga,
                                                        IFNULL((
                                                            SELECT
                                                                MAX(proyectoprorrogas.proyectoprorrogas_fechafin)
                                                            FROM
                                                                proyectoprorrogas
                                                            WHERE
                                                                proyectoprorrogas.proyecto_id = proyecto.id
                                                        ), NULL) AS prorroga_fecha_max,
                                                        proyecto.proyecto_entregado, 
                                                        proyecto.proyecto_fechaentregado
                                                    FROM
                                                        proyecto
                                                    WHERE
                                                        proyecto.proyecto_fechaentrega != "" 
                                                        AND DATE_FORMAT(proyecto.proyecto_fechainicio, "%Y") = DATE_FORMAT(CURDATE(), "%Y")
                                                    ORDER BY
                                                        proyecto.id ASC
                                                ) AS TABLA1');


            $dato['tabla_proyectos_actuales'] = null;
            if (count($proyectos_tabla) > 0)
            {
                foreach ($proyectos_tabla as $key => $value)
                {
                    $dato['tabla_proyectos_actuales'] .=    '<tr>
                                                                <td>'.($key+1).'</td>
                                                                <td>'.$value->proyecto_folio.'</td>
                                                                <td>'.$value->proyecto_clienteinstalacion.'</td>
                                                                <td>'.$value->proyecto_fechainicio.'</td>
                                                                <td>'.$value->proyecto_fechafin.'</td>
                                                                <td>'.$value->prorroga.'</td>
                                                                <td>'.$value->prorroga_fecha_max.'</td>
                                                                <td>'.$value->proyecto_fechaentregado.'</td>
                                                                <td>'.$value->proyecto_estado.'</td>
                                                            </tr>';
                }
            }


            //=====================================================================


            // respuesta
            $dato['grafica_pastel_anio'] = date('Y');
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['reconocimientos_periodo_chartData'][] = array(
                                                                  'periodo' => date('Y')
                                                                , 'total' => 0
                                                                , 'color' => "#999999" //Gris 
                                                            );


            $dato['proyectos_periodo_chartData'][] = array(
                                                        'periodo' => date('Y')
                                                        , 'total' => 0
                                                        , 'color' => "#999999" //Gris 
                                                    );


            $dato['grafica_proyectos_detalles'][] = array(
                                                          "periodo" => date('Y')
                                                        , "activos" => 0
                                                        , "reprogramados" => 0
                                                        , "penalizados" => 0
                                                        , "concluidos" => 0
                                                    );


            $dato['grafica_pastel'][] = array(
                                            'estado' => 'Estado'
                                            , 'total' => 0
                                            , 'color' => "#999999" //Gris 
                                        );


            // respuesta
            $dato['grafica_pastel_anio'] = date('Y');
            $dato['grafica_pastel_total_proyectos'] = 0;
            $dato['tabla_proyectos_actuales'] = null;
            $dato["msj"] = 'Error al consultar las gráficas del tablero '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $usuario_id
     * @return \Illuminate\Http\Response
     */
    public function usuariofoto($usuario_id)
    {
        $usuario = User::findOrFail($usuario_id);
        // return Storage::download($usuario_foto->usuario_foto);
        return Storage::response($usuario->usuario_foto);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function consultanotificaciones()
    {
        try
        {
            // ACREDITACIONES
            $notificaciones_total = 0;
            $notificaciones = '<div style="text-align: center; font-size: 14px; color: #888888; margin: 20px auto;">No hay notificaciones que mostrar</div>';

            // VALIDAR ROLES
            if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'Proyecto']))
            {

                $acreditaciones = DB::select('SELECT
                                                    acreditacion.id,
                                                    proveedor.proveedor_NombreComercial,
                                                    cat_tipoacreditacion.catTipoAcreditacion_Nombre,
                                                    acreditacion.acreditacion_Entidad,
                                                    acreditacion.acreditacion_Numero,
                                                    CONCAT(acreditacion.acreditacion_Vigencia, " (", DATEDIFF(acreditacion.acreditacion_Vigencia, CURDATE()), " d)") AS acreditacion_Vigencia
                                                FROM
                                                    acreditacion
                                                    LEFT JOIN proveedor ON acreditacion.proveedor_id = proveedor.id
                                                    LEFT JOIN cat_tipoacreditacion ON acreditacion.acreditacion_Tipo = cat_tipoacreditacion.id
                                                WHERE
                                                    acreditacion.acreditacion_Eliminado = 0 AND acreditacion.acreditacion_Vigencia <= DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 30 DAY), "%Y-%m-%d")
                                                ORDER BY
                                                    proveedor.proveedor_NombreComercial ASC,
                                                    acreditacion.acreditacion_Vigencia ASC');

                if (count($acreditaciones) > 0)
                {
                    //iniciaizar si apenas son las primeras notificaciones
                    if ($notificaciones_total == 0)
                    {
                        $notificaciones = '';
                    }

                    foreach ($acreditaciones as $key => $value)
                    {
                        $notificaciones .= '<a href="#">
                                                <div class="btn btn-danger btn-circle"><i class="fa fa-file-text-o"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>'.$value->catTipoAcreditacion_Nombre.', '.$value->acreditacion_Numero.'</h5>
                                                    <span class="mail-desc">'.$value->proveedor_NombreComercial.'</span>
                                                    <span class="time">Vigencia: '.$value->acreditacion_Vigencia.'</span>
                                                </div>
                                            </a>';

                        $notificaciones_total += 1;
                    }
                }

                //=======================================================

                // EQUIPOS
                $equipos = DB::select('SELECT
                                            equipo.id,
                                            proveedor.proveedor_NombreComercial,
                                            equipo.equipo_Descripcion,
                                            CONCAT(equipo.equipo_VigenciaCalibracion, " (", DATEDIFF(equipo.equipo_VigenciaCalibracion, CURDATE()), " d)") AS equipo_VigenciaCalibracion
                                        FROM
                                            equipo
                                            LEFT JOIN proveedor ON equipo.proveedor_id = proveedor.id
                                        WHERE
                                            equipo.equipo_Eliminado = 0 AND equipo.equipo_VigenciaCalibracion <= DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 30 DAY), "%Y-%m-%d")
                                        ORDER BY
                                            proveedor.proveedor_NombreComercial ASC,
                                            equipo.equipo_VigenciaCalibracion ASC');

                if (count($equipos) > 0)
                {
                    //iniciaizar si apenas son las primeras notificaciones
                    if ($notificaciones_total == 0)
                    {
                        $notificaciones = '';
                    }

                    foreach ($equipos as $key => $value)
                    {
                        $notificaciones .= '<a href="#">
                                                <div class="btn btn-warning btn-circle"><i class="fa fa-desktop"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Equipo, '.$value->equipo_Descripcion.'</h5>
                                                    <span class="mail-desc">'.$value->proveedor_NombreComercial.'</span>
                                                    <span class="time">Vigencia calibración: '.$value->equipo_VigenciaCalibracion.'</span>
                                                </div>
                                            </a>';

                        $notificaciones_total += 1;
                    }
                }


                //=======================================================

                // SIGNATARIO ACREDITACION
                $cursos = DB::select('SELECT
                                            signatarioacreditacion.id,
                                            proveedor.proveedor_NombreComercial,
                                            signatario.signatario_Nombre,
                                            CONCAT(acreditacionalcance.acreditacionAlcance_agente, IFNULL(CONCAT(" (", acreditacionalcance.acreditacionAlcance_agentetipo, ")"), "")) AS agente,
                                            CONCAT(signatarioacreditacion.signatarioAcreditacion_Vigencia, " (", DATEDIFF(signatarioacreditacion.signatarioAcreditacion_Vigencia, CURDATE()), " d)") AS signatarioAcreditacion_Vigencia
                                        FROM
                                            signatarioacreditacion
                                            LEFT JOIN signatario ON signatarioacreditacion.signatario_id = signatario.id
                                            LEFT JOIN proveedor ON signatario.proveedor_id = proveedor.id
                                            LEFT JOIN acreditacionalcance ON signatarioacreditacion.signatarioAcreditacion_Alcance = acreditacionalcance.id
                                        WHERE
                                            signatarioacreditacion.signatarioAcreditacion_Vigencia <= DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 30 DAY), "%Y-%m-%d")
                                            AND signatarioacreditacion.signatarioAcreditacion_Eliminado = 0
                                            AND signatarioacreditacion.cat_signatariodisponibilidad_id = 1
                                            AND signatario.signatario_Eliminado = 0 
                                            AND signatario.signatario_EstadoActivo = 1
                                        ORDER BY
                                            proveedor.proveedor_NombreComercial ASC,
                                            signatarioacreditacion.signatarioAcreditacion_Vigencia ASC');

                if (count($cursos) > 0)
                {
                    //iniciaizar si apenas son las primeras notificaciones
                    if ($notificaciones_total == 0)
                    {
                        $notificaciones = '';
                    }

                    foreach ($cursos as $key => $value)
                    {
                        $notificaciones .= '<a href="#">
                                                <div class="btn btn-success btn-circle"><i class="fa fa-user"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Signatario, '.$value->signatario_Nombre.'</h5>
                                                    <span class="mail-desc">'.$value->proveedor_NombreComercial.'</span>
                                                    <span class="time">Alcance: '.$value->agente.'</span>
                                                    <span class="time">Vigencia: '.$value->signatarioAcreditacion_Vigencia.'</span>
                                                </div>
                                            </a>';

                        $notificaciones_total += 1;
                    }
                }


                //=======================================================

                // SIGNATARIO CURSOS
                $cursos = DB::select('SELECT
                                            signatariocurso.id,
                                            proveedor.proveedor_NombreComercial,
                                            signatario.signatario_Nombre,
                                            signatariocurso.signatarioCurso_NombreCurso,
                                            CONCAT(DATE_FORMAT(signatariocurso.signatarioCurso_FechaVigencia, "%d-%m-%Y"), " (", DATEDIFF(signatariocurso.signatarioCurso_FechaVigencia, CURDATE()), " d)") AS signatarioCurso_FechaVigencia
                                        FROM
                                            signatariocurso
                                            LEFT JOIN signatario ON signatariocurso.signatario_id = signatario.id
                                            LEFT JOIN proveedor ON signatario.proveedor_id = proveedor.id
                                        WHERE
                                            signatariocurso.signatarioCurso_Eliminado = 0
                                            AND signatario.signatario_Eliminado = 0 
                                            AND signatario.signatario_EstadoActivo = 1
                                            AND signatariocurso.signatarioCurso_FechaVigencia <= DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 30 DAY), "%Y-%m-%d")  
                                        ORDER BY
                                            proveedor.proveedor_NombreComercial ASC,
                                            signatariocurso.signatarioCurso_FechaVigencia ASC');

                if (count($cursos) > 0)
                {
                    //iniciaizar si apenas son las primeras notificaciones
                    if ($notificaciones_total == 0)
                    {
                        $notificaciones = '';
                    }

                    foreach ($cursos as $key => $value)
                    {
                        $notificaciones .= '<a href="#">
                                                <div class="btn btn-info btn-circle"><i class="fa fa-user"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Signatario, '.$value->signatario_Nombre.'</h5>
                                                    <span class="mail-desc">'.$value->proveedor_NombreComercial.'</span>
                                                    <span class="time">Curso: '.$value->signatarioCurso_NombreCurso.'</span>
                                                    <span class="time">Vigencia: '.$value->signatarioCurso_FechaVigencia.'</span>
                                                </div>
                                            </a>';

                        $notificaciones_total += 1;
                    }
                }

            }

            // respuesta
            $dato['notificaciones_total'] = $notificaciones_total;
            $dato['notificaciones'] = $notificaciones;
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            $dato['notificaciones'] = '';
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
        
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $dato
     * @return \Illuminate\Http\Response
     */
    public function vaciarmoduloproveedor($dato)
    {
        if(auth()->user()->hasRoles(['Administrador']))
        {
            if ($dato === date('Ymd'))
            {
                DB::statement('ALTER TABLE cat_area AUTO_INCREMENT=1;');
                DB::statement('ALTER TABLE cat_servicioacreditacion AUTO_INCREMENT=1;');
                DB::statement('ALTER TABLE cat_signatariodisponibilidad AUTO_INCREMENT=1;');
                DB::statement('ALTER TABLE cat_signatarioestado AUTO_INCREMENT=1;');
                DB::statement('ALTER TABLE cat_tipoacreditacion AUTO_INCREMENT=1;');
                DB::statement('ALTER TABLE cat_tipoproveedor AUTO_INCREMENT=1;');
                DB::statement('ALTER TABLE cat_tipoproveedoralcance AUTO_INCREMENT=1;');

                DB::statement('TRUNCATE TABLE acreditacion;');
                DB::statement('ALTER TABLE acreditacion AUTO_INCREMENT=1;');

                DB::statement('TRUNCATE TABLE acreditacionalcance;');
                DB::statement('ALTER TABLE acreditacionalcance AUTO_INCREMENT=1;');

                DB::statement('TRUNCATE TABLE equipo;');
                DB::statement('ALTER TABLE equipo AUTO_INCREMENT=1;');

                DB::statement('TRUNCATE TABLE proveedordocumento;');
                DB::statement('ALTER TABLE proveedordocumento AUTO_INCREMENT=1;');

                DB::statement('TRUNCATE TABLE proveedordomicilio;');
                DB::statement('ALTER TABLE proveedordomicilio AUTO_INCREMENT=1;');

                DB::statement('TRUNCATE TABLE servicio;');
                DB::statement('ALTER TABLE servicio AUTO_INCREMENT=1;');

                DB::statement('TRUNCATE TABLE servicioprecios;');
                DB::statement('ALTER TABLE servicioprecios AUTO_INCREMENT=1;');

                DB::statement('TRUNCATE TABLE signatario;');
                DB::statement('ALTER TABLE signatario AUTO_INCREMENT=1;');

                DB::statement('TRUNCATE TABLE signatarioacreditacion;');
                DB::statement('ALTER TABLE signatarioacreditacion AUTO_INCREMENT=1;');

                DB::statement('TRUNCATE TABLE signatariocurso;');
                DB::statement('ALTER TABLE signatariocurso AUTO_INCREMENT=1;');

                DB::statement('TRUNCATE TABLE signatariodocumento;');
                DB::statement('ALTER TABLE signatariodocumento AUTO_INCREMENT=1;');

                DB::statement('TRUNCATE TABLE proveedor;');
                DB::statement('ALTER TABLE proveedor AUTO_INCREMENT=1;');

                echo "Tablas vacias correctamente ".date('Ymd');
            }
            else
            {
                echo "Dato incorrecto ".date('Ymd');
            }
        }
        else
        {
            echo "No se realizó ningun cambio, Solo el administrador puede vaciar el módulo admnistrador";
        }        
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $dato
     * @return \Illuminate\Http\Response
     */
    public function vaciarmoduloproyectos($dato)
    {
        if(auth()->user()->hasRoles(['Administrador']))
        {
            if ($dato === date('Ymd'))
            {
                DB::statement('TRUNCATE TABLE proyecto');
                DB::statement('ALTER TABLE proyecto AUTO_INCREMENT=1;');

                DB::statement('TRUNCATE TABLE proyectoequipos');
                DB::statement('ALTER TABLE proyectoequipos AUTO_INCREMENT=1;');

                DB::statement('TRUNCATE TABLE proyectoequiposobservacion');

                DB::statement('TRUNCATE TABLE proyectoordencompra');
                DB::statement('ALTER TABLE proyectoordencompra AUTO_INCREMENT=1;');

                DB::statement('TRUNCATE TABLE proyectoordenservicio');
                DB::statement('ALTER TABLE proyectoordenservicio AUTO_INCREMENT=1;');

                DB::statement('TRUNCATE TABLE proyectoproveedoresfisicos');
                DB::statement('ALTER TABLE proyectoproveedoresfisicos AUTO_INCREMENT=1;');

                DB::statement('TRUNCATE TABLE proyectoproveedoresquimicos');
                DB::statement('ALTER TABLE proyectoproveedoresquimicos AUTO_INCREMENT=1;');

                DB::statement('TRUNCATE TABLE proyectosignatarios');
                DB::statement('ALTER TABLE proyectosignatarios AUTO_INCREMENT=1;');

                DB::statement('TRUNCATE TABLE proyectosignatariosobservacion');

                echo "Tablas vacias correctamente ".date('Ymd');
            }
            else
            {
                echo "Dato incorrecto";
            }
        }
        else
        {
            echo "No se realizó ningun cambio, Solo el administrador puede vaciar el módulo admnistrador";
        }        
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $dato
     * @return \Illuminate\Http\Response
     */
    public function probarsql($dato)
    {
        $sql = DB::select($dato);
        dd($sql);
    }
}

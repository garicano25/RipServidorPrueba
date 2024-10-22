<?php

namespace App\Http\Controllers\proyecto;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

use App\modelos\proyecto\proyectovehiculosactualModel;
use App\modelos\proyecto\proyectovehiculosModel;
use App\modelos\proyecto\proyectoModel;
use App\modelos\proyecto\proyectovehiculoshistorialModel;


class proyectoVehiculoController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
       
    }

    public function proyectovehiculosinventario($proyecto_id)
    {
        try {
            $where_adicional = '';
            if (auth()->user()->hasRoles(['Externo'])) {
                $where_adicional = 'AND proyectoproveedores.proveedor_id = ' . auth()->user()->empleado_id;
            }

            $vehiculos = DB::select('SELECT
                                    proveedor.id AS proveedor_id,
                                    proveedor.proveedor_NombreComercial,
                                    vehiculo.id,
                                    vehiculo.vehiculo_marca,
                                    vehiculo.vehiculo_modelo,
                                    vehiculo.vehiculo_placa,
                                    vehiculo.vehiculo_serie,
                                    IFNULL((
                                    SELECT
                                        IF(proyectovehiculosactual.vehiculo_id, "checked", "" ) 
                                    FROM
                                        proyectovehiculosactual 
                                    WHERE
                                        proyectovehiculosactual.proyecto_id = proyectoproveedores.proyecto_id 
                                        AND proyectovehiculosactual.vehiculo_id = vehiculo.id 
                                        LIMIT 1 ), "" ) AS checked,
                                    IF(vehiculo.vehiculo_EstadoActivo = 1, "Si", "No") AS vehiculo_disponible 
                                FROM
                                    proyectoproveedores
                                    INNER JOIN proveedor ON proyectoproveedores.proveedor_id = proveedor.id
                                    INNER JOIN vehiculo ON proyectoproveedores.proveedor_id = vehiculo.proveedor_id 
                                WHERE
                                    proyectoproveedores.proyecto_id = ?
                                GROUP BY
                                    proyectoproveedores.proyecto_id,
                                    proveedor.id,
                                    proveedor.proveedor_NombreComercial,
                                    vehiculo.id,
                                    vehiculo.vehiculo_marca,
                                    vehiculo.vehiculo_modelo,
                                    vehiculo.vehiculo_serie,
                                    vehiculo.vehiculo_placa
                                ORDER BY
                                    proveedor.proveedor_NombreComercial ASC,
                                    vehiculo_disponible DESC', [$proyecto_id]);


            $numero_registro = 0;
            $listavehiculos = '';
            foreach ($vehiculos as $key => $value) { 
                // dibujar filas
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;
                $value->proveedor_NombreComercial;
                $value->vehiculo_disponible;

                if ($value->vehiculo_disponible === "Si") {
                    $value->checkbox = '<div class="switch" style="border: 0px #000 solid;">
                                            <label>
                                                <input type="checkbox" class="checkbox_proyectovehiculos" name="vehiculo[]" value="' . $value->proveedor_id . '-' . $value->id . '" ' . $value->checked . '>
                                                <span class="lever switch-col-light-blue" style="paddin: 0px; margin: 0px;"></span>
                                            </label>
                                        </div>';
                } else {
                    $value->checkbox = '<i class="fa fa-ban"></i>';
                }

                $value->vehiculo_marca = '<span >' . $value->vehiculo_marca . '</span>';
                $value->vehiculo_placa = '<span >' . $value->vehiculo_placa . '</span>';
                $value->vehiculo_modelo = '<span >' . $value->vehiculo_modelo . '</span>';
                $value->vehiculo_serie = '<span >' . $value->vehiculo_serie . '</span>';
            }


            // Respuesta
            $dato["data"] = $vehiculos;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            // Respuesta
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato["data"] = 0;
            return response()->json($dato);
        }
    }


    public function proyectovehiculoslistas($proyecto_id)
    {
        try {
            $vehiculoslistas = DB::select('SELECT
                                            proyectovehiculos.id,
                                            proyectovehiculos.proyecto_id,
                                            (
                                                SELECT
                                                    proyecto.proyecto_folio
                                                FROM
                                                    proyecto
                                                WHERE
                                                    proyecto.id = proyectovehiculos.proyecto_id
                                            ) AS proyecto_folio,
                                            (
                                                SELECT
                                                    proyectoordentrabajo.proyectoordentrabajo_folio
                                                FROM
                                                    proyectoordentrabajo
                                                WHERE
                                                    proyectoordentrabajo.proyecto_id = proyectovehiculos.proyecto_id
                                                ORDER BY
                                                    proyectoordentrabajo.proyectoordentrabajo_revision ASC
                                                LIMIT 1
                                            ) AS ordentrabajo_folio,
                                            proyectovehiculos.proyectovehiculo_revision,
                                            proyectovehiculos.proyectovehiculo_autorizado,
                                            proyectovehiculos.proyectovehiculo_autorizadonombre,
                                            proyectovehiculos.proyectovehiculo_autorizadofecha,
                                            proyectovehiculos.proyectovehiculo_cancelado,
                                            proyectovehiculos.proyectovehiculo_canceladonombre,
                                            proyectovehiculos.proyectovehiculo_canceladofecha,
                                            proyectovehiculos.proyectovehiculo_canceladoobservacion,
                                            proyectovehiculos.created_at,
                                            proyectovehiculos.updated_at 
                                        FROM
                                            proyectovehiculos
                                        WHERE
                                            proyectovehiculos.proyecto_id = ' . $proyecto_id . '
                                        ORDER BY
                                            proyectovehiculos.proyectovehiculo_revision ASC');

            // FORMATEAR FILAS
            $numero_registro = 0;
            foreach ($vehiculoslistas as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                // Numero de revision
                if (($value->proyectovehiculo_revision + 0) > 0) {
                    $value->revision = '<b>Lista de vehiculos Rev-' . $value->proyectovehiculo_revision . '</b><br>' . $value->created_at;
                } else {
                    $value->revision = '<b>Lista de vehiculos</b><br>' . $value->created_at;
                }

                // Diseño Autorizado
                if (($value->proyectovehiculo_autorizado + 0) == 1) {
                    $value->autorizado = $value->proyectovehiculo_autorizadonombre . '<br>' . $value->proyectovehiculo_autorizadofecha;
                } else {
                    $value->autorizado = '<b class="text-danger"><i class="fa fa-ban"></i> Pendiente</b>';
                }

                // Diseño estado
                if (($value->proyectovehiculo_cancelado + 0) == 1) {
                    $value->estado = '<b class="text-danger">Cancelado</b>';
                    $value->cancelado = $value->proyectovehiculo_canceladonombre . '<br>' . $value->proyectovehiculo_canceladofecha;
                } else {
                    $value->estado = '<b class="text-info">Vigente</b>';
                    $value->cancelado = 'N/A';
                }
            }


            // Respuesta
            $dato["data"] = $vehiculoslistas;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            // Respuesta
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato["data"] = 0;
            return response()->json($dato);
        }
    }


    public function proyectovehiculosgenerarlistaestado($proyecto_id)
    {
        try {
            // Proyecto
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            // Obtener folio
            $proyecto_folio = explode("-", $proyecto->proyecto_folio);
            $ot_folio = 'RIP-OTVH-' . $proyecto_folio[1] . '-' . $proyecto_folio[2];

            // Numero de revision
            $no_revision = 0;
            $no_revision_texto = '';
            $cancelado = 1;

            $vehiculoslistastotal = DB::select('SELECT
                                                    proyectovehiculos.id,
                                                    proyectovehiculos.proyecto_id,
                                                    proyectovehiculos.proyectovehiculo_revision,
                                                    proyectovehiculos.proyectovehiculo_autorizadofecha,
                                                    proyectovehiculos.proyectovehiculo_cancelado
                                                FROM
                                                    proyectovehiculos
                                                WHERE
                                                    proyectovehiculos.proyecto_id = ' . $proyecto_id . '
                                                ORDER BY
                                                    proyectovehiculos.proyectovehiculo_revision DESC
                                                LIMIT 1');

            if (count($vehiculoslistastotal) > 0) {
                $no_revision = ($vehiculoslistastotal[0]->proyectovehiculo_revision + 1);
                $no_revision_texto = ' Rev-' . $no_revision;

                $cancelado = ($vehiculoslistastotal[0]->proyectovehiculo_cancelado + 0);
            }

            // Respuesta
            $dato["lista_folioot"] = $ot_folio;
            $dato["lista_revision"] = $no_revision;
            $dato["no_revision_texto"] = $no_revision_texto;
            $dato["lista_cancelado"] = $cancelado;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["lista_folioot"] = 0;
            $dato["lista_revision"] = 0;
            $dato["no_revision_texto"] = 0;
            $dato["lista_cancelado"] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    public function proyectovehiculosconsultaractual($proyecto_id)
    {
        try {
            // Proyecto
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            // Obtener folio
            $proyecto_folio = explode("-", $proyecto->proyecto_folio);
            $ot_folio = 'RIP-OTVH-' . $proyecto_folio[1] . '-' . $proyecto_folio[2];
            $proyecto->folio_ot = $ot_folio;

            // Numero de revision
            $no_revision = 0;
            $no_revision_texto = '';
            $vehiculoslistastotal = DB::select('SELECT
                                                    proyectovehiculos.id,
                                                    proyectovehiculos.proyecto_id,
                                                    proyectovehiculos.proyectovehiculo_revision,
                                                    proyectovehiculos.proyectovehiculo_autorizado,
                                                    proyectovehiculos.proyectovehiculo_cancelado
                                                FROM
                                                    proyectovehiculos
                                                WHERE
                                                    proyectovehiculos.proyecto_id = ' . $proyecto_id . '
                                                ORDER BY
                                                    proyectovehiculos.proyectovehiculo_revision DESC
                                                LIMIT 1');

            if (count($vehiculoslistastotal) > 0) {
                $no_revision = ($vehiculoslistastotal[0]->proyectovehiculo_revision + 1);
                $no_revision_texto = ' Rev-' . $no_revision;
            }

            // Datos de la lista nueva de vehiculos
            $vehiculoslista = array(
                'proyecto_id' => $proyecto_id,
                'proyectovehiculo_revision' => $no_revision,
                'proyectovehiculo_autorizado' => 0,
                'proyectovehiculo_autorizadonombre' => NULL,
                'proyectovehiculo_autorizadofecha' => NULL,
                'proyectovehiculo_cancelado' => 0,
                'proyectovehiculo_canceladonombre' => NULL,
                'proyectovehiculo_canceladofecha' => NULL,
                'proyectovehiculo_canceladoobservacion' => NULL,
                'created_at' => date('Y-m-d H:m:s'),
                'updated_at' => date('Y-m-d H:m:s')
            );

            // Consulta vehiculos historial
            DB::statement("SET lc_time_names = 'es_MX';");
            $vehiculos = DB::select('SELECT
                                        proyectovehiculosactual.proyecto_id,
                                        proyectovehiculosactual.proveedor_id,
                                        proveedor.proveedor_RazonSocial,
                                        proveedor.proveedor_NombreComercial,
                                        proyectovehiculosactual.vehiculo_id,
                                        vehiculo.vehiculo_marca,
                                        vehiculo.vehiculo_modelo,
                                        vehiculo.vehiculo_placa,
                                        vehiculo.vehiculo_serie
                                    FROM
                                        proyectovehiculosactual
                                        LEFT JOIN proveedor ON proyectovehiculosactual.proveedor_id = proveedor.id
                                        LEFT JOIN vehiculo ON proyectovehiculosactual.vehiculo_id = vehiculo.id
                                    WHERE
                                        proyectovehiculosactual.proyecto_id = ?
                                    ORDER BY
                                        proveedor.proveedor_NombreComercial ASC', [$proyecto_id]);


            //===========================================



            return \PDF::loadView('reportes.proyecto.reporteproyectolistavehiculos', compact('proyecto', 'vehiculoslista', 'vehiculos'))->stream($ot_folio . ' Lista de vehiculos' . $no_revision_texto . '.pdf');
            // return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            // $dato['opciones'] = $opciones_select;
            return response()->json($dato);
        }
    }


    public function proyectovehiculosconsultarhistorial($proyecto_id, $proyectovehiculos_revision)
    {
        try {
            // Proyecto
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            // Obtener folio
            $proyecto_folio = explode("-", $proyecto->proyecto_folio);
            $ot_folio = 'RIP-OTVH-' . $proyecto_folio[1] . '-' . $proyecto_folio[2];
            $proyecto->folio_ot = $ot_folio;

            // Lista de vehiculos historial
            $datoslista = DB::select('SELECT
                                            proyectovehiculos.id,
                                            proyectovehiculos.proyecto_id,
                                            proyectovehiculos.proyectovehiculo_revision,
                                            proyectovehiculos.proyectovehiculo_autorizado,
                                            proyectovehiculos.proyectovehiculo_autorizadonombre,
                                            proyectovehiculos.proyectovehiculo_autorizadofecha,
                                            proyectovehiculos.proyectovehiculo_cancelado,
                                            proyectovehiculos.proyectovehiculo_canceladonombre,
                                            proyectovehiculos.proyectovehiculo_canceladofecha,
                                            proyectovehiculos.proyectovehiculo_canceladoobservacion,
                                            proyectovehiculos.created_at,
                                            proyectovehiculos.updated_at 
                                        FROM
                                            proyectovehiculos 
                                        WHERE
                                            proyectovehiculos.proyecto_id = ' . $proyecto_id . ' 
                                            AND proyectovehiculos.proyectovehiculo_revision = ' . $proyectovehiculos_revision . '
                                        LIMIT 1');

            // Datos de la lista nueva de vehiculos
            $vehiculoslista = array(
                'proyecto_id' => $proyecto_id,
                'proyectovehiculo_revision' => $datoslista[0]->proyectovehiculo_revision,
                'proyectovehiculo_autorizado' => $datoslista[0]->proyectovehiculo_autorizado,
                'proyectovehiculo_autorizadonombre' => $datoslista[0]->proyectovehiculo_autorizadonombre,
                'proyectovehiculo_autorizadofecha' => $datoslista[0]->proyectovehiculo_autorizadofecha,
                'proyectovehiculo_cancelado' => $datoslista[0]->proyectovehiculo_cancelado,
                'proyectovehiculo_canceladonombre' => $datoslista[0]->proyectovehiculo_canceladonombre,
                'proyectovehiculo_canceladofecha' => $datoslista[0]->proyectovehiculo_canceladofecha,
                'proyectovehiculo_canceladoobservacion' => $datoslista[0]->proyectovehiculo_canceladoobservacion,
                'created_at' => $datoslista[0]->created_at,
                'updated_at' => $datoslista[0]->updated_at
            );

            // Numero de revision
            $documento_nombre = '';
            if (($proyectovehiculos_revision + 0) > 0) {
                $documento_nombre = 'Lista de vehiculos rev-' . $proyectovehiculos_revision;
            } else {
                $documento_nombre = 'Lista de vehiculos';
            }

            // Consulta vehiculos historial
            DB::statement("SET lc_time_names = 'es_MX';");
            $vehiculos = DB::select('SELECT
                                        proyectovehiculoshistorial.proyecto_id,
                                        proyectovehiculoshistorial.proyectovehiculo_revision,
                                        proyectovehiculoshistorial.proveedor_id,
                                        proyectovehiculoshistorial.vehiculo_id,
                                        proveedor.proveedor_RazonSocial,
                                        proveedor.proveedor_NombreComercial,
                                        vehiculo.vehiculo_marca,
                                        vehiculo.vehiculo_modelo,
                                        vehiculo.vehiculo_placa,
                                        vehiculo.vehiculo_serie
                                    FROM
                                        proyectovehiculoshistorial
                                        LEFT JOIN proveedor ON proyectovehiculoshistorial.proveedor_id = proveedor.id
                                        LEFT JOIN vehiculo ON proyectovehiculoshistorial.vehiculo_id = vehiculo.id
                                    WHERE
                                        proyectovehiculoshistorial.proyecto_id = ' . $proyecto_id . '
                                        AND proyectovehiculoshistorial.proyectovehiculo_revision = ' . $proyectovehiculos_revision . '
                                    ORDER BY
                                        proveedor.proveedor_NombreComercial ASC');


            //===========================================


            return \PDF::loadView('reportes.proyecto.reporteproyectolistavehiculos', compact('proyecto', 'vehiculoslista', 'vehiculos'))->stream($ot_folio . ' ' . $documento_nombre . '.pdf');

            // return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            // $dato['opciones'] = $opciones_select;
            return response()->json($dato);
        }
    }


    public function store(Request $request)
    {
        try {
            if (($request->opcion + 0) == 0) // ASIGNAR VEHICULOS AL PROYECTO
            {
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto'])) {
                    if ($request->vehiculo) {
                        // Eliminar lista de vehiculos actuales
                        $eliminar_vehiculos = proyectovehiculosactualModel::where('proyecto_id',  $request->proyecto_id)->delete();

                        foreach ($request->vehiculo as $key => $value) {
                            // Separar datos [proveedor_id - vehiculo_id]
                            $valor = explode("-", $value);

                            $guardar_vehiculos = proyectovehiculosactualModel::create([
                                'proyecto_id' => $request->proyecto_id,
                                'proveedor_id' => $valor[0],
                                'vehiculo_id' => $valor[1]
                            ]);
                        }
                    }

                    // mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                } else {
                    // Disponibilidad para guardar lista
                    $disponibilidadvehiculos = 1;
                    $vehiculoslistaestado = DB::select('SELECT
                                                            (
                                                                CASE
                                                                    WHEN proyectovehiculo_cancelado = 1 THEN 1
                                                                    WHEN proyectovehiculo_autorizado = 1 THEN 0
                                                                    ELSE 1
                                                                END
                                                            ) AS disponibilidadvehiculos
                                                        FROM
                                                            proyectovehiculos
                                                        WHERE
                                                            proyectovehiculos.proyecto_id = ' . $request->proyecto_id . '
                                                        ORDER BY
                                                            proyectovehiculos.proyectovehiculo_revision DESC
                                                        LIMIT 1');

                    if (count($vehiculoslistaestado) > 0) {
                        $disponibilidadvehiculos = ($vehiculoslistaestado[0]->disponibilidadvehiculos + 0);
                    }

                    // Acccion segun estado
                    if ($disponibilidadvehiculos == 1) {
                        if ($request->vehiculo) {
                            // Eliminar lista de vehiculos actuales
                            $eliminar_vehiculos = proyectovehiculosactualModel::where('proyecto_id',  $request->proyecto_id)->where('proveedor_id',  auth()->user()->empleado_id)->delete();

                            foreach ($request->vehiculo as $key => $value) {
                                // Separar datos [proveedor_id - vehiculo_id]
                                $valor = explode("-", $value);

                                $guardar_vehiculos = proyectovehiculosactualModel::create([
                                    'proyecto_id' => $request->proyecto_id,
                                    'proveedor_id' => $valor[0],
                                    'vehiculo_id' => $valor[1]
                                ]);
                            }
                        }

                        // // Guardar observacion
                        // if ($request->proyectovehiculosobservacion) {
                        //     // Eliminar historial
                        //     $eliminar_observacion = proyectovehiculosobservacionModel::where('proyecto_id', $request->proyecto_id)
                        //         ->where('proveedor_id', auth()->user()->empleado_id)
                        //         ->delete();

                        //     // Guardar
                        //     $guardar_observacion = proyectovehiculosobservacionModel::create([
                        //         'proyecto_id' => $request->proyecto_id,
                        //         'proveedor_id' => auth()->user()->empleado_id,
                        //         'proyectovehiculosobservacion' => $request->proyectovehiculosobservacion
                        //     ]);
                        // }

                        // mensaje
                        $dato["msj"] = 'Datos guardados correctamente';
                    } else {
                        // mensaje
                        $dato["msj"] = 'No se realizó ningun cambio, la lista ha sido bloqueada por el Administrador';
                    }

                    // Respuesta
                    $dato["disponibilidadvehiculos"] = $disponibilidadvehiculos;
                }
            } else // LISTAS DE vehiculoS CREAR / EDITAR
            {
                // Datos Proyecto y ordentrabajo
                $folios = DB::select('SELECT
                                            proyecto.id,
                                            proyecto.proyecto_folio,
                                            IFNULL((
                                                SELECT
                                                    proyectoordentrabajo.proyectoordentrabajo_folio
                                                FROM
                                                    proyectoordentrabajo
                                                WHERE
                                                    proyectoordentrabajo.proyecto_id = proyecto.id
                                                ORDER BY
                                                    proyectoordentrabajo.proyectoordentrabajo_revision ASC
                                                LIMIT 1
                                            ), "OT-PENDIENTE") AS ordentrabajo_folio 
                                        FROM
                                            proyecto
                                        WHERE
                                            proyecto.id = ?'  , [intval($request->proyecto_id)]);


                if (($request->vehiculoslista_id) == 0) // NUEVA LISTA
                {
                    // Numero de revision
                    $no_revision = 0;
                    $vehiculoslistastotal = DB::select('SELECT
                                                            proyectovehiculos.id,
                                                            proyectovehiculos.proyecto_id,
                                                            proyectovehiculos.proyectovehiculo_revision,
                                                            proyectovehiculos.proyectovehiculo_autorizado,
                                                            proyectovehiculos.proyectovehiculo_cancelado
                                                        FROM
                                                            proyectovehiculos
                                                        WHERE
                                                            proyectovehiculos.proyecto_id = ?
                                                        ORDER BY
                                                            proyectovehiculos.proyectovehiculo_revision DESC
                                                        LIMIT 1', [$request->proyecto_id]);

                    if (count($vehiculoslistastotal) > 0) {
                        $no_revision = ($vehiculoslistastotal[0]->proyectovehiculo_revision + 1);
                    }

                    // Valida si viene AUTORIZADO
                    $autorizado = 0;
                    $autorizadonombre = NULL;
                    $autorizadofecha = NULL;
                    if ($request->checkbox_autorizale != NULL) {
                        $autorizado = 1;
                        $autorizadonombre = auth()->user()->empleado->empleado_nombre . " " . auth()->user()->empleado->empleado_apellidopaterno . " " . auth()->user()->empleado->empleado_apellidomaterno;
                        $autorizadofecha = date('Y-m-d H:i:s');
                    }

                    // Valida si viene CANCELADO
                    $cancelado = 0;
                    $canceladonombre = NULL;
                    $canceladofecha = NULL;
                    $canceladoobservacion = NULL;
                    if ($request->checkbox_cancelale != NULL) {
                        $cancelado = 1;
                        $canceladonombre = auth()->user()->empleado->empleado_nombre . " " . auth()->user()->empleado->empleado_apellidopaterno . " " . auth()->user()->empleado->empleado_apellidomaterno;
                        $canceladofecha = date('Y-m-d H:i:s');
                        $canceladoobservacion = $request->proyectovehiculo_canceladoobservacion;
                    }

                    // AUTO_INCREMENT
                    DB::statement('ALTER TABLE proyectovehiculos AUTO_INCREMENT = 1;');

                    // Crear lista
                    $proyectovehiculolista = proyectovehiculosModel::create([
                        'proyecto_id' => $request->proyecto_id,
                        'proyectovehiculo_revision' => $no_revision,
                        'proyectovehiculo_autorizado' => $autorizado,
                        'proyectovehiculo_autorizadonombre' => $autorizadonombre,
                        'proyectovehiculo_autorizadofecha' => $autorizadofecha,
                        'proyectovehiculo_cancelado' => $cancelado,
                        'proyectovehiculo_canceladonombre' => $canceladonombre,
                        'proyectovehiculo_canceladofecha' => $canceladofecha,
                        'proyectovehiculo_canceladoobservacion' => $canceladoobservacion
                    ]);

                    // Consultar lista de vehiculos actual
                    $vehiculosactual = DB::select('SELECT
                                                    proyectovehiculosactual.proyecto_id,
                                                    proyectovehiculosactual.proveedor_id,
                                                    proyectovehiculosactual.vehiculo_id 
                                                FROM
                                                    proyectovehiculosactual
                                                WHERE
                                                    proyectovehiculosactual.proyecto_id = ?' , [$request->proyecto_id]);

                    // AUTO_INCREMENT
                    DB::statement('ALTER TABLE proyectovehiculoshistorial AUTO_INCREMENT = 1;');

                    // Guardar lista de vehiculos actual en historial
                    foreach ($vehiculosactual as $key => $value) {
                        $vehiculoshistorial = proyectovehiculoshistorialModel::create([
                            'proyecto_id' => $value->proyecto_id,
                            'proveedor_id' => $value->proveedor_id,
                            'proyectovehiculo_revision' => $no_revision,
                            'vehiculo_id' => $value->vehiculo_id
                        ]);
                    }

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                } else // EDITAR LISTA
                {
                    // Obtener lista de vehiculos
                    $proyectovehiculolista = proyectovehiculosModel::findOrFail($request->vehiculoslista_id);

                    // Valida si viene AUTORIZADO
                    $autorizado = 0;
                    $autorizadonombre = NULL;
                    $autorizadofecha = NULL;
                    if (($proyectovehiculolista->proyectovehiculo_autorizado + 0) == 1) {
                        // if ($request->checkbox_autorizale != NULL)
                        // {
                        $autorizado = 1;
                        $autorizadonombre = $proyectovehiculolista->proyectovehiculo_autorizadonombre;
                        $autorizadofecha = $proyectovehiculolista->proyectovehiculo_autorizadofecha;
                        // }
                    } else {
                        if ($request->checkbox_autorizale != NULL) {
                            $autorizado = 1;
                            $autorizadonombre = auth()->user()->empleado->empleado_nombre . " " . auth()->user()->empleado->empleado_apellidopaterno . " " . auth()->user()->empleado->empleado_apellidomaterno;
                            $autorizadofecha = date('Y-m-d H:i:s');
                        }
                    }

                    // Valida si viene CANCELADO
                    $cancelado = 0;
                    $canceladonombre = NULL;
                    $canceladofecha = NULL;
                    $canceladoobservacion = NULL;
                    if (($proyectovehiculolista->proyectovehiculo_cancelado + 0) == 1) {
                        if ($request->checkbox_cancelale != NULL) {
                            $cancelado = 1;
                            $canceladonombre = $proyectovehiculolista->proyectovehiculo_canceladonombre;
                            $canceladofecha = $proyectovehiculolista->proyectovehiculo_canceladofecha;
                            $canceladoobservacion = $proyectovehiculolista->proyectovehiculo_canceladoobservacion;
                        }
                    } else {
                        if ($request->checkbox_cancelale != NULL) {
                            $cancelado = 1;
                            $canceladonombre = auth()->user()->empleado->empleado_nombre . " " . auth()->user()->empleado->empleado_apellidopaterno . " " . auth()->user()->empleado->empleado_apellidomaterno;
                            $canceladofecha = date('Y-m-d H:i:s');
                            $canceladoobservacion = $request->proyectovehiculo_canceladoobservacion;
                        }
                    }

                    // Modificar
                    $proyectovehiculolista->update([
                        // 'proyecto_id' => $request->XXXXXXXXX
                        // , 'proyectovehiculo_revision' => $request->XXXXXXXXX
                        'proyectovehiculo_autorizado' => $autorizado,
                        'proyectovehiculo_autorizadonombre' => $autorizadonombre,
                        'proyectovehiculo_autorizadofecha' => $autorizadofecha,
                        'proyectovehiculo_cancelado' => $cancelado,
                        'proyectovehiculo_canceladonombre' => $canceladonombre,
                        'proyectovehiculo_canceladofecha' => $canceladofecha,
                        'proyectovehiculo_canceladoobservacion' => $canceladoobservacion
                    ]);

                    // Mensaje
                    $dato["msj"] = 'Datos actualizados correctamente';
                }

                // respuesta
                $dato["folios"] = $folios;
                $dato["proyectovehiculolista"] = $proyectovehiculolista;
            }

            // respuesta
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["folios"] = 0;
            $dato["proyectovehiculolista"] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


}

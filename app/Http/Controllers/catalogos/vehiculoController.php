<?php

namespace App\Http\Controllers\catalogos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\catalogos\ProveedorModel;
use App\modelos\catalogos\VehiculosModel;
use App\modelos\catalogos\VehiculosDocumentosModel;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTime;
use DB;
use Exception;

use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;
//Recursos para abrir el Excel
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class vehiculoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo,Coordinador');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proveedor_id
     * @return \Illuminate\Http\Response
     */
    public function tablaproveedorvehiculo($proveedor_id)
    {
        try {
            // Proveedor
            $proveedor = ProveedorModel::findOrFail($proveedor_id);

            $equipos = VehiculosModel::where('proveedor_id', $proveedor_id)
                ->where('vehiculo_Eliminado', 0)
                ->OrderBy('id', 'ASC')
                ->get();

            $numero_registro = 0;

            // recorrer la lista de equipos para formatear fechas
            foreach ($equipos  as $key => $value) {
                $numero_registro += 1;

                if ($value->vehiculo_EstadoActivo == 1) {
                    $value->EstadoActivo_texto = "Activo";
                } else {
                    $value->EstadoActivo_texto = '<p class="text-danger"><b>Inactivo</b></p>';
                }
                // Validar si el equipo esta asociado con un AGENTE
                // if (($value->acreditacionalcance_id + 0) > 0)
                // {
                //     $gente = $value->acreditacionalcance->prueba->catPrueba_Nombre;
                // }
                // else
                // {
                //     $gente = 'N/A';
                // }

                // Validar si el equipo cuenta con fecha de vigencia

                // determinar los dias faltantes para vigencia
                $datetime1 = date_create(date('Y-m-d'));
                $datetime2 = date_create($value->equipo_VigenciaCalibracion);
                $interval = date_diff($datetime1, $datetime2);

                // alertas en los dias de la vigencia
                switch (($interval->format('%R%a') + 0)) {
                    case (($interval->format('%R%a') + 0) <= 30):
                        $value->numero_registro = $numero_registro;
                        // $value->agente = '<b class="text-danger">'.$gente.'</b>';
                        $value->marca = '<b class="text-danger">' . $value->vehiculo_marca . '</b>';
                        $value->modelo = '<b class="text-danger">' . $value->vehiculo_modelo . '</b>';
                        $value->serie = '<b class="text-danger">' . $value->vehiculo_serie . '</b>';
                        $value->placa = '<b class="text-danger">' . $value->vehiculo_placa . '</b>';

                        break;
                    case (($interval->format('%R%a') + 0) <= 90):
                        $value->numero_registro = $numero_registro;
                        // $value->agente = '<b class="text-warning">'.$gente.'</b>';
                        $value->marca = '<b class="text-danger">' . $value->vehiculo_marca . '</b>';
                        $value->modelo = '<b class="text-danger">' . $value->vehiculo_modelo . '</b>';
                        $value->serie = '<b class="text-danger">' . $value->vehiculo_serie . '</b>';
                        $value->placa = '<b class="text-danger">' . $value->vehiculo_placa . '</b>';
                        break;
                    default:
                        $value->numero_registro = $numero_registro;
                        // $value->agente = $gente;
                        $value->marca = $value->vehiculo_marca;
                        $value->modelo = $value->vehiculo_modelo;
                        $value->serie = $value->vehiculo_serie;
                        $value->placa = $value->vehiculo_placa;

                        break;
                }

                // Validar si contiene archivo PDF
                // if ($value->equipo_CertificadoPDF) {
                //     $value->certificado_pdf = '<button type="button" class="btn btn-info btn-circle certificadopdf"><i class="fa fa-file-pdf-o"></i></button>';
                // } else {
                //     $value->certificado_pdf = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                // }


                // // Validar si contiene carta PDF
                // if ($value->equipo_cartaPDF) {
                //     $value->carta_pdf = '<button type="button" class="btn btn-success btn-circle cartapdf"><i class="fa fa-file-pdf-o"></i></button>';
                // } else {
                //     $value->carta_pdf = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                // }


                // Formato fecha
                // $value->equipo_FechaCalibracion = Carbon::createFromFormat('Y-m-d', $value->equipo_FechaCalibracion)->format('d-m-Y');
                // $value->equipo_VigenciaCalibracion = Carbon::createFromFormat('Y-m-d', $value->equipo_VigenciaCalibracion)->format('d-m-Y');

                // Valida perfil
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador'])) {
                    $value->perfil = 1;
                } else {
                    $value->perfil = 0;
                }

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Almacén']) && ($proveedor->proveedor_Bloqueado + 0) == 0) {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                } else {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-success btn-circle"><i class="fa fa-eye"></i></button>';
                }
            }

            // devolver datos
            $listado['data'] = $equipos;
            return response()->json($listado);
        } catch (exception $e) {
            $listado['data'] = 0;
            return response()->json($listado);
        }
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $proveedor_id
     * @param  int  $acreditacion_id
     * @param  int  $prueba_id
     * @return \Illuminate\Http\Response
     */


    public function tablavehiculodocumento($vehiculo_id)
    {
        try {
            // Equipo
            $equipo = VehiculosModel::with(['proveedor'])->findOrFail($vehiculo_id);

            //Consulta tabla documentos
            $documentos = VehiculosDocumentosModel::where('VEHICULO_ID', $vehiculo_id)
                ->where('ACTIVO', 1)
                ->get();

            $numero_registro = 0;
            foreach ($documentos  as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                // Valida perfil
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador'])) {
                    $value->perfil = 1;
                } else {
                    $value->perfil = 0;
                }

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Almacén']) && ($equipo->proveedor->proveedor_Bloqueado + 0) == 0) {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                } else {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-success btn-circle"><i class="fa fa-eye"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                }
            }

            $listado['data'] = $documentos;
            return response()->json($listado);
        } catch (exception $e) {
            $listado['data'] = 0;
            return response()->json($listado);
        }
    }


    public function mostrarpdf($documento_id)
    {
        $documento = VehiculosDocumentosModel::findOrFail($documento_id);
        return Storage::response($documento->RUTA_DOCUMENTO);
    }



    public function mostrarFotoVehiculo($vehiculo_id)
    {
        $foto = VehiculosModel::findOrFail($vehiculo_id);
        return Storage::response($foto->vehiculo_imagen);
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

            if ($request['api'] == 1) { #GUARDAMOS LA INFORMACION DEL EQUIPO

                if ($request['vehiculo_Eliminado'] == 0) //SI ES 0 VA A GUARDAR/EDITAR SI ES 1 VA A ELIMINAR
                {
                    if ($request['vehiculo_id'] == 0) //nuevo
                    {
                        // AUTO_INCREMENT
                        DB::statement('ALTER TABLE vehiculo AUTO_INCREMENT=1');
                        $equipo = VehiculosModel::create($request->all());

                        if ($request->file('foto_vehiculo')) {


                            $extension = $request->file('foto_vehiculo')->getClientOriginalExtension();

                            $request['vehiculo_imagen'] = $request->file('foto_vehiculo')->storeAs('proveedores/' . $request['proveedor_id'] . '/Vehiculos/' . $equipo->id, $equipo->id . '.' . $extension);

                            $equipo->update($request->all());
                        }



                        return response()->json($equipo);
                    } else //editar
                    {
                        $equipo = VehiculosModel::findOrFail($request['vehiculo_id']);

                        if ($request->file('foto_vehiculo')) {

                            if (Storage::exists($equipo->vehiculo_imagen)) {
                                Storage::delete($equipo->vehiculo_imagen);
                            }

                            $extension = $request->file('foto_vehiculo')->getClientOriginalExtension();
                            $request['vehiculo_imagen'] = $request->file('foto_vehiculo')->storeAs('proveedores/' . $request['proveedor_id'] . '/Vehiculos/' . $equipo->id, $equipo->id . '.' . $extension);
                        }




                        $equipo->update($request->all());
                        return response()->json($equipo);
                    }
                } else //eliminar
                {
                    $equipo = VehiculosModel::findOrFail($request['vehiculo_id']);
                    $equipo->vehiculo_Eliminado = 1;
                    $equipo->save();
                    return response()->json($equipo);
                }
            } else if ($request['api'] == 2) { #GUARDAMOS LOS DOCUMENTOS DEL EQUIPO

                if ($request['ACTIVO'] == 1) //SI ES 1 ES PORQUE ESTA ACTIVO SI ES 0 ES PORQUE LO VAN A DESACTIVAR
                {

                    if ($request['ID_VEHICULO_DOCUMENTO'] == 0) //nuevo
                    {
                        // AUTO_INCREMENT
                        DB::statement('ALTER TABLE vehiculos_documentos AUTO_INCREMENT=1');
                        $request['FECHA_CARGA'] = date("Y-m-d");

                        $equipoDocumento = VehiculosDocumentosModel::create($request->all());

                        if ($request->file('VEHICULO_PDF')) {

                            $extension = $request->file('VEHICULO_PDF')->getClientOriginalExtension();
                            $request['RUTA_DOCUMENTO'] = $request->file('VEHICULO_PDF')->storeAs('proveedores/' . $request['proveedor_id'] . '/Vehiculos/' . $request['VEHICULO_ID'] . '/documentos', $equipoDocumento->ID_VEHICULO_DOCUMENTO . '.' . $extension);

                            $equipoDocumento->update($request->all());
                        }

                        return response()->json($equipoDocumento);
                    } else //editar
                    {
                        $equipoDocumento = VehiculosDocumentosModel::findOrFail($request['ID_VEHICULO_DOCUMENTO']);

                        if ($request->file('VEHICULO_PDF')) {
                            $extension = $request->file('VEHICULO_PDF')->getClientOriginalExtension();
                            $request['RUTA_DOCUMENTO'] = $request->file('VEHICULO_PDF')->storeAs('proveedores/' . $request['proveedor_id'] . '/Vehiculos/' . $request['VEHICULO_ID'] . '/documentos', $equipoDocumento->ID_VEHICULO_DOCUMENTO . '.' . $extension);
                        }

                        $equipoDocumento->update($request->all());
                        return response()->json($equipoDocumento);
                    }
                } else //eliminar
                {
                    $equipoDocumento = VehiculosDocumentosModel::findOrFail($request['ID_VEHICULO_DOCUMENTO']);
                    $equipoDocumento->update($request->all());
                    return response()->json($equipoDocumento);
                }
            }
        } catch (Exception $e) {

            return response()->json('Error al guardar');
        }
    }
}

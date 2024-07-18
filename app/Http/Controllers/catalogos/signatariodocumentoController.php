<?php

namespace App\Http\Controllers\catalogos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\catalogos\SignatarioModel;
use App\modelos\catalogos\SignatariodocumentoModel;
use App\modelos\catalogos\SignatarioExperienciaModel;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTime;
use DB;

class signatariodocumentoController extends Controller
{




    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo');
    }







    /**
     * Display the specified resource.
     *
     * @param  int  $signatario_id
     * @return \Illuminate\Http\Response
     */
    public function tablasignatariodocumento($signatario_id)
    {
        try {
            // Signatario
            $signatario = SignatarioModel::with(['proveedor'])->findOrFail($signatario_id);

            //Consulta tabla documentos
            $documentos = SignatariodocumentoModel::where('signatario_id', $signatario_id)
                ->where('signatarioDocumento_Eliminado', 0)
                ->get();

            $numero_registro = 0;
            foreach ($documentos  as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                // Valida perfil
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'CoordinadorHI'])) {
                    $value->perfil = 1;
                } else {
                    $value->perfil = 0;
                }

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras','Almacén']) && ($signatario->proveedor->proveedor_Bloqueado + 0) == 0) {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                } else {
                    $value->accion_activa = 0;
                    $value->boton_editar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
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


    public function tablasignatarioexperiencia($signatario_id, $experiencia)
    {
        try {
            // Signatario
            $signatario = SignatarioModel::with(['proveedor'])->findOrFail($signatario_id);

            //Consulta tabla Experiencia
            $documentos = SignatarioExperienciaModel::where('SIGNATARIO_ID', $signatario_id)
                ->where('ELIMINADO', 0)
                ->get();


            // Inicializar variables para la fecha mínima y máxima
            $fechaInicioMinima = null;
            $fechaFinMaxima = null;

            $numero_registro = 0;

            foreach ($documentos  as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;
                
                // Valida perfil
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'CoordinadorHI'])) {
                    $value->perfil = 1;
                } else {
                    $value->perfil = 0;
                }

                // Actualizar la fecha mínima
                if ($fechaInicioMinima === null || $value->FECHA_INICIO < $fechaInicioMinima) {
                    $fechaInicioMinima = $value->FECHA_INICIO;
                }
                // Actualizar la fecha máxima
                if ($fechaFinMaxima === null || $value->FECHA_FIN > $fechaFinMaxima) {
                    $fechaFinMaxima = $value->FECHA_FIN;
                }

             


                $value->TIEMPO = $value->FECHA_INICIO . '<br>' . $value->FECHA_FIN;

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras','Almacén']) && ($signatario->proveedor->proveedor_Bloqueado + 0) == 0) {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                } else {
                    $value->accion_activa = 0;
                    $value->boton_editar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                }

                
            }

            // Calcular la diferencia entre las fechas mínima y máxima
            $diferenciaFechas = null;
            if ($fechaInicioMinima !== null && $fechaFinMaxima !== null) {
                $fechaInicio = new DateTime($fechaInicioMinima);
                $fechaFin = new DateTime($fechaFinMaxima);
                $diferenciaFechas = $fechaInicio->diff($fechaFin)->format('<h3> Tiempo de experiencia general: %y años, %m meses, %d días | </h3> ');

                // Calcula la diferencia entre las fechas
                $diferencia = $fechaInicio->diff($fechaFin);

                // Convertir la diferencia a días totales
                $dias = $diferencia->days;
                $anios = $diferencia->y;
                $meses = $diferencia->y * 12 + $diferencia->m;

                
                $complemento = " <h3 style='color:red'> Equivalente a: $anios años / $meses meses / $dias días </h3>";
                $response_date = "<div style=' display: inline-block'>$diferenciaFechas</div><div style='display: inline-block;'> $complemento</div>"; ;
            }


            if($experiencia == 0){

                $listado['data'] = $documentos;
                return response()->json($listado);

            }
            else{

                $listado['data'] = $response_date;
                return response()->json($listado);
            }

            
        } catch (exception $e) {
            $listado['data'] = 0;
            return response()->json($listado);
        }
    }






    /**
     * Display the specified resource.
     *
     * @param  int  $documento_id
     * @return \Illuminate\Http\Response
     */
    public function mostrarpdf($documento_id)
    {
        $documento = SignatariodocumentoModel::findOrFail($documento_id);
        return Storage::response($documento->signatarioDocumento_SoportePDF);
    }

    public function mostrarpdfExperiencia($documento_id)
    {
        $documento = SignatarioExperienciaModel::findOrFail($documento_id);
        return Storage::response($documento->EXPERIENCIA_PDF);
    }

    public function store(Request $request)
    {
        //
        try {
            if ($request['api']  == 1){ //GUARDAR DOCUMENTOS
                if ($request['signatarioDocumento_Eliminado'] == 0) //valida eliminacion
                {

                    if ($request['signatario_doc_id'] == 0) //nuevo
                    {
                        // AUTO_INCREMENT
                        DB::statement('ALTER TABLE signatariodocumento AUTO_INCREMENT=1');
                        $signatariodocumento = SignatariodocumentoModel::create($request->all());

                        if ($request->file('signatariodocumentopdf')) {
                            $extension = $request->file('signatariodocumentopdf')->getClientOriginalExtension();
                            $request['signatarioDocumento_SoportePDF'] = $request->file('signatariodocumentopdf')->storeAs('proveedores/' . $request['proveedor_id'] . '/signatarios/' . $request['signatario_id'] . '/documentos', $signatariodocumento->id . '.' . $extension);

                            $signatariodocumento->update($request->all());
                        }

                        return response()->json($signatariodocumento);
                    } else //editar
                    {
                        $signatariodocumento = SignatariodocumentoModel::findOrFail($request['signatario_doc_id']);

                        if ($request->file('signatariodocumentopdf')) {
                            $extension = $request->file('signatariodocumentopdf')->getClientOriginalExtension();
                            $request['signatarioDocumento_SoportePDF'] = $request->file('signatariodocumentopdf')->storeAs('proveedores/' . $request['proveedor_id'] . '/signatarios/' . $request['signatario_id'] . '/documentos', $signatariodocumento->id . '.' . $extension);
                        }

                        $signatariodocumento->update($request->all());
                        return response()->json($signatariodocumento);
                    }
                } else //eliminar
                {
                    $signatariodocumento = SignatariodocumentoModel::findOrFail($request['signatario_doc_id']);
                    $signatariodocumento->update($request->all());
                    return response()->json($signatariodocumento);
                }
            
            }else { //GUARDAR LA INFORMACION DE LA EXPERIENCIA

                if ($request['ELIMINADO'] == 0) //valida eliminacion
                {

                    if ($request['ID_EXPERIENCIA'] == 0) //nuevo
                    {
                        // AUTO_INCREMENT
                        DB::statement('ALTER TABLE signatarios_experiencias AUTO_INCREMENT=1');
                        $signatarioexperiencia = SignatarioExperienciaModel::create($request->all());

                        if ($request->file('PDF')) {
                            $extension = $request->file('PDF')->getClientOriginalExtension();
                            $request['EXPERIENCIA_PDF'] = $request->file('PDF')->storeAs('proveedores/' . $request['PROVEEDOR_ID'] . '/signatarios/' . $request['SIGNATARIO_ID'] . '/documentos/experiencia', $signatarioexperiencia->ID_EXPERIENCIA . '.' . $extension);

                            $signatarioexperiencia->update($request->all());
                        }

                        return response()->json($signatarioexperiencia);

                    } else //editar
                    {
                        $signatarioexperiencia = SignatarioExperienciaModel::findOrFail($request['ID_EXPERIENCIA']);

                        if ($request->file('PDF')) {
                            $extension = $request->file('PDF')->getClientOriginalExtension();
                            $request['EXPERIENCIA_PDF'] = $request->file('PDF')->storeAs('proveedores/' . $request['PROVEEDOR_ID'] . '/signatarios/' . $request['SIGNATARIO_ID'] . '/documentos/experiencia', $signatarioexperiencia->ID_EXPERIENCIA . '.' . $extension);
                        }

                        $signatarioexperiencia->update($request->all());
                        return response()->json($signatarioexperiencia);
                    }

                } else //eliminar
                {
                    $experiencia = SignatarioExperienciaModel::findOrFail($request['ID_EXPERIENCIA']);
                    $experiencia->ELIMINADO = 1;
                    $experiencia->save();
                    $dato["experiencia"] = $experiencia;
                    return response()->json($dato);
                }
            }
        } catch (Exception $e) {
            return response()->json('Error al guardar');
        }
    }
}

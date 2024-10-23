<?php

namespace App\Http\Controllers\PSICO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

Use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Support\Facades\Storage;

use App\modelos\reconocimientopsico\recopsicotrabajadoresModel;
use App\modelos\reconocimientopsico\fotostrabajadorespsicoModel;

class guiasController extends Controller
{

public function obtenerExplicaciones(Request $request) {
    $ids = $request->input('ids');  

    $explicaciones = DB::table('catalogoguiaspsico')
                    ->whereIn('PREGUNTA_ID', $ids) 
                    ->pluck('PREGUNTA_EXPLICACION', 'PREGUNTA_ID'); 

    return response()->json(['explicaciones' => $explicaciones]);
}


public function consultarDatosTrabajador(Request $request) {
    $idTrabajador = $request->input('id_trabajador');

    $trabajador = DB::select("
        SELECT RECPSICOTRABAJADOR_NOMBRE, RECPSICOTRABAJADOR_GENERO, RECPSICOTRABAJADOR_CORREO
        FROM recopsicotrabajadores
        WHERE ID_RECOPSICOTRABAJADOR = :idTrabajador
    ", ['idTrabajador' => $idTrabajador]);

    if (!empty($trabajador)) {
        return response()->json($trabajador[0]); 
    }

    return response()->json(['error' => 'No se encontraron datos para este trabajador'], 404);
}

public function guardarFotoRecpsico(Request $request)
{
    $idTrabajador = $request->input('trabajadorId');

    // Buscar el reconocimiento psicológico del trabajador
    $recpsico = DB::selectOne("
        SELECT RECPSICO_ID
        FROM recopsicotrabajadores
        WHERE ID_RECOPSICOTRABAJADOR = :idTrabajador
    ", ['idTrabajador' => $idTrabajador]);

    if ($recpsico) {
        $recpsicoId = $recpsico->RECPSICO_ID;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $extension = $foto->getClientOriginalExtension();
            $nombreArchivo = time() . '.' . $extension;

            $ruta = $foto->storeAs('evidencias_psico/' . $recpsicoId . '/ONLINE', $idTrabajador . '_preguia.' . $extension);

            // Verificar si ya existe un registro para el trabajador en la tabla recopsicoFotosTrabajadores
            $fotoTrabajador = DB::table('recopsicoFotosTrabajadores')
                ->where('RECPSICO_TRABAJADOR', $idTrabajador)
                ->first();

            if ($fotoTrabajador) {
                // Si existe, actualizar los campos
                DB::table('recopsicoFotosTrabajadores')
                    ->where('RECPSICO_TRABAJADOR', $idTrabajador)
                    ->update([
                        'RECPSICO_ID' => $recpsicoId,
                        'RECPSICO_FOTOPREGUIA' => $ruta,
                        'updated_at' => now()
                    ]);

                return response()->json([
                    'mensaje' => 'Registro actualizado correctamente',
                    'ruta' => $ruta
                ]);
            } else {
                // Si no existe, insertar un nuevo registro
                DB::table('recopsicoFotosTrabajadores')->insert([
                    'RECPSICO_ID' => $recpsicoId,
                    'RECPSICO_TRABAJADOR' => $idTrabajador,
                    'RECPSICO_FOTOPREGUIA' => $ruta,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                return response()->json([
                    'mensaje' => 'Foto guardada exitosamente y registro creado',
                    'ruta' => $ruta
                ]);
            }
        }

        return response()->json(['mensaje' => 'Error: no se proporcionó ninguna foto'], 400);
    }

    return response()->json(['mensaje' => 'Trabajador no encontrado o sin reconocimiento psicológico'], 404);
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            if (($request->opcion + 0) == 1) // DATOS DEL RECONOCIMIENTO
            {
                $ano = (date('y')) + 0;
                $recsensorial_activo = 0;

                if (($request->recsensorial_id + 0) == 0) //nuevo
                {

                        DB::statement('ALTER TABLE reconocimientopsico AUTO_INCREMENT=1');

                        //Verificamos si el reconocimiento requiere contrato de no requerir autorizado lo ponemos como 0 para que deba ser autorizado
                        if (intval($request->requiere_contrato) == 1) {
                            $request['autorizado'] = 1;
                            $request['recsensorial_bloqueado'] = 0;
                        } else {
                            $request['recsensorial_bloqueado'] = 1;
                            $request['autorizado'] = 0;
                            $request['contrato_id'] = 0;
                        }

                        $request['recsensorial_fisicosimprimirbloqueado'] = 0;
                        $request['recsensorial_quimicosimprimirbloqueado'] = 0;
                        $request['recsensorial_bloqueado'] = 0;

                        $request['recsensorial_eliminado'] = 0;
                        $reconocimientopsico = reconocimientopsicoModel::create($request->all());
                        // $recsensorial->recsensorialpruebas()->sync($request->parametro); // SE COMENTO PORQUE YA SON DOS ARREGLOS DE PRUEBAS ENTONCES SI HIZO APARTE


                        //UNA VEZ GUARDADO TODO LO DE RECONOCIMIENTO PROCEDEMOS A VINCULAR EL  ID DEL RECONOCIMIENTO CON EL PROYECTO
                        $proyecto = proyectoModel::where('proyecto_folio', $request["proyecto_folio"])->first();
                        $proyecto->reconocimiento_psico_id = $reconocimientopsico->id;
                        $proyecto->save();


                        // mensaje
                        $dato["msj"] = 'Información guardada correctamente y vinculado con el proyecto: ' . $request["proyecto_folio"];
                        $recsensorial_activo = 1;


                        // si envia archivo FOTO ubicacion
                    if ($request->file('inputfotomapa')) {
                        $extension = $request->file('inputfotomapa')->getClientOriginalExtension();
                        $request['fotoubicacion'] = $request->file('inputfotomapa')->storeAs('reconocimiento_psico/' . $reconocimientopsico->id . '/mapa', $reconocimientopsico->id . '.' . $extension);
                        $reconocimientopsico->update($request->all());
                    }else{
                        $recsensorial_extension = $request['hidden_fotomapa_extension'];
                        $recsensorial_id = $request['hidden_fotomapa'];
                        $rutaOriginal = 'recsensorial/' . $recsensorial_id . '/mapa/' . $recsensorial_id . $recsensorial_extension;

                        if (Storage::exists($rutaOriginal)) {
                            // Asegúrate de crear el directorio si no existe
                            $nuevaRuta = 'reconocimiento_psico/' . $reconocimientopsico->id . '/mapa/' . $reconocimientopsico->id . '.' . pathinfo($rutaOriginal, PATHINFO_EXTENSION);
        
                            Storage::makeDirectory('reconocimiento_psico/' . $reconocimientopsico->id . '/mapa');
        
                            // Copiar la imagen a la nueva ubicación
                            Storage::copy($rutaOriginal, $nuevaRuta);
                            
                            // Actualiza la base de datos con la nueva ruta
                            $reconocimientopsico->fotoubicacion = $nuevaRuta;
                            $reconocimientopsico->update($request->all());
                        } else {
                            // Manejar caso en el que la imagen original no existe
                            // Puedes lanzar una excepción o asignar un valor predeterminado
                            throw new Exception("La imagen original no existe en la ruta: " . $rutaOriginal);
                        }
                    }

                    // si envia archivo FOTO plano
                    if ($request->file('inputfotoplano')) {
                        $extension = $request->file('inputfotoplano')->getClientOriginalExtension();
                        $request['fotoplano'] = $request->file('inputfotoplano')->storeAs('reconocimiento_psico/' . $reconocimientopsico->id . '/plano', $reconocimientopsico->id . '.' . $extension);
                        $reconocimientopsico->update($request->all());
                    }else{
                        $recsensorial_extension = $request['hidden_fotoplano_extension'];
                        $recsensorial_id = $request['hidden_fotoplano'];
                        $rutaOriginal = 'recsensorial/' . $recsensorial_id . '/plano/' . $recsensorial_id . $recsensorial_extension;

                        if (Storage::exists($rutaOriginal)) {
                            // Asegúrate de crear el directorio si no existe
                            $nuevaRuta = 'reconocimiento_psico/' . $reconocimientopsico->id . '/plano/' . $reconocimientopsico->id . '.' . pathinfo($rutaOriginal, PATHINFO_EXTENSION);
        
                            Storage::makeDirectory('reconocimiento_psico/' . $reconocimientopsico->id . '/plano');
        
                            // Copiar la imagen a la nueva ubicación
                            Storage::copy($rutaOriginal, $nuevaRuta);
                            
                            // Actualiza la base de datos con la nueva ruta
                            $reconocimientopsico->fotoplano = $nuevaRuta;
                            $reconocimientopsico->update($request->all());
                        } else {
                            // Manejar caso en el que la imagen original no existe
                            // Puedes lanzar una excepción o asignar un valor predeterminado
                            throw new Exception("La imagen original no existe en la ruta: " . $rutaOriginal);
                        }
                    }

                    // si envia archivo FOTO instalacion
                    if ($request->file('inputfotoinstalacion')) {
                        $extension = $request->file('inputfotoinstalacion')->getClientOriginalExtension();
                        $request['fotoinstalacion'] = $request->file('inputfotoinstalacion')->storeAs('reconocimiento_psico/' . $reconocimientopsico->id . '/instalacion', $reconocimientopsico->id . '.' . $extension);
                        $reconocimientopsico->update($request->all());
                    }else{
                        $recsensorial_extension = $request['hidden_fotoinstalacion_extension'];
                        $recsensorial_id = $request['hidden_fotoinstalacion'];
                        $rutaOriginal = 'recsensorial/' . $recsensorial_id . '/instalacion/' . $recsensorial_id . $recsensorial_extension;

                        if (Storage::exists($rutaOriginal)) {
                            // Asegúrate de crear el directorio si no existe
                            $nuevaRuta = 'reconocimiento_psico/' . $reconocimientopsico->id . '/instalacion/' . $reconocimientopsico->id . '.' . pathinfo($rutaOriginal, PATHINFO_EXTENSION);
        
                            Storage::makeDirectory('reconocimiento_psico/' . $reconocimientopsico->id . '/instalacion');
        
                            // Copiar la imagen a la nueva ubicación
                            Storage::copy($rutaOriginal, $nuevaRuta);
                            
                            // Actualiza la base de datos con la nueva ruta
                            $reconocimientopsico->fotoinstalacion = $nuevaRuta;
                            $reconocimientopsico->update($request->all());
                        } else {
                            // Manejar caso en el que la imagen original no existe
                            // Puedes lanzar una excepción o asignar un valor predeterminado
                            throw new Exception("La imagen original no existe en la ruta: " . $rutaOriginal);
                        }
                    }
                } else { //EDITAR 

                    // Obtener registro
                    $reconocimientopsico = reconocimientopsicoModel::findOrFail($request->recsensorial_id);

                    // consultar ID ultimo registro de la tabla
                    $reconocimientopsico_idmax = DB::select('SELECT
                                                            MAX( reconocimientopsico.id ) AS reconocimientopsico_idmax
                                                        FROM
                                                            reconocimientopsico');

                    // Validar que sea el ultimo ID, y permita editar folios

                    $reconocimientopsico->update($request->all());
                    // $recsensorial->recsensorialpruebas()->sync($request->parametro);

                    ///VERIFICAMOS QUE EL FOLIO DEL PROYECTO QUE ENVIA SEA EL MISMO
                    if ($reconocimientopsico->proyecto_folio == $request['proyecto_folio']) {

                        $proyecto = proyectoModel::where('proyecto_folio', $request["proyecto_folio"])->first();
                        $proyecto->reconocimiento_psico_id = $reconocimientopsico->id;
                        $proyecto->save();
                    } else {


                        $proyecto = proyectoModel::where('proyecto_folio', $reconocimientopsico->proyecto_folio)->first();
                        $proyecto->reconocimiento_psico_id = null;
                        $proyecto->save();


                        $proyecto = proyectoModel::where('proyecto_folio', $request["proyecto_folio"])->first();
                        $proyecto->reconocimiento_psico_id = $reconocimientopsico->id;
                        $proyecto->save();
                    }



                    // if ($request->file('inputfotomapa')) {
                    //     $extension = $request->file('inputfotomapa')->getClientOriginalExtension();
                    //     $request['fotoubicacion'] = $request->file('inputfotomapa')->storeAs('reconocimiento_psico/' . $reconocimientopsico->id . '/mapa', $reconocimientopsico->id . '.' . $extension);
                    //     $reconocimientopsico->update($request->all());
                    // }

                    // // si envia archivo FOTO plano
                    // if ($request->file('inputfotoplano')) {
                    //     $extension = $request->file('inputfotoplano')->getClientOriginalExtension();
                    //     $request['fotoplano'] = $request->file('inputfotoplano')->storeAs('reconocimiento_psico/' . $reconocimientopsico->id . '/plano', $reconocimientopsico->id . '.' . $extension);
                    //     $reconocimientopsico->update($request->all());
                    // }

                    // // si envia archivo FOTO instalacion
                    // if ($request->file('inputfotoinstalacion')) {
                    //     $extension = $request->file('inputfotoinstalacion')->getClientOriginalExtension();
                    //     $request['fotoinstalacion'] = $request->file('inputfotoinstalacion')->storeAs('reconocimiento_psico/' . $reconocimientopsico->id . '/instalacion', $reconocimientopsico->id . '.' . $extension);
                    //     $reconocimientopsico->update($request->all());
                    // }

                    function eliminarArchivoAntiguo($id, $folder) {
                        // Definir la ruta del directorio
                        $directory = 'reconocimiento_psico/' . $id . '/' . $folder;
                    
                        // Buscar y eliminar cualquier archivo con el mismo nombre, pero con diferente extensión
                        $files = Storage::files($directory);
                        foreach ($files as $file) {
                            // Verificar si el archivo coincide con el nombre, independientemente de la extensión
                            if (pathinfo($file, PATHINFO_FILENAME) == $id) {
                                Storage::delete($file);
                            }
                        }
                    }

                    if ($request->file('inputfotomapa')) {
                        $extension = $request->file('inputfotomapa')->getClientOriginalExtension();
                        $folder = 'mapa';
                        $path = 'reconocimiento_psico/' . $reconocimientopsico->id . '/' . $folder . '/' . $reconocimientopsico->id . '.' . $extension;
                    
                        // Eliminar cualquier archivo antiguo, sin importar la extensión
                        eliminarArchivoAntiguo($reconocimientopsico->id, $folder);
                    
                        // Guardar la nueva foto
                        $request['fotoubicacion'] = $request->file('inputfotomapa')->storeAs('reconocimiento_psico/' . $reconocimientopsico->id . '/' . $folder, $reconocimientopsico->id . '.' . $extension);
                        $reconocimientopsico->update($request->all());
                    }
                    
                    // Para el archivo FOTO plano
                    if ($request->file('inputfotoplano')) {
                        $extension = $request->file('inputfotoplano')->getClientOriginalExtension();
                        $folder = 'plano';
                        $path = 'reconocimiento_psico/' . $reconocimientopsico->id . '/' . $folder . '/' . $reconocimientopsico->id . '.' . $extension;
                    
                        eliminarArchivoAntiguo($reconocimientopsico->id, $folder);
                    
                        $request['fotoplano'] = $request->file('inputfotoplano')->storeAs('reconocimiento_psico/' . $reconocimientopsico->id . '/' . $folder, $reconocimientopsico->id . '.' . $extension);
                        $reconocimientopsico->update($request->all());
                    }
                    
                    // Para el archivo FOTO instalación
                    if ($request->file('inputfotoinstalacion')) {
                        $extension = $request->file('inputfotoinstalacion')->getClientOriginalExtension();
                        $folder = 'instalacion';
                        $path = 'reconocimiento_psico/' . $reconocimientopsico->id . '/' . $folder . '/' . $reconocimientopsico->id . '.' . $extension;
                    
                        eliminarArchivoAntiguo($reconocimientopsico->id, $folder);
                    
                        $request['fotoinstalacion'] = $request->file('inputfotoinstalacion')->storeAs('reconocimiento_psico/' . $reconocimientopsico->id . '/' . $folder, $reconocimientopsico->id . '.' . $extension);
                        $reconocimientopsico->update($request->all());
                    }


                    // mensaje
                    $dato["msj"] = 'Información modificada correctamente';
                }

                



                // respuesta
                $dato['recsensorial_activo'] = $recsensorial_activo;
                $dato['recsensorial'] = $reconocimientopsico;
            }


            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['recsensorial'] = 0;
            $dato["recsensorial_bloqueado"] = 0;
            return response()->json($dato);
        }
    }


}

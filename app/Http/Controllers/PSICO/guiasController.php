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
use App\modelos\reconocimientopsico\respuestastrabajadorespsicoModel;


class guiasController extends Controller
{
    public function consultarRespuestasGuardadas(Request $request) {
        $idTrabajador = $request->input('id_trabajador');

        $trabajador = DB::select("
            SELECT RECPSICO_GUIAI_RESPUESTAS, RECPSICO_GUIAII_RESPUESTAS, RECPSICO_GUIAIII_RESPUESTAS
            FROM recopsicoTrabajadoresRespuestas
            WHERE RECPSICO_TRABAJADOR = :idTrabajador
        ", ['idTrabajador' => $idTrabajador]);

        if (!empty($trabajador)) {
            return response()->json($trabajador[0]); 
        }

        return response()->json(['error' => 'No se encontraron respuestas guardadas de este trabajador'], 404);
    }

    public function consultarRespuestasGuiaV(Request $request) {
        $idTrabajador = $request->input('id_trabajador');

        $trabajador = DB::select("
            SELECT RECPSICOTRABAJADOR_GENERO, RECPSICOTRABAJADOR_EDAD, 
            RECPSICOTRABAJADOR_ESTADOCIVIL, RECPSICOTRABAJADOR_ESTUDIOS, 
            RECPSICOTRABAJADOR_TIPOPUESTO, RECPSICOTRABAJADOR_TIPOCONTRATACION, 
            RECPSICOTRABAJADOR_TIPOCONTRATACION, RECPSICOTRABAJADOR_TIPOPERSONAL, 
            RECPSICOTRABAJADOR_ROTACIONTURNOS, RECPSICOTRABAJADOR_TIEMPOPUESTO, 
            RECPSICOTRABAJADOR_TIEMPOEXPERIENCIA
            FROM recopsicoguia_5
            WHERE RECPSICOTRABAJADOR_ID =:idTrabajador
        ", ['idTrabajador' => $idTrabajador]);

        if (!empty($trabajador)) {
            return response()->json($trabajador[0]); 
        }

        return response()->json(['error' => 'No se encontraron respuestas guardadas de este trabajador'], 404);
    }

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
            $option = $request->input('option'); // Obtener la guia enviada
            $tipoGuardado = $request->input('tipoGuardado'); //1=En proceso 2=Finaliazado

            if ($option == 1) { // GUIA 1
       
                $datosRegistro = array_fill(0, 21, null);
                    for ($i = 1; $i <= 21; $i++) {
                        $campoNombre = "GUIA1_$i"; 
                        if ($request->has($campoNombre)) {
                            $datosRegistro[$i - 1] = $request->input($campoNombre);
                        }
                }

            
                $jsonDatos = json_encode($datosRegistro);

                    
                $existe = DB::table('recopsicoTrabajadoresRespuestas')
                    ->where('RECPSICO_TRABAJADOR', $request->input('TRABAJADOR_ID'))
                    ->exists();

                if ($existe) {
                    // Si el registro ya existe, realizar un UPDATE selectivo
                    DB::table('recopsicoTrabajadoresRespuestas')
                        ->where('RECPSICO_TRABAJADOR', $request->input('TRABAJADOR_ID'))
                        ->update([
                            'RECPSICO_GUIAI_RESPUESTAS' => $jsonDatos, 
                            'updated_at' => now(),
                        ]);

                    $estadoContestacion = $tipoGuardado == 1 ? 'Finalizado' : 'En proceso';
                
                    DB::table('proyectotrabajadores')
                        ->where('TRABAJADOR_ID', $request->input('TRABAJADOR_ID'))
                        ->update([
                            'TRABAJADOR_ESTADOCONTESTADO' => $estadoContestacion,
                            'updated_at' => now(),
                        ]);

                return response()->json(['mensaje' => 'Registro actualizado y finalizado exitosamente']);
                } else {

                    $registro = DB::table('recopsicotrabajadores')
                        ->where('ID_RECOPSICOTRABAJADOR', $request->input('TRABAJADOR_ID'))
                        ->first(['RECPSICO_ID']);

                    if ($registro) {
                        $dataInsert = [
                            'RECPSICO_TRABAJADOR' => $request->input('TRABAJADOR_ID'),
                            'RECPSICO_GUIAI_RESPUESTAS' => $jsonDatos,
                            'RECPSICO_ID' => $registro->RECPSICO_ID,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        DB::table('recopsicoTrabajadoresRespuestas')->insert($dataInsert);

                        $estadoContestacion = $tipoGuardado == 1 ? 'Finalizado' : 'En proceso';
                    
                        DB::table('proyectotrabajadores')
                            ->where('TRABAJADOR_ID', $request->input('TRABAJADOR_ID'))
                            ->update([
                                'TRABAJADOR_ESTADOCONTESTADO' => $estadoContestacion,
                                'updated_at' => now(),
                            ]);
            
                        return response()->json(['mensaje' => 'Registro creado exitosamente']);
                    }
                }

            } else if ($option == 2) { // GUIA 2

                $datosRegistro2 = array_fill(0, 48, null);
                    for ($i = 1; $i <= 48; $i++) {
                        $campoNombre2 = "GUIA2_$i"; 
                        if ($request->has($campoNombre2)) {
                            $datosRegistro2[$i - 1] = $request->input($campoNombre2);
                        }
                    }
                    
                    $jsonDatos2 = json_encode($datosRegistro2);

                    //validacion 
                    // if ($datosRegistro[0] === null || $datosRegistro[1] === null) {
                    //     return response()->json(['error' => 'Los campos GUIA1_1 y GUIA1_2 son obligatorios.'], 422);
                    // }

                    $existe2 = DB::table('recopsicoTrabajadoresRespuestas')
                        ->where('RECPSICO_TRABAJADOR', $request->input('TRABAJADOR_ID'))
                        ->exists();

                    if ($existe2) {
                        // Si el registro ya existe, realizar un UPDATE selectivo
                        DB::table('recopsicoTrabajadoresRespuestas')
                            ->where('RECPSICO_TRABAJADOR', $request->input('TRABAJADOR_ID'))
                            ->update([
                                'RECPSICO_GUIAII_RESPUESTAS' => $jsonDatos2, // Solo actualizar los datos recibidos
                                'updated_at' => now(),
                            ]);
        
                        return response()->json(['mensaje' => 'Registro actualizado exitosamente']);
                    } else {
                        // Si el registro no existe, realizar un INSERT INTO
                        DB::table('recopsicoTrabajadoresRespuestas')->insert([
                            'RECPSICO_TRABAJADOR' => $request->input('TRABAJADOR_ID'),
                            'RECPSICO_GUIAII_RESPUESTAS' => $jsonDatos2,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
        
                        return response()->json(['mensaje' => 'Registro creado exitosamente']);
                    }
            
            } else if ($option == 3) { // GUIA 3
                $datosRegistro3 = array_fill(0, 74, null);
                for ($i = 1; $i <= 74; $i++) {
                    $campoNombre3 = "GUIA3_$i"; 
                    if ($request->has($campoNombre3)) {
                        $datosRegistro3[$i - 1] = $request->input($campoNombre3);
                    }
                }
                
                $jsonDatos3 = json_encode($datosRegistro3);

                $existe3 = DB::table('recopsicoTrabajadoresRespuestas')
                    ->where('RECPSICO_TRABAJADOR', $request->input('TRABAJADOR_ID'))
                    ->exists();

                if ($existe3) {
                    DB::table('recopsicoTrabajadoresRespuestas')
                        ->where('RECPSICO_TRABAJADOR', $request->input('TRABAJADOR_ID'))
                        ->update([
                            'RECPSICO_GUIAIII_RESPUESTAS' => $jsonDatos3,
                            'updated_at' => now(),
                        ]);
    
                    return response()->json(['mensaje' => 'Registro actualizado exitosamente']);
                } else {
                    DB::table('recopsicoTrabajadoresRespuestas')->insert([
                        'RECPSICO_TRABAJADOR' => $request->input('TRABAJADOR_ID'),
                        'RECPSICO_GUIAIII_RESPUESTAS' => $jsonDatos3,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    return response()->json(['mensaje' => 'Registro creado exitosamente']);
                }
            } else if ($option == 5) { // GUIA 5

                $existe5 = DB::table('recopsicoguia_5')
                    ->where('RECPSICOTRABAJADOR_ID', $request->input('TRABAJADOR_ID'))
                    ->exists();

                if ($existe5) {
                    DB::table('recopsicoguia_5')
                        ->where('RECPSICOTRABAJADOR_ID', $request->input('TRABAJADOR_ID'))
                        ->update([
                            'RECPSICOTRABAJADOR_GENERO' => $request->input('RECPSICOTRABAJADOR_GENERO'),
                            'RECPSICOTRABAJADOR_EDAD' => $request->input('RECPSICOTRABAJADOR_EDAD'),
                            'RECPSICOTRABAJADOR_ESTADOCIVIL' => $request->input('RECPSICOTRABAJADOR_ESTADOCIVIL'),
                            'RECPSICOTRABAJADOR_ESTUDIOS' => $request->input('RECPSICOTRABAJADOR_ESTUDIOS'),
                            'RECPSICOTRABAJADOR_TIPOPUESTO' => $request->input('RECPSICOTRABAJADOR_TIPOPUESTO'),
                            'RECPSICOTRABAJADOR_TIPOCONTRATACION' => $request->input('RECPSICOTRABAJADOR_TIPOCONTRATACION'),
                            'RECPSICOTRABAJADOR_TIPOPERSONAL' => $request->input('RECPSICOTRABAJADOR_TIPOPERSONAL'),
                            'RECPSICOTRABAJADOR_TIPOJORNADA' => $request->input('RECPSICOTRABAJADOR_TIPOJORNADA'),
                            'RECPSICOTRABAJADOR_ROTACIONTURNOS' => $request->input('RECPSICOTRABAJADOR_ROTACIONTURNOS'),
                            'RECPSICOTRABAJADOR_TIEMPOPUESTO' => $request->input('RECPSICOTRABAJADOR_TIEMPOPUESTO'),
                            'RECPSICOTRABAJADOR_TIEMPOEXPERIENCIA' => $request->input('RECPSICOTRABAJADOR_TIEMPOEXPERIENCIA'),
                            'updated_at' => now(),
                        ]);
    
                    return response()->json(['mensaje' => 'Registro actualizado exitosamente']);
                } else {
                    DB::table('recopsicoTrabajadoresRespuestas')->insert([
                        'RECPSICO_TRABAJADOR' => $request->input('TRABAJADOR_ID'),
                        'RECPSICO_GUIAIII_RESPUESTAS' => $jsonDatos3,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    return response()->json(['mensaje' => 'Registro creado exitosamente']);
                }
            }

            $dato["msj"] = "Proceso completado correctamente.";
            return response()->json($dato);

        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }

    
}

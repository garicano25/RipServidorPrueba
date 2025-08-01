<?php


namespace App\Http\Controllers\reportes;

// Modelos
use App\modelos\proyecto\proyectoModel;
use App\modelos\reconocimientopsico\reconocimientopsicoModel;
use App\modelos\reconocimientopsico\respuestastrabajadorespsicoModel;
use App\modelos\reconocimientopsico\proyectotrabajadoresModel;
use App\modelos\recsensorial\recsensorialModel;


//Tablas revisiones
use App\modelos\reportes\reporterevisionesModel;

use App\modelos\reportes\reporte_calificacionesModel;


// Catalogos
use App\modelos\recsensorial\catregionModel;
use App\modelos\recsensorial\catsubdireccionModel;
use App\modelos\recsensorial\catgerenciaModel;
use App\modelos\recsensorial\catactivoModel;
use App\modelos\recsensorial\catConclusionesModel;

use App\modelos\reportes\recursosPortadasInformesModel;

use App\modelos\reportes\reportenom0353Model;
use App\modelos\reportes\reportenom0353catalogoModel;
use App\modelos\reportes\reporterecomendacionescontrolModel;
use App\modelos\reportes\reporterecomendacionescategoriaModel;

//Recursos para el Excel
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Response;



use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use DB;
use Artisan;
use Exception;
use Illuminate\Support\Facades\Log;

class reportenom0353Controller extends Controller
{
   
      //
    
       /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reportenom035vista3($proyecto_id)
    {
        $proyecto = proyectoModel::findOrFail($proyecto_id);

        if ($proyecto->proyecto_clienteinstalacion == NULL || $proyecto->proyecto_fechaentrega == NULL) {
            return '<div style="text-align: center;">
                        <p style="font-size: 24px;">Datos incompletos</p>
                        <b style="font-size: 18px;">Para ingresar al diseño del reporte de NOM-035-STPS-2028 primero debe completar todos los campos vacíos de la sección de datos generales del proyecto.</b>
                    </div>';
        } else {

            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
            ->where('agente_id', 353) //nom 0353
            ->orderBy('reporterevisiones_revision', 'DESC')
            ->get();


            if (count($revision) == 0) {
                DB::statement('ALTER TABLE reporterevisiones AUTO_INCREMENT = 1;');

                $revision = reporterevisionesModel::create([
                    'proyecto_id' => $proyecto_id,
                    'agente_id' => 353,
                    'agente_nombre' => 'NOM0353',
                    'reporterevisiones_revision' => 0,
                    'reporterevisiones_concluido' => 0,
                    'reporterevisiones_concluidonombre' => NULL,
                    'reporterevisiones_concluidofecha' => NULL,
                    'reporterevisiones_cancelado' => 0,
                    'reporterevisiones_canceladonombre' => NULL,
                    'reporterevisiones_canceladofecha' => NULL,
                    'reporterevisiones_canceladoobservacion' => NULL
                ]);
            }

            //CREA LAS CALIFICACIONES
            //-------------------------------------
            $reporte_calificaciones = reporte_calificacionesModel::where('proyecto_id', $proyecto_id)
            ->orderBy('TRABAJADOR_ID', 'DESC')
            ->get();

            $trabajadores_respuestas = respuestastrabajadorespsicoModel::where('RECPSICO_ID', $proyecto->reconocimiento_psico_id)
            ->orderBy('RECPSICO_TRABAJADOR', 'DESC')
            ->get();

            function getValue($array, $index, $default = 0) {
                return isset($array[$index]) ? (int)$array[$index] : $default;
            }

            function sumarItems($array) {
                $suma = 0;
                foreach ($array as $value) {
                    if (is_numeric($value)) {
                        $suma += (int)$value;
                    }
                }
                return $suma;
            }
            
            if (count($reporte_calificaciones) == 0) {
                foreach ($trabajadores_respuestas as $trabajador) {

                    $jsonCalificacionesGuia1 = $trabajador->RECPSICO_GUIAI_RESPUESTAS;
                    $arrayCalificacionesGuia1 = json_decode($jsonCalificacionesGuia1, true) ?? [];

                 
                    
                    $ACONTECIMIENTO_CALIFICACION = getValue($arrayCalificacionesGuia1, 0);
                    $RECUERDOS_CALIFICACION = getValue($arrayCalificacionesGuia1, 7) + getValue($arrayCalificacionesGuia1, 8);
                    $ESFUERZO_CALIFICACION = getValue($arrayCalificacionesGuia1, 9) + getValue($arrayCalificacionesGuia1, 10) + 
                                             getValue($arrayCalificacionesGuia1, 11) + getValue($arrayCalificacionesGuia1, 12) +
                                             getValue($arrayCalificacionesGuia1, 13) + getValue($arrayCalificacionesGuia1, 14) +
                                             getValue($arrayCalificacionesGuia1, 15);
                    $AFECTACION_CALIFICACION = getValue($arrayCalificacionesGuia1, 16) + getValue($arrayCalificacionesGuia1, 17) + 
                                               getValue($arrayCalificacionesGuia1, 18) + getValue($arrayCalificacionesGuia1, 19) + 
                                               getValue($arrayCalificacionesGuia1, 20);
                    
                    $GUIA1_CALIFICACION = ($ACONTECIMIENTO_CALIFICACION == 1 && 
                        ($RECUERDOS_CALIFICACION >= 1 || $ESFUERZO_CALIFICACION >= 3 || $AFECTACION_CALIFICACION >= 2)) ? 1 : 0;


                    $jsonCalificacionesGuia3 = $trabajador->RECPSICO_GUIAIII_RESPUESTAS;
                    $arrayCalificacionesGuia3 = json_decode($jsonCalificacionesGuia3, true) ?? [];
                        

                    //seccion 1 de guia 3
                    $D_CONDICIONES_CALIFICACION = getValue($arrayCalificacionesGuia3, 0) + getValue($arrayCalificacionesGuia3, 1) + getValue($arrayCalificacionesGuia3, 2) + getValue($arrayCalificacionesGuia3, 3) + getValue($arrayCalificacionesGuia3, 4);
                    $C_AMBIENTE_CALIFICACION = $D_CONDICIONES_CALIFICACION;
                    //1 es nulo, 2 es bajo, 3 es medio, 4 es alto, 5 es muy alto
                    $C_AMBIENTE_NIVEL =  ( $C_AMBIENTE_CALIFICACION < 5 ? 1 : ($C_AMBIENTE_CALIFICACION >= 5 && $C_AMBIENTE_CALIFICACION < 9   ? 2 : ($C_AMBIENTE_CALIFICACION >= 9 && $C_AMBIENTE_CALIFICACION < 11   ? 3 :($C_AMBIENTE_CALIFICACION >= 11 && $C_AMBIENTE_CALIFICACION < 14 ? 4 :($C_AMBIENTE_CALIFICACION >= 14 ? 5 : 0)))));
                    $D_CONDICIONES_NIVEL =  ( $D_CONDICIONES_CALIFICACION < 5 ? 1 : ($D_CONDICIONES_CALIFICACION >= 5 && $D_CONDICIONES_CALIFICACION < 9   ? 2 : ($D_CONDICIONES_CALIFICACION >= 9 && $D_CONDICIONES_CALIFICACION < 11   ? 3 :($D_CONDICIONES_CALIFICACION >= 11 && $D_CONDICIONES_CALIFICACION < 14 ? 4 :($D_CONDICIONES_CALIFICACION >= 14 ? 5 : 0)))));
                    
                    //seccion 2 de guia 3
                    $D_CARGA_CALIFICACION = getValue($arrayCalificacionesGuia3, 5) + getValue($arrayCalificacionesGuia3, 6) + 
                    getValue($arrayCalificacionesGuia3, 7) + getValue($arrayCalificacionesGuia3, 8) + 
                    getValue($arrayCalificacionesGuia3, 9) + getValue($arrayCalificacionesGuia3, 10) +
                    getValue($arrayCalificacionesGuia3, 11) + getValue($arrayCalificacionesGuia3, 64) +
                    getValue($arrayCalificacionesGuia3, 65) + getValue($arrayCalificacionesGuia3, 66) +
                    getValue($arrayCalificacionesGuia3, 67) + getValue($arrayCalificacionesGuia3, 12) +
                    getValue($arrayCalificacionesGuia3, 13) + getValue($arrayCalificacionesGuia3, 14) +
                    getValue($arrayCalificacionesGuia3, 15);

                    $D_FALTA_CALIFICACION = getValue($arrayCalificacionesGuia3, 24) + getValue($arrayCalificacionesGuia3, 25) + 
                    getValue($arrayCalificacionesGuia3, 26) + getValue($arrayCalificacionesGuia3, 27) + 
                    getValue($arrayCalificacionesGuia3, 22) + getValue($arrayCalificacionesGuia3, 23) + 
                    getValue($arrayCalificacionesGuia3, 28) + getValue($arrayCalificacionesGuia3, 29) + 
                    getValue($arrayCalificacionesGuia3, 34) + getValue($arrayCalificacionesGuia3, 35);

                    $C_FACTORES_CALIFICACION = $D_CARGA_CALIFICACION + $D_FALTA_CALIFICACION;

                    $D_CARGA_NIVEL =  ( $D_CARGA_CALIFICACION < 15 ? 1 : ($D_CARGA_CALIFICACION >= 15 && $D_CARGA_CALIFICACION < 21   ? 2 : ($D_CARGA_CALIFICACION >= 21 && $D_CARGA_CALIFICACION < 27   ? 3 :($D_CARGA_CALIFICACION >= 27 && $D_CARGA_CALIFICACION < 37 ? 4 :($D_CARGA_CALIFICACION >= 37 ? 5 : 0)))));
                    $D_FALTA_NIVEL =  ( $D_FALTA_CALIFICACION < 11 ? 1 : ($D_FALTA_CALIFICACION >= 11 && $D_FALTA_CALIFICACION < 16   ? 2 : ($D_FALTA_CALIFICACION >= 16 && $D_FALTA_CALIFICACION < 21   ? 3 :($D_FALTA_CALIFICACION >= 21 && $D_FALTA_CALIFICACION < 25 ? 4 :($D_FALTA_CALIFICACION >= 25 ? 5 : 0)))));
                    $C_FACTORES_NIVEL =  ( $C_FACTORES_CALIFICACION < 15 ? 1 : ($C_FACTORES_CALIFICACION >= 15 && $C_FACTORES_CALIFICACION < 30   ? 2 : ($C_FACTORES_CALIFICACION >= 30 && $C_FACTORES_CALIFICACION < 45   ? 3 :($C_FACTORES_CALIFICACION >= 45 && $C_FACTORES_CALIFICACION < 60 ? 4 :($C_FACTORES_CALIFICACION >= 60 ? 5 : 0)))));

                    //seccion 3 de guia 3
                    $D_JORNADA_CALIFICACION = getValue($arrayCalificacionesGuia3, 16) + getValue($arrayCalificacionesGuia3, 17);
                    $D_INTERFERENCIA_CALIFICACION = getValue($arrayCalificacionesGuia3, 18) + getValue($arrayCalificacionesGuia3, 19) + getValue($arrayCalificacionesGuia3, 20) + getValue($arrayCalificacionesGuia3, 21);
                    $C_ORGANIZACION_CALIFICACION = $D_JORNADA_CALIFICACION + $D_INTERFERENCIA_CALIFICACION;

                    $D_JORNADA_NIVEL =  ( $D_JORNADA_CALIFICACION < 1 ? 1 : ($D_JORNADA_CALIFICACION >= 1 && $D_JORNADA_CALIFICACION < 2   ? 2 : ($D_JORNADA_CALIFICACION >= 2 && $D_JORNADA_CALIFICACION < 4   ? 3 :($D_JORNADA_CALIFICACION >= 4 && $D_JORNADA_CALIFICACION < 6 ? 4 :($D_JORNADA_CALIFICACION >= 6 ? 5 : 0)))));
                    $D_INTERFERENCIA_NIVEL =  ( $D_INTERFERENCIA_CALIFICACION < 4 ? 1 : ($D_INTERFERENCIA_CALIFICACION >= 4 && $D_INTERFERENCIA_CALIFICACION < 6   ? 2 : ($D_INTERFERENCIA_CALIFICACION >= 6 && $D_INTERFERENCIA_CALIFICACION < 8   ? 3 :($D_INTERFERENCIA_CALIFICACION >= 8 && $D_INTERFERENCIA_CALIFICACION < 10 ? 4 :($D_INTERFERENCIA_CALIFICACION >= 10 ? 5 : 0)))));
                    $C_ORGANIZACION_NIVEL =  ( $C_ORGANIZACION_CALIFICACION < 5 ? 1 : ($C_ORGANIZACION_CALIFICACION >= 5 && $C_ORGANIZACION_CALIFICACION < 7   ? 2 : ($C_ORGANIZACION_CALIFICACION >= 7 && $C_ORGANIZACION_CALIFICACION < 10   ? 3 :($C_ORGANIZACION_CALIFICACION >= 10 && $C_ORGANIZACION_CALIFICACION < 13 ? 4 :($C_ORGANIZACION_CALIFICACION >= 13 ? 5 : 0)))));

                    //seccion 4 de guia 3
                    $D_LIDERAZGO_CALIFICACION = getValue($arrayCalificacionesGuia3, 30) + getValue($arrayCalificacionesGuia3, 31) + getValue($arrayCalificacionesGuia3, 32) + getValue($arrayCalificacionesGuia3, 33) + getValue($arrayCalificacionesGuia3, 36) + getValue($arrayCalificacionesGuia3, 37) + getValue($arrayCalificacionesGuia3, 38) + getValue($arrayCalificacionesGuia3, 39) + getValue($arrayCalificacionesGuia3, 40);
                    $D_RELACIONES_CALIFICACION = getValue($arrayCalificacionesGuia3, 41) + getValue($arrayCalificacionesGuia3, 42) + getValue($arrayCalificacionesGuia3, 43) + getValue($arrayCalificacionesGuia3, 44) + getValue($arrayCalificacionesGuia3, 45) + getValue($arrayCalificacionesGuia3, 68) + getValue($arrayCalificacionesGuia3, 69) + getValue($arrayCalificacionesGuia3, 70) + getValue($arrayCalificacionesGuia3, 71);
                    $D_VIOLENCIA_CALIFICACION = getValue($arrayCalificacionesGuia3, 56) + getValue($arrayCalificacionesGuia3, 57) + getValue($arrayCalificacionesGuia3, 58) + getValue($arrayCalificacionesGuia3, 59) + getValue($arrayCalificacionesGuia3, 60) + getValue($arrayCalificacionesGuia3, 61) + getValue($arrayCalificacionesGuia3, 62) + getValue($arrayCalificacionesGuia3, 63);
                    $C_LIDERAZGO_CALIFICACION = $D_LIDERAZGO_CALIFICACION + $D_RELACIONES_CALIFICACION + $D_VIOLENCIA_CALIFICACION;


                    $D_LIDERAZGO_NIVEL =  ( $D_LIDERAZGO_CALIFICACION < 9 ? 1 : ($D_LIDERAZGO_CALIFICACION >= 9 && $D_LIDERAZGO_CALIFICACION < 12   ? 2 : ($D_LIDERAZGO_CALIFICACION >= 12 && $D_LIDERAZGO_CALIFICACION < 16   ? 3 :($D_LIDERAZGO_CALIFICACION >= 16 && $D_LIDERAZGO_CALIFICACION < 20 ? 4 :($D_LIDERAZGO_CALIFICACION >= 20 ? 5 : 0)))));
                    $D_RELACIONES_NIVEL =  ( $D_RELACIONES_CALIFICACION < 10 ? 1 : ($D_RELACIONES_CALIFICACION >= 10 && $D_RELACIONES_CALIFICACION < 13   ? 2 : ($D_RELACIONES_CALIFICACION >= 13 && $D_RELACIONES_CALIFICACION < 17   ? 3 :($D_RELACIONES_CALIFICACION >= 17 && $D_RELACIONES_CALIFICACION < 21 ? 4 :($D_RELACIONES_CALIFICACION >= 21 ? 5 : 0)))));
                    $D_VIOLENCIA_NIVEL =  ( $D_VIOLENCIA_CALIFICACION < 7 ? 1 : ($D_VIOLENCIA_CALIFICACION >= 7 && $D_VIOLENCIA_CALIFICACION < 10   ? 2 : ($D_VIOLENCIA_CALIFICACION >= 10 && $D_VIOLENCIA_CALIFICACION < 13   ? 3 :($D_VIOLENCIA_CALIFICACION >= 13 && $D_VIOLENCIA_CALIFICACION < 16 ? 4 :($D_VIOLENCIA_CALIFICACION >= 16 ? 5 : 0)))));
                    $C_LIDERAZGO_NIVEL =  ( $C_LIDERAZGO_CALIFICACION < 14 ? 1 : ($C_LIDERAZGO_CALIFICACION >= 14 && $C_LIDERAZGO_CALIFICACION < 29   ? 2 : ($C_LIDERAZGO_CALIFICACION >= 29 && $C_LIDERAZGO_CALIFICACION < 42   ? 3 :($C_LIDERAZGO_CALIFICACION >= 42 && $C_LIDERAZGO_CALIFICACION < 58 ? 4 :($C_LIDERAZGO_CALIFICACION >= 58 ? 5 : 0)))));

                    //seccion 5 de guia 3
                    $D_RECONOCIMIENTO_CALIFICACION = getValue($arrayCalificacionesGuia3, 46) + getValue($arrayCalificacionesGuia3, 47) + getValue($arrayCalificacionesGuia3, 48) + getValue($arrayCalificacionesGuia3, 49) + getValue($arrayCalificacionesGuia3, 50) + getValue($arrayCalificacionesGuia3, 51);
                    $D_INSUFICIENTE_CALIFICACION = getValue($arrayCalificacionesGuia3, 54) + getValue($arrayCalificacionesGuia3, 55) + getValue($arrayCalificacionesGuia3, 52) + getValue($arrayCalificacionesGuia3, 53);
                    $C_ENTORNO_CALIFICACION = $D_RECONOCIMIENTO_CALIFICACION + $D_INSUFICIENTE_CALIFICACION;
                    

                    $D_RECONOCIMIENTO_NIVEL =  ( $D_RECONOCIMIENTO_CALIFICACION < 6 ? 1 : ($D_RECONOCIMIENTO_CALIFICACION >= 6 && $D_RECONOCIMIENTO_CALIFICACION < 10   ? 2 : ($D_RECONOCIMIENTO_CALIFICACION >= 10 && $D_RECONOCIMIENTO_CALIFICACION < 14   ? 3 :($D_RECONOCIMIENTO_CALIFICACION >= 14 && $D_RECONOCIMIENTO_CALIFICACION < 18 ? 4 :($D_RECONOCIMIENTO_CALIFICACION >= 18 ? 5 : 0)))));
                    $D_INSUFICIENTE_NIVEL =  ( $D_INSUFICIENTE_CALIFICACION < 4 ? 1 : ($D_INSUFICIENTE_CALIFICACION >= 4 && $D_INSUFICIENTE_CALIFICACION < 6   ? 2 : ($D_INSUFICIENTE_CALIFICACION >= 6 && $D_INSUFICIENTE_CALIFICACION < 8   ? 3 :($D_INSUFICIENTE_CALIFICACION >= 8 && $D_INSUFICIENTE_CALIFICACION < 10 ? 4 :($D_INSUFICIENTE_CALIFICACION >= 10 ? 5 : 0)))));
                    $C_ENTORNO_NIVEL =  ( $C_ENTORNO_CALIFICACION < 10 ? 1 : ($C_ENTORNO_CALIFICACION >= 10 && $C_ENTORNO_CALIFICACION < 14   ? 2 : ($C_ENTORNO_CALIFICACION >= 14 && $C_ENTORNO_CALIFICACION < 18   ? 3 :($C_ENTORNO_CALIFICACION >= 18 && $C_ENTORNO_CALIFICACION < 23 ? 4 :($C_ENTORNO_CALIFICACION >= 23 ? 5 : 0)))));

                    //calif y nivel global + suma de todo los item
                   
                    $GLOBAL_CALIFICACION = sumarItems($arrayCalificacionesGuia3);
                    $GLOBAL_NIVEL =  ( $GLOBAL_CALIFICACION < 50 ? 1 : ($GLOBAL_CALIFICACION >= 50 && $GLOBAL_CALIFICACION < 75   ? 2 : ($GLOBAL_CALIFICACION >= 75 && $GLOBAL_CALIFICACION < 99   ? 3 :($GLOBAL_CALIFICACION >= 99 && $GLOBAL_CALIFICACION < 140 ? 4 :($GLOBAL_CALIFICACION >= 140 ? 5 : 0)))));

                    reporte_calificacionesModel::create([
                        'proyecto_id' => $proyecto_id,
                        'TRABAJADOR_ID' => $trabajador->RECPSICO_TRABAJADOR,
                        'ACONTECIMIENTO_CALIFICACION' => $ACONTECIMIENTO_CALIFICACION,
                        'RECUERDOS_CALIFICACION' => $RECUERDOS_CALIFICACION,
                        'ESFUERZO_CALIFICACION' => $ESFUERZO_CALIFICACION,
                        'AFECTACION_CALIFICACION' => $AFECTACION_CALIFICACION,
                        'GUIA1_CALIFICACION' => $GUIA1_CALIFICACION,
                        'C_AMBIENTE_CALIFICACION' => $C_AMBIENTE_CALIFICACION,
                        'D_CONDICIONES_CALIFICACION' => $D_CONDICIONES_CALIFICACION,
                        'C_AMBIENTE_NIVEL' => $C_AMBIENTE_NIVEL,
                        'D_CONDICIONES_NIVEL' => $D_CONDICIONES_NIVEL,
                        'C_FACTORES_CALIFICACION' => $C_FACTORES_CALIFICACION,
                        'D_CARGA_CALIFICACION' => $D_CARGA_CALIFICACION,
                        'D_FALTA_CALIFICACION' => $D_FALTA_CALIFICACION,
                        'C_FACTORES_NIVEL' => $C_FACTORES_NIVEL,
                        'D_CARGA_NIVEL' => $D_CARGA_NIVEL,
                        'D_FALTA_NIVEL' => $D_FALTA_NIVEL,
                        'C_FALTA_CALIFICACION' => null,
                        'C_FALTA_NIVEL' => null,
                        'C_ORGANIZACION_CALIFICACION' => $D_JORNADA_CALIFICACION,
                        'D_JORNADA_CALIFICACION' => $D_JORNADA_CALIFICACION,
                        'D_INTERFERENCIA_CALIFICACION' => $D_INTERFERENCIA_CALIFICACION,
                        'C_ORGANIZACION_NIVEL' => $C_ORGANIZACION_NIVEL,
                        'D_JORNADA_NIVEL' => $D_JORNADA_NIVEL,
                        'D_INTERFERENCIA_NIVEL' => $D_INTERFERENCIA_NIVEL,
                        'C_LIDERAZGO_CALIFICACION' => $C_LIDERAZGO_CALIFICACION,
                        'D_LIDERAZGO_CALIFICACION' => $D_LIDERAZGO_CALIFICACION,
                        'D_RELACIONES_CALIFICACION' => $D_RELACIONES_CALIFICACION,
                        'D_VIOLENCIA_CALIFICACION' => $D_VIOLENCIA_CALIFICACION,
                        'C_LIDERAZGO_NIVEL' => $C_LIDERAZGO_NIVEL,
                        'D_LIDERAZGO_NIVEL' => $D_LIDERAZGO_NIVEL,
                        'D_RELACIONES_NIVEL' => $D_RELACIONES_NIVEL,
                        'D_VIOLENCIA_NIVEL' => $D_VIOLENCIA_NIVEL,
                        'C_ENTORNO_CALIFICACION' => $C_ENTORNO_CALIFICACION,
                        'D_RECONOCIMIENTO_CALIFICACION' => $D_RECONOCIMIENTO_CALIFICACION,
                        'D_INSUFICIENTE_CALIFICACION' => $D_INSUFICIENTE_CALIFICACION,
                        'C_ENTORNO_NIVEL' => $C_ENTORNO_NIVEL,
                        'D_RECONOCIMIENTO_NIVEL' => $D_RECONOCIMIENTO_NIVEL,
                        'D_INSUFICIENTE_NIVEL' => $D_INSUFICIENTE_NIVEL,
                        'GLOBAL_CALIFICACION' => $GLOBAL_CALIFICACION,
                        'GLOBAL_NIVEL' => $GLOBAL_NIVEL,
                    ]);
                }
            }
            //CATEGORIAS POE
            //-------------------------------------


            $categorias = DB::select('SELECT
                                            reportenom0353categoria.proyecto_id, 
                                            reportenom0353categoria.registro_id, 
                                            reportenom0353categoria.id, 
                                            reportenom0353categoria.reportenom0353categoria_nombre, 
                                            reportenom0353categoria.reportenom0353categoria_total
                                        FROM
                                            reportenom0353categoria
                                        WHERE
                                            reportenom0353categoria.proyecto_id = ' . $proyecto_id . ' 
                                        ORDER BY
                                            reportenom0353categoria.reportenom0353categoria_nombre ASC');


            if (count($categorias) > 0) {
                $categorias_poe = 0; // NO TIENE POE GENERAL
            } else {
                $categorias_poe = 1; // TIENE POE GENERAL
            }


            // AREAS POE
            //-------------------------------------

            $areas = DB::select('SELECT
                                    reportenom0353area.proyecto_id, 
                                    reportenom0353area.registro_id, 
                                    reportenom0353area.id, 
                                    reportenom0353area.reportenom0353area_instalacion,
                                    reportenom0353area.reportenom0353area_nombre, 
                                    reportenom0353area.reportenom0353area_numorden, 
                                    reportenom0353area.reportenom0353area_porcientooperacion
                                FROM
                                    reportenom0353area
                                WHERE
                                    reportenom0353area.proyecto_id = ' . $proyecto_id . ' 
                                ORDER BY
                                    reportenom0353area.reportenom0353area_numorden ASC,
                                    reportenom0353area.reportenom0353area_nombre ASC');


            if (count($areas) > 0) {
                $areas_poe = 0; // NO TIENE POE GENERAL
            } else {
                $areas_poe = 1; // TIENE POE GENERAL
            }


            $recsensorial = reconocimientopsicoModel::findOrFail($proyecto->reconocimiento_psico_id);

            // Catalogos
            $catregion = catregionModel::get();
            $catsubdireccion = catsubdireccionModel::orderBy('catsubdireccion_nombre', 'ASC')->get();
            $catgerencia = catgerenciaModel::orderBy('catgerencia_nombre', 'ASC')->get();
            $catactivo = catactivoModel::orderBy('catactivo_nombre', 'ASC')->get();
            $catConclusiones = catConclusionesModel::where('ACTIVO', 1)->get();


            // Vista
            return view('reportes.psico.reportenom035guia3', compact('proyecto', 'recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'categorias_poe', 'areas_poe', 'catConclusiones'));
        }
    }

      /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $agente_id
     * @param  $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reportepsico3datosgenerales($proyecto_id, $agente_id, $agente_nombre)
    {
        try {
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = reconocimientopsicoModel::findOrFail($proyecto->reconocimiento_psico_id);
            $recoHigiene = recsensorialModel::findOrFail($proyecto->recsensorial_id);
            $respuestasTrabajadores = respuestastrabajadorespsicoModel::where('RECPSICO_ID', $proyecto->reconocimiento_psico_id)->get();


            $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $proyectofecha = explode("-", $proyecto->proyecto_fechaentrega);

            $reportecatalogo = reportenom0353catalogoModel::limit(1)->get();
            $reporte  = reportenom0353Model::where('proyecto_id', $proyecto_id)
                ->orderBy('reportenom0353_revision', 'DESC')
                ->limit(1)
                ->get();


            if (count($reporte) > 0) {
                $reporte = $reporte[0];
                $dato['reporteregistro_id'] = $reporte->id;
            } else {
                if (($recsensorial->tipocliente + 0) == 1) // 1 = Pemex, 0 = cliente
                {
                    // $reporte = reportenom0353Model::where('catactivo_id', $proyecto->catactivo_id)
                    //     ->orderBy('proyecto_id', 'DESC')
                    //     ->orderBy('reporteruido_revision', 'DESC')
                    //     ->limit(1)
                    //     ->get();
                    $reporte = DB::select('SELECT
                        reconocimientopsico.tipocliente,
                        reconocimientopsico.cliente_id,
                        reportenom0353.id,
                        reportenom0353.proyecto_id,
                        reportenom0353.agente_id,
                        reportenom0353.agente_nombre,
                        reportenom0353.reportenom0353_revision,
                        reportenom0353.reportenom0353_fecha,
                        reportenom0353.reporte_mes,
                        reportenom0353.reportenom0353_instalacion,
                        reportenom0353.reportenom0353_catregion_activo,
                        reportenom0353.reportenom0353_catsubdireccion_activo,
                        reportenom0353.reportenom0353_catgerencia_activo,
                        reportenom0353.reportenom0353_catactivo_activo,
                        reportenom0353.reportenom0353_introduccion,
                        reportenom0353.reportenom0353_objetivogeneral,
                        reportenom0353.reportenom0353_objetivoespecifico,
                        reportenom0353.reportenom0353_metodologiainstrumentos,
                        reportenom0353.reportenom0353_ubicacioninstalacion,
                        reportenom0353.reportenom0353_ubicacionfoto,
                        reportenom0353.reportenom0353_procesoinstalacion,
                        reportenom0353.reportenom0353_metodoevaluacion,
                        reportenom0353.reportenom0353_datosdemograficos,
                        reportenom0353.reportenom0353_interpretacion1,
                        reportenom0353.reportenom0353_interpretacion2,
                        reportenom0353.reportenom0353_interpretacion3,
                        reportenom0353.reportenom0353_interpretacion4,
                        reportenom0353.reportenom0353_interpretacion5,
                        reportenom0353.reportenom0353_interpretacion6,
                        reportenom0353.reportenom0353_interpretacion7,
                        reportenom0353.reportenom0353_conclusion,
                        reportenom0353.reportenom0353_responsable1,
                        reportenom0353.reportenom0353_responsable1cargo,
                        reportenom0353.reportenom0353_responsable1documento,
                        reportenom0353.reportenom0353_responsable2,
                        reportenom0353.reportenom0353_responsable2cargo,
                        reportenom0353.reportenom0353_responsable2doc,
                        reportenom0353.reportenom0353_concluido,
                        reportenom0353.reportenom0353_concluidonombre,
                        reportenom0353.reportenom0353_concluidofecha,
                        reportenom0353.reportenom0353_cancelado,
                        reportenom0353.reportenom0353_canceladonombre,
                        reportenom0353.reportenom0353_canceladofecha,
                        reportenom0353.reportenom0353_canceladoobservacion,
                        reportenom0353.created_at,
                        reportenom0353.updated_at
                    FROM
                        reconocimientopsico
                        LEFT JOIN proyecto ON reconocimientopsico.id = proyecto.reconocimiento_psico_id
                        LEFT JOIN reportenom0353 ON proyecto.id = reportenom0353.proyecto_id 
                    WHERE
                        reconocimientopsico.cliente_id = ' . $recsensorial->cliente_id . ' 
                        AND reportenom0353.reportenom0353_instalacion <> "" 
                    ORDER BY
                        reportenom0353.updated_at DESC');
                } else {
                    $reporte = DB::select('SELECT
                    reconocimientopsico.tipocliente,
                    reconocimientopsico.cliente_id,
                    reportenom0353.id,
                    reportenom0353.proyecto_id,
                    reportenom0353.agente_id,
                    reportenom0353.agente_nombre,
                    reportenom0353.reportenom0353_revision,
                    reportenom0353.reportenom0353_fecha,
                    reportenom0353.reporte_mes,
                    reportenom0353.reportenom0353_instalacion,
                    reportenom0353.reportenom0353_catregion_activo,
                    reportenom0353.reportenom0353_catsubdireccion_activo,
                    reportenom0353.reportenom0353_catgerencia_activo,
                    reportenom0353.reportenom0353_catactivo_activo,
                    reportenom0353.reportenom0353_introduccion,
                    reportenom0353.reportenom0353_objetivogeneral,
                    reportenom0353.reportenom0353_objetivoespecifico,
                    reportenom0353.reportenom0353_metodologiainstrumentos,
                    reportenom0353.reportenom0353_ubicacioninstalacion,
                    reportenom0353.reportenom0353_ubicacionfoto,
                    reportenom0353.reportenom0353_procesoinstalacion,
                    reportenom0353.reportenom0353_metodoevaluacion,
                    reportenom0353.reportenom0353_datosdemograficos,
                    reportenom0353.reportenom0353_interpretacion1,
                    reportenom0353.reportenom0353_interpretacion2,
                    reportenom0353.reportenom0353_interpretacion3,
                    reportenom0353.reportenom0353_interpretacion4,
                    reportenom0353.reportenom0353_interpretacion5,
                    reportenom0353.reportenom0353_interpretacion6,
                    reportenom0353.reportenom0353_interpretacion7,
                    reportenom0353.reportenom0353_conclusion,
                    reportenom0353.reportenom0353_responsable1,
                    reportenom0353.reportenom0353_responsable1cargo,
                    reportenom0353.reportenom0353_responsable1documento,
                    reportenom0353.reportenom0353_responsable2,
                    reportenom0353.reportenom0353_responsable2cargo,
                    reportenom0353.reportenom0353_responsable2doc,
                    reportenom0353.reportenom0353_concluido,
                    reportenom0353.reportenom0353_concluidonombre,
                    reportenom0353.reportenom0353_concluidofecha,
                    reportenom0353.reportenom0353_cancelado,
                    reportenom0353.reportenom0353_canceladonombre,
                    reportenom0353.reportenom0353_canceladofecha,
                    reportenom0353.reportenom0353_canceladoobservacion,
                    reportenom0353.created_at,
                    reportenom0353.updated_at
                FROM
                    reconocimientopsico
                    LEFT JOIN proyecto ON reconocimientopsico.id = proyecto.reconocimiento_psico_id
                    LEFT JOIN reportenom0353 ON proyecto.id = reportenom0353.proyecto_id 
                WHERE
                    reconocimientopsico.cliente_id = ' . $recsensorial->cliente_id . ' 
                    AND reportenom0353.reportenom0353_instalacion <> "" 
                ORDER BY
                    reportenom0353.updated_at DESC');
                }
                if (count($reporte) > 0) {
                    $reporte = $reporte[0];
                    $dato['reporteregistro_id'] = 0;
                } else {
                    $reporte = array(0, 0);
                    $dato['reporteregistro_id'] = -1;
                }
            }

            //------------------------------

            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
            ->where('agente_id', 353) //nom0353
            ->orderBy('reporterevisiones_revision', 'DESC')
            ->get();


            if (count($revision) > 0) {
                $revision = reporterevisionesModel::findOrFail($revision[0]->id);


                $dato['reportenom0353_concluido'] = $revision->reporterevisiones_concluido;
                $dato['reportenom0353_cancelado'] = $revision->reporterevisiones_cancelado;
            } else {
                $dato['reportenom0353_concluido'] = 0;
                $dato['reportenom0353_cancelado'] = 0;
            }


    

            // PORTADA
            //===================================================

            $dato['tipocliente'] = ($recsensorial->tipocliente + 0);


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportenom0353_fecha != NULL && $reporte->proyecto_id == $proyecto_id) {
                $reportefecha = $reporte->reportenom0353_fecha;
                $dato['reporte_portada_guardado'] = 1;

                $dato['reporte_portada'] = array(
                    'reporte_catregion_activo' => $reporte->reportenom0353_catregion_activo,
                    'catregion_id' => $proyecto->catregion_id,
                    'reporte_catsubdireccion_activo' => $reporte->reportenom0353_catsubdireccion_activo,
                    'catsubdireccion_id' => $proyecto->catsubdireccion_id,
                    'reporte_catgerencia_activo' => $reporte->reportenom0353_catgerencia_activo,
                    'catgerencia_id' => $proyecto->catgerencia_id,
                    'reporte_catactivo_activo' => $reporte->reportenom0353_catactivo_activo,
                    'catactivo_id' => $proyecto->catactivo_id,
                    'reporte_instalacion' => $proyecto->proyecto_clienteinstalacion,
                    'reporte_fecha' => $reportefecha,
                    'reporte_mes' => $reporte->reporte_mes
                );
            } else {
                $reportefecha = $meses[$proyectofecha[1] + 0] . " del " . $proyectofecha[0];
                $dato['reporte_portada_guardado'] = 0;

                $dato['reporte_portada'] = array(
                    'reporte_catregion_activo' => 1,
                    'catregion_id' => $proyecto->catregion_id,
                    'reporte_catsubdireccion_activo' => 1,
                    'catsubdireccion_id' => $proyecto->catsubdireccion_id,
                    'reporte_catgerencia_activo' => 1,
                    'catgerencia_id' => $proyecto->catgerencia_id,
                    'reporte_catactivo_activo' => 1,
                    'catactivo_id' => $proyecto->catactivo_id,
                    'reporte_instalacion' => $proyecto->proyecto_clienteinstalacion,
                    'reporte_fecha' => $reportefecha,
                    'reporte_mes' => ""
                );
            }


            // INTRODUCCION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportenom0353_introduccion != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_introduccion_guardado'] = 1;
                } else {
                    $dato['reporte_introduccion_guardado'] = 0;
                }

                $introduccion = $reporte->reportenom0353_introduccion;
            } else {
                $dato['reporte_introduccion_guardado'] = 0;
               
                    $introduccion = $reportecatalogo[0]->reportenom0353catalogo_introduccion;
            }

            $dato['reporte_introduccion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $introduccion);



            // OBJETIVO GENERAL
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportenom0353_objetivogeneral != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_objetivogeneral_guardado'] = 1;
                } else {
                    $dato['reporte_objetivogeneral_guardado'] = 0;
                }

                $objetivogeneral = $reporte->reportenom0353_objetivogeneral;
            } else {
                $dato['reporte_objetivogeneral_guardado'] = 0;
                $objetivogeneral = $reportecatalogo[0]->reportenom0353catalogo_objetivogeneral;
            }

            $dato['reporte_objetivogeneral'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivogeneral);

            // OBJETIVOS ESPECIFICOS
            //===================================================

            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportenom0353_objetivoespecifico != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_objetivoespecifico_guardado'] = 1;
                } else {
                    $dato['reporte_objetivoespecifico_guardado'] = 0;
                }

                $objetivoespecifico = $reporte->reportenom0353_objetivoespecifico;
            } else {
                $dato['reporte_objetivoespecifico_guardado'] = 0;
                $objetivoespecifico = $reportecatalogo[0]->reportenom0353catalogo_objetivoespecifico;
            }

            $dato['reporte_objetivoespecifico'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivoespecifico);


            // METODOLOGIA PUNTO 4.1 instrumentoos
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportenom0353_metodologiainstrumentos != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_metodologia_4_1_guardado'] = 1;
                } else {
                    $dato['reporte_metodologia_4_1_guardado'] = 0;
                }

                $metodologia_4_1 = $reporte->reportenom0353_metodologiainstrumentos;
            } else {
                $dato['reporte_metodologia_4_1_guardado'] = 0;
                $metodologia_4_1 = $reportecatalogo[0]->reportenom0353catalogo_metodologia;
            }

            $dato['reporte_metodologia_4_1'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_1);



            // UBICACION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportenom0353_ubicacioninstalacion != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_ubicacioninstalacion_guardado'] = 1;
                } else {
                    $dato['reporte_ubicacioninstalacion_guardado'] = 0;
                }

                $ubicacion = $reporte->reportenom0353_ubicacioninstalacion;
            } else {
                $dato['reporte_ubicacioninstalacion_guardado'] = 0;
                $ubicacion = $reportecatalogo[0]->reportenom0353catalogo_ubicacioninstalacion;
            }


            $ubicacionfoto = NULL;
            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportenom0353_ubicacionfoto != NULL && $reporte->proyecto_id == $proyecto_id) {
                $ubicacionfoto = $reporte->reportenom0353_ubicacionfoto;
            }

            $dato['reporte_ubicacioninstalacion'] = array(
                'ubicacion' => $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $ubicacion),
                'ubicacionfoto' => $ubicacionfoto
            );


            // PROCESO INSTALACION
            //===================================================

            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportenom0353_procesoinstalacion != NULL && $reporte->proyecto_id == $proyecto_id) {
                $dato['reporte_procesoinstalacion_guardado'] = 1;
                $procesoinstalacion = $reporte->reportenom0353_procesoinstalacion;
            }else if ($dato['reporteregistro_id'] >= 0 && $recoHigiene->recsensorial_descripcionproceso != NULL) {
                $dato['reporte_procesoinstalacion_guardado'] = 0;
                $procesoinstalacion = $recoHigiene->recsensorial_descripcionproceso;
            } else {
                $dato['reporte_procesoinstalacion_guardado'] = 0;
                $procesoinstalacion = $recsensorial->descripcionproceso;
            }

            $dato['reporte_procesoinstalacion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $procesoinstalacion);


            // ACTIVIDAD PRINCIPAL
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportenom0353_actividadprincipal != NULL && $reporte->proyecto_id == $proyecto_id) {
                $procesoinstalacion = $reporte->reportenom0353_actividadprincipal;
            } else {
                $procesoinstalacion = $recsensorial->actividadprincipal;
            }

            $dato['reporte_actividadprincipal'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $procesoinstalacion);


           // MÉTODO DE EVALUACIÓN
            // ===================================================

            $tipoAplicacion = 0; // 1 es Online, 2 es Offline (Presencial), y 3 es Mixta

            // MODELO DE LA MODALIDAD DE LOS TRABAJADORES
            $proyectoTrabajadores = proyectotrabajadoresModel::where('proyecto_id', $proyecto_id)->get();

            if ($dato['reporteregistro_id'] >= 0 && 
                $reporte->reportenom0353_metodoevaluacion != NULL && 
                $reporte->proyecto_id == $proyecto_id) {
                
                // Método de evaluación ya guardado previamente
                $dato['reporte_metodoevaluacion_guardado'] = 1;
                $metodoevaluacion = $reporte->reportenom0353_metodoevaluacion;
                
            } else {
                $dato['reporte_metodoevaluacion_guardado'] = 0;

                // Obtener las modalidades de los trabajadores
                $modalidades = $proyectoTrabajadores->pluck('TRABAJADOR_MODALIDAD')->unique();

                // Determinar el tipo de aplicación basado en las modalidades
                if ($modalidades->count() === 1) {
                    $tipoAplicacion = $modalidades->first() === 'Online' ? 1 : 2; // 1 para Online, 2 para Presencial
                } else {
                    $tipoAplicacion = 3; // Mixta
                }

                // Asignar el método de evaluación según el tipo de aplicación
                switch ($tipoAplicacion) {
                    case 1: // Online
                        $metodoevaluacion = $reportecatalogo[0]->reportenom0353catalogo_aplicaciononline;
                        break;
                    case 2: // Presencial
                        $metodoevaluacion = $reportecatalogo[0]->reportenom0353catalogo_aplicacionoffline;
                        break;
                    case 3: // Mixta
                        $metodoevaluacion = $reportecatalogo[0]->reportenom0353catalogo_aplicacionmixta;
                        break;
                    default:
                        $metodoevaluacion = null;
                        break;
                }
            }

            // Reemplazar texto en el reporte con los datos del proyecto
            $dato['reporte_metodoevaluacion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodoevaluacion);


            // CONCLUSION
            //===================================================

            $acontecimiento = 0;
            $totalTrabajadores = count($respuestasTrabajadores);
            $cantAcontecimientos = 0;
            $cantSinAcontecimientos = 0;

            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportenom0353_conclusiones != NULL && $reporte->proyecto_id == $proyecto_id) {
                $dato['reporte_conclusion_guardado'] = 1;
                    $conclusionesJson = json_decode($reporte->reportenom0353_conclusiones, true);

                    // Asignar valores desde el JSON a los campos correspondientes
                    $dato['reporte_acontecimientos_conclusiones'] = $conclusionesJson['acontecimientos_traumaticos'] ?? null;
                    $dato['reporte_ambiente_conclusiones'] = $conclusionesJson['ambiente_trabajo'] ?? null;
                    $dato['reporte_condiciones_conclusiones'] = $conclusionesJson['condiciones_ambiente'] ?? null;
                    $dato['reporte_factores_conclusiones'] = $conclusionesJson['factores_actividad'] ?? null;
                    $dato['reporte_carga_conclusiones'] = $conclusionesJson['carga_trabajo'] ?? null;
                    $dato['reporte_falta_conclusiones'] = $conclusionesJson['falta_control'] ?? null;
                    $dato['reporte_organizacion_conclusiones'] = $conclusionesJson['organizacion_tiempo'] ?? null;
                    $dato['reporte_jornada_conclusiones'] = $conclusionesJson['jornada_trabajo'] ?? null;
                    $dato['reporte_interferencia_conclusiones'] = $conclusionesJson['interferencia_trabajo_familia'] ?? null;
                    $dato['reporte_liderazgorelaciones_conclusiones'] = $conclusionesJson['liderazgo_relaciones'] ?? null;
                    $dato['reporte_liderazgo_conclusiones'] = $conclusionesJson['liderazgo'] ?? null;
                    $dato['reporte_relaciones_conclusiones'] = $conclusionesJson['relaciones_trabajo'] ?? null;
                    $dato['reporte_violencia_conclusiones'] = $conclusionesJson['violencia'] ?? null;
                    $dato['reporte_entorno_conclusiones'] = $conclusionesJson['entorno_organizacional'] ?? null;
                    $dato['reporte_reconocimiento_conclusiones'] = $conclusionesJson['reconocimiento_desempeno'] ?? null;
                    $dato['reporte_insuficiente_conclusiones'] = $conclusionesJson['insuficiente_pertenencia'] ?? null;
            } else {
                $dato['reporte_conclusion_guardado'] = 0;
                
                // Contar trabajadores con y sin acontecimientos
                foreach ($respuestasTrabajadores as $respuesta) {
                    $respuestasJson = json_decode($respuesta->RECPSICO_GUIAI_RESPUESTAS, true);
                    if (isset($respuestasJson[0]) && $respuestasJson[0] == "1") {
                        $cantAcontecimientos++;
                        $acontecimiento = 1;
                    } else {
                        $cantSinAcontecimientos++;
                    }
                }
                
                // Calcular porcentajes
                $porcentajeAcontecimientos = $totalTrabajadores > 0 ? 
                    round(($cantAcontecimientos / $totalTrabajadores) * 100, 2) : 0;
                $porcentajeSinAcontecimientos = $totalTrabajadores > 0 ? 
                    round(($cantSinAcontecimientos / $totalTrabajadores) * 100, 2) : 0;
                
                if($acontecimiento == 1) { //si hay acontecimiento traumático
                    $acontecimientotraumatico = DB::select('SELECT 
                                                            psicocat_conclusiones.CONCLUSION 
                                                        FROM 
                                                            psicocat_conclusiones 
                                                        WHERE 
                                                            psicocat_conclusiones.NIVEL = 6');
                    $dato['reporte_acontecimientos_conclusiones'] = "El " . $porcentajeAcontecimientos . "% (" . $cantAcontecimientos . ") " .
                                            $acontecimientotraumatico[0]->CONCLUSION;
                } else {
                    $acontecimientotraumatico = DB::select('SELECT 
                                                            psicocat_conclusiones.CONCLUSION 
                                                        FROM 
                                                            psicocat_conclusiones 
                                                        WHERE 
                                                            psicocat_conclusiones.NIVEL = 7');
                    $dato['reporte_acontecimientos_conclusiones'] = "El " . $porcentajeSinAcontecimientos . "% (" . $cantSinAcontecimientos . ") " .
                                            $acontecimientotraumatico[0]->CONCLUSION;
                }

                $dato['reporte_ambiente_conclusiones'] = "El 50% (1) de los trabajadores percibe como un riesgo alto, las condiciones del ambiente de trabajo son peligrosas, inseguras, deficientes o insalubres, lo cual puede generar o aumentar el nivel de estrés laboral y ansiedad.";
                $dato['reporte_condiciones_conclusiones'] = "El 50% (1) de los trabajadores percibe como un riesgo alto, las condiciones del ambiente de trabajo son peligrosas, inseguras, deficientes o insalubres, lo cual puede generar o aumentar el nivel de estrés laboral y ansiedad.";
                $dato['reporte_factores_conclusiones'] = "El 50% (1) de los trabajadores percibe como un riesgo alto, las condiciones del ambiente de trabajo son peligrosas, inseguras, deficientes o insalubres, lo cual puede generar o aumentar el nivel de estrés laboral y ansiedad.";
                $dato['reporte_carga_conclusiones'] = "El 50% (1) de los trabajadores percibe como un riesgo alto, las condiciones del ambiente de trabajo son peligrosas, inseguras, deficientes o insalubres, lo cual puede generar o aumentar el nivel de estrés laboral y ansiedad.";
                $dato['reporte_falta_conclusiones'] = "El 50% (1) de los trabajadores percibe como un riesgo alto, las condiciones del ambiente de trabajo son peligrosas, inseguras, deficientes o insalubres, lo cual puede generar o aumentar el nivel de estrés laboral y ansiedad.";
                $dato['reporte_organizacion_conclusiones'] = "El 50% (1) de los trabajadores percibe como un riesgo alto, las condiciones del ambiente de trabajo son peligrosas, inseguras, deficientes o insalubres, lo cual puede generar o aumentar el nivel de estrés laboral y ansiedad.";
                $dato['reporte_jornada_conclusiones'] = "El 50% (1) de los trabajadores percibe como un riesgo alto, las condiciones del ambiente de trabajo son peligrosas, inseguras, deficientes o insalubres, lo cual puede generar o aumentar el nivel de estrés laboral y ansiedad.";
                $dato['reporte_interferencia_conclusiones'] = "El 50% (1) de los trabajadores percibe como un riesgo alto, las condiciones del ambiente de trabajo son peligrosas, inseguras, deficientes o insalubres, lo cual puede generar o aumentar el nivel de estrés laboral y ansiedad.";
                $dato['reporte_liderazgorelaciones_conclusiones'] = "El 50% (1) de los trabajadores percibe como un riesgo alto, las condiciones del ambiente de trabajo son peligrosas, inseguras, deficientes o insalubres, lo cual puede generar o aumentar el nivel de estrés laboral y ansiedad.";
                $dato['reporte_liderazgo_conclusiones'] = "El 50% (1) de los trabajadores percibe como un riesgo alto, las condiciones del ambiente de trabajo son peligrosas, inseguras, deficientes o insalubres, lo cual puede generar o aumentar el nivel de estrés laboral y ansiedad.";
                $dato['reporte_relaciones_conclusiones'] = "El 50% (1) de los trabajadores percibe como un riesgo alto, las condiciones del ambiente de trabajo son peligrosas, inseguras, deficientes o insalubres, lo cual puede generar o aumentar el nivel de estrés laboral y ansiedad.";
                $dato['reporte_violencia_conclusiones'] = "El 50% (1) de los trabajadores percibe como un riesgo alto, las condiciones del ambiente de trabajo son peligrosas, inseguras, deficientes o insalubres, lo cual puede generar o aumentar el nivel de estrés laboral y ansiedad.";
                $dato['reporte_entorno_conclusiones'] = "El 50% (1) de los trabajadores percibe como un riesgo alto, las condiciones del ambiente de trabajo son peligrosas, inseguras, deficientes o insalubres, lo cual puede generar o aumentar el nivel de estrés laboral y ansiedad.";
                $dato['reporte_reconocimiento_conclusiones'] = "El 50% (1) de los trabajadores percibe como un riesgo alto, las condiciones del ambiente de trabajo son peligrosas, inseguras, deficientes o insalubres, lo cual puede generar o aumentar el nivel de estrés laboral y ansiedad.";
                $dato['reporte_insuficiente_conclusiones'] = "El 50% (1) de los trabajadores percibe como un riesgo alto, las condiciones del ambiente de trabajo son peligrosas, inseguras, deficientes o insalubres, lo cual puede generar o aumentar el nivel de estrés laboral y ansiedad.";
    
            }


            //AMBIENTE DE TRABAJO
            $ambiente = 0;
            $cantambiente = 0;

          

               // RECOMENDACIONES
            //===================================================

            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportenom0353_recomendaciones != NULL && $reporte->proyecto_id == $proyecto_id) {
                $dato['reporte_recomendacion_guardado'] = 1;
                 // Decodificar el JSON guardado
                $recomendacionesJson = json_decode($reporte->reportenom0353_recomendaciones, true);

                // Asignar valores desde el JSON a los campos correspondientes
                $dato['reporte_acontecimientos_recomendaciones'] = $recomendacionesJson['acontecimientos_traumaticos'] ?? null;
                $dato['reporte_ambiente_recomendaciones'] = $recomendacionesJson['ambiente_trabajo'] ?? null;
              
                $dato['reporte_factores_recomendaciones'] = $recomendacionesJson['factores_actividad'] ?? null;
              
                $dato['reporte_organizacion_recomendaciones'] = $recomendacionesJson['organizacion_tiempo'] ?? null;
               
                $dato['reporte_liderazgorelaciones_recomendaciones'] = $recomendacionesJson['liderazgo_relaciones'] ?? null;
              
                $dato['reporte_entorno_recomendaciones'] = $recomendacionesJson['entorno_organizacional'] ?? null;
              
            } else {
                $dato['reporte_recomendacion_guardado'] = 0;
              
                $dato['reporte_acontecimientos_recomendaciones'] =  "Realizar evaluaciones específicas y desarrollar estrategias para abordar las consecuencias de eventos traumáticos, priorizando la salud mental.";

                
            $dato['reporte_ambiente_recomendaciones'] = "Realizar una renovación integral del entorno laboral, integrando diseños innovadores que fomenten la seguridad y el bienestar.";
            $dato['reporte_factores_recomendaciones'] = "Diseñar un programa integral de intervención para reestructurar roles y responsabilidades, disminuyendo significativamente el estrés laboral.";
            $dato['reporte_organizacion_recomendaciones'] = "Revisar y reestructurar los turnos y horarios para minimizar las horas extras y garantizar suficientes períodos de descanso y días libres.";
            $dato['reporte_liderazgorelaciones_recomendaciones'] = "Reestructurar equipos y liderazgos para resolver conflictos crónicos y mejorar el ambiente laboral.";
            $dato['reporte_entorno_recomendaciones'] = "Transformar profundamente las políticas organizacionales para garantizar equidad y seguridad para todos.";

            }

            // RESPONSABLES DEL INFORME
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportenom0353_responsable1 != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_responsablesinforme_guardado'] = 1;
                } else {
                    $dato['reporte_responsablesinforme_guardado'] = 0;
                }

                $dato['reporte_responsablesinforme'] = array(
                    'responsable1' => $reporte->reportenom0353_responsable1,
                    'responsable1cargo' => $reporte->reportenom0353_responsable1cargo,
                    'responsable1documento' => $reporte->reportenom0353_responsable1documento,
                    'responsable2' => $reporte->reportenom0353_responsable2,
                    'responsable2cargo' => $reporte->reportenom0353_responsable2cargo,
                    'responsable2documento' => $reporte->reportenom0353_responsable2doc,
                    'proyecto_id' => $reporte->proyecto_id,
                    'registro_id' => $reporte->id
                );
            } else {
                $dato['reporte_responsablesinforme_guardado'] = 0;
                    $dato['reporte_responsablesinforme'] = array(
                        'responsable1' => $recsensorial->NOMBRE_TECNICO,
                        'responsable1cargo' => $recsensorial->CARGO_TECNICO,
                        'responsable1documento' => $recsensorial->TECNICO_DOC,
                        'responsable2' => $recsensorial->NOMBRE_CONTRATO,
                        'responsable2cargo' => $recsensorial->CARGO_CONTRATO,
                        'responsable2documento' => $recsensorial->CONTRATO_DOC,
                        'proyecto_id' => $reporte->proyecto_id,
                        'registro_id' => $recsensorial->id
                    );
              
            }


            // MEMORIA FOTOGRAFICA
            //===================================================



            $recoid = $proyecto->reconocimiento_psico_id;

            $memoriafotografica = DB::select("SELECT
                        -- Conteo total de fotopreguia (si no son NULL)
                        SUM(CASE WHEN recopsicoFotosTrabajadores.RECPSICO_FOTOPREGUIA IS NOT NULL THEN 1 ELSE 0 END) AS total_fotopreguia,
                        -- Conteo total de fotopostguia (si no son NULL)
                        SUM(CASE WHEN recopsicoFotosTrabajadores.RECPSICO_FOTOPOSTGUIA IS NOT NULL THEN 1 ELSE 0 END) AS total_fotopostguia,
                                -- Conteo total de fotopresencial (si no son NULL)
                        SUM(CASE WHEN recopsicoFotosTrabajadores.RECPSICO_FOTOPRESENCIAL IS NOT NULL THEN 1 ELSE 0 END) AS total_fotopresencial,
                        -- Conteo total de fotos entre ambos campos
                        SUM(
                            CASE WHEN recopsicoFotosTrabajadores.RECPSICO_FOTOPREGUIA IS NOT NULL THEN 1 ELSE 0 END +
                            CASE WHEN recopsicoFotosTrabajadores.RECPSICO_FOTOPOSTGUIA IS NOT NULL THEN 1 ELSE 0 END +
                            CASE WHEN recopsicoFotosTrabajadores.RECPSICO_FOTOPRESENCIAL IS NOT NULL THEN 1 ELSE 0 END 
                        ) AS total_fotos
                    FROM
                        recopsicoFotosTrabajadores
                    WHERE
                        recopsicoFotosTrabajadores.RECPSICO_ID = ?",
            [$recoid]);
                                   
            //count($memoriafotografica);
            

            if (count($memoriafotografica) > 0) {
                $dato['reporte_memoriafotografica_guardado'] = $memoriafotografica[0]->total_fotos;
            } else {
                $dato['reporte_memoriafotografica_guardado'] = 0;
            }


            // respuesta
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


       /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function generarMEL0353($proyecto_id)
    {
        try {

            //=============================================================== FUNCIONES GENERALES ===============================================================

            function pintarCelda($sheet, $columna, $fila, $nivel)
                {
                    $colores = [
                        'NULO' => '00B0F0', // Azul para nulo
                        'NO' => '00B0F0', // Azul para NO
                        'BAJO' => '92D050', // Verde limón
                        'MEDIO' => 'FFFF00', // Verde bandera
                        'ALTO' => 'ED7D31', // Rojo
                        'MUY ALTO' => 'FF0000', // Vino
                        'SI' => 'FF0000' // Vino
                    ];

                    $color = isset($colores[$nivel]) ? $colores[$nivel] : 'D3D3D3'; // Gris por defecto

                    $style = $sheet->getStyle($columna . $fila);
                    $style->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB($color);
                }

            //=============================================================== CARGAMOS EL EXCEL ===============================================================
            $ruta = storage_path('app/plantillas_reportes/proyecto_infomes/plantilla_mel_0353.xlsx');
            $spreadsheet = IOFactory::load($ruta);
            $HOJA_MEL = $spreadsheet->getSheet(0);


            //=============================================================== OBTENEMOS LOS DATOS GENERALES DEL PROYECTO ===============================================================
            $datosMEL = DB::select("SELECT
                                    IFNULL(recopsicoguia_5.RECPSICOTRABAJADOR_ID, 'NA') as TRABAJADOR_ID,
                                    IFNULL(proyectotrabajadores.TRABAJADOR_NOMBRE, 'NA') as TRABAJADOR_NOMBRE,
                                    IFNULL(recopsicoguia_5.RECPSICOTRABAJADOR_GENERO, 'NA') as RECPSICOTRABAJADOR_GENERO,
                                    IFNULL(recopsicoguia_5.RECPSICOTRABAJADOR_EDAD, 'NA') as RECPSICOTRABAJADOR_EDAD,
                                    IFNULL(recopsicoguia_5.RECPSICOTRABAJADOR_ESTADOCIVIL, 'NA') as RECPSICOTRABAJADOR_ESTADOCIVIL,
                                    IFNULL(recopsicoguia_5.RECPSICOTRABAJADOR_ESTUDIOS, 'NA') as RECPSICOTRABAJADOR_ESTUDIOS,
                                    IFNULL(recopsicoguia_5.RECPSICOTRABAJADOR_TIPOPUESTO, 'NA') as RECPSICOTRABAJADOR_TIPOPUESTO,
                                    'PENDIENTE' AS AREA,
                                    IFNULL(recopsicoguia_5.RECPSICOTRABAJADOR_TIPOCONTRATACION, 'NA') as RECPSICOTRABAJADOR_TIPOCONTRATACION,
                                    IFNULL(recopsicoguia_5.RECPSICOTRABAJADOR_TIPOJORNADA, 'NA') as RECPSICOTRABAJADOR_TIPOJORNADA,
                                    IFNULL(recopsicoguia_5.RECPSICOTRABAJADOR_ROTACIONTURNOS, 'NA') as RECPSICOTRABAJADOR_ROTACIONTURNOS,
                                    IFNULL(
                                            CASE 
                                                WHEN reporte_calificacionestrabajadornom035.GUIA1_CALIFICACION = 0 THEN 'NO'
                                                WHEN reporte_calificacionestrabajadornom035.GUIA1_CALIFICACION = 1 THEN 'SI'
                                                ELSE 'NA'
                                            END, 
                                            'NA'
                                        ) AS GUIA1_CALIFICACION,
                                    IFNULL(
                                            CASE 
                                                WHEN reporte_calificacionestrabajadornom035.GLOBAL_NIVEL = 1 THEN 'NULO'
                                                WHEN reporte_calificacionestrabajadornom035.GLOBAL_NIVEL = 2 THEN 'BAJO'
                                                WHEN reporte_calificacionestrabajadornom035.GLOBAL_NIVEL = 3 THEN 'MEDIO'
                                                WHEN reporte_calificacionestrabajadornom035.GLOBAL_NIVEL = 4 THEN 'ALTO'
                                                WHEN reporte_calificacionestrabajadornom035.GLOBAL_NIVEL = 5 THEN 'MUY ALTO'
                                                ELSE 'NA'
                                            END, 
                                            'NA'
                                        ) AS GLOBAL_NIVEL,
                                            IFNULL(
                                            CASE 
                                                WHEN reporte_calificacionestrabajadornom035.C_AMBIENTE_NIVEL = 1 THEN 'NULO'
                                                WHEN reporte_calificacionestrabajadornom035.C_AMBIENTE_NIVEL = 2 THEN 'BAJO'
                                                WHEN reporte_calificacionestrabajadornom035.C_AMBIENTE_NIVEL = 3 THEN 'MEDIO'
                                                WHEN reporte_calificacionestrabajadornom035.C_AMBIENTE_NIVEL = 4 THEN 'ALTO'
                                                WHEN reporte_calificacionestrabajadornom035.C_AMBIENTE_NIVEL = 5 THEN 'MUY ALTO'
                                                ELSE 'NA'
                                            END, 
                                            'NA'
                                        ) AS C_AMBIENTE_NIVEL,
                                            IFNULL(
                                            CASE 
                                                WHEN reporte_calificacionestrabajadornom035.D_CONDICIONES_NIVEL = 1 THEN 'NULO'
                                                WHEN reporte_calificacionestrabajadornom035.D_CONDICIONES_NIVEL = 2 THEN 'BAJO'
                                                WHEN reporte_calificacionestrabajadornom035.D_CONDICIONES_NIVEL = 3 THEN 'MEDIO'
                                                WHEN reporte_calificacionestrabajadornom035.D_CONDICIONES_NIVEL = 4 THEN 'ALTO'
                                                WHEN reporte_calificacionestrabajadornom035.D_CONDICIONES_NIVEL = 5 THEN 'MUY ALTO'
                                                ELSE 'NA'
                                            END, 
                                            'NA'
                                        ) AS D_CONDICIONES_NIVEL,
                                            IFNULL(
                                            CASE 
                                                WHEN reporte_calificacionestrabajadornom035.C_FACTORES_NIVEL = 1 THEN 'NULO'
                                                WHEN reporte_calificacionestrabajadornom035.C_FACTORES_NIVEL = 2 THEN 'BAJO'
                                                WHEN reporte_calificacionestrabajadornom035.C_FACTORES_NIVEL = 3 THEN 'MEDIO'
                                                WHEN reporte_calificacionestrabajadornom035.C_FACTORES_NIVEL = 4 THEN 'ALTO'
                                                WHEN reporte_calificacionestrabajadornom035.C_FACTORES_NIVEL = 5 THEN 'MUY ALTO'
                                                ELSE 'NA'
                                            END, 
                                            'NA'
                                        ) AS C_FACTORES_NIVEL,
                                            IFNULL(
                                            CASE 
                                                WHEN reporte_calificacionestrabajadornom035.D_CARGA_NIVEL = 1 THEN 'NULO'
                                                WHEN reporte_calificacionestrabajadornom035.D_CARGA_NIVEL = 2 THEN 'BAJO'
                                                WHEN reporte_calificacionestrabajadornom035.D_CARGA_NIVEL = 3 THEN 'MEDIO'
                                                WHEN reporte_calificacionestrabajadornom035.D_CARGA_NIVEL = 4 THEN 'ALTO'
                                                WHEN reporte_calificacionestrabajadornom035.D_CARGA_NIVEL = 5 THEN 'MUY ALTO'
                                                ELSE 'NA'
                                            END, 
                                            'NA'
                                        ) AS D_CARGA_NIVEL,
                                            IFNULL(
                                            CASE 
                                                WHEN reporte_calificacionestrabajadornom035.D_FALTA_NIVEL = 1 THEN 'NULO'
                                                WHEN reporte_calificacionestrabajadornom035.D_FALTA_NIVEL = 2 THEN 'BAJO'
                                                WHEN reporte_calificacionestrabajadornom035.D_FALTA_NIVEL = 3 THEN 'MEDIO'
                                                WHEN reporte_calificacionestrabajadornom035.D_FALTA_NIVEL = 4 THEN 'ALTO'
                                                WHEN reporte_calificacionestrabajadornom035.D_FALTA_NIVEL = 5 THEN 'MUY ALTO'
                                                ELSE 'NA'
                                            END, 
                                            'NA'
                                        ) AS D_FALTA_NIVEL,
                                            IFNULL(
                                            CASE 
                                                WHEN reporte_calificacionestrabajadornom035.C_ORGANIZACION_NIVEL = 1 THEN 'NULO'
                                                WHEN reporte_calificacionestrabajadornom035.C_ORGANIZACION_NIVEL = 2 THEN 'BAJO'
                                                WHEN reporte_calificacionestrabajadornom035.C_ORGANIZACION_NIVEL = 3 THEN 'MEDIO'
                                                WHEN reporte_calificacionestrabajadornom035.C_ORGANIZACION_NIVEL = 4 THEN 'ALTO'
                                                WHEN reporte_calificacionestrabajadornom035.C_ORGANIZACION_NIVEL = 5 THEN 'MUY ALTO'
                                                ELSE 'NA'
                                            END, 
                                            'NA'
                                        ) AS C_ORGANIZACION_NIVEL,
                                            IFNULL(
                                            CASE 
                                                WHEN reporte_calificacionestrabajadornom035.D_JORNADA_NIVEL = 1 THEN 'NULO'
                                                WHEN reporte_calificacionestrabajadornom035.D_JORNADA_NIVEL = 2 THEN 'BAJO'
                                                WHEN reporte_calificacionestrabajadornom035.D_JORNADA_NIVEL = 3 THEN 'MEDIO'
                                                WHEN reporte_calificacionestrabajadornom035.D_JORNADA_NIVEL = 4 THEN 'ALTO'
                                                WHEN reporte_calificacionestrabajadornom035.D_JORNADA_NIVEL = 5 THEN 'MUY ALTO'
                                                ELSE 'NA'
                                            END, 
                                            'NA'
                                        ) AS D_JORNADA_NIVEL,
                                            IFNULL(
                                            CASE 
                                                WHEN reporte_calificacionestrabajadornom035.D_INTERFERENCIA_NIVEL = 1 THEN 'NULO'
                                                WHEN reporte_calificacionestrabajadornom035.D_INTERFERENCIA_NIVEL = 2 THEN 'BAJO'
                                                WHEN reporte_calificacionestrabajadornom035.D_INTERFERENCIA_NIVEL = 3 THEN 'MEDIO'
                                                WHEN reporte_calificacionestrabajadornom035.D_INTERFERENCIA_NIVEL = 4 THEN 'ALTO'
                                                WHEN reporte_calificacionestrabajadornom035.D_INTERFERENCIA_NIVEL = 5 THEN 'MUY ALTO'
                                                ELSE 'NA'
                                            END, 
                                            'NA'
                                        ) AS D_INTERFERENCIA_NIVEL,
                                                    IFNULL(
                                            CASE 
                                                WHEN reporte_calificacionestrabajadornom035.C_LIDERAZGO_NIVEL = 1 THEN 'NULO'
                                                WHEN reporte_calificacionestrabajadornom035.C_LIDERAZGO_NIVEL = 2 THEN 'BAJO'
                                                WHEN reporte_calificacionestrabajadornom035.C_LIDERAZGO_NIVEL = 3 THEN 'MEDIO'
                                                WHEN reporte_calificacionestrabajadornom035.C_LIDERAZGO_NIVEL = 4 THEN 'ALTO'
                                                WHEN reporte_calificacionestrabajadornom035.C_LIDERAZGO_NIVEL = 5 THEN 'MUY ALTO'
                                                ELSE 'NA'
                                            END, 
                                            'NA'
                                        ) AS C_LIDERAZGO_NIVEL,
                                                    IFNULL(
                                            CASE 
                                                WHEN reporte_calificacionestrabajadornom035.D_LIDERAZGO_NIVEL = 1 THEN 'NULO'
                                                WHEN reporte_calificacionestrabajadornom035.D_LIDERAZGO_NIVEL = 2 THEN 'BAJO'
                                                WHEN reporte_calificacionestrabajadornom035.D_LIDERAZGO_NIVEL = 3 THEN 'MEDIO'
                                                WHEN reporte_calificacionestrabajadornom035.D_LIDERAZGO_NIVEL = 4 THEN 'ALTO'
                                                WHEN reporte_calificacionestrabajadornom035.D_LIDERAZGO_NIVEL = 5 THEN 'MUY ALTO'
                                                ELSE 'NA'
                                            END, 
                                            'NA'
                                        ) AS D_LIDERAZGO_NIVEL,
                                                    IFNULL(
                                            CASE 
                                                WHEN reporte_calificacionestrabajadornom035.D_RELACIONES_NIVEL = 1 THEN 'NULO'
                                                WHEN reporte_calificacionestrabajadornom035.D_RELACIONES_NIVEL = 2 THEN 'BAJO'
                                                WHEN reporte_calificacionestrabajadornom035.D_RELACIONES_NIVEL = 3 THEN 'MEDIO'
                                                WHEN reporte_calificacionestrabajadornom035.D_RELACIONES_NIVEL = 4 THEN 'ALTO'
                                                WHEN reporte_calificacionestrabajadornom035.D_RELACIONES_NIVEL = 5 THEN 'MUY ALTO'
                                                ELSE 'NA'
                                            END, 
                                            'NA'
                                        ) AS D_RELACIONES_NIVEL,
                                                    IFNULL(
                                            CASE 
                                                WHEN reporte_calificacionestrabajadornom035.D_VIOLENCIA_NIVEL = 1 THEN 'NULO'
                                                WHEN reporte_calificacionestrabajadornom035.D_VIOLENCIA_NIVEL = 2 THEN 'BAJO'
                                                WHEN reporte_calificacionestrabajadornom035.D_VIOLENCIA_NIVEL = 3 THEN 'MEDIO'
                                                WHEN reporte_calificacionestrabajadornom035.D_VIOLENCIA_NIVEL = 4 THEN 'ALTO'
                                                WHEN reporte_calificacionestrabajadornom035.D_VIOLENCIA_NIVEL = 5 THEN 'MUY ALTO'
                                                ELSE 'NA'
                                            END, 
                                            'NA'
                                        ) AS D_VIOLENCIA_NIVEL,
                                            IFNULL(
                                            CASE 
                                                WHEN reporte_calificacionestrabajadornom035.C_ENTORNO_NIVEL = 1 THEN 'NULO'
                                                WHEN reporte_calificacionestrabajadornom035.C_ENTORNO_NIVEL = 2 THEN 'BAJO'
                                                WHEN reporte_calificacionestrabajadornom035.C_ENTORNO_NIVEL = 3 THEN 'MEDIO'
                                                WHEN reporte_calificacionestrabajadornom035.C_ENTORNO_NIVEL = 4 THEN 'ALTO'
                                                WHEN reporte_calificacionestrabajadornom035.C_ENTORNO_NIVEL = 5 THEN 'MUY ALTO'
                                                ELSE 'NA'
                                            END, 
                                            'NA'
                                        ) AS C_ENTORNO_NIVEL,
                                            IFNULL(
                                            CASE 
                                                WHEN reporte_calificacionestrabajadornom035.D_RECONOCIMIENTO_NIVEL = 1 THEN 'NULO'
                                                WHEN reporte_calificacionestrabajadornom035.D_RECONOCIMIENTO_NIVEL = 2 THEN 'BAJO'
                                                WHEN reporte_calificacionestrabajadornom035.D_RECONOCIMIENTO_NIVEL = 3 THEN 'MEDIO'
                                                WHEN reporte_calificacionestrabajadornom035.D_RECONOCIMIENTO_NIVEL = 4 THEN 'ALTO'
                                                WHEN reporte_calificacionestrabajadornom035.D_RECONOCIMIENTO_NIVEL = 5 THEN 'MUY ALTO'
                                                ELSE 'NA'
                                            END, 
                                            'NA'
                                        ) AS D_RECONOCIMIENTO_NIVEL,
                                            IFNULL(
                                            CASE 
                                                WHEN reporte_calificacionestrabajadornom035.D_INSUFICIENTE_NIVEL = 1 THEN 'NULO'
                                                WHEN reporte_calificacionestrabajadornom035.D_INSUFICIENTE_NIVEL = 2 THEN 'BAJO'
                                                WHEN reporte_calificacionestrabajadornom035.D_INSUFICIENTE_NIVEL = 3 THEN 'MEDIO'
                                                WHEN reporte_calificacionestrabajadornom035.D_INSUFICIENTE_NIVEL = 4 THEN 'ALTO'
                                                WHEN reporte_calificacionestrabajadornom035.D_INSUFICIENTE_NIVEL = 5 THEN 'MUY ALTO'
                                                ELSE 'NA'
                                            END, 
                                            'NA'
                                        ) AS D_INSUFICIENTE_NIVEL
                                    FROM
                                    reporte_calificacionestrabajadornom035
                                    INNER JOIN recopsicoguia_5 ON reporte_calificacionestrabajadornom035.TRABAJADOR_ID = recopsicoguia_5.RECPSICOTRABAJADOR_ID
                                    INNER JOIN proyectotrabajadores ON recopsicoguia_5.RECPSICOTRABAJADOR_ID = proyectotrabajadores.TRABAJADOR_ID
                                    WHERE reporte_calificacionestrabajadornom035.proyecto_id = ?", [$proyecto_id]);
                     
                     
            
                     $fila = 8; // Inicia en la fila 8

                     foreach ($datosMEL as $dato) {
                         $HOJA_MEL->setCellValue('C' . $fila, strtoupper($dato->TRABAJADOR_NOMBRE));
                         $HOJA_MEL->setCellValue('D' . $fila, strtoupper($dato->RECPSICOTRABAJADOR_GENERO));
                         $HOJA_MEL->setCellValue('E' . $fila, strtoupper($dato->RECPSICOTRABAJADOR_EDAD));
                         $HOJA_MEL->setCellValue('F' . $fila, strtoupper($dato->RECPSICOTRABAJADOR_ESTADOCIVIL));
                         $HOJA_MEL->setCellValue('G' . $fila, strtoupper($dato->RECPSICOTRABAJADOR_ESTUDIOS));
                         $HOJA_MEL->setCellValue('H' . $fila, strtoupper($dato->RECPSICOTRABAJADOR_TIPOPUESTO));
                         $HOJA_MEL->setCellValue('I' . $fila, strtoupper($dato->AREA));
                         $HOJA_MEL->setCellValue('J' . $fila, strtoupper($dato->RECPSICOTRABAJADOR_TIPOCONTRATACION));
                         $HOJA_MEL->setCellValue('K' . $fila, strtoupper($dato->RECPSICOTRABAJADOR_TIPOJORNADA));
                         $HOJA_MEL->setCellValue('L' . $fila, strtoupper($dato->RECPSICOTRABAJADOR_ROTACIONTURNOS));
                         $HOJA_MEL->setCellValue('M' . $fila, $dato->GUIA1_CALIFICACION);
                         $HOJA_MEL->setCellValue('N' . $fila, $dato->GLOBAL_NIVEL);
                         $HOJA_MEL->setCellValue('O' . $fila, $dato->C_AMBIENTE_NIVEL);
                         $HOJA_MEL->setCellValue('P' . $fila, $dato->D_CONDICIONES_NIVEL);
                         $HOJA_MEL->setCellValue('Q' . $fila, $dato->C_FACTORES_NIVEL);
                         $HOJA_MEL->setCellValue('R' . $fila, $dato->D_CARGA_NIVEL);
                         $HOJA_MEL->setCellValue('S' . $fila, $dato->D_FALTA_NIVEL);
                         $HOJA_MEL->setCellValue('T' . $fila, $dato->C_ORGANIZACION_NIVEL);
                         $HOJA_MEL->setCellValue('U' . $fila, $dato->D_JORNADA_NIVEL);
                         $HOJA_MEL->setCellValue('V' . $fila, $dato->D_INTERFERENCIA_NIVEL);
                         $HOJA_MEL->setCellValue('W' . $fila, $dato->C_LIDERAZGO_NIVEL);
                         $HOJA_MEL->setCellValue('X' . $fila, $dato->D_LIDERAZGO_NIVEL);
                         $HOJA_MEL->setCellValue('Y' . $fila, $dato->D_RELACIONES_NIVEL);
                         $HOJA_MEL->setCellValue('Z' . $fila, $dato->D_VIOLENCIA_NIVEL);
                         $HOJA_MEL->setCellValue('AA' . $fila, $dato->C_ENTORNO_NIVEL);
                         $HOJA_MEL->setCellValue('AB' . $fila, $dato->D_RECONOCIMIENTO_NIVEL);
                         $HOJA_MEL->setCellValue('AC' . $fila, $dato->D_INSUFICIENTE_NIVEL);
                     
                         $columnasNiveles = [
                            'M' => $dato->GUIA1_CALIFICACION,
                            'N' => $dato->GLOBAL_NIVEL,
                            'O' => $dato->C_AMBIENTE_NIVEL,
                            'P' => $dato->D_CONDICIONES_NIVEL,
                            'Q' => $dato->C_FACTORES_NIVEL,
                            'R' => $dato->D_CARGA_NIVEL,
                            'S' => $dato->D_FALTA_NIVEL,
                            'T' => $dato->C_ORGANIZACION_NIVEL,
                            'U' => $dato->D_JORNADA_NIVEL,
                            'V' => $dato->D_INTERFERENCIA_NIVEL,
                            'W' => $dato->C_LIDERAZGO_NIVEL,
                            'X' => $dato->D_LIDERAZGO_NIVEL,
                            'Y' => $dato->D_RELACIONES_NIVEL,
                            'Z' => $dato->D_VIOLENCIA_NIVEL,
                            'AA' => $dato->C_ENTORNO_NIVEL,
                            'AB' => $dato->D_RECONOCIMIENTO_NIVEL,
                            'AC' => $dato->D_INSUFICIENTE_NIVEL
                        ];
                    
                        foreach ($columnasNiveles as $columna => $valorNivel) {
                            $HOJA_MEL->setCellValue($columna . $fila, $valorNivel);
                            pintarCelda($HOJA_MEL, $columna, $fila, $valorNivel);
                        }

                         $fila++; // Mueve a la siguiente fila
                     }



            // =============================================================== DESCARGAMOS EL ARCHIVO Y LO MANDAMOS AL FRONT PARA DARLE NOMBRE Y QUE EL USUARIO PUEDA VER LA DESCARGA
            $nombre_descarga = "MEL - MATRIZ DE EXPOSICION LABORAL.xls";
            $writer = IOFactory::createWriter($spreadsheet, 'Xls');
            return response()->stream(function () use ($writer) {
                $writer->save('php://output');
            }, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => "attachment; filename=\"{$nombre_descarga}\"",
            ]);
            
        } catch (Exception $e) {

            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    public function store(Request $request)
    {
        try {

            // TABLAS
            //============================================================

            $proyectoRecursos = recursosPortadasInformesModel::where('PROYECTO_ID', $request->proyecto_id)->where('AGENTE_ID', $request->agente_id)->get();
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($request->proyecto_id);
            $recsensorial = reconocimientopsicoModel::findOrFail($proyecto->reconocimiento_psico_id);

            $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);


            if (($request->reporteregistro_id + 0) > 0) {
                $reporte = reportenom0353Model::findOrFail($request->reporteregistro_id);


                $reporte->update([
                    'reportenom0353_instalacion' => $request->reporte_instalacion
                ]);


                $dato["reporteregistro_id"] = $reporte->id;


                //--------------------------------


                $revision = reporterevisionesModel::where('proyecto_id', $request->proyecto_id)
                    ->where('agente_id', $request->agente_id)
                    ->orderBy('reporterevisiones_revision', 'DESC')
                    ->get();


                if (count($revision) > 0) {
                    $revision = reporterevisionesModel::findOrFail($revision[0]->id);
                }



                if (($revision->reporterevisiones_concluido == 1 || $revision->reporterevisiones_cancelado == 1) && ($request->opcion + 0) != 26) // Valida disponibilidad de esta version
                {
                    // respuesta
                    $dato["msj"] = 'Informe de ' . $request->agente_nombre . ' NO disponible para edición';
                    return response()->json($dato);
                }
            } else {
                DB::statement('ALTER TABLE reportenom0353 AUTO_INCREMENT = 1;');

                if (!$request->catactivo_id) {
                    $request['catactivo_id'] = 0; // es es modo cliente y viene en null se pone en cero
                }

                $reporte = reportenom0353Model::create([
                    'proyecto_id' => $request->proyecto_id,
                    'agente_id' => $request->agente_id,
                    'agente_nombre' => $request->agente_nombre,
                    'catactivo_id' => $request->catactivo_id,
                    'reportenom0353_revision' => 0,
                    'reportenom0353_instalacion' => $request->reporte_instalacion,
                    'reportenom0353_catregion_activo' => 1,
                    'reportenom0353_catsubdireccion_activo' => 1,
                    'reportenom0353_catgerencia_activo' => 1,
                    'reportenom0353_catactivo_activo' => 1,
                    'reportenom0353_concluido' => 0,
                    'reportenom0353_cancelado' => 0
                ]);


                //--------------------------------------


                // ASIGNAR CATEGORIAS AL REGISTRO ACTUAL
                DB::statement('UPDATE 
                                    reportenom0353categoria
                                SET 
                                    registro_id = ' . $reporte->id . '
                                WHERE 
                                    proyecto_id = ' . $request->proyecto_id . '
                                    AND IFNULL(registro_id, "") = "";');


                // ASIGNAR AREAS AL REGISTRO ACTUAL
                DB::statement('UPDATE 
                                    reporteruidoarea
                                SET 
                                    registro_id = ' . $reporte->id . '
                                WHERE 
                                    proyecto_id = ' . $request->proyecto_id . '
                                    AND IFNULL(registro_id, "") = "";');
            }


            //============================================================


            // PORTADA
            if (($request->opcion + 0) == 0) {
                $reporte->update([
                    'reportenom0353_catregion_activo' => 0,
                    'reportenom0353_catsubdireccion_activo' => 0,
                    'reportenom0353_catgerencia_activo' => 0,
                    'reportenom0353_catactivo_activo' => 0,
                    'reportenom0353_instalacion' => $request->reporte_instalacion,
                    'reportenom0353_fecha' => $request->reporte_fecha,
                    'reporte_mes' => $request->reporte_mes

                ]);

                if (count($proyectoRecursos) == 0) {

                    $recusros = recursosPortadasInformesModel::create([
                        'PROYECTO_ID' => $request->proyecto_id,
                        'AGENTE_ID' => $request->agente_id,
                        'NORMA_ID' => 0,
                        'NIVEL1' => is_null($request->NIVEL1) ? null : $request->NIVEL1,
                        'NIVEL2' => is_null($request->NIVEL2) ? null : $request->NIVEL2,
                        'NIVEL3' => is_null($request->NIVEL3) ? null : $request->NIVEL3,
                        'NIVEL4' => is_null($request->NIVEL4) ? null : $request->NIVEL4,
                        'NIVEL5' => is_null($request->NIVEL5) ? null : $request->NIVEL5,
                        'OPCION_PORTADA1' => is_null($request->OPCION_PORTADA1) ? null : $request->OPCION_PORTADA1,
                        'OPCION_PORTADA2' => is_null($request->OPCION_PORTADA2) ? null : $request->OPCION_PORTADA2,
                        'OPCION_PORTADA3' => is_null($request->OPCION_PORTADA3) ? null : $request->OPCION_PORTADA3,
                        'OPCION_PORTADA4' => is_null($request->OPCION_PORTADA4) ? null : $request->OPCION_PORTADA4,
                        'OPCION_PORTADA5' => is_null($request->OPCION_PORTADA5) ? null : $request->OPCION_PORTADA5,
                        'OPCION_PORTADA6' => is_null($request->OPCION_PORTADA6) ? null : $request->OPCION_PORTADA6
                    ]);

                    if ($request->file('PORTADA')) {
                        // Eliminar IMG anterior
                        if (Storage::exists($recusros->RUTA_IMAGEN_PORTADA)) {
                            Storage::delete($recusros->RUTA_IMAGEN_PORTADA);
                        }

                        $extension = $request->file('PORTADA')->getClientOriginalExtension();
                        $imgGuardada = $request->file('PORTADA')->storeAs('reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $request->reporteregistro_id . '/imagenPortada', 'PORTADA_IAMGEN.' . $extension);

                        $recusros->update(['RUTA_IMAGEN_PORTADA' => $imgGuardada]);
                    }
                    
                } else {

                    foreach ($proyectoRecursos as $recurso) {
                        $recurso->update([
                            'NORMA_ID' => 0,
                            'NIVEL1' => is_null($request->NIVEL1) ? null : $request->NIVEL1,
                            'NIVEL2' => is_null($request->NIVEL2) ? null : $request->NIVEL2,
                            'NIVEL3' => is_null($request->NIVEL3) ? null : $request->NIVEL3,
                            'NIVEL4' => is_null($request->NIVEL4) ? null : $request->NIVEL4,
                            'NIVEL5' => is_null($request->NIVEL5) ? null : $request->NIVEL5,
                            'OPCION_PORTADA1' => is_null($request->OPCION_PORTADA1) ? null : $request->OPCION_PORTADA1,
                            'OPCION_PORTADA2' => is_null($request->OPCION_PORTADA2) ? null : $request->OPCION_PORTADA2,
                            'OPCION_PORTADA3' => is_null($request->OPCION_PORTADA3) ? null : $request->OPCION_PORTADA3,
                            'OPCION_PORTADA4' => is_null($request->OPCION_PORTADA4) ? null : $request->OPCION_PORTADA4,
                            'OPCION_PORTADA5' => is_null($request->OPCION_PORTADA5) ? null : $request->OPCION_PORTADA5,
                            'OPCION_PORTADA6' => is_null($request->OPCION_PORTADA6) ? null : $request->OPCION_PORTADA6
                        ]);
                    }

                    foreach ($proyectoRecursos as $recurso) {
                        if ($request->file('PORTADA')) {
                            // Eliminar IMG anterior
                            if (Storage::exists($recurso->RUTA_IMAGEN_PORTADA)) {
                                Storage::delete($recurso->RUTA_IMAGEN_PORTADA);
                            }

                            $extension = $request->file('PORTADA')->getClientOriginalExtension();
                            $imgGuardada = $request->file('PORTADA')->storeAs(
                                'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $request->reporteregistro_id . '/imagenPortada',
                                'PORTADA_IMAGEN.' . $extension
                            );

                            $recurso->update(['RUTA_IMAGEN_PORTADA' => $imgGuardada]);
                        }
                    }
                }



                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // INTRODUCCION
            if (($request->opcion + 0) == 1) {
                $reporte->update([
                    'reportenom0353_introduccion' => $request->reporte_introduccion,
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // DEFINICIONES
            if (($request->opcion + 0) == 2) {
                if (!$request->catactivo_id) {
                    $request['catactivo_id'] = 0; // es es modo cliente y viene en null se pone en cero
                }

                if (($request->reportedefiniciones_id + 0) == 0) //NUEVO
                {
                    DB::statement('ALTER TABLE reportedefiniciones AUTO_INCREMENT = 1;');

                    $definicion = reportedefinicionesModel::create([
                        'agente_id' => $request->agente_id,
                        'agente_nombre' => $request->agente_nombre,
                        'catactivo_id' => $request->catactivo_id,
                        'reportedefiniciones_concepto' => $request->reportedefiniciones_concepto,
                        'reportedefiniciones_descripcion' => $request->reportedefiniciones_descripcion,
                        'reportedefiniciones_fuente' => $request->reportedefiniciones_fuente
                    ]);

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                } else //EDITAR
                {
                    $definicion = reportedefinicionesModel::findOrFail($request->reportedefiniciones_id);

                    $definicion->update([
                        'catactivo_id' => $request->catactivo_id,
                        'reportedefiniciones_concepto' => $request->reportedefiniciones_concepto,
                        'reportedefiniciones_descripcion' => $request->reportedefiniciones_descripcion,
                        'reportedefiniciones_fuente' => $request->reportedefiniciones_fuente
                    ]);

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // OBJETIVO GENERAL
            if (($request->opcion + 0) == 3) {
                $reporte->update([
                    'reportenom0353_objetivogeneral' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_objetivogeneral)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // OBJETIVOS  ESPECIFICOS
            if (($request->opcion + 0) == 4) {
                $reporte->update([
                    'reportenom0353_objetivoespecifico' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_objetivoespecifico)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.1
            if (($request->opcion + 0) == 5) {
                $reporte->update([
                    'reportenom0353_metodologiainstrumentos' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_metodologia_4_1)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }

            // UBICACION
            if (($request->opcion + 0) == 7) {
                $reporte->update([
                    'reportenom0353_ubicacioninstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_ubicacioninstalacion)
                ]);

                // si envia archivo
                if ($request->file('reporteubicacionfoto')) {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->ubicacionmapa); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reporte->id . '/ubicacionfoto/ubicacionfoto.jpg';

                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $reporte->update([
                        'reportenom0353_ubicacionfoto' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PROCESO INSTALACION
            if (($request->opcion + 0) == 8) {
                $reporte->update([
                    'reportenom0353_procesoinstalacion' => $request->reporte_procesoinstalacion,
                    'reportenom0353_actividadprincipal' => $request->reporte_actividadprincipal
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // CATEGORIAS
            if (($request->opcion + 0) == 9) {
                if (($request->reportecategoria_id + 0) == 0) {
                    DB::statement('ALTER TABLE reporteruidocategoria AUTO_INCREMENT = 1;');

                    $request['recsensorialcategoria_id'] = 0;
                    $request['registro_id'] = $reporte->id;
                    $categoria = reporteruidocategoriaModel::create($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                } else {
                    $request['registro_id'] = $reporte->id;
                    $categoria = reporteruidocategoriaModel::findOrFail($request->reportecategoria_id);
                    $categoria->update($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // AREAS
            if (($request->opcion + 0) == 10) {
                // dd($request->all());


                if (($request->areas_poe + 0) == 1) {
                    $request['reportearea_proceso'] = $request->reporteruidoarea_proceso;
                    $request['reportearea_tiporuido'] = $request->reporteruidoarea_tiporuido;
                    $request['reportearea_evaluacion'] = $request->reporteruidoarea_evaluacion;
                    $request['reportearea_LNI_1'] = $request->reporteruidoarea_LNI_1;
                    $request['reportearea_LNI_2'] = $request->reporteruidoarea_LNI_2;
                    $request['reportearea_LNI_3'] = $request->reporteruidoarea_LNI_3;
                    $request['reportearea_LNI_4'] = $request->reporteruidoarea_LNI_4;
                    $request['reportearea_LNI_5'] = $request->reporteruidoarea_LNI_5;
                    $request['reportearea_LNI_6'] = $request->reporteruidoarea_LNI_6;
                    $request['reportearea_LNI_7'] = $request->reporteruidoarea_LNI_7;
                    $request['reportearea_LNI_8'] = $request->reporteruidoarea_LNI_8;
                    $request['reportearea_LNI_9'] = $request->reporteruidoarea_LNI_9;
                    $request['reportearea_LNI_10'] = $request->reporteruidoarea_LNI_10;

                    $area = reporteareaModel::findOrFail($request->reportearea_id);
                    $area->update($request->all());


                    $eliminar_categorias = reporteruidoareacategoriaModel::where('reporteruidoarea_id', $request->reportearea_id)
                        ->where('reporteruidoareacategoria_poe', $request->reporteregistro_id)
                        ->delete();


                    if ($request->checkbox_categoria_id) {
                        DB::statement('ALTER TABLE reporteruidoareacategoria AUTO_INCREMENT = 1;');


                        foreach ($request->checkbox_categoria_id as $key => $value) {
                            $areacategoria = reporteruidoareacategoriaModel::create([
                                'reporteruidoarea_id' => $area->id,
                                'reporteruidocategoria_id' => $value,
                                'reporteruidoareacategoria_poe' => $request->reporteregistro_id,
                                'reporteruidoareacategoria_actividades' => $request['areacategoria_actividades_' . $value]
                            ]);
                        }
                    }


                    $eliminar_maquinaria = reporteruidoareamaquinariaModel::where('reporteruidoarea_id', $request->reportearea_id)
                        ->where('reporteruidoareamaquinaria_poe', $request->reporteregistro_id)
                        ->delete();


                    if ($request->reporteruidoareamaquinaria_nombre) {
                        DB::statement('ALTER TABLE reporteruidoareamaquinaria AUTO_INCREMENT = 1;');

                        foreach ($request->reporteruidoareamaquinaria_nombre as $key => $value) {
                            $areamaquinaria = reporteruidoareamaquinariaModel::create([
                                'reporteruidoarea_id' => $area->id,
                                'reporteruidoareamaquinaria_poe' => $request->reporteregistro_id,
                                'reporteruidoareamaquinaria_nombre' => $value,
                                'reporteruidoareamaquinaria_cantidad' => $request['reporteruidoareamaquinaria_cantidad'][$key]
                            ]);
                        }
                    }


                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                } else {
                    if (($request->reportearea_id + 0) == 0) {
                        DB::statement('ALTER TABLE reporteruidoarea AUTO_INCREMENT = 1;');


                        $request['registro_id'] = $reporte->id;
                        $request['recsensorialarea_id'] = 0;
                        $area = reporteruidoareaModel::create($request->all());


                        if ($request->checkbox_categoria_id) {
                            DB::statement('ALTER TABLE reporteruidoareacategoria AUTO_INCREMENT = 1;');

                            foreach ($request->checkbox_categoria_id as $key => $value) {
                                $areacategoria = reporteruidoareacategoriaModel::create([
                                    'reporteruidoarea_id' => $area->id,
                                    'reporteruidocategoria_id' => $value,
                                    'reporteruidoareacategoria_poe' => 0,
                                    'reporteruidoareacategoria_actividades' => $request['areacategoria_actividades_' . $value]
                                ]);
                            }
                        }


                        if ($request->reporteruidoareamaquinaria_nombre) {
                            DB::statement('ALTER TABLE reporteruidoareamaquinaria AUTO_INCREMENT = 1;');

                            foreach ($request->reporteruidoareamaquinaria_nombre as $key => $value) {
                                $areamaquinaria = reporteruidoareamaquinariaModel::create([
                                    'reporteruidoarea_id' => $area->id,
                                    'reporteruidoareamaquinaria_poe' => 0,
                                    'reporteruidoareamaquinaria_nombre' => $value,
                                    'reporteruidoareamaquinaria_cantidad' => $request['reporteruidoareamaquinaria_cantidad'][$key]
                                ]);
                            }
                        }


                        // Mensaje
                        $dato["msj"] = 'Datos guardados correctamente';
                    } else {
                        $request['registro_id'] = $reporte->id;
                        $area = reporteruidoareaModel::findOrFail($request->reportearea_id);
                        $area->update($request->all());


                        $eliminar_categorias = reporteruidoareacategoriaModel::where('reporteruidoarea_id', $request->reportearea_id)
                            ->where('reporteruidoareacategoria_poe', 0)
                            ->delete();


                        if ($request->checkbox_categoria_id) {
                            DB::statement('ALTER TABLE reporteruidoareacategoria AUTO_INCREMENT = 1;');


                            foreach ($request->checkbox_categoria_id as $key => $value) {
                                $areacategoria = reporteruidoareacategoriaModel::create([
                                    'reporteruidoarea_id' => $area->id,
                                    'reporteruidocategoria_id' => $value,
                                    'reporteruidoareacategoria_poe' => 0,
                                    'reporteruidoareacategoria_actividades' => $request['areacategoria_actividades_' . $value]
                                ]);
                            }
                        }



                        $eliminar_maquinaria = reporteruidoareamaquinariaModel::where('reporteruidoarea_id', $request->reportearea_id)
                            ->where('reporteruidoareamaquinaria_poe', 0)
                            ->delete();


                        if ($request->reporteruidoareamaquinaria_nombre) {
                            DB::statement('ALTER TABLE reporteruidoareamaquinaria AUTO_INCREMENT = 1;');

                            foreach ($request->reporteruidoareamaquinaria_nombre as $key => $value) {
                                $areamaquinaria = reporteruidoareamaquinariaModel::create([
                                    'reporteruidoarea_id' => $area->id,
                                    'reporteruidoareamaquinaria_poe' => 0,
                                    'reporteruidoareamaquinaria_nombre' => $value,
                                    'reporteruidoareamaquinaria_cantidad' => $request['reporteruidoareamaquinaria_cantidad'][$key]
                                ]);
                            }
                        }


                        // Mensaje
                        $dato["msj"] = 'Datos modificados correctamente';
                    }
                }
            }


            // METODO DE EVALUACION
            if (($request->opcion + 0) == 14) {
                $reporte->update([
                    'reportenom0353_metodoevaluacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_descripcionmetodo)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }

            // CONCLUSION
            if (($request->opcion + 0) == 19) {
                $form_conclusiones = [
                    'acontecimientos_traumaticos' => $request->input('reporte_acontecimientos_conclusiones'),
                    'ambiente_trabajo' => $request->input('reporte_ambiente_conclusiones'),
                    'condiciones_ambiente' => $request->input('reporte_condiciones_conclusiones'),
                    'factores_actividad' => $request->input('reporte_factores_conclusiones'),
                    'carga_trabajo' => $request->input('reporte_carga_conclusiones'),
                    'falta_control' => $request->input('reporte_falta_conclusiones'),
                    'organizacion_tiempo' => $request->input('reporte_organizacion_conclusiones'),
                    'jornada_trabajo' => $request->input('reporte_jornada_conclusiones'),
                    'interferencia_trabajo_familia' => $request->input('reporte_interferencia_conclusiones'),
                    'liderazgo_relaciones' => $request->input('reporte_liderazgorelaciones_conclusiones'),
                    'liderazgo' => $request->input('reporte_liderazgo_conclusiones'),
                    'relaciones_trabajo' => $request->input('reporte_relaciones_conclusiones'),
                    'violencia' => $request->input('reporte_violencia_conclusiones'),
                    'entorno_organizacional' => $request->input('reporte_entorno_conclusiones'),
                    'reconocimiento_desempeno' => $request->input('reporte_reconocimiento_conclusiones'),
                    'insuficiente_pertenencia' => $request->input('reporte_insuficiente_conclusiones'),
                ];
            
                // Convertir el array a JSON
                $jsonConclusiones = json_encode($form_conclusiones);
            
                // Actualizar el registro en la base de datos
                $reporte->update([
                    'reportenom0353_conclusiones' => $jsonConclusiones,
                ]);
            

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }

             // RECOMENDACIONES
             if (($request->opcion + 0) == 30) {
                if ($request->recomendacion_checkbox) {
                    $eliminar_recomendaciones = reporterecomendacionescontrolModel::where('proyecto_id', $request->proyecto_id)
                        ->where('registro_id', $reporte->id)
                        ->delete();

                    DB::statement('ALTER TABLE reporterecomendacionescontrol AUTO_INCREMENT = 1;');

                    foreach ($request->recomendacion_checkbox as $key => $value) {
                        $recomendacion = reporterecomendacionescontrolModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reporte->id,
                            'reporterecomendacionescatalogo_id' => $value,
                            'reporterecomendaciones_descripcion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request['recomendacion_descripcion_' . $value])
                        ]);
                    }

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }


                if ($request->recomendacionadicional_checkbox) {
                    if (!$request->recomendacion_checkbox) {
                        $eliminar_recomendaciones = reporterecomendacionescontrolModel::where('proyecto_id', $request->proyecto_id)
                            ->where('registro_id', $reporte->id)
                            ->delete();
                    }

                    DB::statement('ALTER TABLE reporterecomendacionescontrol AUTO_INCREMENT = 1;');

                    foreach ($request->recomendacionadicional_checkbox as $key => $value) {
                        $recomendacion = reporterecomendacionescontrolModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reporte->id,
                            'reporterecomendacionescatalogo_id' => 0,
                            'reporterecomendaciones_descripcion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->recomendacionadicional_descripcion[$key])
                        ]);
                    }

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }
            }


              // recomendaciones
            if (($request->opcion + 0) == 20) {
                if ($request->recomendacion_categoria_checkbox) {
                    $eliminar_recomendaciones_categoria = reporterecomendacionescategoriaModel::where('proyecto_id', $request->proyecto_id)
                        ->where('registro_id', $reporte->id)
                        ->where('reporterecomendacionescategoria_id', $request->categoria_id)
                        ->delete();

                    DB::statement('ALTER TABLE reporterecomendacionescategoria AUTO_INCREMENT = 1;');

                    foreach ($request->recomendacion_categoria_checkbox as $key => $value) {
                        $recomendacionCategoria = reporterecomendacionescategoriaModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reporte->id,
                            'reporterecomendacionescatalogo_id' => $value,
                            'reporterecomendaciones_descripcion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request['recomendacion_categoria_descripcion_' . $value]),
                            'reporterecomendacionescategoria_id' => $request->categoria_id,
                        ]);
                    }

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }


                if ($request->recomendacionadicional_categoria_checkbox) {
                    if (!$request->recomendacion_categoria_checkbox) {
                        $eliminar_recomendaciones_categoria = reporterecomendacionescategoriaModel::where('proyecto_id', $request->proyecto_id)
                            ->where('registro_id', $reporte->id)
                            ->where('reporterecomendacionescategoria_id', $request->categoria_id)
                            ->delete();
                    }

                    DB::statement('ALTER TABLE reporterecomendacionescategoria AUTO_INCREMENT = 1;');

                    foreach ($request->recomendacionadicional_categoria_checkbox as $key => $value) {
                        $recomendacionCategoria = reporterecomendacionescategoriaModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reporte->id,
                            'reporterecomendacionescatalogo_id' => 0,
                            'reporterecomendaciones_descripcion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->recomendacionadicional_categoria_descripcion[$key]),
                            'reporterecomendacionescategoria_id' => $request->categoria_id,
                        ]);
                    }

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }
            }


            // RESPONSABLES DEL INFORME
            if (($request->opcion + 0) == 21) {
                $reporte->update([
                    'reportenom0353_responsable1' => $request->reporte_responsable1,
                    'reportenom0353_responsable1cargo' => $request->reporte_responsable1cargo,
                    'reportenom0353_responsable2' => $request->reporte_responsable2,
                    'reportenom0353_responsable2cargo' => $request->reporte_responsable2cargo
                ]);


                if ($request->responsablesinforme_carpetadocumentoshistorial) {
                    $nuevo_destino = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reporte->id . '/responsables informe/';
                    Storage::makeDirectory($nuevo_destino); //crear directorio

                    File::copyDirectory(storage_path('app/' . $request->responsablesinforme_carpetadocumentoshistorial), storage_path('app/' . $nuevo_destino));

                    $reporte->update([
                        'reportenom0353_responsable1documento' => $nuevo_destino . 'responsable1_doc.jpg',
                        'reportenom0353_responsable2documento' => $nuevo_destino . 'responsable2_doc.jpg'
                    ]);
                }


                if ($request->file('reporteresponsable1documento')) {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->reporte_responsable1_documentobase64); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reporte->id . '/responsables informe/responsable1_doc.jpg';

                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $reporte->update([
                        'reportenom0353_responsable1documento' => $destinoPath
                    ]);
                }


                if ($request->file('reporteresponsable2documento')) {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->reporte_responsable2_documentobase64); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reporte->id . '/responsables informe/responsable2_doc.jpg';

                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $reporte->update([
                        'reportenom0353_responsable2doc' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PLANOS
            if (($request->opcion + 0) == 22) {
                $eliminar_carpetasplanos = reporteplanoscarpetasModel::where('proyecto_id', $request->proyecto_id)
                    ->where('agente_nombre', $request->agente_nombre)
                    ->where('registro_id', $reporte->id)
                    ->delete();

                if ($request->planoscarpeta_checkbox) {
                    DB::statement('ALTER TABLE reporteplanoscarpetas AUTO_INCREMENT = 1;');

                    $dato["total"] = 0;
                    foreach ($request->planoscarpeta_checkbox as $key => $value) {
                        $anexo = reporteplanoscarpetasModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'registro_id' => $reporte->id,
                            'reporteplanoscarpetas_nombre' => str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $value)
                        ]);

                        $dato["total"] += 1;
                    }
                } else {
                    $dato["total"] = 0;
                }

                // Mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }


            // EQUIPO UTILIZADO
            if (($request->opcion + 0) == 23) {
                if ($request->equipoutilizado_checkbox) {
                    $eliminar_equiposutilizados = reporteequiposutilizadosModel::where('proyecto_id', $request->proyecto_id)
                        ->where('agente_nombre', $request->agente_nombre)
                        ->where('registro_id', $reporte->id)
                        ->delete();


                    DB::statement('ALTER TABLE reporteequiposutilizados AUTO_INCREMENT = 1;');


                    foreach ($request->equipoutilizado_checkbox as $key => $value) {
                        if ($request['equipoutilizado_checkboxcarta_' . $value]) {

                            $request->reporteequiposutilizados_cartacalibracion = 1;
                        } else {


                            $request->reporteequiposutilizados_cartacalibracion = null;
                        }


                        $equipoutilizado = reporteequiposutilizadosModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'registro_id' => $reporte->id,
                            'equipo_id' => $value,
                            'reporteequiposutilizados_cartacalibracion' => $request->reporteequiposutilizados_cartacalibracion
                        ]);
                    }
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // INFORMES RESULTADOS
            if (($request->opcion + 0) == 24) {
                $eliminar_anexos = reporteanexosModel::where('proyecto_id', $request->proyecto_id)
                    ->where('agente_nombre', $request->agente_nombre)
                    ->where('registro_id', $reporte->id)
                    ->where('reporteanexos_tipo', 1) // INFORMES DE RESULTADOS
                    ->delete();

                if ($request->anexoresultado_checkbox) {
                    DB::statement('ALTER TABLE reporteanexos AUTO_INCREMENT = 1;');

                    $dato["total"] = 0;
                    foreach ($request->anexoresultado_checkbox as $key => $value) {
                        $anexo = reporteanexosModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'registro_id' => $reporte->id,
                            'reporteanexos_tipo' => 1  // INFORMES DE RESULTADOS
                            ,
                            'reporteanexos_anexonombre' => str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request['anexoresultado_nombre_' . $value]),
                            'reporteanexos_rutaanexo' => $request['anexoresultado_archivo_' . $value]
                        ]);

                        $dato["total"] += 1;
                    }
                } else {
                    $dato["total"] = 0;
                }

                // Mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }


            // ANEXOS 7 STPS y 8 EMA
            if (($request->opcion + 0) == 25) {
                $eliminar_anexos = reporteanexosModel::where('proyecto_id', $request->proyecto_id)
                    ->where('agente_nombre', $request->agente_nombre)
                    ->where('registro_id', $reporte->id)
                    ->where('reporteanexos_tipo', 2) // ANEXOS TIPO STPS Y EMA
                    ->delete();

                if ($request->anexoacreditacion_checkbox) {
                    DB::statement('ALTER TABLE reporteanexos AUTO_INCREMENT = 1;');

                    $dato["total"] = 0;
                    foreach ($request->anexoacreditacion_checkbox as $key => $value) {
                        $anexo = reporteanexosModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'registro_id' => $reporte->id,
                            'reporteanexos_tipo' => 2  // ANEXOS TIPO STPS Y EMA
                            ,
                            'reporteanexos_anexonombre' => ($key + 1) . '.- ' . str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request['anexoacreditacion_nombre_' . $value]),
                            'reporteanexos_rutaanexo' => $request['anexoacreditacion_archivo_' . $value]
                        ]);

                        $dato["total"] += 1;
                    }
                } else {
                    $dato["total"] = 0;
                }

                // Mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }


            // REVISION INFORME, CANCELACION
            if (($request->opcion + 0) == 26) {
                $revision = reporterevisionesModel::findOrFail($request->reporterevisiones_id);


                $cancelado = 0;
                $canceladonombre = NULL;
                $canceladofecha = NULL;
                $canceladoobservacion = NULL;


                if ($revision->reporterevisiones_cancelado == 0) {
                    $cancelado = 1;
                    $canceladonombre = auth()->user()->empleado->empleado_nombre . " " . auth()->user()->empleado->empleado_apellidopaterno . " " . auth()->user()->empleado->empleado_apellidomaterno;
                    $canceladofecha = date('Y-m-d H:i:s');
                    $canceladoobservacion = $request->reporte_canceladoobservacion;
                }


                $revision->update([
                    'reporterevisiones_cancelado' => $cancelado,
                    'reporterevisiones_canceladonombre' => $canceladonombre,
                    'reporterevisiones_canceladofecha' => $canceladofecha,
                    'reporterevisiones_canceladoobservacion' => $canceladoobservacion
                ]);


                $dato["estado"] = 0;
                if ($revision->reporterevisiones_concluido == 1 || $cancelado == 1) {
                    $dato["estado"] = 1;
                }


                $dato["msj"] = 'Datos modificados correctamente';
            }


            /*

            // GRAFICAS FOTOS BASE64
            if (($request->opcion+0) == 70)
            {
                dd($request);

                if ($request->grafica1)
                {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->grafica1); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/informes/graficapastel1_iluminacion_informe'.$reporteiluminacion->id.'.jpg';
                    
                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public
                }

                // Mensaje
                $dato["msj"] = 'Imagen guardada correctamente';
            }

            */


            // respuesta
            $dato["reporteregistro_id"] = $reporte->id;
            return response()->json($dato);
        } catch (Exception $e) {
            // respuesta
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }

      /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reportenom0353tabladefiniciones($proyecto_id)
    {
        try {


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 1)
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            $edicion = 1;
            if (count($revision) > 0) {
                if ($revision[0]->reporterevisiones_concluido == 1 || $revision[0]->reporterevisiones_cancelado == 1) {
                    $edicion = 0;
                }
            }


            //==========================================


            // Datos
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = reconocimientopsicoModel::findOrFail($proyecto->reconocimiento_psico_id);

            $where_definiciones = '';

            $definiciones_catalogo = collect(DB::select('SELECT psicocat_definiciones.ID_DEFINICION_INFORME,
                                                            psicocat_definiciones.CONCEPTO,
                                                            psicocat_definiciones.DESCRIPCION,
                                                            psicocat_definiciones.FUENTE
                                                            FROM
                                                                                    psicocat_definiciones
                                                                            WHERE
                                                                                    psicocat_definiciones.ACTIVO = 1
                                                            ORDER BY
                                                            psicocat_definiciones.CONCEPTO ASC'));

            foreach ($definiciones_catalogo as $key => $value) {
                    $value->descripcion_fuente = $value->DESCRIPCION . '<br><span style="color: #999999; font-style: italic;">Fuente: ' . $value->FUENTE . '</span>';
                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-1x"></i></button>';
                    if ($edicion == 1) {
                        $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-1x"></i></button>';
                    } else {
                        $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-eye fa-1x"></i></button>';
                    }
                }
            

            // respuesta
            $dato['data'] = $definiciones_catalogo;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }

       /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteregistro_id
     * @return \Illuminate\Http\Response
     */
    public function reportenom0353tablarecomendaciones($proyecto_id, $reporteregistro_id)
    {
        try {
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = reconocimientopsicoModel::findOrFail($proyecto->reconocimiento_psico_id);

            $tabla = collect(DB::select('SELECT
                                            TABLA.id,
                                            TABLA.recomendaciones_descripcion,
                                            TABLA.checked
                                        FROM
                                            (
                                                -- Primera subconsulta para obtener datos de psicocat_recomendacionescontrol
                                                SELECT
                                                    CATALOGO.id,
                                                    IF(
                                                        CATALOGO.recomendaciones_descripcion != "",
                                                        CATALOGO.recomendaciones_descripcion,
                                                        CATALOGO.recomendacionescatalogo_descripcion
                                                    ) AS recomendaciones_descripcion,
                                                    IF(
                                                        CATALOGO.recomendaciones_descripcion != "",
                                                        "checked",
                                                        ""
                                                    ) AS checked
                                                FROM
                                                    (
                                                        SELECT
                                                            psicocat_recomendacionescontrol.ID_RECOMENDACION_CONTROL_INFORME AS id,
                                                            psicocat_recomendacionescontrol.RECOMENDACION_CONTROL AS recomendacionescatalogo_descripcion,
                                                            IFNULL(
                                                                (
                                                                    SELECT
                                                                        reporterecomendacionescontrol.reporterecomendaciones_descripcion
                                                                    FROM
                                                                        reporterecomendacionescontrol
                                                                    WHERE
                                                                        reporterecomendacionescontrol.proyecto_id = ' . $proyecto_id . '
                                                                        AND reporterecomendacionescontrol.registro_id = ' . $reporteregistro_id . '
                                                                        AND reporterecomendacionescontrol.reporterecomendacionescatalogo_id = psicocat_recomendacionescontrol.ID_RECOMENDACION_CONTROL_INFORME
                                                                    LIMIT 1
                                                                ),
                                                                NULL
                                                            ) AS recomendaciones_descripcion
                                                        FROM
                                                            psicocat_recomendacionescontrol
                                                        WHERE
                                                            psicocat_recomendacionescontrol.ACTIVO = 1
                                                    ) AS CATALOGO

                                                UNION ALL

                                                -- Segunda subconsulta para obtener datos de reporterecomendacionescontrol
                                                SELECT
                                                    0 AS id,
                                                    reporterecomendacionescontrol.reporterecomendaciones_descripcion AS recomendaciones_descripcion,
                                                    "checked" AS checked
                                                FROM
                                                    reporterecomendacionescontrol
                                                WHERE
                                                    reporterecomendacionescontrol.proyecto_id = ' . $proyecto_id . '
                                                    AND reporterecomendacionescontrol.registro_id = ' . $reporteregistro_id . '
                                                    AND reporterecomendacionescontrol.reporterecomendacionescatalogo_id = 0
                                            ) AS TABLA
                                        ORDER BY
                                            TABLA.id ASC;'));

            $numero_registro = 0;
            $total = 0;
            foreach ($tabla as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                if (($value->id + 0) > 0) {
                    $required_readonly = 'readonly';
                    if ($value->checked) {
                        $required_readonly = 'required';
                    }

                    $value->checkbox = '<div class="switch">
                                            <label>
                                                <input type="checkbox" class="recomendacion_checkbox" name="recomendacion_checkbox[]" value="' . $value->id . '" ' . $value->checked . ' onclick="activa_recomendacion(this);">
                                                <span class="lever switch-col-light-blue"></span>
                                            </label>
                                        </div>';

                    $value->descripcion = '
                                            <textarea  class="form-control" rows="5" id="recomendacion_descripcion_' . $value->id . '" name="recomendacion_descripcion_' . $value->id . '" ' . $required_readonly . '>' . $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $value->recomendaciones_descripcion) . '</textarea>';
                } else {
                    $value->checkbox = '<input type="checkbox" class="recomendacionadicional_checkbox" name="recomendacionadicional_checkbox[]" value="0" checked/>
                                        <button type="button" class="btn btn-danger waves-effect btn-circle eliminar" data-toggle="tooltip" title="Eliminar recomendación"><i class="fa fa-trash fa-1x"></i></button>';

                    

                    $value->descripcion = '
                                            <div class="form-group">
                                                <label>Descripción</label>
                                                <textarea  class="form-control" rows="5" name="recomendacionadicional_descripcion[]" required>' . $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $value->recomendaciones_descripcion) . '</textarea>
                                            </div>';
                }

                if ($value->checked) {
                    $total += 1;
                }
            }
            // respuesta
            $dato['data'] = $tabla;
            $dato['total'] = $total;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato['total'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }

          /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteregistro_id
     * @param  int $categoria_id
     * @return \Illuminate\Http\Response
     */
    public function reportenom0353tablarecomendaciones_categorias($proyecto_id, $reporteregistro_id, $categoria_id)
    {
        try {
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = reconocimientopsicoModel::findOrFail($proyecto->reconocimiento_psico_id);

            $tabla = collect(DB::select('SELECT
                                            TABLA.id,
                                            TABLA.recomendaciones_descripcion,
                                            TABLA.checked
                                        FROM
                                            (
                                                -- Primera subconsulta para obtener datos de psicocat_recomendacionescontrol
                                                SELECT
                                                    CATALOGO.id,
                                                    IF(
                                                        CATALOGO.recomendaciones_descripcion != "",
                                                        CATALOGO.recomendaciones_descripcion,
                                                        CATALOGO.recomendacionescatalogo_descripcion
                                                    ) AS recomendaciones_descripcion,
                                                    IF(
                                                        CATALOGO.recomendaciones_descripcion != "",
                                                        "checked",
                                                        ""
                                                    ) AS checked
                                                FROM
                                                    (
                                                        SELECT
                                                            psicocat_recomendaciones.ID_RECOMENDACION_INFORME AS id,
                                                            psicocat_recomendaciones.RECOMENDACION AS recomendacionescatalogo_descripcion,
                                                            psicocat_recomendaciones.CATEGORIA AS recomendacionescatalogo_categoria,
                                                            IFNULL(
                                                                (
                                                                    SELECT
                                                                        reporterecomendacionescategoria.reporterecomendaciones_descripcion
                                                                    FROM
                                                                        reporterecomendacionescategoria
                                                                    WHERE
                                                                        reporterecomendacionescategoria.proyecto_id = ' . $proyecto_id . '
                                                                        AND reporterecomendacionescategoria.registro_id = ' . $reporteregistro_id . '
																		AND reporterecomendacionescategoria.reporterecomendacionescategoria_id = ' . $categoria_id . '
                                                                        AND reporterecomendacionescategoria.reporterecomendacionescatalogo_id = psicocat_recomendaciones.ID_RECOMENDACION_INFORME
                                                                    LIMIT 1
                                                                ),
                                                                NULL
                                                            ) AS recomendaciones_descripcion
                                                        FROM
                                                            psicocat_recomendaciones
                                                        WHERE
                                                            psicocat_recomendaciones.ACTIVO = 1
                                                            AND psicocat_recomendaciones.CATEGORIA = ' . $categoria_id . '
                                                    ) AS CATALOGO

                                                UNION ALL

                                                -- Segunda subconsulta para obtener datos de reporterecomendaciones
                                                SELECT
                                                    0 AS id,
                                                    reporterecomendacionescategoria.reporterecomendaciones_descripcion AS recomendaciones_descripcion,
                                                    "checked" AS checked
                                                FROM
                                                    reporterecomendacionescategoria
                                                WHERE
                                                    reporterecomendacionescategoria.proyecto_id = ' . $proyecto_id . '
                                                    AND reporterecomendacionescategoria.registro_id = ' . $reporteregistro_id . '
                                                    AND reporterecomendacionescategoria.reporterecomendacionescategoria_id = ' . $categoria_id . '
                                                    AND reporterecomendacionescategoria.reporterecomendacionescatalogo_id = 0
                                            ) AS TABLA
                                        ORDER BY
                                            TABLA.id ASC;'));

            $numero_registro = 0;
            $total = 0;
            foreach ($tabla as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                if (($value->id + 0) > 0) {
                    $required_readonly = 'readonly';
                    if ($value->checked) {
                        $required_readonly = 'required';
                    }

                    $value->checkbox = '<div class="switch">
                                            <label>
                                                <input type="checkbox" class="recomendacion_categoria_checkbox" name="recomendacion_categoria_checkbox[]" value="' . $value->id . '" ' . $value->checked . ' onclick="activa_recomendacion_categoria(this);">
                                                <span class="lever switch-col-light-blue"></span>
                                            </label>
                                        </div>';

                    $value->descripcion = '
                                            <textarea  class="form-control" rows="5" id="recomendacion_categoria_descripcion_' . $value->id . '" name="recomendacion_categoria_descripcion_' . $value->id . '" ' . $required_readonly . '>' . $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $value->recomendaciones_descripcion) . '</textarea>';
                } else {
                    $value->checkbox = '<input type="checkbox" class="recomendacionadicional_categoria_checkbox" name="recomendacionadicional_categoria_checkbox[]" value="0" checked/>
                                        <button type="button" class="btn btn-danger waves-effect btn-circle eliminar" data-toggle="tooltip" title="Eliminar recomendación"><i class="fa fa-trash fa-1x"></i></button>';

                    

                    $value->descripcion = '
                                            <div class="form-group">
                                                <label>Descripción</label>
                                                <textarea  class="form-control" rows="5" name="recomendacionadicional_categoria_descripcion[]" required>' . $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $value->recomendaciones_descripcion) . '</textarea>
                                            </div>';
                }

                if ($value->checked) {
                    $total += 1;
                }
            }

            // respuesta
            $dato['data'] = $tabla;
            $dato['total'] = $total;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato['total'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }



    public function datosproyectolimpiartexto($proyecto, $recsensorial, $texto)
    {
        $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);

        $texto = str_replace($proyecto->proyecto_clienteinstalacion, 'INSTALACION_NOMBRE', $texto);
        $texto = str_replace($proyecto->proyecto_clientedireccionservicio, 'INSTALACION_DIRECCION', $texto);
        $texto = str_replace($reportefecha[2] . " de " . $meses[($reportefecha[1] + 0)] . " del año " . $reportefecha[0], 'REPORTE_FECHA_LARGA', $texto);

        if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = pemex, 0 = cliente
        {
            $texto = str_replace($proyecto->catsubdireccion->catsubdireccion_nombre, 'SUBDIRECCION_NOMBRE', $texto);
            $texto = str_replace($proyecto->catgerencia->catgerencia_nombre, 'GERENCIA_NOMBRE', $texto);
            $texto = str_replace($proyecto->catactivo->catactivo_nombre, 'ACTIVO_NOMBRE', $texto);
        } else {
            $texto = str_replace($recsensorial->recsensorial_empresa, 'PEMEX Exploración y Producción', $texto);
        }

        return $texto;
    }


    public function datosproyectoreemplazartexto($proyecto, $recsensorial, $texto)
    {
        $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);

        $texto = str_replace('INSTALACION_NOMBRE', $proyecto->proyecto_clienteinstalacion, $texto);
        $texto = str_replace('INSTALACION_DIRECCION', $proyecto->proyecto_clientedireccionservicio, $texto);
        $texto = str_replace('INSTALACION_CODIGOPOSTAL', 'C.P. ' . $recsensorial->codigopostal, $texto);
        $texto = str_replace('INSTALACION_COORDENADAS', $recsensorial->coordenadas, $texto);
        $texto = str_replace('REPORTE_FECHA_LARGA', $reportefecha[2] . " de " . $meses[($reportefecha[1] + 0)] . " del año " . $reportefecha[0], $texto);

        if (($recsensorial->tipocliente + 0) == 1) // 1 = pemex, 0 = cliente
        {
            $texto = str_replace('SUBDIRECCION_NOMBRE', $proyecto->catsubdireccion->catsubdireccion_nombre, $texto);
            $texto = str_replace('GERENCIA_NOMBRE', $proyecto->catgerencia->catgerencia_nombre, $texto);
            $texto = str_replace('ACTIVO_NOMBRE', $proyecto->catactivo->catactivo_nombre, $texto);
        } else {
            $texto = str_replace('SUBDIRECCION_NOMBRE', '', $texto);
            $texto = str_replace('GERENCIA_NOMBRE', '', $texto);
            $texto = str_replace('ACTIVO_NOMBRE', '', $texto);

            $texto = str_replace('PEMEX Exploración y Producción', $recsensorial->recsensorial_empresa, $texto);
        }

        return $texto;
    }

        /**
     * Display the specified resource.
     *
     * @param  int  $reporteregistro_id
     * @param  int  $archivo_opcion
     * @return \Illuminate\Http\Response
     */
    public function reportenom0353mapaubicacion($reporteregistro_id, $archivo_opcion)
    {
        $reporte  = reportenom0353Model::findOrFail($reporteregistro_id);

        if ($archivo_opcion == 0) {
            return Storage::response($reporte->reportenom0353_ubicacionfoto);
        } else {
            return Storage::download($reporte->reportenom0353_ubicacionfoto);
        }
    }

    
    /**
     * Display the specified resource.
     *
     * @param int $reporteregistro_id
     * @param int $responsabledoc_tipo
     * @param int $responsabledoc_opcion
     * @return \Illuminate\Http\Response
     */
    public function reportenom0353responsabledocumento($reporteregistro_id, $responsabledoc_tipo, $responsabledoc_opcion)
    {
        $reporte = reportenom0353Model::findOrFail($reporteregistro_id);

        if ($responsabledoc_tipo == 1) {
            if ($responsabledoc_opcion == 0) {
                return Storage::response($reporte->reportenom0353_responsable1documento);
            } else {
                return Storage::download($reporte->reportenom0353_responsable1documento);
            }
        } else {
            if ($responsabledoc_opcion == 0) {
                return Storage::response($reporte->reportenom0353_responsable2doc);
            } else {
                return Storage::download($reporte->reportenom0353_responsable2doc);
            }
        }
    }

     /**
     * Display the specified resource.
     *
     * @param int $proyecto_id
     * @param int $categoria_dominio
     * @param int $opcion
     * @return \Illuminate\Http\Response
     */
    public function reportenom0353recomendacionicono($proyecto_id, $categoria_dominio, $opcion)
    {
        //$reporte = reportenom0353Model::findOrFail($proyecto_id);
        $nivel = 0;

        $ruta_muyalto = 'plantillas_reportes/recursosPsico/nivel_riesgo/muyalto.png';
        $ruta_alto = 'plantillas_reportes/recursosPsico/nivel_riesgo/alto.png';
        $ruta_medio = 'plantillas_reportes/recursosPsico/nivel_riesgo/medio.png';
        $ruta_bajo = 'plantillas_reportes/recursosPsico/nivel_riesgo/bajo.png';
        $ruta_nulo = 'plantillas_reportes/recursosPsico/nivel_riesgo/nulo.png';

        //1 nulo, 2 bajo, 3 medio, 4 alto, 5 muy alto
        if ($nivel == 1) {
            if ($opcion == 0) {
                return Storage::response($ruta_nulo);
            } else {
                return Storage::download($ruta_nulo);
            }
        }else if($nivel == 2){
            if ($opcion == 0) {
                return Storage::response($ruta_bajo);
            } else {
                return Storage::download($ruta_bajo);
            }
        }else if($nivel == 3){
            if ($opcion == 0) {
                return Storage::response($ruta_medio);
            } else {
                return Storage::download($ruta_medio);
            }
        }else if($nivel == 4){
            if ($opcion == 0) {
                return Storage::response($ruta_alto);
            } else {
                return Storage::download($ruta_alto);
            }
        }else if($nivel == 5){
            if ($opcion == 0) {
                return Storage::response($ruta_muyalto);
            } else {
                return Storage::download($ruta_muyalto);
            }
        }
    }

}

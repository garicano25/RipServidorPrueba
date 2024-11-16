<?php

namespace App\Http\Controllers\PSICO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\reconocimientopsico\reconocimientopsicoModel;
use App\modelos\reconocimientopsico\recopsiconormativaModel;
use App\modelos\reconocimientopsico\guiavnormativapsicoModel;
use App\modelos\reconocimientopsico\recopsicotrabajadoresModel;
use App\modelos\reconocimientopsico\recopsicoguia5Model;
use App\modelos\reconocimientopsico\proyectotrabajadoresModel;
use App\modelos\reconocimientopsico\seguimientotrabajadoresModel;
use App\modelos\reconocimientopsico\respuestastrabajadorespsicoModel;
use App\modelos\reconocimientopsico\recopsicoproyectotrabajadoresModel;

use App\modelos\proyecto\proyectoModel;

//use DB;
//Re//cursos para abrir el Excel
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\DB;
//fechas
use Carbon\Carbon;
class recopsiconormativaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo');
    }




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    
      /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  
    public function recopsicotrabajadoresCargadosTabla($RECPSICO_ID)
    {
        
        $trabajadores = recopsicotrabajadoresModel::where('RECPSICO_ID', $RECPSICO_ID)
            ->select('ID_RECOPSICOTRABAJADOR', 'RECPSICOTRABAJADOR_NOMBRE', 'RECPSICOTRABAJADOR_MUESTRA')
            ->get();

        return response()->json($trabajadores);
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  
     public function recopsiconormativadatos($RECPSICO_ID)
     {
         
         $normativa = recopsiconormativaModel::where('RECPSICO_ID', $RECPSICO_ID)
             ->select('ID_RECOPSICONORMATIVA', 'RECPSICO_GUIAI', 'RECPSICO_GUIAII', 'RECPSICO_GUIAIII', 'RECPSICO_TOTALTRABAJADORES', 'RECPSICO_TIPOAPLICACION', 'RECPSICO_TOTALAPLICACION', 'RECPSICO_GENEROS', 'RECPSICO_PORCENTAJEHOMBRESTRABAJO', 'RECPSICO_PORCENTAJEMUJERESTRABAJO', 'RECPSICO_TOTALHOMBRESTRABAJO', 'RECPSICO_TOTALMUJERESTRABAJO', 'RECPSICO_TOTALHOMBRESSELECCION', 'RECPSICO_TOTALMUJERESSELECCION')
             ->get();
 
         return response()->json($normativa);
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
            
             //IMPORTACION DE DATOS POR MEDIO DE EXEL
            if ($request->opcion == 1000) {

                //VARIABLES GLOBALES
                $RECPSICO_ID = $request['RECPSICO_ID'];
                $RECPSICOTRABAJADOR_MUESTRA = $request->input('RECPSICOTRABAJADOR_MUESTRA');
                $RECPSICO_APLICACION = $request->input('RECPSICO_APLICACION');
               
                $excelTrabajadoresExists = recopsicotrabajadoresModel::where('RECPSICO_ID', $RECPSICO_ID)->exists();
                $proyectoExists = proyectoModel::where('reconocimiento_psico_id', $RECPSICO_ID)->value('id');

                if ($excelTrabajadoresExists) {
                    recopsicotrabajadoresModel::where('RECPSICO_ID', $RECPSICO_ID)->delete();
                    recopsicoguia5Model::where('RECPSICO_ID', $RECPSICO_ID)->delete();
                    respuestastrabajadorespsicoModel::where('RECPSICO_ID', $RECPSICO_ID)->delete();
                    seguimientotrabajadoresModel::where('proyecto_id', $proyectoExists)->delete(); 
                    recopsicoproyectotrabajadoresModel::where('RECPSICO_ID', $RECPSICO_ID)->delete();
                    proyectotrabajadoresModel::where('proyecto_id', $proyectoExists)->delete();
                }
        
                try {

                    // Verificar si hay un archivo en la solicitud
                    if ($request->hasFile('excelTrabajadores')) {

                        // Obtenemos el Excel de los personales
                        $excel = $request->file('excelTrabajadores');

                        // Cargamos el archivo usando la libreria de PhpSpreadsheet
                        $spreadsheet = IOFactory::load($excel->getPathname());
                        $sheet = $spreadsheet->getActiveSheet();
                        $data = $sheet->toArray(null, true, true, true);

                        // Eliminar los encabezados
                        $data = array_slice($data, 2);
                       


                        $datosGenerales = [];
                        foreach ($data as $row) {
                            // Verificar si la fila no está completamente vacía
                            if (!empty(array_filter($row))) {

                                $datosGenerales[] = $row;
                            }
                        }

                        // return response()->json(['msj' => $datosGenerales, "code" => 500]);

                        // ========================================================== DATOS GENERALES ===============================================================
                        // =========================================================================================================================
                        //Puntos totales
                        $totalTrabajadores = count($datosGenerales);
                        $trabajadoresInsertados = 0;


                        // //BUCAMOS Y ARMAMOS EL ARRAY PARA OBTENER LAS CATEGORIAS CON SU ID
                        // $IdCategorias = [];
                        // $caategorias = recopsicocategoriaModel::where('RECPSICO_ID', $RECPSICO_ID)->get();
                        // foreach ($caategorias as $cat) {
                        //     $clave = $cat->RECPSICO_NOMBRECATEGORIA;
                        //     $IdCategorias[$clave] = $cat->ID_RECOPSICOCATEGORIA;
                        // }


                        // //BUCAMOS Y ARMAMOS EL ARRAY PARA OBTENER LAS AREAS CON SU ID
                        // $IdAreas = [];
                        // $areas = recopsicoareaModel::where('RECPSICO_ID', $RECPSICO_ID)->get();
                        // foreach ($areas as $area) {
                        //     $clave = $area->RECPSICOAREA_NOMBRE;
                        //     $IdAreas[$clave] = $area->ID_RECOPSICOAREA;
                        // }


                        // =========================================================================================================================
                        // =========================================================================================================================


                        // ====================================================== FUNCIONES ===================================================================
                        // =========================================================================================================================
                    


                        // =========================================================================================================================
                        // =========================================================================================================================


                        // ====================================================== INSERCION DE DATOS ===================================================================
                        // ========================================================================================================================
                               
                                //Limpiamos, Validamos y Insertamos todos los datos del Excel
                                foreach ($datosGenerales as $rowData) {

                                    $TRABAJADOR = recopsicotrabajadoresModel::create([
                                        'RECPSICO_ID' => $RECPSICO_ID,
                                        'RECPSICOTRABAJADOR_MUESTRA' => 0,
                                        'RECPSICOTRABAJADOR_ORDEN' => is_null($rowData['A']) ? null : $rowData['A'],
                                        'RECPSICOTRABAJADOR_NOMBRE' => is_null($rowData['B']) ? null : $rowData['B'],
                                        'RECPSICOTRABAJADOR_GENERO' => is_null($rowData['C']) ? null : $rowData['C'],
                                        'RECPSICOTRABAJADOR_AREA' => null,
                                        'RECPSICOTRABAJADOR_CATEGORIA' => null,
                                        'RECPSICOTRABAJADOR_FICHA' => is_null($rowData['F']) ? null : $rowData['F'],
                                        'RECPSICOTRABAJADOR_CORREO' => is_null($rowData['G']) ? null : str_replace(' ', '', $rowData['G']),
                                        'RECPSICOTRABAJADOR_SELECCIONADO' => null,
                                        'RECPSICOTRABAJADOR_OBSERVACION' => null,
                                        'RECPSICOTRABAJADOR_MODALIDAD' => null
                                    ]);

                                    $fechaNacimiento = $rowData['H'];
                                    $fechaNacimientoFormat = Carbon::createFromFormat('d/m/Y', $fechaNacimiento);
                                    $fechaActual = Carbon::now();
                                    $edad = $fechaNacimientoFormat->diffInYears($fechaActual);
                                    //$edadEntera = (int) $edad->y;
                                    // // validar si envia guiav
                                    $TRABAJADORGUIA5 = recopsicoguia5Model::create([
                                        'RECPSICOTRABAJADOR_ID' => $TRABAJADOR->ID_RECOPSICOTRABAJADOR,
                                        'RECPSICO_ID' => $RECPSICO_ID,
                                        'RECPSICOTRABAJADOR_GENERO' => is_null($rowData['C']) ? null : $rowData['C'],
                                        'RECPSICOTRABAJADOR_EDAD' => $edad,
                                        'RECPSICOTRABAJADOR_FNACIMIENTO' => is_null($rowData['H']) ? null : $rowData['H'],
                                        'RECPSICOTRABAJADOR_ESTADOCIVIL' => is_null($rowData['I']) ? null : $rowData['I'],
                                        'RECPSICOTRABAJADOR_ESTUDIOS' => is_null($rowData['J']) ? null : $rowData['J'],
                                        'RECPSICOTRABAJADOR_TIPOPUESTO' => is_null($rowData['K']) ? null : $rowData['K'],
                                        'RECPSICOTRABAJADOR_TIPOCONTRATACION' => is_null($rowData['L']) ? null : $rowData['L'],
                                        'RECPSICOTRABAJADOR_TIPOPERSONAL' => is_null($rowData['M']) ? null : $rowData['M'],
                                        'RECPSICOTRABAJADOR_TIPOJORNADA' => is_null($rowData['N']) ? null : $rowData['N'],
                                        'RECPSICOTRABAJADOR_ROTACIONTURNOS' => is_null($rowData['O']) ? null : $rowData['O'],
                                        'RECPSICOTRABAJADOR_TIEMPOPUESTO' => is_null($rowData['P']) ? null : $rowData['P'],
                                        'RECPSICOTRABAJADOR_TIEMPOEXPERIENCIA' => is_null($rowData['Q']) ? null : $rowData['Q']
                                    ]);


                                    $trabajadoresInsertados++;
                                }
                                
                               
                                if($RECPSICOTRABAJADOR_MUESTRA==1){

                                    $numMuestraHombres = DB::table('recopsiconormativa')
                                        ->where('RECPSICO_ID', $RECPSICO_ID)
                                        ->value('RECPSICO_TOTALHOMBRESSELECCION');
                                
                                    $trabajadoresAleatorios = DB::table('recopsicotrabajadores')
                                        ->where('RECPSICO_ID', $RECPSICO_ID)
                                        ->where('RECPSICOTRABAJADOR_GENERO', 'Masculino') 
                                        ->inRandomOrder() 
                                        ->limit($numMuestraHombres) 
                                        ->get();
                    
                                    foreach ($trabajadoresAleatorios as $trabajador) {
                                        recopsicotrabajadoresModel::where('ID_RECOPSICOTRABAJADOR', $trabajador->ID_RECOPSICOTRABAJADOR)
                                            ->update(['RECPSICOTRABAJADOR_MUESTRA' => 1]);
                                    }

                                    $numMuestraMujeres = DB::table('recopsiconormativa')
                                        ->where('RECPSICO_ID', $RECPSICO_ID)
                                        ->value('RECPSICO_TOTALMUJERESSELECCION');
                                
                                    $trabajadoresAleatorios2 = DB::table('recopsicotrabajadores')
                                        ->where('RECPSICO_ID', $RECPSICO_ID)
                                        ->where('RECPSICOTRABAJADOR_GENERO', 'Femenino') 
                                        ->inRandomOrder() 
                                        ->limit($numMuestraMujeres) 
                                        ->get();
                    
                                    foreach ($trabajadoresAleatorios2 as $trabajador) {
                                        recopsicotrabajadoresModel::where('ID_RECOPSICOTRABAJADOR', $trabajador->ID_RECOPSICOTRABAJADOR)
                                            ->update(['RECPSICOTRABAJADOR_MUESTRA' => 1]);
                                    }
                                }else{
                                    $trabajadoresAleatorios = DB::table('recopsicotrabajadores')
                                    ->where('RECPSICO_ID', $RECPSICO_ID)
                                    ->get();
                    
                                    foreach ($trabajadoresAleatorios as $trabajador) {
                                        recopsicotrabajadoresModel::where('ID_RECOPSICOTRABAJADOR', $trabajador->ID_RECOPSICOTRABAJADOR)
                                            ->update(['RECPSICOTRABAJADOR_MUESTRA' => 1]);
                                    }
                                }

                                //$RECPSICO_MUESTRAINT = intval($RECPSICO_MUESTRA);
                             

                               
                                // $todosLosTrabajadores = DB::table('recopsicotrabajadores')
                                // ->where('RECPSICO_ID', $RECPSICO_ID)
                                // ->get();
                            
                                // $trabajadoresAleatorios = $todosLosTrabajadores->random($RECPSICO_MUESTRAINT);

                                //         foreach ($trabajadoresAleatorios as $trabajador) {
                                //                     recopsicotrabajadoresModel::where('ID_RECOPSICOTRABAJADOR', $trabajador->ID_RECOPSICOTRABAJADOR)
                                //                         ->update(['RECPSICOTRABAJADOR_MUESTRA' => 1]);
                                //         }
                                

                               
                   

                        //RETORNAMOS UN MENSAJE DE CUANTOS INSERTO 
                        return response()->json(['msj' => 'Total de trabajadores cargados : ' . $trabajadoresInsertados . ' de ' . $totalTrabajadores, 'code' => 200]);
                    } else {

                        return response()->json(["msj" => 'No se ha subido ningún archivo Excel', "code" => 500]);
                    }
                } catch (Exception $e) {

                    return response()->json(['msj' => 'Se produjo un error al intentar cargar los trabajadores, inténtelo de nuevo o comuníquelo con el responsable ' . ' ---- ' . $e->getMessage(), 'code' => 500]);
                }
            }
            //SELECCIONAR LAS PREGUNTAS QUE DESEA APLICAR DE LA GUIA V
            if ($request->opcion == 5){
                $RECPSICO_ID = $request['RECPSICO_ID'];

                $guiavNormativa = guiavnormativapsicoModel::where('RECPSICO_ID', $RECPSICO_ID)->exists();

                if ($guiavNormativa) {
                    guiavnormativapsicoModel::where('RECPSICO_ID', $RECPSICO_ID)->delete();
                }
                try {
                    // Procesar las preguntas, asignando 1 si está marcada y 0 si no está presente en el request
                    $guiavNormativaSend = guiavnormativapsicoModel::create([
                        'RECPSICO_ID' => $RECPSICO_ID,
                        'RECPSICO_PREGUNTA1' => $request->has('pregunta1') ? 1 : 0,
                        'RECPSICO_PREGUNTA2' => $request->has('pregunta2') ? 1 : 0,
                        'RECPSICO_PREGUNTA3' => $request->has('pregunta3') ? 1 : 0,
                        'RECPSICO_PREGUNTA4' => $request->has('pregunta4') ? 1 : 0,
                        'RECPSICO_PREGUNTA5' => $request->has('pregunta5') ? 1 : 0,
                        'RECPSICO_PREGUNTA6' => $request->has('pregunta6') ? 1 : 0,
                        'RECPSICO_PREGUNTA7' => $request->has('pregunta7') ? 1 : 0,
                        'RECPSICO_PREGUNTA8' => $request->has('pregunta8') ? 1 : 0,
                        'RECPSICO_PREGUNTA9' => $request->has('pregunta9') ? 1 : 0,
                        'RECPSICO_PREGUNTA10' => $request->has('pregunta10') ? 1 : 0,
                        'RECPSICO_PREGUNTA11' => $request->has('pregunta11') ? 1 : 0,
                        'RECPSICO_PREGUNTA12' => $request->has('pregunta12') ? 1 : 0,
                        'RECPSICO_PREGUNTA13' => $request->has('pregunta13') ? 1 : 0,
                    ]);
            
                    $dato["msj"] = 'Información guardada correctamente';
                    return response()->json($dato);
                } catch (Exception $e) {
                    $dato["msj"] = 'Error: ' . $e->getMessage();
                    return response()->json($dato);
                }
                
            }

                $RECPSICO_ID = $request['RECPSICO_ID'];
                $normativaExists = recopsiconormativaModel::where('RECPSICO_ID', $RECPSICO_ID)->exists();


                if ($normativaExists) {
                    
                    $normativapsico = recopsiconormativaModel::where('RECPSICO_ID', $RECPSICO_ID);
                    $normativapsico->update([
                        'RECPSICO_TOTALTRABAJADORES' => $request['RECPSICO_TOTALTRABAJADORES'],
                        'RECPSICO_TIPOAPLICACION' => $request['RECPSICO_TIPOAPLICACION'],
                        'RECPSICO_TOTALHOMBRESTRABAJO' => $request['RECPSICO_TOTALHOMBRESTRABAJO'],
                        'RECPSICO_TOTALMUJERESTRABAJO' => $request['RECPSICO_TOTALMUJERESTRABAJO'],
                        'RECPSICO_TOTALAPLICACION' => $request['RECPSICO_TOTALAPLICACION'],
                        'RECPSICO_GENEROS' => $request['RECPSICO_GENEROS'],
                        'RECPSICO_GUIAI' => $request['RECPSICO_GUIAI'],
                        'RECPSICO_GUIAII' => $request['RECPSICO_GUIAII'],
                        'RECPSICO_GUIAIII' => $request['RECPSICO_GUIAIII'],
                        'RECPSICO_GUIAV' => $request['RECPSICO_GUIAV'],
                        'RECPSICO_TOTALHOMBRESSELECCION' => $request['RECPSICO_TOTALHOMBRESSELECCION'],
                        'RECPSICO_TOTALMUJERESSELECCION' => $request['RECPSICO_TOTALMUJERESSELECCION'],
                        'updated_at' => now(),
                    ]);
                    $dato["msj"] = 'Información modificada correctamente';
                }else{
                 DB::statement('ALTER TABLE recopsiconormativa AUTO_INCREMENT=1');

                 $normativapsico = recopsiconormativaModel::create($request->all());

                 $dato["msj"] = 'Informacion guardada correctamente';
                }
 

             // respuesta
             $dato['normativapsico'] = $normativapsico;
             return response()->json($dato);
         } catch (Exception $e) {
             $dato["msj"] = 'Error ' . $e->getMessage();
             return response()->json($dato);
         }
     }

     
}

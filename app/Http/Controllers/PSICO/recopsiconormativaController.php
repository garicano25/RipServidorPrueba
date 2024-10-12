<?php

namespace App\Http\Controllers\PSICO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\reconocimientopsico\reconocimientopsicoModel;
use App\modelos\reconocimientopsico\recopsiconormativaModel;
use App\modelos\reconocimientopsico\recopsicotrabajadoresModel;
use App\modelos\reconocimientopsico\recopsicoguia5Model;
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
  
    public function recopsicotrabajadoresCargadosTabla(Request $request)
    {
        $RECPSICO_ID = $request->input('RECPSICO_ID');
        
        $trabajadores = recopsicotrabajadoresModel::where('RECPSICO_ID', 3)
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

                if ($excelTrabajadoresExists) {
                    recopsicotrabajadoresModel::where('RECPSICO_ID', $RECPSICO_ID)->delete();
                    recopsicoguia5Model::where('RECPSICO_ID', $RECPSICO_ID)->delete();
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
                                        'RECPSICOTRABAJADOR_CORREO' => is_null($rowData['G']) ? null : $rowData['G'],
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
                                    $trabajadoresAleatorios = DB::table('recopsicotrabajadores')
                                    ->where('RECPSICO_ID', $RECPSICO_ID)
                                    ->inRandomOrder() 
                                    ->limit($RECPSICO_APLICACION) 
                                    ->get();
                    
                                    foreach ($trabajadoresAleatorios as $trabajador) {
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


             if ($request['ID_RECOPSICONORMATIVA'] == 0) //nuevo
             {
                 // AUTO_INCREMENT
                 DB::statement('ALTER TABLE recopsiconormativa AUTO_INCREMENT=1');
 
                 // guardar
                 $normativapsico = recopsiconormativaModel::create($request->all());
 
                 // mensaje
                 $dato["msj"] = 'Informacion guardada correctamente';
             } else //editar
             {
                 // modificar
                  $normativapsico = recopsiconormativaModel::findOrFail($request['ID_RECOPSICONORMATIVA']);
 
                 $normativapsico->update($request->all());
 
                 // mensaje
                 $dato["msj"] = 'Informacion modificada correctamente';
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

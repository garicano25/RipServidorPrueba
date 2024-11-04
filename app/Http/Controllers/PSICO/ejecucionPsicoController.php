<?php

namespace App\Http\Controllers\PSICO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Mail\sendGuiaPsico;
use Illuminate\Support\Facades\Mail;
use App\modelos\reconocimientopsico\recopsicotrabajadoresModel;
use Illuminate\Support\Facades\Crypt; // Importa el helper de encriptación
class ejecucionPsicoController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('catalogos.psico.ejecucion_psicosocial');
    }

    public function tablaEjecucion()
    {

        $tabla = DB::select('SELECT p.id ID_PROYECTO,
                                    p.proyecto_folio FOLIO,
                                    p.proyecto_fechainicio AS FECHA_INICIO,
                                    p.proyecto_fechafin AS FECHA_FIN,
                                    IFNULL(p.reconocimiento_psico_id, 0) TIENE_RECONOCIMIENTO,
                                    p.proyecto_clienteinstalacion,
                                    p.proyecto_clientedireccionservicio
                            FROM proyecto p
                            LEFT JOIN reconocimientopsico r ON r.id = p.reconocimiento_psico_id  
                            LEFT JOIN serviciosProyecto s ON s.PROYECTO_ID = p.id
                            WHERE s.PSICO_EJECUCION = 1');


        $count = 0;
        foreach ($tabla as $key => $value) {
            $count += 1;

            $value->COUNT = $count;
            $value->instalacion_y_direccion = '<span style="color: #999999;">' . $value->proyecto_clienteinstalacion . '</span><br>' . $value->proyecto_clientedireccionservicio;

            if ($value->TIENE_RECONOCIMIENTO  != 0) {
                $value->boton_mostrar = '<button type="button" class="btn btn-info btn-circle mostrar" style="padding: 0px;"><i class="fa fa-eye fa-2x"></i></button>';
            } else {
                $value->RECONOCIMIENTO_VINCULADO = 'Sin vincular <br><i class="fa fa-ban text-danger"></i>';
                $value->boton_mostrar = '<button type="button" class="btn btn-secondary btn-circle" style="padding: 0px; border: 1px #999999 solid!important;" disabled><i class="fa fa-eye-slash fa-2x"></i></button>';
            }
        }

        $listado['data']  = $tabla;
        return response()->json($listado);
    }

        /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function tablaTrabajadoresOnline($proyecto_id)
    {   
        $existingRecords = DB::table('proyectotrabajadores')
        ->where('proyecto_id', $proyecto_id)
        ->exists();

        if($existingRecords){
            //TARE LOS DATOS DE LA TABLA DE SEGUIMIENTO RELACIONADO CON LA DE PROGRAMA DE TRABAJO
            $tablaOnline = DB::select('SELECT p.TRABAJADOR_NOMBRE TRABAJADOR_NOMBRE,
                                            p.TRABAJADOR_ID TRABAJADOR_ID, 
                                            p.TRABAJADOR_ESTADOCORREO ESTADOCORREO, 
                                            p.TRABAJADOR_FECHAINICIO FECHAINICIO,
                                            p.TRABAJADOR_FECHAFIN FECHAFIN,
                                            p.TRABAJADOR_ESTADOCONTESTADO ESTADOCONTESTADO,
                                            r.RECPSICO_ID,
                                            r.RECPSICOTRABAJADOR_CORREO TRABAJADOR_CORREO
                                        FROM proyectotrabajadores p
                                        LEFT JOIN recopsicotrabajadores r ON p.TRABAJADOR_ID = r.ID_RECOPSICOTRABAJADOR
                                        WHERE p.TRABAJADOR_SELECCIONADO = 1 AND p.TRABAJADOR_MODALIDAD = "Online" AND p.proyecto_id = ' . $proyecto_id . '');


            $count = 0;
            foreach ($tablaOnline as $key => $value) {
            $count += 1;

            $value->COUNT = $count;

            if($value->ESTADOCORREO == 'Sin enviar'){
                $value->TRABAJADOR_ESTADOCORREO = '<span class="badge badge-pill badge-danger" style="font-size: 12px">'. $value->ESTADOCORREO .'</span>';
            }
            else if($value->ESTADOCORREO == null){
                $value->TRABAJADOR_ESTADOCORREO = '<span class="badge badge-pill badge-danger" style="font-size: 12px">Sin enviar</span>';
            }else{
                $value->TRABAJADOR_ESTADOCORREO = '<span class="badge badge-pill badge-success" style="font-size: 12px">'. $value->ESTADOCORREO .'</span>';
                    
            }
            
            $value->FECHAINICIO = $value->FECHAINICIO ?? '';
            $value->FECHAFIN = $value->FECHAFIN ?? '';
            $value->TRABAJADOR_ID = $value->TRABAJADOR_ID;
            $value->TRABAJADOR_NOMBRE = $value->TRABAJADOR_NOMBRE;


            if ($value->ESTADOCONTESTADO == 'Sin iniciar') {
                $value->TRABAJADOR_ESTADOCONTESTADO = '<span class="badge badge-pill badge-danger" style="font-size: 12px">' . $value->ESTADOCONTESTADO . '</span>';
            
            } else if ($value->ESTADOCONTESTADO == 'En proceso'){

                $value->TRABAJADOR_ESTADOCONTESTADO = '<span class="badge badge-pill badge-warning" style="font-size: 12px">' . $value->ESTADOCONTESTADO . '</span>';
            }
            else if($value->ESTADOCONTESTADO == null) {
                    $value->TRABAJADOR_ESTADOCONTESTADO = '<span class="badge badge-pill badge-danger" style="font-size: 12px">Sin iniciar</span>';
                
            }else{
                $value->TRABAJADOR_ESTADOCONTESTADO = '<span class="badge badge-pill badge-success" style="font-size: 12px">' . $value->ESTADOCONTESTADO . '</span>';
            }
            


            $value->boton_enviarCorreo = '<button type="button" class="btn btn-warning btn-circle enviarcorreo" id="enviarCorreoTrabajador'.$count.'" name="enviarCorreoTrabajador" onclick="enviarCorreo('.$value->TRABAJADOR_ID.', '.$value->RECPSICO_ID.')" style="padding: 0px;"><i class="fa fa-paper-plane "></i></button>';
            }
        }



        $online['data']  = $tablaOnline;
        return response()->json($online);
    }

     
   
         /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function tablaTrabajadoresPresencial($proyecto_id)
    {
        $tablaPresencial = DB::select('SELECT p.TRABAJADOR_NOMBRE NOMBRE
                                        FROM proyectotrabajadores p
                                        WHERE p.TRABAJADOR_SELECCIONADO = 1 
                                        AND p.TRABAJADOR_MODALIDAD = "Presencial" 
                                        AND p.proyecto_id = ' . $proyecto_id . '');


        $count = 0;
        foreach ($tablaPresencial as $key => $value) {
            $count += 1;

            $value->COUNT = $count;
            $value->FECHAAPLICACION = '';
            $value->ESTADOCUESTIONARIO = 'En proceso';
        }

        $online['data']  = $tablaPresencial;
        return response()->json($online);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function trabajadoresNombres(Request $request)
     {
         $term = $request->input('term');
     
         $trabajadores = recopsicotrabajadoresModel::where('RECPSICOTRABAJADOR_NOMBRE', 'LIKE', '%' . $term . '%')
             ->where('RECPSICO_ID', 3)
             ->where('RECPSICOTRABAJADOR_MUESTRA', 1)
             ->select('ID_RECOPSICOTRABAJADOR', 'RECPSICOTRABAJADOR_NOMBRE') 
             ->get();
        
         return response()->json($trabajadores);
     }

          /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
     
    public function actualizarFechasOnline(Request $request)
    {

        try {
        $datosJson = $request->input('datos');
        $proyecto_id = $request->input('proyecto_id'); 

        $datos = json_decode($datosJson, true); 

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Error al decodificar JSON: ' . json_last_error_msg()], 400);
        }
    
            $actualizarTablaOnline = DB::update('UPDATE proyectotrabajadores 
            SET TRABAJADOR_FECHAINICIO = ?, TRABAJADOR_FECHAFIN = ?
            WHERE proyecto_id = ? AND TRABAJADOR_MODALIDAD = ?', 
            [$request->fechaInicio, $request->fechaFin, $proyecto_id, 'Online']);

            $response["msj"] = 'Datos actualizados correctamente';
            return response()->json($response);
        } catch (Exception $e) {
            $response["msj"] = 'Error: ' . $e->getMessage();
            return response()->json($response);
        }

    }


    public function envioGuia($tipo, $idPersonal, $idRecsensorial){
        try {
            
            //Validamos el tipo de envio
            if ($tipo == 1) { //-> Envio masivo

            } else { //-> Envio unico

                //Obtenemos los datos del trabajador segun el id
                $datos = DB::select('SELECT t.RECPSICOTRABAJADOR_NOMBRE,
                                            t.RECPSICOTRABAJADOR_CORREO,
                                            s.TRABAJADOR_FECHAINICIO,
                                            s.TRABAJADOR_FECHAFIN,
                                            IF(s.TRABAJADOR_FECHAINICIO = s.TRABAJADOR_FECHAFIN , 0 ,DATEDIFF(s.TRABAJADOR_FECHAFIN, s.TRABAJADOR_FECHAINICIO)) AS DIAS
                                FROM recopsicotrabajadores t
                                LEFT JOIN seguimientotrabajadores s ON s.TRABAJADOR_ID = t.ID_RECOPSICOTRABAJADOR
                                WHERE t.ID_RECOPSICOTRABAJADOR = ?', [$idPersonal]);

                $nombre = $datos[0]->RECPSICOTRABAJADOR_NOMBRE;
                $correo = $datos[0]->RECPSICOTRABAJADOR_CORREO;
                $dias = $datos[0]->DIAS;


                //Obtenemos los datos del reconocimiento para las guias
                $datos = DB::select('SELECT RECPSICO_GUIAI, RECPSICO_GUIAII, RECPSICO_GUIAIII, RECPSICO_GUIAV
                                    FROM recopsiconormativa
                                    WHERE RECPSICO_ID = ?', [$idRecsensorial]);

                $guia1 = $datos[0]->RECPSICO_GUIAI;
                $guia2 = $datos[0]->RECPSICO_GUIAII;
                $guia3 = $datos[0]->RECPSICO_GUIAIII;
                $guia5 = $datos[0]->RECPSICO_GUIAV;
                // Encriptamos las guías
                $encryptedGuia1 = Crypt::encrypt($guia1);
                $encryptedGuia2 = Crypt::encrypt($guia2);
                $encryptedGuia3 = Crypt::encrypt($guia3);
                $encryptedGuia5 = Crypt::encrypt($guia5);

                $encryptedId = Crypt::encrypt($idPersonal);

                Mail::to($correo)->send(new sendGuiaPsico($nombre, $encryptedGuia1, $encryptedGuia2, $encryptedGuia3, $encryptedGuia5, $encryptedId, $dias));
            
                //cambiar el estado de envio de correo en el registro deltrabajador
                DB::table('proyectotrabajadores')
                ->where('TRABAJADOR_ID', $idPersonal)
                ->update(['TRABAJADOR_ESTADOCORREO' => 'Enviado']);  
            }

            //Retornamos respuesta
            $response["msj"] = "Correo enviado correctamente";
            return response()->json($response, 200);
        
        }  catch (Exception $e) {

            $response["msj"] = 'Error: ' . $e->getMessage();
            return response()->json($response, 500);
        }

    }

}
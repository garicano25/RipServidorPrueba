<?php

namespace App\Http\Controllers\programa;
use Carbon\Carbon;
use DateTime;
use DB;

use App\modelos\programa\Programa;
use App\modelos\programa\Actividad;
use App\modelos\proyecto\proyectoModel;
use App\modelos\proyecto\proyectoordentrabajoModel;
use App\modelos\proyecto\proyectoordentrabajodatosModel;
use App\modelos\proyecto\proyectoproveedoresfisicosModel;
use App\modelos\proyecto\proyectoproveedoresquimicosModel;
use App\modelos\recsensorial\recsensorialModel;
//Modelos catalogos
use App\modelos\recsensorial\catregionModel;
use App\modelos\recsensorial\catsubdireccionModel;
use App\modelos\recsensorial\catgerenciaModel;
use App\modelos\recsensorial\catactivoModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class seguimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $catregion = catregionModel::where('catregion_activo', 1)->get();
        $catsubdireccion = catsubdireccionModel::where('catsubdireccion_activo', 1)->orderBy('catsubdireccion_nombre', 'ASC')->get();
        $catgerencia = catgerenciaModel::where('catgerencia_activo', 1)->orderBy('catgerencia_nombre', 'ASC')->get();
        $catactivo = catactivoModel::where('catactivo_activo', 1)->orderBy('catactivo_nombre', 'ASC')->get();
        return view('seguimiento.proyectos', compact('catregion', 'catsubdireccion', 'catgerencia', 'catactivo'));
        // $proyectos = proyectoModel::with(['recsensorial','recsensorial.catregion','recsensorial.catactivo','recsensorial.catgerencia'])->get();
        // return response()->json($proyectos);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listadoproyectos()
    {
       try
        {
            // TIPO DE USUARIO
            
                // $proyectos = proyectoModel::all();
                $proyectos = proyectoModel::with(['proyectoordentrabajo','recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'recsensorial.cliente', 'recsensorial.catregion', 'recsensorial.catgerencia', 'recsensorial.catactivo'])
                                            // ->whereIn('id', [5, 6, 10, 35])
                                            // ->whereIn('id', [59])
                                            ->orderBy('id', 'ASC')
                                            ->get();
            $numero_registro = 0;
            foreach ($proyectos as $key => $value)
            {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                $value->instalacion_y_direccion = '<span style="color: #999999;">'.$value->proyecto_clienteinstalacion.'</span><br>'.$value->proyecto_clientedireccionservicio;
                // $value->recsensorial_folios = $value->recsensorial->recsensorial_foliofisico."<br>".$value->recsensorial->recsensorial_folioquimico;
                // formatear fecha
                if ($value->proyecto_fechainicio)
                {
                    // $value->proyecto_fechainicio = Carbon::createFromFormat('Y-m-d', $value->proyecto_fechainicio)->format('d-m-Y');
                    // $value->proyecto_fechafin = Carbon::createFromFormat('Y-m-d', $value->proyecto_fechafin)->format('d-m-Y');
                    $value->inicio_y_fin = $value->proyecto_fechainicio."<br>".$value->proyecto_fechafin;
                    // $value->proyecto_fechaelaboracion_texto = Carbon::createFromFormat('Y-m-d', $value->proyecto_fechaelaboracion)->format('d-m-Y');
                }
                // Si hay recsensorial
                if ($value->recsensorial_id)
                {
                    // Folios RECSENSORIAL asignado
                    if (($value->recsensorial->recsensorial_alcancefisico+0) > 0 && ($value->recsensorial->recsensorial_alcancefisico+0) > 0)
                    {
                        $value->recsensorial_folios = $value->recsensorial->recsensorial_foliofisico."<br>".$value->recsensorial->recsensorial_folioquimico;
                    }
                    else if (($value->recsensorial->recsensorial_alcancefisico+0) > 0)
                    {
                        $value->recsensorial_folios = $value->recsensorial->recsensorial_foliofisico;
                    }
                    else
                    {
                        $value->recsensorial_folios = $value->recsensorial->recsensorial_folioquimico;
                    }
                }
                else
                {
                    $value->recsensorial_folios = 'Sin asignar<br><i class="fa fa-ban text-danger"></i>';
                }

                $value->proyecto_fechacreacion = Carbon::createFromFormat('Y-m-d H:i:s', $value->created_at)->format('Y-m-d - h:i:s a');
                if ($value->proyectoordentrabajo->count('id') > 0) {
                        $value->boton_mostrar = '<button onclick="detalleproyecto('. $value->id.')" type="button" class="btn btn-info btn-circle" style="padding: 0px;"><i class="fa fa-eye fa-2x"></i></button>';
                }
                else{
                    $value->boton_mostrar = "";
                }    
                
               
            }

            // respuesta
            $dato['data'] = $proyectos;
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            $dato['data'] = 0;
            return response()->json($dato);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $catregion = catregionModel::where('catregion_activo', 1)->get();
        $catsubdireccion = catsubdireccionModel::where('catsubdireccion_activo', 1)->orderBy('catsubdireccion_nombre', 'ASC')->get();
        $catgerencia = catgerenciaModel::where('catgerencia_activo', 1)->orderBy('catgerencia_nombre', 'ASC')->get();
        $catactivo = catactivoModel::where('catactivo_activo', 1)->orderBy('catactivo_nombre', 'ASC')->get();
        $proyecto = proyectoModel::with(['recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'recsensorial.cliente', 'recsensorial.catregion', 'recsensorial.catgerencia', 'recsensorial.catactivo','proyectoproveedores','prorrogas'])->findOrFail($id);
        $ordentrabajo = proyectoordentrabajoModel::where('proyecto_id',$id)->first();
        // dd($detalleproyecto->proyecto->proyecto_fechainicio);
        return view('seguimiento.detalleproyecto', compact('catregion', 'catsubdireccion', 'catgerencia', 'catactivo','proyecto','ordentrabajo'));
        // return view('seguimiento.datosproyecto', compact('catregion', 'catsubdireccion', 'catgerencia', 'catactivo','proyecto','ordentrabajo'));

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $fase_id
     * @param  int  $proyecto_id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detalletablaproyecto($fase_id,$proyecto_id,$id)
    {
        $proyecto = proyectoModel::findOrFail($proyecto_id);
        $fecha_inicioProyecto = Carbon::createFromFormat('Y-m-d', $proyecto->proyecto_fechainicio);
        $diasAcumulados = 0;
        $fecha_inicio = Carbon::createFromFormat('Y-m-d', $proyecto->proyecto_fechainicio);
        $fecha_fin = Carbon::createFromFormat('Y-m-d', $proyecto->proyecto_fechainicio);

        // ACTIVIDADE DE PLANEACION DE LA ORDEN DE SERVISIO
        if ($fase_id == 1) {
            
        }
        //ACTIDADES DE EJECUCION DE LA ORDEN DE SERVICIO
        // ACTIVIDADES DE CIERRE DE ORDEN DE SERVICIOS
        $detalleproyecto = proyectoordentrabajodatosModel::with(['proveedor','proyectoordentrabajo.proyecto'])->where('proyectoordentrabajo_id',$id)->get();
        $dato['data'] = $detalleproyecto;
        return response()->json($dato);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @param  int  $proyectoordentrabajo_id
     * @param  int  $proyectoordentrabajodatos_id
     * @return \Illuminate\Http\Response
     */
    public function detalleprograma($proyecto_id,$proyectoordentrabajo_id,$proyectoordentrabajodatos_id)
    {
        $fechahoy = new DateTime();
        $fechahoy = $fechahoy->format('Y-m-d');
        $programa = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['proyectoordentrabajodatos_id',$proyectoordentrabajodatos_id]])->get();
        // dd($programa);
        foreach ($programa as $key => $value) {
            $value->programa_InicioReal = $value->programa_InicioReal?$value->programa_InicioReal:""; 
            $value->programa_FinReal = $value->programa_FinReal?$value->programa_FinReal:"";
            if ($value->programa_Estatus == 1) {
                $value->Semaforo = '<span class="text-success"><i class="fa fa-check fa-2x"></i> Terminado</span>';
            }
            else
            {
                if ($fechahoy > $value->programa_FinPrograma) {
                    $value->Semaforo = '<span class="text-danger"><i class="fa fa-exclamation-triangle fa-2x"></i> Atrazado</span>';
                }
                else
                {
                    $value->Semaforo = '<span class="text-info"><i class="fa fa-cogs fa-2x"></i>Proceso</span>';
                }
            }
        }
        return response()->json($programa);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @param  int  $proyectoordentrabajo_id
     * @param  int  $fase_id
     * @return \Illuminate\Http\Response
     */
    public function detalleprogramagestion($proyecto_id,$proyectoordentrabajo_id,$fase_id)
    {
        
        if ($fase_id == 1) {
            $programa = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Gestion']])->get();
            if (!$programa->count('id') > 0) {
                $this->CrearPrograma($proyecto_id,$proyectoordentrabajo_id,$fase_id);
            }
            $programa = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Muestreo']])->get();
            if (!$programa->count('id') > 0) {
                $this->CrearPrograma($proyecto_id,$proyectoordentrabajo_id,2);
            }
        }
        if ($fase_id == 3) {
            $programa = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Resultados']])->get();
            if (!$programa->count('id') > 0) {
                $this->CrearPrograma($proyecto_id,$proyectoordentrabajo_id,$fase_id);
            }
        }
        if ($fase_id == 4) {
            $programa = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Finalizacion']])->get();
            if (!$programa->count('id') > 0) {
                $this->CrearPrograma($proyecto_id,$proyectoordentrabajo_id,$fase_id);
            }
        }

        

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




        $fechahoy = new DateTime();
        $fechahoy = $fechahoy->format('Y-m-d');
        if ($fase_id == 1) {
            $programa = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Gestion']])->get();
        }
        elseif($fase_id == 3)
        {
            $programa = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Resultados']])->get();
        }
        elseif($fase_id == 4)
        {
            $programa = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Finalizacion']])->get();
        }
        
        // dd($programa);
        foreach ($programa as $key => $value) {
            $value->Consecutivo = $key+1;
            $value->programa_FechasPrograma = 'Inicio<input type="text" step="any" class="form-controljc FechaIncioPrograma" value="'.$value->programa_InicioPrograma.'" id="programa_InicioPrograma'.$value->id.'" name="programa_InicioPrograma'.$value->id.'">Fin<input type="text" step="any" class="form-controljc FechaFinalPrograma" value="'.$value->programa_FinPrograma.'" id="programa_FinPrograma'.$value->id.'" name="programa_FinPrograma'.$value->id.'">';
            $value->programa_FechasReal = 'Inicio<input type="text" step="any" class="form-controljc FechaIncioReal" value="'.$value->programa_InicioReal.'" id="programa_InicioReal'.$value->id.'" name="programa_InicioReal'.$value->id.'">Fin<input type="text" step="any" class="form-controljc FechaFinalReal"  value="'.$value->programa_FinReal.'" id="programa_FinReal'.$value->id.'" name="programa_FinReal'.$value->id.'">';
            $value->programaResponsable = '<textarea class="form-control" id="programa_Responsable'.$value->id.'" name="programa_Responsable'.$value->id.'">'.($value->programa_Responsable?$value->programa_Responsable:"").'</textarea>';
            $value->programaEstatus = '<br><input type="checkbox" id="programa_Estatus'.$value->id.'" name="programa_Estatus'.$value->id.'" class="material-inputs filled-in chk-col-light-blue" value="1" '.($value->programa_Estatus==1?'checked':'').'><label for="programa_Estatus'.$value->id.'"></label>';
            $value->btnGuardar = '<button type="button" class="btn btn-success waves-effect" id="boton_GuardarActividad'.$value->id.'" onClick="guardarActividad('.$value->id.')"><i class="fa fa-save fa-2x"></i></button>';
            if ($value->programa_Estatus == 1) {
                $value->Semaforo = '<span class="text-success"><i class="fa fa-check fa-2x"></i> Terminado</span>';
            }
            else
            {
                if ($fechahoy > $value->programa_FinPrograma) {
                    $value->Semaforo = '<span class="text-danger"><i class="fa fa-exclamation-triangle fa-2x"></i> Atrazado</span>';
                }
                else
                {
                    $value->Semaforo = '<span class="text-info"><i class="fa fa-cogs fa-2x"></i>Proceso</span>';
                }
            }
        }
        $dato['data'] = $programa;
        return response()->json($dato);

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @param  int  $proyectoordentrabajo_id
     * @param  int  $fase_id
     * @param  int  $reprograma_id
     * @return \Illuminate\Http\Response
     */
    public function detallereprogramagestion($proyecto_id,$proyectoordentrabajo_id,$fase_id,$reprograma_id)
    {
        $proyecto = proyectoprorrogasModel::findOrFail($reprograma_id);
        $fecha_inicioProyecto = Carbon::createFromFormat('Y-m-d', $proyecto->proyectoprorrogas_fechainicio);
        $diasAcumulados = 0;
        ///////// CREACION DE LAS ACTIVIDADES DE LA FASE DE GESTION ///////////////////////////////////////////////////////////////////////////////////
        $fecha_inicio = Carbon::createFromFormat('Y-m-d', $proyecto->proyectoprorrogas_fechainicio);
        $fecha_fin = Carbon::createFromFormat('Y-m-d', $proyecto->proyectoprorrogas_fechainicio);
        $Actividadesprograma = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Gestion']])->get();
        $actividades = Actividad::where('actividad_Clasificacion','Gestion')->get();
        if ($Actividadesprograma->count('id') == 0) {
                foreach ($actividades as $key => $value) {
                    if ($key == 0) {
                        $fecha_fin = date("Y-m-d",strtotime($proyecto->proyecto_fechainicio."+ ".($value->actividad_Acumulado-1)." days"));
                    }
                    else
                    {
                        $fecha_fin = date("Y-m-d",strtotime($proyecto->proyectoprorrogas_fechainicio."+ ".($value->actividad_Acumulado-1)." days"));
                        $fecha_inicio = date("Y-m-d",strtotime($fecha_fin."- ".($value->actividad_Duracion-1)." days"));
                    }
                   $Actividadesprograma = Programa::create([
                        'proyecto_id' => $proyecto_id,
                        'proyectoordentrabajo_id' => $proyectoordentrabajo_id,
                        // 'proyectoordentrabajodatos_id' => $proyectoordentrabajodatos_id,
                        'actividad_id' => $value->id,
                        'programa_Actividad' => $value->actividad_Actividad,
                        'programa_Clasificacion' => $value->actividad_Clasificacion,
                        'programa_DuracionPrograma' => $value->actividad_Duracion,
                        'programa_InicioPrograma' => $fecha_inicio,
                        'programa_FinPrograma' =>  $fecha_fin
                   ]);  
                }
        }
        ///////// CREACION DE LAS ACTIVIDADES DE LA FASE DE RESULTADOS ///////////////////////////////////////////////////////////////////////////////////
        $fecha_inicio = Carbon::createFromFormat('Y-m-d', $proyecto->proyectoprorrogas_fechainicio);
        $fecha_fin = Carbon::createFromFormat('Y-m-d', $proyecto->proyectoprorrogas_fechainicio);
        $Actividadesprograma = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Resultados']])->get();
        $actividades = Actividad::where('actividad_Clasificacion','Resultados')->get();
        if ($Actividadesprograma->count('id') == 0) {
                foreach ($actividades as $key => $value) {
                    if ($key == 0) {
                        $fecha_fin = date("Y-m-d",strtotime($proyecto->proyecto_fechainicio."+ ".($value->actividad_Acumulado-1)." days"));
                    }
                    else
                    {
                        $fecha_fin = date("Y-m-d",strtotime($proyecto->proyectoprorrogas_fechainicio."+ ".($value->actividad_Acumulado-1)." days"));
                        $fecha_inicio = date("Y-m-d",strtotime($fecha_fin."- ".($value->actividad_Duracion-1)." days"));
                    }
                   $Actividadesprograma = Programa::create([
                        'proyecto_id' => $proyecto_id,
                        'proyectoordentrabajo_id' => $proyectoordentrabajo_id,
                        // 'proyectoordentrabajodatos_id' => $proyectoordentrabajodatos_id,
                        'actividad_id' => $value->id,
                        'programa_Actividad' => $value->actividad_Actividad,
                        'programa_Clasificacion' => $value->actividad_Clasificacion,
                        'programa_DuracionPrograma' => $value->actividad_Duracion,
                        'programa_InicioPrograma' => $fecha_inicio,
                        'programa_FinPrograma' =>  $fecha_fin
                   ]);  
                }
        }
        ///////// CREACION DE LAS ACTIVIDADES DE LA FASE DE FINALIZACION DE LA ORDEN ///////////////////////////////////////////////////////////////////////////////////
        $fecha_inicio = Carbon::createFromFormat('Y-m-d', $proyecto->proyectoprorrogas_fechainicio);
        $fecha_fin = Carbon::createFromFormat('Y-m-d', $proyecto->proyectoprorrogas_fechainicio);
        $Actividadesprograma = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Finalizacion']])->get();
        $actividades = Actividad::where('actividad_Clasificacion','Finalizacion')->get();
        if ($Actividadesprograma->count('id') == 0) {
                foreach ($actividades as $key => $value) {
                    if ($key == 0) {
                        $fecha_fin = date("Y-m-d",strtotime($proyecto->proyecto_fechainicio."+ ".($value->actividad_Acumulado-1)." days"));
                    }
                    else
                    {
                        $fecha_fin = date("Y-m-d",strtotime($proyecto->proyectoprorrogas_fechainicio."+ ".($value->actividad_Acumulado-1)." days"));
                        $fecha_inicio = date("Y-m-d",strtotime($fecha_fin."- ".($value->actividad_Duracion-1)." days"));
                    }
                   $Actividadesprograma = Programa::create([
                        'proyecto_id' => $proyecto_id,
                        'proyectoordentrabajo_id' => $proyectoordentrabajo_id,
                        // 'proyectoordentrabajodatos_id' => $proyectoordentrabajodatos_id,
                        'actividad_id' => $value->id,
                        'programa_Actividad' => $value->actividad_Actividad,
                        'programa_Clasificacion' => $value->actividad_Clasificacion,
                        'programa_DuracionPrograma' => $value->actividad_Duracion,
                        'programa_InicioPrograma' => $fecha_inicio,
                        'programa_FinPrograma' =>  $fecha_fin
                   ]);  
                }
        }
        ///////// CREACION DE LAS ACTIVIDADES DE LA FASE DE MUESTREO ///////////////////////////////////////////////////////////////////////////////////
        $proyectoordentrabajodatos = proyectoordentrabajodatosModel::where('proyectoordentrabajo_id',$proyectoordentrabajo_id)->get();
        foreach ($proyectoordentrabajodatos as $key => $valueO) {
            $fecha_inicio = Carbon::createFromFormat('Y-m-d', $proyecto->proyectoprorrogas_fechainicio);
            $fecha_fin = Carbon::createFromFormat('Y-m-d', $proyecto->proyectoprorrogas_fechainicio);
            $Actividadesprograma = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['proyectoordentrabajodatos_id',$valueO->id],['programa_Clasificacion','Muestreo']])->get();
            $actividades = Actividad::where('actividad_Clasificacion','Muestreo')->get();
            if ($Actividadesprograma->count('id') == 0) {
                    foreach ($actividades as $key => $value) {
                        if ($key == 0) {
                            $fecha_fin = date("Y-m-d",strtotime($proyecto->proyecto_fechainicio."+ ".($value->actividad_Acumulado-1)." days"));
                        }
                        else
                        {
                            $fecha_fin = date("Y-m-d",strtotime($proyecto->proyectoprorrogas_fechainicio."+ ".($value->actividad_Acumulado-1)." days"));
                            $fecha_inicio = date("Y-m-d",strtotime($fecha_fin."- ".($value->actividad_Duracion-1)." days"));
                        }
                    $Actividadesprograma = Programa::create([
                        'proyecto_id' => $proyecto_id,
                        'proyectoordentrabajo_id' => $proyectoordentrabajo_id,
                        'proyectoordentrabajodatos_id' => $valueO->id,
                        'actividad_id' => $value->id,
                        'programa_Actividad' => $value->actividad_Actividad,
                        'programa_Clasificacion' => $value->actividad_Clasificacion,
                        'programa_DuracionPrograma' => $value->actividad_Duracion,
                        'programa_InicioPrograma' => $fecha_inicio,
                        'programa_FinPrograma' =>  $fecha_fin
                    ]);  
                    }
            }
        }
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $fechahoy = new DateTime();
        $fechahoy = $fechahoy->format('Y-m-d');
        if ($fase_id == 1) {
            $programa = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Gestion']])->get();
        }
        elseif($fase_id == 3)
        {
            $programa = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Resultados']])->get();
        }
        elseif($fase_id == 4)
        {
            $programa = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Finalizacion']])->get();
        }
        // dd($programa);
        foreach ($programa as $key => $value) {
            $value->Consecutivo = $key+1;
            $value->programa_FechasPrograma = 'Inicio<input type="text" step="any" class="form-controljc FechaIncioPrograma" value="'.$value->programa_InicioPrograma.'" id="programa_InicioPrograma'.$value->id.'" name="programa_InicioPrograma'.$value->id.'">Fin<input type="text" step="any" class="form-controljc FechaFinalPrograma" value="'.$value->programa_FinPrograma.'" id="programa_FinPrograma'.$value->id.'" name="programa_FinPrograma'.$value->id.'">';
            $value->programa_FechasReal = 'Inicio<input type="text" step="any" class="form-controljc FechaIncioReal" value="'.$value->programa_InicioReal.'" id="programa_InicioReal'.$value->id.'" name="programa_InicioReal'.$value->id.'">Fin<input type="text" step="any" class="form-controljc FechaFinalReal"  value="'.$value->programa_FinReal.'" id="programa_FinReal'.$value->id.'" name="programa_FinReal'.$value->id.'">';
            $value->programaResponsable = '<textarea class="form-control" id="programa_Responsable'.$value->id.'" name="programa_Responsable'.$value->id.'">'.($value->programa_Responsable?$value->programa_Responsable:"").'</textarea>';
            $value->programaEstatus = '<br><input type="checkbox" id="programa_Estatus'.$value->id.'" name="programa_Estatus'.$value->id.'" class="material-inputs filled-in chk-col-light-blue" value="1" '.($value->programa_Estatus==1?'checked':'').'><label for="programa_Estatus'.$value->id.'"></label>';
            $value->btnGuardar = '<button type="button" class="btn btn-success waves-effect" id="boton_GuardarActividad'.$value->id.'" onClick="guardarActividad('.$value->id.')"><i class="fa fa-save fa-2x"></i></button>';
            if ($value->programa_Estatus == 1) {
                $value->Semaforo = '<span class="text-success"><i class="fa fa-check fa-2x"></i> Terminado</span>';
            }
            else
            {
                if ($fechahoy > $value->programa_FinPrograma) {
                    $value->Semaforo = '<span class="text-danger"><i class="fa fa-exclamation-triangle fa-2x"></i> Atrazado</span>';
                }
                else
                {
                    $value->Semaforo = '<span class="text-info"><i class="fa fa-cogs fa-2x"></i>Proceso</span>';
                }
            }
        }
        $dato['data'] = $programa;
        return response()->json($dato);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function CrearPrograma($proyecto_id,$proyectoordentrabajo_id,$fase_id)
    {
        $proyecto = proyectoModel::findOrFail($proyecto_id);
        $fecha_inicioProyecto = Carbon::createFromFormat('Y-m-d', $proyecto->proyecto_fechainicio);
        $diasAcumulados = 0;
        ///////// CREACION DE LAS ACTIVIDADES DE LA FASE DE GESTION ///////////////////////////////////////////////////////////////////////////////////
        $fecha_iniciog = Carbon::createFromFormat('Y-m-d', $proyecto->proyecto_fechainicio);
        $fecha_fing = Carbon::createFromFormat('Y-m-d', $proyecto->proyecto_fechainicio);
        $Actividadesprograma = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Gestion']])->get();
        // dd($Actividadesprograma->count('id'));
        $actividades = Actividad::where('actividad_Clasificacion','Gestion')->get();
        if (($Actividadesprograma->count('id') == 0) && ($fase_id == 1)) {
                foreach ($actividades as $key => $value) {
                    if ($key == 0) {
                        $fecha_fing = date("Y-m-d",strtotime($proyecto->proyecto_fechainicio."+ ".($value->actividad_Acumulado-1)." days"));
                    }
                    else
                    {
                        $fecha_fing = date("Y-m-d",strtotime($proyecto->proyecto_fechainicio."+ ".($value->actividad_Acumulado-1)." days"));
                        $fecha_iniciog = date("Y-m-d",strtotime($fecha_fing."- ".($value->actividad_Duracion-1)." days"));
                    }
                   $Actividadesprograma = Programa::create([
                                'proyecto_id' => $proyecto_id,
                                'proyectoordentrabajo_id' => $proyectoordentrabajo_id,
                                // 'proyectoordentrabajodatos_id' => $proyectoordentrabajodatos_id,
                                'actividad_id' => $value->id,
                                'programa_Actividad' => $value->actividad_Actividad,
                                'programa_Clasificacion' => $value->actividad_Clasificacion,
                                'programa_DuracionPrograma' => $value->actividad_Duracion,
                                'programa_InicioPrograma' => $fecha_iniciog,
                                'programa_FinPrograma' =>  $fecha_fing
                   ]);  
                }
        }
        ///////// CREACION DE LAS ACTIVIDADES DE LA FASE DE RESULTADOS ///////////////////////////////////////////////////////////////////////////////////
        $fecha_inicior = Carbon::createFromFormat('Y-m-d', $proyecto->proyecto_fechainicio);
        $fecha_finr = Carbon::createFromFormat('Y-m-d', $proyecto->proyecto_fechainicio);
        $Actividadesprograma = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Resultados']])->get();
        $actividades = Actividad::where('actividad_Clasificacion','Resultados')->get();
        if (($Actividadesprograma->count('id') == 0) && ($fase_id == 3)) {
                foreach ($actividades as $key => $value) {
                        $fecha_finr = date("Y-m-d",strtotime($proyecto->proyecto_fechainicio."+ ".($value->actividad_Acumulado-1)." days"));
                        $fecha_inicior = date("Y-m-d",strtotime($fecha_finr."- ".($value->actividad_Duracion-1)." days"));
                   $Actividadesprograma = Programa::create([
                                'proyecto_id' => $proyecto_id,
                                'proyectoordentrabajo_id' => $proyectoordentrabajo_id,
                                // 'proyectoordentrabajodatos_id' => $proyectoordentrabajodatos_id,
                                'actividad_id' => $value->id,
                                'programa_Actividad' => $value->actividad_Actividad,
                                'programa_Clasificacion' => $value->actividad_Clasificacion,
                                'programa_DuracionPrograma' => $value->actividad_Duracion,
                                'programa_InicioPrograma' => $fecha_inicior,
                                'programa_FinPrograma' =>  $fecha_finr
                   ]);  
                }
        }
        ///////// CREACION DE LAS ACTIVIDADES DE LA FASE DE FINALIZACION DE LA ORDEN ///////////////////////////////////////////////////////////////////////////////////
        $fecha_iniciof = Carbon::createFromFormat('Y-m-d', $proyecto->proyecto_fechainicio);
        $fecha_finf = Carbon::createFromFormat('Y-m-d', $proyecto->proyecto_fechainicio);
        $Actividadesprograma = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Finalizacion']])->get();
        $actividades = Actividad::where('actividad_Clasificacion','Finalizacion')->get();
        if (($Actividadesprograma->count('id') == 0) && ($fase_id == 4)) {
                foreach ($actividades as $key => $value) {
                        $fecha_finf = date("Y-m-d",strtotime($proyecto->proyecto_fechainicio."+ ".($value->actividad_Acumulado-1)." days"));
                        $fecha_iniciof = date("Y-m-d",strtotime($fecha_finf."- ".($value->actividad_Duracion-1)." days"));
                   $Actividadesprograma = Programa::create([
                                'proyecto_id' => $proyecto_id,
                                'proyectoordentrabajo_id' => $proyectoordentrabajo_id,
                                // 'proyectoordentrabajodatos_id' => $proyectoordentrabajodatos_id,
                                'actividad_id' => $value->id,
                                'programa_Actividad' => $value->actividad_Actividad,
                                'programa_Clasificacion' => $value->actividad_Clasificacion,
                                'programa_DuracionPrograma' => $value->actividad_Duracion,
                                'programa_InicioPrograma' => $fecha_iniciof,
                                'programa_FinPrograma' =>  $fecha_finf
                   ]);  
                }
        }
        ///////// CREACION DE LAS ACTIVIDADES DE LA FASE DE MUESTREO ///////////////////////////////////////////////////////////////////////////////////
        $proyectoordentrabajodatos = proyectoordentrabajodatosModel::where('proyectoordentrabajo_id',$proyectoordentrabajo_id)->get();
        $fecha_iniciom = Carbon::createFromFormat('Y-m-d', $proyecto->proyecto_fechainicio);
        $fecha_finm = Carbon::createFromFormat('Y-m-d', $proyecto->proyecto_fechainicio);
        foreach ($proyectoordentrabajodatos as $key => $valueO) {
            $fecha_iniciom = Carbon::createFromFormat('Y-m-d', $proyecto->proyecto_fechainicio);
            $fecha_finm = Carbon::createFromFormat('Y-m-d', $proyecto->proyecto_fechainicio);
            $Actividadesprograma = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['proyectoordentrabajodatos_id',$valueO->id],['programa_Clasificacion','Muestreo']])->get();
            $actividades = Actividad::where('actividad_Clasificacion','Muestreo')->get();
            if (($Actividadesprograma->count('id') == 0) && ($fase_id == 2)) {
                    foreach ($actividades as $key => $value) {
                            $fecha_finm = date("Y-m-d",strtotime($proyecto->proyecto_fechainicio."+ ".($value->actividad_Acumulado-1)." days"));
                            $fecha_iniciom = date("Y-m-d",strtotime($fecha_finm."- ".($value->actividad_Duracion-1)." days"));
                            // dd($fecha_iniciom);
                    $Actividadesprograma = Programa::create([
                                    'proyecto_id' => $proyecto_id,
                                    'proyectoordentrabajo_id' => $proyectoordentrabajo_id,
                                    'proyectoordentrabajodatos_id' => $valueO->id,
                                    'actividad_id' => $value->id,
                                    'programa_Actividad' => $value->actividad_Actividad,
                                    'programa_Clasificacion' => $value->actividad_Clasificacion,
                                    'programa_DuracionPrograma' => $value->actividad_Duracion,
                                    'programa_InicioPrograma' => $fecha_iniciom,
                                    'programa_FinPrograma' =>  $fecha_finm
                    ]);

                    }
                    
            }
        }
    }
}

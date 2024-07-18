<?php

namespace App\Http\Controllers\catalogos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\catalogos\SignatarioModel;
use App\modelos\catalogos\SignatarioacreditacionModel;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTime;
use DB;

class signatarioacreditacionController extends Controller
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
     * Display the specified resource.
     *
     * @param  int  $signatario_id
     * @return \Illuminate\Http\Response
     */
    public function tablasignatarioacreditacion($signatario_id)
    {
        try
        {
            // Signatario
            $signatario = SignatarioModel::with(['proveedor'])->findOrFail($signatario_id);

            $acreditaciones = SignatarioacreditacionModel::with(['alcance', 'alcance.acreditacion', 'alcance.acreditacion.cat_tipoacreditacion', 'alcance.prueba', 'cat_signatarioestado', 'cat_signatariodisponibilidad'])
                                                            ->where('signatario_id', $signatario_id)
                                                            ->where('signatarioAcreditacion_Eliminado', 0)
                                                            ->get();

            $numero_registro = 0;

            // recorrer las acreditaciones para formatear fechas
            foreach ($acreditaciones  as $key => $value) 
            {
                $numero_registro += 1;

                // Formatear fecha
                // $value->signatarioAcreditacion_Expedicion = Carbon::createFromFormat('Y-m-d', $value->signatarioAcreditacion_Expedicion)->format('d-m-Y');
                // $value->signatarioAcreditacion_Vigencia = Carbon::createFromFormat('Y-m-d', $value->signatarioAcreditacion_Vigencia)->format('d-m-Y');

                // Validar si los agentes estan relacionados con acreditaciones
                if (($value->alcance->acreditacion_id+0) > 0)
                {
                    $acreditacion = $value->alcance->acreditacion->cat_tipoacreditacion->catTipoAcreditacion_Nombre.' ['.$value->alcance->acreditacion->acreditacion_Numero.']';
                }
                else
                {
                    $acreditacion = 'N/A';
                }

                // validar si el agente contiene tipo (microbiologico / fisicoquimico)
                if ($value->alcance->acreditacionAlcance_agentetipo)
                {
                    $agente = '['.$value->alcance->prueba->catPrueba_Tipo.'] '.$value->alcance->acreditacionAlcance_agente.' ('.$value->alcance->acreditacionAlcance_agentetipo.')';
                }
                else
                {
                    $agente = '['.$value->alcance->prueba->catPrueba_Tipo.'] '.$value->alcance->acreditacionAlcance_agente;
                }

                // determinar los dias faltantes para vigencia
                $datetime1 = date_create(date('Y-m-d'));
                $datetime2 = date_create($value->signatarioAcreditacion_Vigencia);
                $interval = date_diff($datetime1, $datetime2);

                // Color registro segun vigencia
                switch (($interval->format('%R%a')+0))
                {
                    case (($interval->format('%R%a')+0) <= 30):
                        $value->numero_registro = $numero_registro;
                        $value->acreditacion = '<b class="text-danger">'.$acreditacion.'</b>';
                        $value->agente = '<b class="text-danger">'.$agente.'</b>';
                        $value->estado = '<b class="text-danger">'.$value->cat_signatarioestado->cat_Signatarioestado_Nombre.'</b>';
                        $value->activo = '<b class="text-danger">'.$value->cat_signatariodisponibilidad->cat_Signatariodisponibilidad_Nombre.'</b>';
                        $value->Vigencia_texto = '<b class="text-danger">'.$value->signatarioAcreditacion_Vigencia.' ('.($interval->format('%R%a')+0).' d)</b>';
                        break;
                    case (($interval->format('%R%a')+0) <= 90):
                        $value->numero_registro = $numero_registro;
                        $value->acreditacion = '<b class="text-warning">'.$acreditacion.'</b>';
                        $value->agente = '<b class="text-warning">'.$agente.'</b>';
                        $value->estado = '<b class="text-warning">'.$value->cat_signatarioestado->cat_Signatarioestado_Nombre.'</b>';
                        $value->activo = '<b class="text-warning">'.$value->cat_signatariodisponibilidad->cat_Signatariodisponibilidad_Nombre.'</b>';
                        $value->Vigencia_texto = '<b class="text-warning">'.$value->signatarioAcreditacion_Vigencia.' ('.($interval->format('%R%a')+0).' d)</b>';
                        break;                    
                    default:
                        $value->numero_registro = $numero_registro;
                        $value->acreditacion = $acreditacion;
                        $value->agente = $agente;
                        $value->estado = $value->cat_signatarioestado->cat_Signatarioestado_Nombre;
                        $value->activo = $value->cat_signatariodisponibilidad->cat_Signatariodisponibilidad_Nombre;
                        $value->Vigencia_texto = $value->signatarioAcreditacion_Vigencia;
                        break;
                }

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras','Almacén']) && ($signatario->proveedor->proveedor_Bloqueado + 0) == 0)
                {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                }
                else
                {
                    $value->accion_activa = 0;
                    $value->boton_editar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                }
            }

            $listado['data'] = $acreditaciones;
            return response()->json($listado);
        }
        catch(exception $e){
            $listado['data'] = 0;
            return response()->json($listado);
        }
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
        try
        {
            // formatear campos fechas antes de guardar
            // $request['signatarioAcreditacion_Expedicion'] = Carbon::createFromFormat('d-m-Y', $request['signatarioAcreditacion_Expedicion'])->format('Y-m-d');
            // $request['signatarioAcreditacion_Vigencia'] = Carbon::createFromFormat('d-m-Y', $request['signatarioAcreditacion_Vigencia'])->format('Y-m-d');

            if ($request['signatarioAcreditacion_Eliminado']==0)//valida eliminacion
            {
                if ($request['signatarioacreditacion_id']==0)//nuevo
                {
                    // AUTO_INCREMENT
                    DB::statement('ALTER TABLE signatarioacreditacion AUTO_INCREMENT=1');
                    $acreditaciones = SignatarioacreditacionModel::create($request->all());
                    return response()->json($acreditaciones);
                }
                else //editar
                {
                    $acreditaciones = SignatarioacreditacionModel::findOrFail($request['signatarioacreditacion_id']);
                    $acreditaciones->update($request->all());
                    return response()->json($acreditaciones);
                }
            }
            else //eliminar
            {
                $acreditaciones = SignatarioacreditacionModel::findOrFail($request['signatarioacreditacion_id']);
                $acreditaciones->update($request->all());
                return response()->json($acreditaciones);
            }
        }
        catch(Exception $e){
            return response()->json('Error al guardar');
        }
    }








    /**
     * Display the specified resource.
     *
     * @param  int  $signatarioacreditacion_id
     * @return \Illuminate\Http\Response
     */
    public function signatarioacreditacioneliminar($signatarioacreditacion_id)
    {
        try
        {
            $tabla = SignatarioacreditacionModel::findOrFail($signatarioacreditacion_id);
            $tabla->update(['signatarioAcreditacion_Eliminado' => 1]);

            // Respuesta
            $dato["msj"] = 'Registro eliminado correctamente';
            return response()->json($dato);
        }
        catch(Exception $e){
            // Respuesta
            $dato["msj"] = 'Error al eliminar '.$e->getMessage();
            return response()->json($dato);
            // return $e->getMessage();
        }
    }




}

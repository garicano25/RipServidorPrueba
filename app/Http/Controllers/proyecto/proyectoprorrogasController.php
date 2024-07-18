<?php

namespace App\Http\Controllers\proyecto;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\proyecto\proyectoModel;
use App\modelos\proyecto\proyectoprorrogasModel;
use DB;

class proyectoprorrogasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,ApoyoTecnico,Reportes,Externo');
        // $this->middleware('roles:Superusuario,Administrador,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,ApoyoTecnico,Reportes');
    }


    /**
     * Display a listing of the resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoprorrogastabla($proyecto_id)
    {
        try
        {
            $proyecto = proyectoModel::findOrFail($proyecto_id);


            $prorrogas = proyectoprorrogasModel::where('proyecto_id', $proyecto_id)
                                                ->orderBy('id', 'ASC')
                                                ->get();


            // Formaterar filas
            $numero_registro = 0;
            foreach ($prorrogas as $key => $value)
            {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';
                $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';

                if (($proyecto->proyecto_concluido+0) == 0 && (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto'])))
                {
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                }
                else
                {
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                }
            }

            // respuesta
            $dato["msj"] = 'Datos consultados correctamente';
            $dato['data'] = $prorrogas;
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
     * Display the specified resource.
     *
     * @param  int  $prorroga_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoprorrogaseliminar($prorroga_id)
    {
        try
        {
            $prorroga = proyectoprorrogasModel::where('id', $prorroga_id)->delete();

            // respuesta
            $dato["msj"] = 'Prorroga eliminada correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
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
        try
        {
            // dd($request->all());
            if (($request->prorroga_id+0) == 0) //NUEVA
            {
                // guardar
                DB::statement('ALTER TABLE proyectoprorrogas AUTO_INCREMENT=1');
                $prorroga = proyectoprorrogasModel::create($request->all());
            }
            else //EDITAR
            {
                // modificar
                $prorroga = proyectoprorrogasModel::findOrFail($request->prorroga_id);
                $prorroga->update($request->all());
            }


            // respuesta
            $dato["msj"] = 'Informacion guardada correctamente';
            $dato['prorroga'] = $prorroga;
            return response()->json($dato);
        }
        catch(\Exception $e)
        {
            $dato["msj"] = 'Error en el controlador: '.$e->getMessage();
            $dato['prorroga'] = 0;
            return response()->json($dato);
        }
    }
}

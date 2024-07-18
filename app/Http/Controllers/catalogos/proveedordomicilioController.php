<?php

namespace App\Http\Controllers\catalogos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\catalogos\ProveedorModel;
use App\modelos\catalogos\DomicilioModel;
use DB;

class proveedordomicilioController extends Controller
{



    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo');
        $this->middleware('roles:Superusuario,Administrador,Coordinador,Operativo HI,Almacén,Compras');
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
     * @param  int  $proveedor_id
     * @return \Illuminate\Http\Response
     */
    public function tablaproveedordomicilio($proveedor_id)
    {
        try
        {
            // Proveedores
            $proveedor = ProveedorModel::findOrFail($proveedor_id);

            // Domicilios
            $tabla = DomicilioModel::where('proveedor_id', $proveedor_id)
                                    ->where('proveedorDomicilio_Eliminado', 0)
                                    ->orderBy('id', 'asc')
                                    ->get();

            $numero_registro = 1;
            foreach ($tabla  as $key => $value) 
            {
                $value->numero_registro = $numero_registro;
                $numero_registro += 1;

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proveedor','Compras']) && ($proveedor->proveedor_Bloqueado + 0) == 0)
                {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                }
                else
                {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-success btn-circle"><i class="fa fa-eye"></i></button>';
                }
            }

            $listado['data'] = $tabla;
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
    // validar si el campo ID es mayor a cero, edita, sino guarda
    try {
        if ($request['proveedorDomicilio_Eliminado'] == 0) {//valida eliminacion
            if ($request['domicilio_id'] == 0) {//valida nuevo o edicion
                // AUTO_INCREMENT
                DB::statement('ALTER TABLE proveedordomicilio AUTO_INCREMENT = 1;');

                $domicilio = DomicilioModel::create($request->all());

                return response()->json($domicilio);

            } else {
                // Obtener el domicilio existente
                $domicilio = DomicilioModel::findOrFail($request['domicilio_id']);

                $domicilio->update($request->all());

                return response()->json($domicilio);
            }
            
        } else {//Actualizar campo eliminado
            $domicilio = DomicilioModel::findOrFail($request['domicilio_id']);
            $domicilio->update($request->all());
            return response()->json($domicilio);
        }
    } catch (Exception $e) {
        return response()->json('Error al guardar documento');
    }
}
}
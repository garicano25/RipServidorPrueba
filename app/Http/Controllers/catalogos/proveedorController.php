<?php

namespace App\Http\Controllers\catalogos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\catalogos\ProveedorModel;
use App\modelos\catalogos\Cat_tipoproveedorModel;
use App\modelos\catalogos\Cat_servicioacreditacionModel;
use App\modelos\catalogos\Cat_tipoacreditacionModel;
use App\modelos\catalogos\Cat_areaModel;
use App\modelos\catalogos\Cat_pruebaModel;
use App\modelos\catalogos\Cat_signatarioestadoModel;
use App\modelos\catalogos\Cat_signatariodisponibilidadModel;
use App\modelos\recsensorialquimicos\catSustanciasQuimicasModel;

use DB;


class proveedorController extends Controller
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
        // Consultar el catalogo completo sin el WHERE
        // $cat_tipoproveedor = Cat_tipoproveedorModel::all();

        // consultar CATALOGOS
        $cat_tipoproveedor = Cat_tipoproveedorModel::where('catTipoproveedor_Activo', 1)->get();
        $serviciosacreditacion = Cat_servicioacreditacionModel::where('catServicioAcreditacion_Activo', 1)->get();
        $tipoacreditacion = Cat_tipoacreditacionModel::where('catTipoAcreditacion_Activo', 1)->get();
        $areas = Cat_areaModel::where('catArea_Activo', 1)->get();
        $tipopruebas = Cat_pruebaModel::select('catPrueba_Tipo')->where('catPrueba_Activo', 1)->groupBy('catPrueba_Tipo')->get();
        $pruebas = Cat_pruebaModel::where('catPrueba_Activo', 1)->get();
        $signatarioestado = Cat_signatarioestadoModel::where('cat_Signatarioestado_Activo', 1)->get();
        $signataridisponible = Cat_signatariodisponibilidadModel::all();
        $sustanciasQuimicas =  catSustanciasQuimicasModel::where('ACTIVO', 1)->get();

        return view('catalogos.proveedor.proveedor', compact('cat_tipoproveedor', 'serviciosacreditacion', 'tipoacreditacion', 'areas', 'tipopruebas', 'pruebas', 'signatarioestado', 'signataridisponible', 'sustanciasQuimicas'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tablaproveedor()
    {
        
        $tabla = ProveedorModel::with(['cat_tipoproveedor', 'cat_tipoproveedor.alcance'])
                                ->where('proveedor_Eliminado', 0)
                                ->orderBy('id', 'asc')
                                ->get();

        $numero_registro = 0;
        foreach ($tabla as $key => $value)
        {
            $numero_registro += 1;

            $value->numero_registro = $numero_registro;
            $value->contacto_telefono = $value->proveedor_NombreContacto.'<br>'.$value->proveedor_TelefonoContacto;

            // BOTON MOSTRAR [proveedor Bloqueado]
            if (($value->proveedor_Bloqueado + 0) == 0) //Desbloqueado
            {
                $value->boton_mostrar = '<button type="button" class="btn btn-info btn-circle" style="padding: 0px;"><i class="fa fa-eye fa-2x"></i></button>';
            }
            else
            {
                $value->boton_mostrar = '<button type="button" class="btn btn-secondary btn-circle" style="padding: 0px;" data-toggle="tooltip" title="Solo lectura"><i class="fa fa-eye-slash fa-2x"></i></button>';
            }
        }

        $listado['data']  = $tabla;
        return response()->json($listado);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proveedor_id
     * @return \Illuminate\Http\Response
     */
    public function finalizarcaptura($proveedor_id)
    {
        $proveedor = ProveedorModel::findOrFail($proveedor_id);
        $proveedor->proveedor_Bloqueado = 1;
        // dd($proveedor);
        $proveedor->save();
        return response()->json($proveedor);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proveedor_id
     * @param  int  $proveedor_estado
     * @return \Illuminate\Http\Response
     */
    public function proveedorbloqueo($proveedor_id, $proveedor_estado)
    {
        try
        {
            // Proveedor
            $proveedor = ProveedorModel::findOrFail($proveedor_id);

            // Valida estado
            if (($proveedor_estado + 0) == 0)
            {
                $proveedor->proveedor_Bloqueado = 1;
                $dato["msj"] = 'Proveedor bloqueado correctamente';
            }
            else
            {
                $proveedor->proveedor_Bloqueado = 0;
                $dato["msj"] = 'Proveedor desbloqueado correctamente';
            }

            // Guardar cambios
            $proveedor->save();

            // Respuesta
            $dato["proveedor"] = $proveedor;
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["proveedor"] = 0;
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
   

        try {
            if ($request['proveedor_id'] == 0) {
                // AUTO_INCREMENT
                DB::statement('ALTER TABLE proveedor AUTO_INCREMENT=1');
        
                $request['proveedor_Bloqueado'] = 0;
                $request['proveedor_Eliminado'] = 0;
    
                // Crear el proveedor con todos los datos
                $proveedor = ProveedorModel::create($request->except('contactos'));

                $proveedor = ProveedorModel::with(['cat_tipoproveedor.alcance'])->findOrFail($proveedor->id);
    
                return response()->json($proveedor);
               
            } else {
                // Obtener el proveedor existente
                $proveedor = ProveedorModel::findOrFail($request['proveedor_id']);
                $proveedor->update($request->all());          
        
                $proveedor = ProveedorModel::with(['cat_tipoproveedor.alcance'])->findOrFail($request['proveedor_id']);
        
                return response()->json($proveedor);
            }

            
        } catch (Exception $e) {
            return response()->json('Error al guardar', 500);
        }
}
}

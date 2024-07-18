<?php

namespace App\Http\Controllers\catalogos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\catalogos\ProveedorModel;
use App\modelos\catalogos\DocumentoModel;
use Illuminate\Support\Facades\Storage;
use DB;

class proveedordocumentoController extends Controller
{



    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,Externo');
        $this->middleware('roles:Superusuario,Administrador,Proveedor,Coordinador,Operativo HI,Almacén,Compras');
    }






    /**
     * Display the specified resource.
     *
     * @param  int  $proveedor_id
     * @return \Illuminate\Http\Response
     */
    public function tablaproveedordocumento($proveedor_id)
    {
        try {
            // Proveedor
            $proveedor = ProveedorModel::findOrFail($proveedor_id);

            // Documentos
            $tabla = DocumentoModel::where('proveedor_id', $proveedor_id)
                ->where('proveedorDocumento_Eliminado', 0) //Si es 0 es porque no esta eliminado el documento
                ->get();

            $numero_registro = 0;
            foreach ($tabla  as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                // Valida perfil
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador'])) {
                    $value->perfil = 1;
                } else {
                    $value->perfil = 0;
                }

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras']) && ($proveedor->proveedor_Bloqueado + 0) == 0) {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                } else {
                    $value->accion_activa = 0;
                    $value->boton_editar = '<button type="button" class="btn btn-success btn-circle"><i class="fa fa-eye"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                }
            }

            $listado['data'] = $tabla;
            return response()->json($listado);
        } catch (exception $e) {
            $listado['data'] = 0;
            return response()->json($listado);
        }
    }










    /**
     * Display the specified resource.
     *
     * @param  int  $documento_id
     * @return \Illuminate\Http\Response
     */
    public function mostrarpdf($documento_id)
    {
        $documento = DocumentoModel::findOrFail($documento_id);
        return Storage::response($documento->proveedorDocumento_SoportePDF);
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
            if ($request['proveedorDocumento_Eliminado'] == 0) //valida eliminacion
            {
                if ($request['documento_id'] == 0) //nuevo
                {
                    // AUTO_INCREMENT
                    DB::statement('ALTER TABLE proveedordocumento AUTO_INCREMENT=1;');
                    $documento = DocumentoModel::create($request->all());

                    if ($request->file('documento')) {
                        $extension = $request->file('documento')->getClientOriginalExtension();
                        $request['proveedorDocumento_SoportePDF'] = $request->file('documento')->storeAs('proveedores/' . $request['proveedor_id'] . '/documentos', $documento->id . '.' . $extension);

                        $documento->update($request->all());
                    }

                    return response()->json($documento);
                } else //editar
                {
                    $documento = DocumentoModel::findOrFail($request['documento_id']);

                    if ($request->file('documento')) {
                        $extension = $request->file('documento')->getClientOriginalExtension();
                        $request['proveedorDocumento_SoportePDF'] = $request->file('documento')->storeAs('proveedores/' . $request['proveedor_id'] . '/documentos', $documento->id . '.' . $extension);
                    }

                    $documento->update($request->all());
                    return response()->json($documento);
                }
            } else //eliminar
            {
                $documento = DocumentoModel::findOrFail($request['documento_id']);
                $documento->update($request->all());
                return response()->json($documento);
            }
        } catch (Exception $e) {
            return response()->json('Error al guardar');
        }
    }
}

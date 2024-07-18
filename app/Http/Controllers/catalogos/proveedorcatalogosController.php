<?php

namespace App\Http\Controllers\catalogos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\catalogos\Cat_tipoproveedorModel;
use App\modelos\catalogos\Cat_tipoproveedoralcanceModel;
use App\modelos\catalogos\Cat_servicioacreditacionModel;
use App\modelos\catalogos\Cat_tipoacreditacionModel;
use App\modelos\catalogos\Cat_areaModel;
// use App\modelos\catalogos\Cat_pruebaModel;
use App\modelos\catalogos\Cat_signatarioestadoModel;
use App\modelos\catalogos\Cat_signatariodisponibilidadModel;

class proveedorcatalogosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo,Coordinador');
        $this->middleware('roles:Superusuario,Administrador,Coordinador,Operativo HI,Almacén,Compras');
    }









    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //catalogo
        $cat_tipoproveedor = Cat_tipoproveedorModel::all();
        return view('catalogos.proveedor.proveedor_catalogos', compact('cat_tipoproveedor'));
    }












    /**
     * Display the specified resource.
     *
     * @param  int  $num_catalogo
     * @return \Illuminate\Http\Response
     */
    public function consultacatalogo($num_catalogo)
    {
        switch (($num_catalogo+0)) {
            case 1:
                    // consulta catalogo
                    // $lista = Cat_tipoproveedorModel::all();
                    $lista = Cat_tipoproveedorModel::with(['alcance'])->get();

                    // crear campos NOMBRE Y ESTADO
                    foreach ($lista as $key => $value)
                    {
                        $value['nombre'] = $value->catTipoproveedor_Nombre;

                        // obtener las pruebas
                        $alcance = $value->alcance->pluck('cat_tipoproveedoralcance_alcance');
                        $cadena = "";
                        foreach ($alcance as $key => $dato) 
                        {
                            switch ($dato)
                            {
                                case 2:
                                    $cadena .= "<li>Acreditaciones / Aprobaciones</li>";
                                    break;
                                case 3:
                                    $cadena .= "<li>Equipos</li>";
                                    break;
                                case 4:
                                    $cadena .= "<li>Signatarios</li>";
                                    break;
                                case 5:
                                    $cadena .= "<li>Precios ($)</li>";
                                    break;
                                case 6:
                                    $cadena .= "<li>Alcances (Factores de riesgo / Servicios)</li>";
                                    break;
                            }
                            
                        }
                        $value['lista_alcances'] = $cadena;

                        $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_tipoproveedor();"><i class="fa fa-pencil"></i></button>';

                        if ($value->catTipoproveedor_Activo==1)
                        {
                            $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_tipoproveedor('.$num_catalogo.', '.$value->id.', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        }
                        else
                        {
                            $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_tipoproveedor('.$num_catalogo.', '.$value->id.', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        }
                    }
                    break;
            case 2:
                    // consulta catalogo
                    $lista = Cat_servicioacreditacionModel::all();
                    // crear campos NOMBRE Y ESTADO
                    foreach ($lista as $key => $value)
                    {
                        $value['nombre'] = $value->catServicioAcreditacion_Nombre;
                        $value['estado'] = $value->catServicioAcreditacion_Activo;

                        $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_registro();"><i class="fa fa-pencil"></i></button>';

                        if ($value->catServicioAcreditacion_Activo==1)
                        {
                            $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro('.$num_catalogo.', '.$value->id.', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        }
                        else
                        {
                            $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro('.$num_catalogo.', '.$value->id.', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                        }
                    }
                    break;
            case 3:
                // consulta catalogo
                $lista = Cat_tipoacreditacionModel::all();
                // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value)
                {
                    $value['nombre'] = $value->catTipoAcreditacion_Nombre;
                    $value['estado'] = $value->catTipoAcreditacion_Activo;

                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_registro();"><i class="fa fa-pencil"></i></button>';

                    if ($value->catTipoAcreditacion_Activo==1)
                    {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro('.$num_catalogo.', '.$value->id.', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                    else
                    {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro('.$num_catalogo.', '.$value->id.', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 4:
                // consulta catalogo
                $lista = Cat_areaModel::all();
                // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value)
                {
                    $value['nombre'] = $value->catArea_Nombre;
                    $value['estado'] = $value->catArea_Activo;

                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_registro();"><i class="fa fa-pencil"></i></button>';

                    if ($value->catArea_Activo==1)
                    {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro('.$num_catalogo.', '.$value->id.', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                    else
                    {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro('.$num_catalogo.', '.$value->id.', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 5:
                // consulta catalogo
                $lista = Cat_signatarioestadoModel::all();
                // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value)
                {
                    $value['nombre'] = $value->cat_Signatarioestado_Nombre;
                    $value['estado'] = $value->cat_Signatarioestado_Activo;

                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_registro();"><i class="fa fa-pencil"></i></button>';

                    if ($value->cat_Signatarioestado_Activo==1)
                    {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro('.$num_catalogo.', '.$value->id.', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                    else
                    {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro('.$num_catalogo.', '.$value->id.', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
        }

        // Respuesta
        $catalogo['data']  = $lista;
        return response()->json($catalogo);
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
            switch (($request['catalogo']+0))
            {
                case 1:
                    if ($request['id']==0)
                    {
                        $request['catTipoproveedor_Activo'] = 1;
                        $catalogo = Cat_tipoproveedorModel::create($request->all());

                        // alcances
                        foreach ($request->alcance as $key => $value) 
                        {
                            $sustancia = Cat_tipoproveedoralcanceModel::create([
                                  'cat_tipoproveedor_id' => $catalogo["id"]
                                , 'cat_tipoproveedoralcance_alcance' => $value
                            ]);
                        }
                    }
                    else
                    {
                        $catalogo = Cat_tipoproveedorModel::findOrFail($request['id']);
                        $catalogo->update($request->all());

                        // eliminar alcances
                        $eliminar = Cat_tipoproveedoralcanceModel::where('cat_tipoproveedor_id', $request['id'])->delete();

                         // alcances
                        foreach ($request->alcance as $key => $value) 
                        {
                            $sustancia = Cat_tipoproveedoralcanceModel::create([
                                  'cat_tipoproveedor_id' => $request['id']
                                , 'cat_tipoproveedoralcance_alcance' => $value
                            ]);
                        }
                    }
                    break;
                case 2:
                    if ($request['id']==0)
                    {
                        $request['catServicioAcreditacion_Activo'] = 1;
                        $catalogo = Cat_servicioacreditacionModel::create($request->all());
                    }
                    else
                    {
                        $catalogo = Cat_servicioacreditacionModel::findOrFail($request['id']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 3:
                    if ($request['id']==0)
                    {
                        $request['catTipoAcreditacion_Activo'] = 1;
                        $catalogo = Cat_tipoacreditacionModel::create($request->all());
                    }
                    else
                    {
                        $catalogo = Cat_tipoacreditacionModel::findOrFail($request['id']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 4:
                    if ($request['id']==0)
                    {
                        $request['catArea_Activo'] = 1;
                        $catalogo = Cat_areaModel::create($request->all());
                    }
                    else
                    {
                        $catalogo = Cat_areaModel::findOrFail($request['id']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 5:
                    if ($request['id']==0)
                    {
                        $request['cat_Signatarioestado_Activo'] = 1;
                        $catalogo = Cat_signatarioestadoModel::create($request->all());
                    }
                    else
                    {
                        $catalogo = Cat_signatarioestadoModel::findOrFail($request['id']);
                        $catalogo->update($request->all());
                    }
                    break;
            }

            // Respuesta
            $dato['msj'] = "Datos guardados correctamente";
            $dato['catalogo'] = $catalogo;
            return response()->json($dato);
        }
        catch(Exception $e){
            return response()->json('Error al guardar informacion');
        }
    }














    /**
     * Display the specified resource.
     *
     * @param  int  $catalogo
     * @param  int  $registro
     * @param  int  $estado
     * @return \Illuminate\Http\Response
     */
    public function proveedordesactivacatalogo($catalogo, $registro, $estado)
    {
        try
        {
            switch (($catalogo+0))
            {
                case 1:
                        $tabla = Cat_tipoproveedorModel::findOrFail($registro);
                        $tabla->update(['catTipoproveedor_Activo' => $estado]);
                        break;
                case 2:
                        $tabla = Cat_servicioacreditacionModel::findOrFail($registro);
                        $tabla->update(['catServicioAcreditacion_Activo' => $estado]);
                        break;
                case 3:
                        $tabla = Cat_tipoacreditacionModel::findOrFail($registro);
                        $tabla->update(['catTipoAcreditacion_Activo' => $estado]);
                        break;
                case 4:
                        $tabla = Cat_areaModel::findOrFail($registro);
                        $tabla->update(['catArea_Activo' => $estado]);
                        break;
                case 5:
                        $tabla = Cat_signatarioestadoModel::findOrFail($registro);
                        $tabla->update(['cat_Signatarioestado_Activo' => $estado]);
                        break;
            }

            if ($estado == 0) {
                $dato["msj"] = 'Registro desactivado correctamente';
            }
            else{
                $dato["msj"] = 'Registro activado correctamente';
            }

            // Respuesta
            // $dato["msj"] = 'Dato modificado correctamente';
            return response()->json($dato);
        }
        catch(Exception $e){
            // Respuesta
            $dato["msj"] = 'Error al modificar la información '.$e->getMessage();
            return response()->json($dato);
            // return $e->getMessage();
        }
    }



}

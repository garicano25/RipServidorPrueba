<?php

namespace App\Http\Controllers\SEGURIDADINDUSTRIAL;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Image;
use Carbon\Carbon;
use DateTime;
use DB;
use Artisan;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use ZipArchive;


/// MODEL 

use App\modelos\eppcatalogo\catregionanatomicaModel;
use App\modelos\eppcatalogo\catclaveyeppModel;
use App\modelos\eppcatalogo\catmarcaseppModel;
use App\modelos\eppcatalogo\catnormasnacionalesModel;
use App\modelos\eppcatalogo\catnormasinternacionalesModel;
use App\modelos\eppcatalogo\cattallaseppModel;
use App\modelos\eppcatalogo\catclasificacionriesgoModel;
use App\modelos\eppcatalogo\cattipousoModel;
use App\modelos\eppcatalogo\catepps;
use App\modelos\eppcatalogo\documentoseppModel;




class eppcatalogosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,ApoyoTecnico,Reportes,Externo');
        $this->middleware('roles:Superusuario,Administrador,Coordinador,Operativo HI,Almacén,Compras,Psicólogo,Ergónomo');

        // $this->middleware('asignacionUser:RECSENSORIAL')->only('store');

    }



    public function index()
    {

        $catregionanatomica = catregionanatomicaModel::where('ACTIVO', 1)->get();
        $catclaveyepp = catclaveyeppModel::where('ACTIVO', 1)->get();
        $catmarcas = catmarcaseppModel::where('ACTIVO', 1)->get();
        $catnormasnacionales = catnormasnacionalesModel::where('ACTIVO', 1)->get();
        $catnormasinternacionales = catnormasinternacionalesModel::where('ACTIVO', 1)->get();
        $cattallas = cattallaseppModel::where('ACTIVO', 1)->get();
        $catclasificacion = catclasificacionriesgoModel::where('ACTIVO', 1)->get();
        $cattipouso = cattipousoModel::where('ACTIVO', 1)->get();


        return view('catalogos.seguridad.seguridacatalogo',compact('catregionanatomica', 'catclaveyepp', 'catmarcas', 'catnormasnacionales', 'catnormasinternacionales', 'cattallas', 'catclasificacion', 'cattipouso'));

    }



    public function eppconsultacatalogo($num_catalogo)
    {
        switch (($num_catalogo + 0)) {
            case 1:
                $regiones = catregionanatomicaModel::pluck('NOMBRE_REGION', 'ID_REGION_ANATOMICA')->toArray();

                $claves = catclaveyeppModel::all()->mapWithKeys(function ($item) {
                    $texto = $item->CLAVE . ') ' . $item->EPP;
                    return [$item->ID_CLAVE_EPP => $texto];
                })->toArray();

                $marcas = catmarcaseppModel::pluck('NOMBRE_MARCA', 'ID_MARCAS_EPP')->toArray();

                $clasificaciones = catclasificacionriesgoModel::pluck('CLASIFICACION_RIESGO', 'ID_CLASIFICACION_RIESGO')->toArray();


                $lista = catepps::all();

                foreach ($lista as $key => $value) {
                    
                    $idRegion = $value->REGION_ANATOMICA_EPP;

                    $value['TEXTO_REGION_EPP'] = isset($regiones[$idRegion])
                        ? $regiones[$idRegion]
                        : 'SIN REGIÓN';

                    $idClave = $value->CLAVEYEPP_EPP;
                    $value['TEXTO_CLAVEYEPP_EPP'] = $claves[$idClave] ?? 'SIN CLAVE';

                    $idMarca = $value->MARCA_EPP;
                    $value['TEXTO_MARCA_EPP'] = $marcas[$idMarca] ?? 'SIN MARCA';
                
                    $listaIds = $value->CLASIFICACION_RIESGO_EPP;

                    if (is_string($listaIds)) {
                        $decoded = json_decode($listaIds, true);
                        $listaIds = is_array($decoded) ? $decoded : [];
                    }

                    if (!is_array($listaIds)) {
                        $listaIds = [];
                    }

                    $textoClasificacion = "";

                    foreach ($listaIds as $id) {
                        if (isset($clasificaciones[$id])) {
                            $textoClasificacion .= "• " . $clasificaciones[$id] . "<br>";
                        }
                    }

                    $value["TEXTO_CLASIFICACION_RIESGO_EPP"] = $textoClasificacion ?: "SIN CLASIFICACIÓN";

                    $value['ID_CAT_EPP'] = $value->ID_CAT_EPP;
                    $value['ACTIVO'] = $value->ACTIVO;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_epp();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_CAT_EPP . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_CAT_EPP . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;

            case 2:
                $lista = catregionanatomicaModel::all();

                foreach ($lista as $key => $value) {
                    $value['ID_REGION_ANATOMICA'] = $value->ID_REGION_ANATOMICA;
                    $value['NOMBRE_REGION'] = $value->NOMBRE_REGION;
                    $value['ACTIVO'] = $value->ACTIVO;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_cat_regionanatomica();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_REGION_ANATOMICA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_REGION_ANATOMICA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 3:
                $lista = catclaveyeppModel::all();

                foreach ($lista as $value) {
                    $region = catregionanatomicaModel::find($value->REGION_ANATOMICA_ID);
                    $value['REGION_ANATOMICA_NOMBRE'] = $region ? $region->NOMBRE_REGION : 'N/A';
                    $value['CLAVE_EPP'] = $value->CLAVE . ' - ' . $value->EPP;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_cat_claveyepp();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_CLAVE_EPP . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_CLAVE_EPP . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;

            case 4:
                $lista = catmarcaseppModel::all();

                foreach ($lista as $key => $value) {
                    $value['ID_MARCAS_EPP'] = $value->ID_MARCAS_EPP;
                    $value['NOMBRE_MARCA'] = $value->NOMBRE_MARCA;
                    $value['ACTIVO'] = $value->ACTIVO;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_cat_marcas();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_MARCAS_EPP . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_MARCAS_EPP . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;

            case 5:
                $lista = catnormasnacionalesModel::all();

                foreach ($lista as $key => $value) {
                    $value['ID_NORMAS_NACIONALES'] = $value->ID_NORMAS_NACIONALES;
                    $value['NOMBRE_NORMA_NACIONALES'] = $value->NOMBRE_NORMA_NACIONALES;
                    $value['DESCRIPCION_NORMA_NACIONALES'] = $value->DESCRIPCION_NORMA_NACIONALES;

                    $value['ACTIVO'] = $value->ACTIVO;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_cat_normasnacionales();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_NORMAS_NACIONALES . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_NORMAS_NACIONALES . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;

            case 6:
                $lista = catnormasinternacionalesModel::all();

                foreach ($lista as $key => $value) {
                    $value['ID_NORMAS_INTERNACIONALES'] = $value->ID_NORMAS_INTERNACIONALES;
                    $value['NOMBRE_NORMA_INTERNACIONALES'] = $value->NOMBRE_NORMA_INTERNACIONALES;
                    $value['DESCRIPCION_NORMA_INTERNACIONALES'] = $value->DESCRIPCION_NORMA_INTERNACIONALES;
                    $value['ACTIVO'] = $value->ACTIVO;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_cat_normasinternacionales();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_NORMAS_INTERNACIONALES . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_NORMAS_INTERNACIONALES . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 7:
                $lista = cattallaseppModel::all();

                foreach ($lista as $key => $value) {
                    $value['ID_TALLA'] = $value->ID_TALLA;
                    $value['NOMBRE_TALLA'] = $value->NOMBRE_TALLA;
                    $value['ACTIVO'] = $value->ACTIVO;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_cat_tallas();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_TALLA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_TALLA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 8:
                $lista = catclasificacionriesgoModel::all();

                foreach ($lista as $key => $value) {
                    $value['ID_CLASIFICACION_RIESGO'] = $value->ID_CLASIFICACION_RIESGO;
                    $value['CLASIFICACION_RIESGO'] = $value->CLASIFICACION_RIESGO;
                    $value['ACTIVO'] = $value->ACTIVO;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_cat_clasificacionriesgo();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_CLASIFICACION_RIESGO . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_CLASIFICACION_RIESGO . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 9:
                $lista = cattipousoModel::all();

                foreach ($lista as $key => $value) {
                    $value['ID_TIPO_USO'] = $value->ID_TIPO_USO;
                    $value['TIPO_USO'] = $value->TIPO_USO;
                    $value['ACTIVO'] = $value->ACTIVO;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_cat_tipouso();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_TIPO_USO . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_TIPO_USO . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
        }

        // Respuesta
        $catalogo['data']  = $lista;
        return response()->json($catalogo);
    }


    public function vereppfoto($epp_id)
    {
        $foto = catepps::findOrFail($epp_id);
        return Storage::response($foto->FOTO_EPP);
    }




    public function eppcatalogodesactiva($catalogo, $registro, $estado)
    {
        try {
            switch (($catalogo + 0)) {
                case 1:
                    $tabla = catepps::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 2:
                    $tabla = catregionanatomicaModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 3:
                    $tabla = catclaveyeppModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 4:
                    $tabla = catmarcaseppModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 5:
                    $tabla = catnormasnacionalesModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 6:
                    $tabla = catnormasinternacionalesModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 7:
                    $tabla = cattallaseppModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 8:
                    $tabla = catclasificacionriesgoModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 9:
                    $tabla = cattipousoModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                    
            }

            if ($estado == 0) {
                // Mensaje
                $dato["msj"] = 'Registro desactivado correctamente';
            } else {
                // Mensaje
                $dato["msj"] = 'Registro activado correctamente';
            }

            // Respuesta
            return response()->json($dato);
        } catch (Exception $e) {
            // Respuesta
            $dato["msj"] = 'Error al modificar la información ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /////// DOCUMENTOS EPP


    public function tablaeppdocumento($epp_id)
    {
        try {

            $documentos = documentoseppModel::where('EPP_ID', $epp_id)
                ->where('ACTIVO', 1)
                ->get();

            $numero_registro = 0;

            foreach ($documentos  as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                // Valida perfil
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador'])) {
                    $value->perfil = 1;
                } else {
                    $value->perfil = 0;
                }

                if ($value->DOCUMENTO_TIPO == 1) {
                    $value->TIPO_DOCUMENTO_TEXTO = "Documento";
                } elseif ($value->DOCUMENTO_TIPO == 2) {
                    $value->TIPO_DOCUMENTO_TEXTO = "Imagen";
                } else {
                    $value->TIPO_DOCUMENTO_TEXTO = "N/A";
                }

                

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador'])) {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                } else {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-success btn-circle"><i class="fa fa-eye"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                }
            }

            $listado['data'] = $documentos;
            return response()->json($listado);
        } catch (exception $e) {
            $listado['data'] = 0;
            return response()->json($listado);
        }
    }


    public function vereeppdocumentopdf($documento_id)
    {
        $documento = documentoseppModel::findOrFail($documento_id);
        return Storage::response($documento->EPP_PDF);
    }


    public function vereppfotodocumento($epp_id)
    {
        $foto = documentoseppModel::findOrFail($epp_id);
        return Storage::response($foto->FOTO_DOCUMENTO);
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
            switch (($request['catalogo'] + 0)) {

                // case 1:
                //     if ($request['ID_CAT_EPP'] == 0) {

                //         $request['NORMASNACIONALES_EPP'] = isset($request['NORMASNACIONALES_EPP']) ? $request['NORMASNACIONALES_EPP'] : null;
                //         $request['NORMASINTERNACIONALES_EPP'] = isset($request['NORMASINTERNACIONALES_EPP']) ? $request['NORMASINTERNACIONALES_EPP'] : null;
                //         $request['TALLAS_EPP'] = isset($request['TALLAS_EPP']) ? $request['TALLAS_EPP'] : null;


                //         $catalogo = catepps::create($request->all());
                //     } else {
                //         $catalogo = catepps::findOrFail($request['ID_CAT_EPP']);


                //         if (!isset($request['NORMASNACIONALES_EPP']) || empty($request['NORMASNACIONALES_EPP']) || is_null($request['NORMASNACIONALES_EPP']) || $request['NORMASNACIONALES_EPP'] == '') {
                //             $request['NORMASNACIONALES_EPP'] = null;
                //         } else {
                //             $request['NORMASNACIONALES_EPP'] = $request['NORMASNACIONALES_EPP'];
                //         }



                //         if (!isset($request['NORMASINTERNACIONALES_EPP']) || empty($request['NORMASINTERNACIONALES_EPP']) || is_null($request['NORMASINTERNACIONALES_EPP']) || $request['NORMASINTERNACIONALES_EPP'] == '') {
                //             $request['NORMASINTERNACIONALES_EPP'] = null;
                //         } else {
                //             $request['NORMASINTERNACIONALES_EPP'] = $request['NORMASINTERNACIONALES_EPP'];
                //         }

                //         if (!isset($request['TALLAS_EPP']) || empty($request['TALLAS_EPP']) || is_null($request['TALLAS_EPP']) || $request['TALLAS_EPP'] == '') {
                //             $request['TALLAS_EPP'] = null;
                //         } else {
                //             $request['TALLAS_EPP'] = $request['TALLAS_EPP'];
                //         }


                //         $catalogo->update($request->all());
                //     }
                //     break;

                case 1:

                    try {

                      
                        if ($request['ID_CAT_EPP'] == 0) {

                            $request['NORMASNACIONALES_EPP'] = isset($request['NORMASNACIONALES_EPP']) ? $request['NORMASNACIONALES_EPP'] : null;
                            $request['NORMASINTERNACIONALES_EPP'] = isset($request['NORMASINTERNACIONALES_EPP']) ? $request['NORMASINTERNACIONALES_EPP'] : null;
                            $request['TALLAS_EPP'] = isset($request['TALLAS_EPP']) ? $request['TALLAS_EPP'] : null;
                            $request['CLASIFICACION_RIESGO_EPP'] = isset($request['CLASIFICACION_RIESGO_EPP']) ? $request['CLASIFICACION_RIESGO_EPP'] : null;

                            $catalogo = catepps::create($request->except('FOTO_EPP'));

                         
                            if ($request->hasFile('FOTO_EPP')) {

                                $file = $request->file('FOTO_EPP');

                                $folder = "cat_epp/{$catalogo->ID_CAT_EPP}/FOTO_EPP";
                                $filename = "foto_epp." . $file->getClientOriginalExtension();

                                $ruta = $file->storeAs($folder, $filename);

                                $catalogo->FOTO_EPP = $ruta;
                                $catalogo->save();

                                Log::info("📷 FOTO EPP GUARDADA:", ['ruta' => $ruta]);
                            }
                        }

                      
                        else {

                            $catalogo = catepps::findOrFail($request['ID_CAT_EPP']);

                            if (!isset($request['NORMASNACIONALES_EPP']) || empty($request['NORMASNACIONALES_EPP'])) {
                                $request['NORMASNACIONALES_EPP'] = null;
                            }

                            if (!isset($request['NORMASINTERNACIONALES_EPP']) || empty($request['NORMASINTERNACIONALES_EPP'])) {
                                $request['NORMASINTERNACIONALES_EPP'] = null;
                            }

                            if (!isset($request['TALLAS_EPP']) || empty($request['TALLAS_EPP'])) {
                                $request['TALLAS_EPP'] = null;
                            }

                            if (!isset($request['CLASIFICACION_RIESGO_EPP']) || empty($request['CLASIFICACION_RIESGO_EPP'])) {
                                $request['CLASIFICACION_RIESGO_EPP'] = null;
                            }

                            if ($request->hasFile('FOTO_EPP')) {


                                if ($catalogo->FOTO_EPP && Storage::exists($catalogo->FOTO_EPP)) {
                                    Storage::delete($catalogo->FOTO_EPP);
                                }

                                $file = $request->file('FOTO_EPP');
                                $folder = "cat_epp/{$catalogo->ID_CAT_EPP}/FOTO_EPP";
                                $filename = "foto_epp." . $file->getClientOriginalExtension();

                                $ruta = $file->storeAs($folder, $filename);

                                $catalogo->FOTO_EPP = $ruta;
                            }

                            $catalogo->update($request->except('FOTO_EPP'));
                            $catalogo->save();
                        }

                      
                        return response()->json([
                            'success' => true,
                            'message' => 'Guardado correctamente'
                        ]);
                    } catch (\Exception $e) {

                        Log::error("ERROR AL GUARDAR EPP:", [
                            'error' => $e->getMessage(),
                            'line'  => $e->getLine(),
                            'file'  => $e->getFile(),
                            'trace' => $e->getTraceAsString(),
                            'request' => $request->all()
                        ]);

                        return response()->json([
                            'success' => false,
                            'message' => 'Error al guardar',
                            'error' => $e->getMessage(),
                            'line'  => $e->getLine(),
                            'file'  => $e->getFile()
                        ], 500);
                    }

                    break;



                case 2:
                    if ($request['ID_REGION_ANATOMICA'] == 0) {
                        $catalogo = catregionanatomicaModel::create($request->all());
                    } else {
                        $catalogo = catregionanatomicaModel::findOrFail($request['ID_REGION_ANATOMICA']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 3:
                    if ($request['ID_CLAVE_EPP'] == 0) {
                        $catalogo = catclaveyeppModel::create($request->all());
                    } else {
                        $catalogo = catclaveyeppModel::findOrFail($request['ID_CLAVE_EPP']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 4:
                    if ($request['ID_MARCAS_EPP'] == 0) {
                        $catalogo = catmarcaseppModel::create($request->all());
                    } else {
                        $catalogo = catmarcaseppModel::findOrFail($request['ID_MARCAS_EPP']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 5:
                    if ($request['ID_NORMAS_NACIONALES'] == 0) {
                        $catalogo = catnormasnacionalesModel::create($request->all());
                    } else {
                        $catalogo = catnormasnacionalesModel::findOrFail($request['ID_NORMAS_NACIONALES']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 6:
                    if ($request['ID_NORMAS_INTERNACIONALES'] == 0) {
                        $catalogo = catnormasinternacionalesModel::create($request->all());
                    } else {
                        $catalogo = catnormasinternacionalesModel::findOrFail($request['ID_NORMAS_INTERNACIONALES']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 7:
                    if ($request['ID_TALLA'] == 0) {
                        $catalogo = cattallaseppModel::create($request->all());
                    } else {
                        $catalogo = cattallaseppModel::findOrFail($request['ID_TALLA']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 8:
                    if ($request['ID_CLASIFICACION_RIESGO'] == 0) {
                        $catalogo = catclasificacionriesgoModel::create($request->all());
                    } else {
                        $catalogo = catclasificacionriesgoModel::findOrFail($request['ID_CLASIFICACION_RIESGO']);
                        $catalogo->update($request->all());
                    }
                    break;
                case 9:
                    if ($request['ID_TIPO_USO'] == 0) {
                        $catalogo = cattipousoModel::create($request->all());
                    } else {
                        $catalogo = cattipousoModel::findOrFail($request['ID_TIPO_USO']);
                        $catalogo->update($request->all());
                    }
                    break;
                // case 10:
                //     if ($request['ID_EPP_DOCUMENTO'] == 0) {
                //         $catalogo = documentoseppModel::create($request->all());
                //     } else {
                //         $catalogo = documentoseppModel::findOrFail($request['ID_EPP_DOCUMENTO']);
                //         $catalogo->update($request->all());
                //     }
                //     break;

                case 10:

                    try {

                        if ($request['ID_EPP_DOCUMENTO'] == 0) {

                            $catalogo = documentoseppModel::create($request->except(['EPP_PDF', 'FOTO_DOCUMENTO']));
                        } else {

                            $catalogo = documentoseppModel::findOrFail($request['ID_EPP_DOCUMENTO']);
                            $catalogo->update($request->except(['EPP_PDF', 'FOTO_DOCUMENTO']));
                        }

                        $baseFolder = "cat_epp/{$request->EPP_ID}";

                        // ===============================================================
                        // 2️⃣ SUBIR PDF
                        // ===============================================================
                        if ($request->DOCUMENTO_TIPO == 1 && $request->hasFile('EPP_PDF')) {

                            if ($catalogo->EPP_PDF && Storage::exists($catalogo->EPP_PDF)) {
                                Storage::delete($catalogo->EPP_PDF);
                            }

                            $file = $request->file('EPP_PDF');

                            $folder = "{$baseFolder}/Documento/{$catalogo->ID_EPP_DOCUMENTO}";
                            $filename = "documento." . $file->getClientOriginalExtension();

                            $ruta = $file->storeAs($folder, $filename);

                            // Guardar en BD
                            $catalogo->EPP_PDF = $ruta;
                            $catalogo->save();

                            Log::info("📄 PDF GUARDADO", ['ruta' => $ruta]);
                        }

                        if ($request->DOCUMENTO_TIPO == 2 && $request->hasFile('FOTO_DOCUMENTO')) {

                            if ($catalogo->FOTO_DOCUMENTO && Storage::exists($catalogo->FOTO_DOCUMENTO)) {
                                Storage::delete($catalogo->FOTO_DOCUMENTO);
                            }

                            $file = $request->file('FOTO_DOCUMENTO');

                            $folder = "{$baseFolder}/Imagen/{$catalogo->ID_EPP_DOCUMENTO}";
                            $filename = "imagen." . $file->getClientOriginalExtension();

                            $ruta = $file->storeAs($folder, $filename);

                            $catalogo->FOTO_DOCUMENTO = $ruta;
                            $catalogo->save();

                            Log::info("🖼️ IMAGEN GUARDADA", ['ruta' => $ruta]);
                        }
                    } catch (\Exception $e) {
                        Log::error("❌ Error guardando documento EPP: " . $e->getMessage());
                    }

                    break;
            }

            $dato["code"] = 1;
            $dato["msj"] = 'Información guardada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            return response()->json('Error al guardar informacion');
        }
    }
}

<?php

namespace App\Http\Controllers\clientes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\clientes\clienteModel;
use App\modelos\clientes\clientedocumentoModel;
use App\modelos\clientes\clientepartidasModel;
use App\modelos\clientes\clienteconvenioModel;
use App\modelos\clientes\clientecontratoModel;
use App\modelos\catalogos\Cat_pruebaModel;
use App\modelos\clientes\contratoDocumentoCierre;
use App\modelos\clientes\contratoAnexosModel;
use App\modelos\catalogos\TablaPantillaClientesModel;
use App\modelos\recsensorial\catregionModel;
use App\modelos\recsensorial\catsubdireccionModel;
use App\modelos\recsensorial\catgerenciaModel;
use App\modelos\recsensorial\catactivoModel;


use App\modelos\catalogos\Cat_etiquetaModel;
use App\modelos\catalogos\CatetiquetaopcionesModel;


use App\modelos\clientes\estructuraclientesModel;

use DB;



use Illuminate\Support\Facades\Storage;
use Image;
// use Artisan;


// plugins PDF
use Barryvdh\DomPDF\Facade as PDF;
use PDFMerger;

class clienteController extends Controller
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
        // CATALOGO PRUEBAS
        $catpruebas = Cat_pruebaModel::where('catPrueba_Activo', 1)->OrderBy('catPrueba_Nombre', 'ASC')->get();

        //CATALAGO LOGOS
        $banco_img = TablaPantillaClientesModel::OrderBy('NOMBRE_PLANTILLA', 'ASC')->get();
        $catregion = catregionModel::where('catregion_activo', 1)->get();
        $catsubdireccion = catsubdireccionModel::where('catsubdireccion_activo', 1)->orderBy('catsubdireccion_nombre', 'ASC')->get();
        $catgerencia = catgerenciaModel::where('catgerencia_activo', 1)->orderBy('catgerencia_nombre', 'ASC')->get();
        $catactivo = catactivoModel::where('catactivo_activo', 1)->orderBy('catactivo_nombre', 'ASC')->get();



        $etiqueta = Cat_etiquetaModel::where('ACTIVO', 1)->orderBy('NOMBRE_ETIQUETA', 'ASC')->get();

        return view('catalogos.cliente.cliente', compact('catpruebas', 'banco_img', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo','etiqueta'));
    }



    public function obteneretiquetas($etiquetaId)
{
    $opciones = CatetiquetaopcionesModel::where('ETIQUETA_ID', $etiquetaId)->where('ACTIVO', 1)->orderBy('ID_OPCIONES_ETIQUETAS', 'ASC')->get();
    return response()->json($opciones);
}


public function obtenerEstructuraCliente($clienteId)
{
    $estructuras = DB::table('estructuraclientes')
        ->where('CLIENTES_ID', $clienteId)
        ->orderBy('NIVEL', 'ASC')
        ->get();

    $resulta = [];
    foreach ($estructuras as $estructura) {
        $etiqueta = DB::table('cat_etiquetas')
            ->where('ID_ETIQUETA', $estructura->ETIQUETA_ID)
            ->first();
        
        $opcion = DB::table('catetiquetas_opciones')
            ->where('ID_OPCIONES_ETIQUETAS', $estructura->OPCIONES_ID)
            ->first();

        $resulta[] = [
            'nivel' => $estructura->NIVEL,
            'etiqueta_id' => $estructura->ETIQUETA_ID,
            'etiqueta_nombre' => $etiqueta ? $etiqueta->NOMBRE_ETIQUETA : null,
            'opcion_id' => $estructura->OPCIONES_ID,
            'opcion_nombre' => $opcion ? $opcion->NOMBRE_OPCIONES : null,
        ];
    }

    return response()->json($resulta);
}


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tablacliente()
    {

        $tabla = clienteModel::where('cliente_Eliminado', 0)
            ->orderBy('id', 'asc')
            ->get();

        $numero_registro = 0;
        foreach ($tabla as $key => $value) {
            $numero_registro += 1;

            $value->numero_registro = $numero_registro;
            $value->contacto_telefono = $value->cliente_NombreContacto . '<br>' . $value->cliente_TelefonoContacto;
            $value->vigencia = $value->cliente_fechainicio . '<br>' . $value->cliente_fechafin;
            $value->cliente_Rfc = $value->cliente_Rfc;

            $value->ciudad_pais = $value->cliente_CiudadPais . '<br>' . $value->cliente_Pais;

            // BOTON MOSTRAR [cliente Bloqueado]
            if (($value->cliente_Bloqueado + 0) == 0) //Desbloqueado
            {
                $value->boton_mostrar = '<button type="button" class="btn btn-info btn-circle" style="padding: 0px;"><i class="fa fa-eye fa-2x"></i></button>';
            } else {
                $value->boton_mostrar = '<button type="button" class="btn btn-secondary btn-circle" style="padding: 0px;" data-toggle="tooltip" title="Solo lectura"><i class="fa fa-eye-slash fa-2x"></i></button>';
            }
        }

        $listado['data']  = $tabla;
        return response()->json($listado);
    }


    public function tablaAnexos($contrato_id)
    {
        try {

            $cliente = clientecontratoModel::findOrFail($contrato_id);

            // CONVENIOS
            $tabla = contratoAnexosModel::where('CONTRATO_ID', $contrato_id)->get();

            $numero_registro = 0;
            foreach ($tabla  as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;


                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador']) && ($cliente->cliente_Bloqueado + 0) == 0) {
                    $value->accion_activa = 1;
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle eliminar"><i class="fa fa-trash"></i></button>';
                } else {
                    $value->accion_activa = 0;
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


    public function clientetablaconvenios($contrato_id)
    {
        try {
            // CONTRATO
            $cliente = clientecontratoModel::findOrFail($contrato_id);

            // CONVENIOS
            $tabla = clienteconvenioModel::where('CONTRATO_ID', $contrato_id)->get();

            $numero_registro = 0;
            foreach ($tabla  as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;


                if ((($value->clienteconvenio_montomxn + 0) > 0 || ($value->clienteconvenio_montousd + 0) > 0) && $value->clienteconvenio_vigencia > $cliente->cliente_fechafin) {
                    $value->tipo = 'Convenio de Monto y Plazo';
                } else if (($value->clienteconvenio_montomxn + 0) > 0 || ($value->clienteconvenio_montousd + 0) > 0) {
                    $value->tipo = 'Convenio de Monto';
                } else {
                    $value->tipo = 'Convenio de Plazo';
                }


                if (($value->clienteconvenio_montomxn + 0) > 0) {
                    $value->montomxn = '$' . number_format($value->clienteconvenio_montomxn, 2);
                }


                if (($value->clienteconvenio_montousd + 0) > 0) {
                    $value->montousd = '$' . number_format($value->clienteconvenio_montousd, 2);
                }


                if ($value->clienteconvenio_vigencia > $cliente->cliente_fechafin) {
                    $value->vigencia = $value->clienteconvenio_vigencia;
                }


                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador']) && ($cliente->cliente_Bloqueado + 0) == 0) {
                    // $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle editar"><i class="fa fa-pencil"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle eliminar"><i class="fa fa-trash"></i></button>';
                } else {
                    // $value->accion_activa = 0;
                    $value->boton_editar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
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


    #Funcion para obtener los datos de la tabla de convenios     
    public function clientetablacontratos($cliente_id)
    {
        try {
            // CLIENTE
            $cliente = clienteModel::findOrFail($cliente_id);

            // CONTRATOS
            $tabla = clientecontratoModel::where('CLIENTE_ID', $cliente_id)
                ->where('ACTIVO', 1)
                ->get();

            $NUMERO_REGISTROS = 0;
            foreach ($tabla  as $key => $value) {
                $NUMERO_REGISTROS += 1;
                $value->NUMERO_REGISTROS = $NUMERO_REGISTROS;
                $value->MONTO_SINFORMATO = $value->MONTO;

                if (($value->MONTO + 0) > 0) {
                    $value->MONTO = '$' . number_format($value->MONTO, 2) . $value->MONEDA_MONTO;
                } else {

                    $value->MONTO = 'NA';
                }

                $value->FECHAS = $value->FECHA_INICIO . '<br>' . $value->FECHA_FIN;


                //CONVENIOS
                $tieneConvenio = clienteconvenioModel::where('CONTRATO_ID', $value->ID_CONTRATO)->get();

                if ($tieneConvenio->isEmpty()) {
                    $value->TIENE_CONVENIO = '<i class="fa fa-times-circle fa-2x text-danger"></i>';
                    $value->BIT_CONVENIO = 0;
                } else {
                    $value->TIENE_CONVENIO = '<i class="fa fa-check-circle fa-2x text-success"></i>';
                    $value->BIT_CONVENIO = 1;
                }



                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador']) && ($cliente->cliente_Bloqueado + 0) == 0) {
                    // $value->accion_activa = 1;
                    $value->BOTONES = '<button type="button" class="btn btn-warning btn-circle editar" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fa fa-pencil" ></i></button>

                     <button type="button" class="btn btn-info btn-circle informacion" aria-hidden="true"  data-toggle="tooltip" data-placement="top" title="Documentación" id="contrato-' . $value->ID_CONTRATO . '"><i class="fa fa-file-text "></i></button>
                     
                    
                 
                 
                    ';
                    //  <button type="button" class="btn btn-success btn-circle finalizar" data-toggle="tooltip" data-placement="top" title="Finalizar" style="background-color: green; border-color: green;">
                    //  <i class="fa fa-check-circle" aria-hidden="true" style="color: white; font-size: 20px;"></i>
                    //  </button>
                    // $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle eliminar"><i class="fa fa-trash"></i></button>';
                } else {
                    // $value->accion_activa = 0;
                    $value->BOTONES = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
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
     * @param  int  $convenio_id
     * @return \Illuminate\Http\Response
     */
    public function clientetablaconvenioseliminar($convenio_id)
    {
        try {
            clienteconvenioModel::where('id', $convenio_id)->delete();

            // respuesta
            $dato["msj"] = 'Información eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }

    #eliminar un contrato
    public function clientetablacontratoeliminar($ID_CONTRATO)
    {
        try {
            clientecontratoModel::where('ID_CONTRATO', $ID_CONTRATO)->delete();

            // respuesta
            $dato["msj"] = 'Contrato eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }

    public function tablaclientedocumentoscierre($contrato_id)
    {
        try {

            // Contrato
            $CLIENTE_ID = clientecontratoModel::where('ID_CONTRATO', $contrato_id)
                ->where('ACTIVO', 1)
                ->pluck('CLIENTE_ID')->first();

            //Cliente
            $clienteActivo = clienteModel::where('id', $CLIENTE_ID)->pluck('cliente_Bloqueado')->first();



            $tabla = contratoDocumentoCierre::where('CONTRATO_ID', $contrato_id)
                ->where('ELIMINADO', 0)
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
                if ($value->AUTORIZADO == 0) {
                    if (auth()->user()->hasRoles(['Superusuario', 'Administrador']) && $clienteActivo == 0) {
                        $value->accion_aut = 1;
                        $value->AUTORIZAR_BTN = '<button type="button" class="btn btn-success btn-circle"><i class="fa fa-gavel"></i></button>';
                    } else {
                        $value->accion_aut = 0;
                        $value->AUTORIZAR_BTN = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                    }
                } else {
                    $value->AUTORIZAR_BTN = '<i class="fa fa-check-circle fa-2x text-success"></i>';
                }

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador']) && $clienteActivo == 0) {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                } else {
                    $value->accion_activa = 0;
                    $value->boton_editar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
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


    public function tablaclientedocumentos($contrato_id)
    {
        try {

            // Contrato
            $CLIENTE_ID = clientecontratoModel::where('ID_CONTRATO', $contrato_id)
                ->where('ACTIVO', 1)
                ->pluck('CLIENTE_ID')->first();

            //Cliente
            $clienteActivo = clienteModel::where('id', $CLIENTE_ID)->pluck('cliente_Bloqueado')->first();


            // Documentos
            $tabla = clientedocumentoModel::where('CONTRATO_ID', $contrato_id)
                ->where('clienteDocumento_Eliminado', 0)
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
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador']) && $clienteActivo == 0) {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                } else {
                    $value->accion_activa = 0;
                    $value->boton_editar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
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

    public function listalogo($ID_PLANTILLA_IMAGEN)
    {
        $logo = TablaPantillaClientesModel::findOrFail($ID_PLANTILLA_IMAGEN);
        // return Storage::download($usuario_foto->usuario_foto);
        return Storage::response($logo->RUTA_IMAGEN);
    }

    public function tablaplantilla()
    {
        try {


            $tabla = TablaPantillaClientesModel::get();

            $num_registro = 0;
            foreach ($tabla  as $key => $value) {
                $num_registro += 1;
                $value->num_registro = $num_registro;

                $value->RUTA_IMAGEN_LOGO = '<img src="/listalogo/' . $value->ID_PLANTILLA_IMAGEN . '" alt="" class="img-fluid" style="display: block; margin: auto;" width="200" height="200">';
                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Almacen','Operativo HI','Compras'])) {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle boton_editar" ><i class="fa fa-pencil"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle boton_eliminar"><i class="fa fa-trash"></i></button>';
                } else {
                    $value->accion_activa = 0;
                    $value->boton_editar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
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

    public function catalogoimageneseliminar($ID_PLANTILLA_IMAGEN)
    {
        try {
            TablaPantillaClientesModel::where('ID_PLANTILLA_IMAGEN', $ID_PLANTILLA_IMAGEN)->delete();

            // respuesta
            $dato["msj"] = 'Logo  eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $documento_id
     * @return \Illuminate\Http\Response
     */
    public function clientedocumentopdf($documento_id)
    {
        $documento = clientedocumentoModel::findOrFail($documento_id);
        return Storage::response($documento->clienteDocumento_SoportePDF);
    }


    public function clientedocumentocierrepdf($documento_id)
    {
        $documento = contratoDocumentoCierre::findOrFail($documento_id);
        return Storage::response($documento->RUTA_DOCUMENTO);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $logo_tipo
     * @param  int  $contrato_id
     * @return \Illuminate\Http\Response
     */
    public function clientelogo($logo_tipo, $contrato_id)
    {
        $foto = clientecontratoModel::findOrFail($contrato_id);

        if ($logo_tipo == 1) {

            return Storage::response($foto->CONTRATO_PLANTILLA_LOGOIZQUIERDO);
        } else if ($logo_tipo == 2) {

            return Storage::response($foto->CONTRATO_PLANTILLA_LOGODERECHO);
        } else {

            return Storage::response($foto->cliente_plantillafotoinstalacion);
        }
    }

    public function logoPlantilla($ID_PLANTILLA_IMAGEN)
    {

        $logo = TablaPantillaClientesModel::findOrFail($ID_PLANTILLA_IMAGEN);
        return Storage::response($logo->RUTA_IMAGEN);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $cliente_id
     * @return \Illuminate\Http\Response
     */
    public function clientetablapartidas($contrato_id, $convenio_id)
    {
        try {

            // Contrato
            $CLIENTE_ID = clientecontratoModel::where('ID_CONTRATO', $contrato_id)
                ->where('ACTIVO', 1)
                ->pluck('CLIENTE_ID')->first();

            //Cliente
            $clienteActivo = clienteModel::where('id', $CLIENTE_ID)->pluck('cliente_Bloqueado')->first();

            // Mostrar las partidas dependiento si se llaman por parte del contrato o se llaman de parte de los convenios
            if ($convenio_id == 0) {
                //Mostramos las partidas que no estan relacionadas con ningun convenios estas para la parte de contratos
                $tabla = clientepartidasModel::with(['catprueba'])
                    ->where('CONTRATO_ID', $contrato_id)
                    ->where('CONVENIO_ID', null)
                    ->orderBy('clientepartidas_tipo', 'ASC')
                    ->orderBy('clientepartidas_nombre', 'ASC')
                    ->get();
            } elseif ($convenio_id == -1) {

                //No mostramos partidas porque no hay convenio
                $tabla = clientepartidasModel::with(['catprueba'])
                    ->where('CONTRATO_ID', -1)
                    ->where('ACTIVO', 1)
                    ->orderBy('clientepartidas_tipo', 'ASC')
                    ->orderBy('clientepartidas_nombre', 'ASC')
                    ->get();
            } else {
                //Mostramos las partidas que estan relacionadas con convenios y que esten activas estas para la parte de convenios
                $tabla = clientepartidasModel::with(['catprueba'])
                    ->where('CONTRATO_ID', $contrato_id)
                    ->where('ACTIVO', 1)
                    ->orderBy('clientepartidas_tipo', 'ASC')
                    ->orderBy('clientepartidas_nombre', 'ASC')
                    ->get();
            }



            $numero_registro = 0;
            foreach ($tabla  as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;


                if (($value->clientepartidas_tipo + 0) == 1) {
                    $value->clientepartidas_tipotexto = 'Reconocimiento';
                } else {
                    $value->clientepartidas_tipotexto = 'Informe de resultados';
                }


                if (($value->clientepartidas_tipo + 0) == 1) {
                    $value->clientepartidas_parametro = $value->clientepartidas_nombre;
                } else {
                    $value->clientepartidas_parametro = $value->catprueba->catPrueba_Nombre;
                }

                $value->PRECIO = '$ ' . $value->PRECIO_UNITARIO;

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador']) && $clienteActivo == 0) {
                    $value->accion_activa = 1;

                    if ($value->ACTIVO == 1) {

                        if ($convenio_id == 1) {

                            $value->BOTONES = '
                    
                    <button type="button" class="btn btn-warning btn-circle boton_editar" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fa fa-pencil"></i></button>
                    <button type="button" class="btn btn-danger btn-circle boton_eliminar" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fa fa-trash"></i></button>';
                        } else {
                            $value->BOTONES = '
                        <button type="button" class="btn btn-info btn-circle boton_desactivar" data-toggle="tooltip" data-placement="top" title="Desactivar partidas"><i class="fa fa-lock"></i></button>
                        <button type="button" class="btn btn-warning btn-circle boton_editar" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-danger btn-circle boton_eliminar" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fa fa-trash"></i></button>';
                        }
                    } else {

                        $value->BOTONES = '
                    <button type="button" class="btn btn-warning btn-circle boton_editar" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fa fa-pencil"></i></button>
                    <button type="button" class="btn btn-info btn-circle boton_activar" data-toggle="tooltip" data-placement="top" title="Activar partida"><i class="fa fa-unlock"></i></button>

                    <button type="button" class="btn btn-danger btn-circle boton_eliminar" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fa fa-trash"></i></button>';
                    }
                } else {
                    $value->accion_activa = 0;
                    $value->BOTONES = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
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
     * @param  int  $partida_id
     * @return \Illuminate\Http\Response
     */
    public function clientepartidaeliminar($partida_id)
    {
        try {
            clientepartidasModel::where('id', $partida_id)->delete();

            // respuesta
            $dato["msj"] = 'Información eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    public function clienteanexoeliminar($anexo_id)
    {
        try {
            contratoAnexosModel::where('ID_CONTRATO_ANEXO', $anexo_id)->delete();

            // respuesta
            $dato["msj"] = 'Información eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }

    //Desactivamos la partida
    public function clientepartidaBloqueo($partida_id, $accion)
    {
        try {
            $partida = clientepartidasModel::findOrFail($partida_id);

            if ($accion == 1) {

                $partida->ACTIVO = 0;
                $dato["msj"] = 'Partida Desactivada';
            } else {

                $partida->ACTIVO = 1;
                $dato["msj"] = 'Partida Activada';
            }

            $partida->save();
            $dato["partida"] = $partida;
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $cliente_id
     * @param  int  $cliente_estado
     * @return \Illuminate\Http\Response
     */
    public function clientebloqueo($cliente_id, $cliente_estado)
    {
        try {
            // cliente
            $cliente = clienteModel::findOrFail($cliente_id);

            // Valida estado
            if (($cliente_estado + 0) == 0) {
                $cliente->cliente_Bloqueado = 1;
                $dato["msj"] = 'cliente bloqueado correctamente';
            } else {
                $cliente->cliente_Bloqueado = 0;
                $dato["msj"] = 'cliente desbloqueado correctamente';
            }

            // Guardar cambios
            $cliente->save();

            // Respuesta
            $dato["cliente"] = $cliente;
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["cliente"] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }

    //Funcion para finalizar el contrato
    public function finalizarContrato($contrato_id)
    {
        try {

            $contrato = clientecontratoModel::findOrFail($contrato_id);

            $contrato->CONCLUIDO = 1;

            // Guardar cambios
            $contrato->save();

            // Respuesta
            $dato["contrato"] = $contrato;
            return response()->json($dato);
        } catch (Exception $e) {

            $dato["contrato"] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    //Funcion para autorizar el documento de cierre
    public function autorizardocumento($contrato_id, $nombre)
    {
        try {
            // Obtener el documento de cierre del contrato
            $documento = contratoDocumentoCierre::where('CONTRATO_ID', $contrato_id)
                ->where('NOMBRE', $nombre)
                ->where('ELIMINADO', 0)
                ->where('AUTORIZADO', 0)
                ->first();

            // Verificar si se encontró el documento
            if ($documento) {
                // Actualizar el estado del documento a autorizado
                $documento->AUTORIZADO = 1;

                // Guardar cambios
                $documento->save();

                // Respuesta
                $dato["auth"] = $documento;
                return response()->json($dato);
            } else {
                // Si no se encontró el documento
                $dato["auth"] = 0;
                $dato["msj"] = 'No se encontró el documento de cierre para el contrato.';
                return response()->json($dato);
            }
        } catch (Exception $e) {
            // Manejo de errores
            $dato["auth"] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
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
        // return response()->json([$request->all()], 500);
        // print_r($request->all());
        // exit();
        try {
            // dd($request->all());

         if (($request->opcion + 0) == 1) // NUEVO CLIENTE
        {
            if ($request['cliente_id'] == 0) {
                // AUTO_INCREMENT
                DB::statement('ALTER TABLE cliente AUTO_INCREMENT=1');

                $request['cliente_Bloqueado'] = 0;
                $request['cliente_Eliminado'] = 0;
                $cliente = clienteModel::create($request->all());
                $cliente = clienteModel::findOrFail($cliente->id);

            } else {
                $cliente = clienteModel::findOrFail($request['cliente_id']);
                $cliente->update($request->all());
                $cliente = clienteModel::findOrFail($request['cliente_id']);
            }

            // Guardar etiquetas y opciones seleccionadas
            $etiquetas = $request->input('ETIQUETA_ID', []);
            $opciones = $request->input('OPCIONES_ID', []);
            $nivel = $request->input('NIVEL', []);

            // Primero, eliminar las entradas existentes para este cliente
            estructuraclientesModel::where('CLIENTES_ID', $cliente->id)->delete();

            // Luego, agregar las nuevas entradas
            foreach ($etiquetas as $index => $etiqueta_id) {
                if (!empty($etiqueta_id)) {
                    $opcion_id = $opciones[$index] ?? null;
                    $nivel_id = $nivel[$index] ?? null;

                    estructuraclientesModel::create([
                        'CLIENTES_ID' => $cliente->id,
                        'ETIQUETA_ID' => $etiqueta_id,
                        'OPCIONES_ID' => $opcion_id,
                        'NIVEL'=> $nivel_id,
                    ]);
                }
            }

            return response()->json($cliente);
        }

            


            if (($request->opcion + 0) == 2) // NUEVO Y EDITAR DOCUMENTOS
            {
                if ($request['documento_id'] == 0) //nuevo
                {
                    // AUTO_INCREMENT
                    DB::statement('ALTER TABLE contratos_documentos AUTO_INCREMENT=1;');
                    $documento = clientedocumentoModel::create($request->all());

                    if ($request->file('documento')) {
                        $request['clienteDocumento_SoportePDF'] = $request->file('documento')->storeAs('clientes/contrato-' . $request['CONTRATO_ID'] . '/documentos', str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->file('documento')->getclientoriginalname()));


                        $documento->update($request->all());
                    }

                    return response()->json($documento);
                } else //editar
                {
                    $documento = clientedocumentoModel::findOrFail($request['documento_id']);

                    if ($request->file('documento')) {
                        // Eliminar DOC anterior
                        if (Storage::exists($documento->clienteDocumento_SoportePDF)) {
                            Storage::delete($documento->clienteDocumento_SoportePDF);
                        }

                        $request['clienteDocumento_SoportePDF'] = $request->file('documento')->storeAs('clientes/contrato-' . $request['CONTRATO_ID'] . '/documentos', str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->file('documento')->getclientoriginalname()));

                        // $extension = $request->file('documento')->getClientOriginalExtension();
                        // $request['clienteDocumento_SoportePDF'] = $request->file('documento')->storeAs('clientes/'.$request['cliente_id'].'/documentos', $documento->id.'.'.$extension);
                    }

                    $documento->update($request->all());
                    return response()->json($documento);
                }

                return response()->json($cliente);
            }


            if (($request->opcion + 0) == 3) // ELIMINAR DOCUMENTOS
            {
                $documento = clientedocumentoModel::findOrFail($request['documento_id']);
                $documento->update($request->all());
                return response()->json($documento);
            }


            if (($request->opcion + 0) == 4) // PLANTILLA CLIENTE
            {
                // dd($request->cliente_plantillapiepagina);

                $contrado = clientecontratoModel::findOrFail($request['contrato_id']);

                if ($request->RUTA_PLANTILLA_IZQUIERDO == null || $request->RUTA_PLANTILLA_IZQUIERDO == 'null') {

                    if ($request->file('plantillalogoizquierdo')) {
                        // Eliminar IMG anterior
                        if (Storage::exists($contrado->CONTRATO_PLANTILLA_LOGOIZQUIERDO)) {
                            Storage::delete($contrado->CONTRATO_PLANTILLA_LOGOIZQUIERDO);
                        }


                        $extension = $request->file('plantillalogoizquierdo')->getClientOriginalExtension();

                        $request['CONTRATO_PLANTILLA_LOGOIZQUIERDO'] = $request->file('plantillalogoizquierdo')->storeAs('clientes/contrato-' . $request['contrato_id'] . '/plantilla', 'logoizquierdo.' . $extension);
                    }
                } else {

                    if (Storage::exists($contrado->CONTRATO_PLANTILLA_LOGOIZQUIERDO)) {
                        Storage::delete($contrado->CONTRATO_PLANTILLA_LOGOIZQUIERDO);
                    }
                    $request['CONTRATO_PLANTILLA_LOGOIZQUIERDO'] = $request->RUTA_PLANTILLA_IZQUIERDO;
                }

                if ($request->RUTA_PLANTILLA_DERECHO == 'null' || $request->RUTA_PLANTILLA_DERECHO == null) {

                    if ($request->file('plantillalogoderecho')) {
                        // Eliminar IMG anterior
                        if (Storage::exists($contrado->CONTRATO_PLANTILLA_LOGODERECHO)) {
                            Storage::delete($contrado->CONTRATO_PLANTILLA_LOGODERECHO);
                        }


                        $extension = $request->file('plantillalogoderecho')->getClientOriginalExtension();

                        $request['CONTRATO_PLANTILLA_LOGODERECHO'] = $request->file('plantillalogoderecho')->storeAs('clientes/contrato-' . $request['contrato_id'] . '/plantilla', 'logoderecho.' . $extension);
                    }
                } else {

                    if (Storage::exists($contrado->CONTRATO_PLANTILLA_LOGODERECHO)) {
                        Storage::delete($contrado->CONTRATO_PLANTILLA_LOGODERECHO);
                    }
                    $request['CONTRATO_PLANTILLA_LOGODERECHO'] = $request->RUTA_PLANTILLA_DERECHO;
                }

                // $request['CONTRATO_PLANTILLA_LOGODERECHO'];
                // return response()->json([$request->all()],500);
                // exit();
                $contrado->update($request->all());

                return response()->json($contrado);
            }


            if (($request->opcion + 0) == 5) // PARTIDA INFORMES
            {

                if ($request->CONVENIO == 0) { //LA PARTIDA ES PARA UN CONTRATO

                    if (($request->partida_id + 0) == 0) {
                        // AUTO_INCREMENT
                        DB::statement('ALTER TABLE contratos_partidas AUTO_INCREMENT=1');

                        $request['clientepartidas_nombre'] = $request->parametro_nombre;
                        clientepartidasModel::create($request->all());

                        $dato['msj'] = 'Partida guardada correctamente';
                    } else {
                        $partida = clientepartidasModel::findOrFail($request->partida_id);
                        $request['clientepartidas_nombre'] = $request->parametro_nombre;
                        $partida->update($request->all());


                        $dato['msj'] = 'Datos actualizados correctamente';
                    }
                } else { //LA PARTIDA ES PARA UN CONVENIO

                    if (($request->partida_id + 0) == 0) {
                        // AUTO_INCREMENT
                        DB::statement('ALTER TABLE contratos_partidas AUTO_INCREMENT=1');

                        $request['clientepartidas_nombre'] = $request->parametro_nombre;
                        $request['CONVENIO_ID'] = $request->CONVENIO;

                        clientepartidasModel::create($request->all());

                        $dato['msj'] = 'Partida creada';
                    } else {
                        $partida = clientepartidasModel::findOrFail($request->partida_id);
                        $request['clientepartidas_nombre'] = $request->parametro_nombre;
                        $partida->update($request->all());


                        $dato['msj'] = 'Datos actualizados correctamente';
                    }
                }


                return response()->json($dato);
            }


            if (($request->opcion + 0) == 6) // CONVENIO DE AMPLIACION
            {
                if (($request->convenio_id + 0) == 0) {
                    // AUTO_INCREMENT
                    DB::statement('ALTER TABLE contratos_convenios AUTO_INCREMENT=1');

                    clienteconvenioModel::create($request->all());

                    $dato['msj'] = 'Datos guardados correctamente';
                } else {
                    $partida = clienteconvenioModel::findOrFail($request->convenio_id);

                    $partida->update($request->all());

                    $dato['msj'] = 'Datos actualizados correctamente';
                }

                return response()->json($dato);
            }

            if (($request->opcion + 0) == 7) // GUARDAR UN NUEVO CONTRATO AL CLIENT
            {
                $request['MONTO'] = str_replace(',', '', $request->MONTO);

                if (($request->CONTRATO_ID + 0) == 0) {
                    // AUTO_INCREMENT
                    DB::statement('ALTER TABLE contratos_clientes AUTO_INCREMENT=1');

                    clientecontratoModel::create($request->all());

                    $dato['msj'] = 'El contrato fue registrado correctamente';
                } else {

                    $contrato = clientecontratoModel::findOrFail($request->CONTRATO_ID);

                    $contrato->update($request->all());

                    $dato['msj'] = 'Contrato actualizado correctamente';
                }

                return response()->json($dato);
            }


            if (($request->opcion + 0) == 8) // GUARDAR O ACTUALIZAR DOCUMENTO DE CIERRE
            {
                if ($request['documento_cierre_id'] == 0) //nuevo
                {
                    // AUTO_INCREMENT
                    DB::statement('ALTER TABLE contratos_documentos_cierre AUTO_INCREMENT=1;');
                    $documento = contratoDocumentoCierre::create($request->all());
                    $idDocumentoCierre = $documento->ID_DOCUMENTO_CIERRE;

                    if ($request->file('DOCUMENTO_CIERRE')) {
                        $request['RUTA_DOCUMENTO'] = $request->file('DOCUMENTO_CIERRE')->storeAs('clientes/contrato-' . $request['CONTRATO_ID'] . '/cierre', str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->file('DOCUMENTO_CIERRE')->getclientoriginalname()));


                        $documento->update($request->all());
                    }


                    return response()->json($documento);
                } else //editar
                {
                    $documento = contratoDocumentoCierre::findOrFail($request['documento_cierre_id']);

                    if ($request->file('DOCUMENTO_CIERRE')) {

                        // Eliminar DOC anterior
                        if (Storage::exists($documento->RUTA_DOCUMENTO)) {
                            Storage::delete($documento->RUTA_DOCUMENTO);
                        }

                        $request['RUTA_DOCUMENTO'] = $request->file('DOCUMENTO_CIERRE')->storeAs('clientes/contrato-' . $request['CONTRATO_ID'] . '/cierre', str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->file('DOCUMENTO_CIERRE')->getclientoriginalname()));
                    }

                    $documento->update($request->all());
                    return response()->json($documento);
                }

                return response()->json($cliente);
            }



            if (($request->opcion + 0) == 9) // ACTUALIZAR DOCUMENTOS DE CIERRE
            {
                $documento = contratoDocumentoCierre::findOrFail($request['documento_cierre_id']);
                $documento->update($request->all());
                return response()->json($documento);
            }

            if (($request->opcion + 0) == 10) // PLANTILLA CLIENTE
            {

                // $contrado = TablaPantillaClientesModel::findOrFail($request['ID_PLANTILLA_IMAGEN']);
                DB::statement('ALTER TABLE plantillas_imagenes_clientes AUTO_INCREMENT=1;');
                $banco_img = TablaPantillaClientesModel::create($request->all());

                if ($request->file('logo')) {
                    // Eliminar IMG anterior
                    if (Storage::exists($banco_img->RUTA_IMAGEN)) {
                        Storage::delete($banco_img->RUTA_IMAGEN);
                    }

                    $extension = $request->file('logo')->getClientOriginalExtension();

                    $request['RUTA_IMAGEN'] = $request->file('logo')->storeAs('clientes/Banco de imagenes', $request->NOMBRE_PLANTILLA . '.' . $extension);
                }

                $banco_img->update($request->all());

                return response()->json($banco_img);
            }

            if (($request->opcion + 0) == 11) // PLANTILLA CLIENTE
            {

                if ($request->ELIMINADO == 0) {
                    DB::statement('ALTER TABLE contratros_anexos AUTO_INCREMENT=1;');
                    $anexos = contratoAnexosModel::create($request->all());
                    return response()->json($anexos);
                } else {

                    $anexos = contratoAnexosModel::where('ID_CONTRATO_ANEXO', $request->ANEXO_ID)->delete();
                    return response()->json($anexos);
                }
            }
        } catch (Exception $e) {
            return response()->json('Error al guardar CLIENTE');
        }
    }
}

<?php

namespace App\Http\Controllers\recsensorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorial\catcontratoModel;
use App\modelos\recsensorial\catregionModel;
use App\modelos\recsensorial\catsubdireccionModel;
use App\modelos\recsensorial\catgerenciaModel;
use App\modelos\recsensorial\catactivoModel;
use App\modelos\recsensorial\catdepartamentoModel;
use App\modelos\recsensorial\catmovilfijoModel;
use App\modelos\recsensorial\catpartecuerpoModel;
use App\modelos\recsensorial\catparametroalimentocaracteristicaModel;
use App\modelos\recsensorial\catparametrosuperficiecaracteristicaModel;
use App\modelos\recsensorial\catparametroaguacaracteristicaModel;
use App\modelos\recsensorial\catparametrohielocaracteristicaModel;
use App\modelos\recsensorial\catparametrocalidadairecaracteristicaModel;
use App\modelos\recsensorial\catPartesCuerpoDescripcionModel;
use App\modelos\catalogos\Cat_pruebaModel;
use App\modelos\catalogos\Cat_pruebanormaModel;
use App\modelos\recsensorial\catCargosInformeModel;
use App\modelos\recsensorial\catFormatosCampo;
use App\modelos\recsensorial\catConclusionesModel;
use App\modelos\recsensorial\catProteccion_auditivaModel;
use App\modelos\recsensorial\cat_descripcionarea;
use App\modelos\recsensorial\cat_sistemailuminacionModel;


use App\modelos\catalogos\Cat_etiquetaModel;
use App\modelos\catalogos\CatetiquetaopcionesModel;


use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Support\Facades\Storage;


//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class recsensorialcatalogosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('roles:Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Usuario,Externo');
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
        // $cat_tipoproveedor = Cat_tipoproveedorModel::all();
        // return view('catalogos.recsensorial.recsensorial_catalogos', compact('cat_tipoproveedor'));
        return view('catalogos.recsensorial.recsensorial_catalogos');
    }







    /**
     * Display the specified resource.
     *
     * @param  int  $num_catalogo
     * @return \Illuminate\Http\Response
     */
    public function recsensorialconsultacatalogo($num_catalogo)
    {
        switch (($num_catalogo + 0)) {
            case 1:
                // consulta catalogo
                $lista = Cat_etiquetaModel::all();

                // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value) {
                    $value['ETIQUETA'] = $value->NOMBRE_ETIQUETA;
                    $value['ID_ETIQUETA'] = $value->ID_ETIQUETA;

                    if (is_null($value->DESCRIPCION_ETIQUETA) || $value->DESCRIPCION_ETIQUETA === '') {
                        $value->DESCRIPCION_ETIQUETA = 'N/A';
                    }


                    // Consulta las opciones relacionadas
                    $opciones = CatetiquetaopcionesModel::where('ETIQUETA_ID', $value->ID_ETIQUETA)->pluck('NOMBRE_OPCIONES')->toArray();

                    // Generar lista HTML de opciones
                    $opcionesHtml = '<ul>';
                    foreach ($opciones as $opcion) {
                        $opcionesHtml .= '<li>' . $opcion . '</li>';
                    }
                    $opcionesHtml .= '</ul>';

                    $value['OPCIONES'] = $opcionesHtml;

                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_catalogoetiqueta();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_ETIQUETA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_ETIQUETA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }

                // Convertir la lista a JSON y enviarla a la vista
                break;
            case 2:
                // consulta catalogo
                $lista = catregionModel::all();
                // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value) {
                    $value['nombre'] = $value->catregion_nombre;

                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_registro();"><i class="fa fa-pencil"></i></button>';

                    if ($value->catregion_activo == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 3:
                // consulta catalogo
                $lista = catgerenciaModel::all();
                // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value) {
                    $value['siglas'] = $value->catgerencia_siglas;
                    $value['nombre'] = $value->catgerencia_nombre;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_catalogo2campos();"><i class="fa fa-pencil"></i></button>';

                    if ($value->catgerencia_activo == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 4:
                // consulta catalogo
                $lista = catactivoModel::orderBy('catactivo_nombre', 'ASC')->get();
                // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value) {
                    $value['siglas'] = $value->catactivo_siglas;
                    $value['nombre'] = $value->catactivo_nombre;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_catalogo2campos();"><i class="fa fa-pencil"></i></button>';

                    if ($value->catactivo_activo == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 5:
                // consulta catalogo
                $lista = catdepartamentoModel::all();
                // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value) {
                    $value['nombre'] = $value->catdepartamento_nombre;

                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_registro();"><i class="fa fa-pencil"></i></button>';

                    if ($value->catdepartamento_activo == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 6:
                $lista = DB::select('SELECT
                                        cc.catpartecuerpo_nombre as PARTECUERPO,
                                        cd.PARTECUERPO_ID,
                                        cd.CLAVE_EPP,
                                        cd.TIPO_RIEGO,
                                        cd.ACTIVO,
                                        cd.ID_PARTESCUERPO_DESCRIPCION
                                    FROM
                                            catpartescuerpo_descripcion cd
                                            LEFT JOIN catpartecuerpo cc ON cd.PARTECUERPO_ID = cc.id 
                                    WHERE 
                                            cc.catpartecuerpo_activo = 1');

                // crear campos NOMBRE Y ESTADO
                $COUNT = 0;
                foreach ($lista as $key => $value) {
                    $COUNT++;
                    $value->COUNT = $COUNT;

                    $value->boton_editar = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_registro_epp();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value->CheckboxEstado = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_PARTESCUERPO_DESCRIPCION . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value->CheckboxEstado = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_PARTESCUERPO_DESCRIPCION . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 7:
                // consulta catalogo
                $lista = catparametroalimentocaracteristicaModel::all();
                // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value) {
                    $value['nombre'] = $value->catparametroalimentocaracteristica_caracteristica;

                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_registro();"><i class="fa fa-pencil"></i></button>';

                    if ($value->catparametroalimentocaracteristica_activo == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 8:
                // consulta catalogo
                // $lista = catparametroaguacaracteristicaModel::all();
                $lista = catparametroaguacaracteristicaModel::orderBy('catparametroaguacaracteristica_tipo', 'DESC')->get();

                // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value) {
                    $value['tipo'] = $value->catparametroaguacaracteristica_tipo;
                    $value['caracteristica'] = $value->catparametroaguacaracteristica_caracteristica;

                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_caracteristica();"><i class="fa fa-pencil"></i></button>';

                    if ($value->catparametroaguacaracteristica_activo == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 9:
                // consulta catalogo
                $lista = catparametrocalidadairecaracteristicaModel::all();
                // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value) {
                    $value['nombre'] = $value->catparametrocalidadairecaracteristica_caracteristica;

                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_registro();"><i class="fa fa-pencil"></i></button>';

                    if ($value->catparametrocalidadairecaracteristica_activo == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 10:
                // consulta catalogo
                // $lista = Cat_pruebaModel::all();
                $lista = Cat_pruebaModel::with(['pruebanorma'])->orderBy('catPrueba_Tipo', 'ASC')->orderBy('catPrueba_Nombre', 'ASC')->get();

                // Formatear registros
                foreach ($lista as $key => $value) {
                    $value['tipo_agente'] = $value->catPrueba_Tipo;
                    $value['nombre'] = $value->catPrueba_Nombre;
                    $value['nombre2'] = $value->catPrueba_Normas;
                    $value['estado'] = $value->catPrueba_Activo;

                    // obtener normas
                    $value['tipo'] = $value->pruebanorma->pluck('catpruebanorma_tipo');
                    $value['numero'] = $value->pruebanorma->pluck('catpruebanorma_numero');
                    $value['descripcion'] = $value->pruebanorma->pluck('catpruebanorma_descripcion');

                    // Lista de normas y procedimientos
                    $cadena = "";
                    foreach ($value['numero'] as $key => $norma) {
                        // $cadena .= "<li>".$norma."</li>";
                        // $cadena .= "<li><b>".$value['tipo'][$key]." - ".$value['numero'][$key]."</b>: ".substr($value['descripcion'][$key], 1, 70)."...</li>";
                        $cadena .= "<li><b>" . $value['numero'][$key] . ":</b> " . $value['tipo'][$key] . ".</li>";
                    }
                    $value['normas'] = $cadena;

                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_agente();"><i class="fa fa-pencil"></i></button>';

                    // boton checkbox
                    if ($value->catPrueba_Activo == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_agente(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_agente(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 11:
                // consulta catalogo
                $lista = catparametrosuperficiecaracteristicaModel::all();
                // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value) {
                    $value['nombre'] = $value->catparametrosuperficiecaracteristica_caracteristica;

                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_registro();"><i class="fa fa-pencil"></i></button>';

                    if ($value->catparametrosuperficiecaracteristica_activo == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 12:
                // consulta catalogo
                // $lista = catparametrohielocaracteristicaModel::all();
                $lista = catparametrohielocaracteristicaModel::orderBy('catparametrohielocaracteristica_tipo', 'DESC')->get();

                // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value) {
                    $value['tipo'] = $value->catparametrohielocaracteristica_tipo;
                    $value['caracteristica'] = $value->catparametrohielocaracteristica_caracteristica;

                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_caracteristica();"><i class="fa fa-pencil"></i></button>';

                    if ($value->catparametrohielocaracteristica_activo == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 13:
                // consulta catalogo
                $lista = catsubdireccionModel::all();
                // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value) {
                    $value['siglas'] = $value->catsubdireccion_siglas;
                    $value['nombre'] = $value->catsubdireccion_nombre;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_catalogo2campos();"><i class="fa fa-pencil"></i></button>';

                    if ($value->catsubdireccion_activo == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->id . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 14:
                // consulta catalogo
                $lista = catCargosInformeModel::all();

                // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value) {
                    $value['CARGO'] = $value->CARGO;
                    $value['ID_CARGO_INFORME'] = $value->ID_CARGO_INFORME;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_cargoInforme();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_CARGO_INFORME . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_CARGO_INFORME . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 15:
                // consulta catalogo
                $lista = catFormatosCampo::all();

                // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value) {
                    $value['NOMBRE'] = $value->NOMBRE;
                    $value['DESCRIPCION'] = $value->DESCRIPCION;
                    $value['ID_FORMATO'] = $value->ID_FORMATO;

                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editarFormatoCampo();"><i class="fa fa-pencil"></i></button>';
                    $value['boton_ver'] = '<button type="button" class="btn btn-info btn-circle" onclick="verFormatoCampo();"><i class="fa fa-eye"></i></button>';
                    $value['boton_descargar'] = '<button type="button" class="btn btn-success btn-circle" onclick="descargarFormatoCampo();"><i class="fa fa-download"></i></button>';
                }
                break;
            case 16:
                // consulta catalogo
                $lista = catConclusionesModel::all();

                // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value) {
                    $value['NOMBRE'] = $value->NOMBRE;
                    $value['DESCRIPCION'] = $value->DESCRIPCION;
                    $value['ID_CATCONCLUSION'] = $value->ID_CATCONCLUSION;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_catConclusion();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_CATCONCLUSION . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_CATCONCLUSION . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;

            case 17:
                // consulta catalogo
                $lista = cat_descripcionarea::all();

                // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value) {
                    $value['DESCRIPCION'] = $value->DESCRIPCION;
                    $value['ID_DESCRIPCION_AREA'] = $value->ID_DESCRIPCION_AREA;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_catdescripcion();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_DESCRIPCION_AREA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_DESCRIPCION_AREA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
            case 18:
                // consulta catalogo
                $lista = cat_sistemailuminacionModel::all();

                // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value) {
                    $value['NOMBRE'] = $value->NOMBRE;
                    $value['DESCRIPCION'] = $value->DESCRIPCION;
                    $value['ID_SISTEMA_ILUMINACION'] = $value->ID_SISTEMA_ILUMINACION;
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_catsistema();"><i class="fa fa-pencil"></i></button>';

                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_SISTEMA_ILUMINACION . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_SISTEMA_ILUMINACION . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;

            case 19:
                    // consulta catalogo
                $lista = catProteccion_auditivaModel::all();
    
                    // crear campos NOMBRE Y ESTADO
                foreach ($lista as $key => $value) {
                    $value['TIPO'] = $value->TIPO;
                    $value['MODELO'] = $value->MODELO;
                    $value['MARCA'] = $value->MARCA;
                    $value['CUMPLIMIENTO'] = $value->CUMPLIMIENTO;
                   
                    $value['boton_editar'] = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_proteccion();"><i class="fa fa-pencil"></i></button>';
                    $value['boton_ver'] = '<button type="button" class="btn btn-info btn-circle" onclick="verFichaTecnica();"><i class="fa fa-eye"></i></button>';
                    $value['boton_descargar'] = '<button type="button" class="btn btn-success btn-circle" onclick="descargarFichaTecnica();"><i class="fa fa-download"></i></button>';
    
                    if ($value->ACTIVO == 1) {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_PROTECCION . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_PROTECCION . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
                break;
        }

        // Respuesta
        $catalogo['data']  = $lista;
        return response()->json($catalogo);
    }




    public function verFormatoCampo($opcion, $ID)
    {
        $documento = catFormatosCampo::findOrFail($ID);

        if ($opcion = 0) {

            return Storage::response($documento->RUTA_PDF);
        } else {
            return Storage::download($documento->RUTA_PDF);
        }
    }

    public function verFichaTecnica($opcion, $ID)
    {
        $documento = catProteccion_auditivaModel::findOrFail($ID);

        if ($opcion = 0) {
            return Storage::response($documento->RUTA_PDF);
        } else {
            return Storage::download($documento->RUTA_PDF);
        }
    }
    public function verProteccionFoto($ID)
    {
        $foto = catProteccion_auditivaModel::findOrFail($ID);
        return Storage::response($foto->RUTA_IMG);
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
                case 0:
                    DB::statement('ALTER TABLE catetiquetas_opciones AUTO_INCREMENT=1');
                    $request["ACTIVO"] = 1;
                    $catalogo = CatetiquetaopcionesModel::create($request->all());
                    $dato["msj"] = 'Información guardada correctamente';
                    break;
                case 1:

                    if ($request['ID_ETIQUETA'] == 0) {
                        DB::statement('ALTER TABLE cat_etiquetas AUTO_INCREMENT=1');
                        $request["ACTIVO"] = 1;
                        $catalogo = Cat_etiquetaModel::create($request->all());
                        $dato["etiqueta"] = $catalogo;


                        $dato["msj"] = 'Información guardada correctamente';
                    } else {

                        $catalogo = Cat_etiquetaModel::findOrFail($request['ID_ETIQUETA']);
                        $dato["etiqueta"] = $catalogo;
                        $catalogo->update($request->all());
                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
                case 2:
                    if ($request['id'] == 0) {
                        DB::statement('ALTER TABLE catregion AUTO_INCREMENT=1');
                        $request["catregion_activo"] = 1;
                        $catalogo = catregionModel::create($request->all());
                        $dato["msj"] = 'Información guardada correctamente';
                    } else {
                        $catalogo = catregionModel::findOrFail($request['id']);
                        $catalogo->update($request->all());
                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
                case 3:
                    if ($request['id'] == 0) {
                        DB::statement('ALTER TABLE catgerencia AUTO_INCREMENT=1');
                        $request["catgerencia_activo"] = 1;
                        $catalogo = catgerenciaModel::create($request->all());
                        $dato["msj"] = 'Información guardada correctamente';
                    } else {
                        $catalogo = catgerenciaModel::findOrFail($request['id']);
                        $catalogo->update($request->all());
                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
                case 4:
                    if ($request['id'] == 0) {
                        DB::statement('ALTER TABLE catactivo AUTO_INCREMENT=1');
                        $request["catactivo_activo"] = 1;
                        $catalogo = catactivoModel::create($request->all());
                        $dato["msj"] = 'Información guardada correctamente';
                    } else {
                        $catalogo = catactivoModel::findOrFail($request['id']);
                        $catalogo->update($request->all());
                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
                case 5:
                    if ($request['id'] == 0) {
                        DB::statement('ALTER TABLE catactivo AUTO_INCREMENT=1');
                        $request["catdepartamento_activo"] = 1;
                        $catalogo = catdepartamentoModel::create($request->all());
                        $dato["msj"] = 'Información guardada correctamente';
                    } else {
                        $catalogo = catdepartamentoModel::findOrFail($request['id']);
                        $catalogo->update($request->all());
                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
                case 6:
                    if ($request['ID_PARTESCUERPO_DESCRIPCION'] == 0) {
                        DB::statement('ALTER TABLE catpartescuerpo_descripcion AUTO_INCREMENT=1');
                        $request["ACTIVO"] = 1;
                        $catalogo = catPartesCuerpoDescripcionModel::create($request->all());
                        $dato["msj"] = 'Información guardada correctamente';
                    } else {
                        $catalogo = catPartesCuerpoDescripcionModel::findOrFail($request['ID_PARTESCUERPO_DESCRIPCION']);
                        $catalogo->update($request->all());
                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
                case 7:
                    if ($request['id'] == 0) {
                        DB::statement('ALTER TABLE catparametroalimentocaracteristica AUTO_INCREMENT=1');
                        $request["catparametroalimentocaracteristica_activo"] = 1;
                        $catalogo = catparametroalimentocaracteristicaModel::create($request->all());
                        $dato["msj"] = 'Información guardada correctamente';
                    } else {
                        $catalogo = catparametroalimentocaracteristicaModel::findOrFail($request['id']);
                        $catalogo->update($request->all());
                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
                case 8:
                    if ($request['id'] == 0) {
                        DB::statement('ALTER TABLE catparametroaguacaracteristica AUTO_INCREMENT=1');
                        $request["catparametroaguacaracteristica_activo"] = 1;
                        $catalogo = catparametroaguacaracteristicaModel::create($request->all());
                        $dato["msj"] = 'Información guardada correctamente';
                    } else {
                        $catalogo = catparametroaguacaracteristicaModel::findOrFail($request['id']);
                        $catalogo->update($request->all());
                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
                case 9:
                    if ($request['id'] == 0) {
                        DB::statement('ALTER TABLE catparametrocalidadairecaracteristica AUTO_INCREMENT=1');
                        $request["catparametrocalidadairecaracteristica_activo"] = 1;
                        $catalogo = catparametrocalidadairecaracteristicaModel::create($request->all());
                        $dato["msj"] = 'Información guardada correctamente';
                    } else {
                        $catalogo = catparametrocalidadairecaracteristicaModel::findOrFail($request['id']);
                        $catalogo->update($request->all());
                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
                    break;
                case 10:
                    if ($request['id'] == 0) {
                        // nuevo
                        DB::statement('ALTER TABLE cat_prueba AUTO_INCREMENT=1');
                        $request["catPrueba_Activo"] = 1;
                        $catalogo = Cat_pruebaModel::create($request->all());

                        // guardar normas
                        foreach ($request->tipo as $key => $value) {
                            $normas = Cat_pruebanormaModel::create([
                                'cat_prueba_id' => $catalogo['id'], 'catpruebanorma_tipo' => $request->tipo[$key], 'catpruebanorma_numero' => $request->numero[$key], 'catpruebanorma_descripcion' => $request->descripcion[$key]
                            ]);
                        }

                        $dato["msj"] = 'Información guardada correctamente';
                    } else {
                        $catalogo = Cat_pruebaModel::findOrFail($request['id']);
                        $catalogo->update($request->all());

                        $eliminar = Cat_pruebanormaModel::where('cat_prueba_id', $request['id'])->delete();

                        // guardar normas
                        foreach ($request->tipo as $key => $value) {
                            $normas = Cat_pruebanormaModel::create([
                                'cat_prueba_id' => $request['id'], 'catpruebanorma_tipo' => $request->tipo[$key], 'catpruebanorma_numero' => $request->numero[$key], 'catpruebanorma_descripcion' => $request->descripcion[$key]
                            ]);
                        }

                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
                case 11:
                    if ($request['id'] == 0) {
                        DB::statement('ALTER TABLE catparametrosuperficiecaracteristica AUTO_INCREMENT=1');
                        $request["catparametrosuperficiecaracteristica_activo"] = 1;
                        $catalogo = catparametrosuperficiecaracteristicaModel::create($request->all());
                        $dato["msj"] = 'Información guardada correctamente';
                    } else {
                        $catalogo = catparametrosuperficiecaracteristicaModel::findOrFail($request['id']);
                        $catalogo->update($request->all());
                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
                case 12:
                    if ($request['id'] == 0) {
                        DB::statement('ALTER TABLE catparametrohielocaracteristica AUTO_INCREMENT=1');
                        $request["catparametrohielocaracteristica_activo"] = 1;
                        $catalogo = catparametrohielocaracteristicaModel::create($request->all());
                        $dato["msj"] = 'Información guardada correctamente';
                    } else {
                        $catalogo = catparametrohielocaracteristicaModel::findOrFail($request['id']);
                        $catalogo->update($request->all());
                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
                case 13:
                    if ($request['id'] == 0) {
                        DB::statement('ALTER TABLE catsubdireccion AUTO_INCREMENT=1');
                        $request["catsubdireccion_activo"] = 1;
                        $catalogo = catsubdireccionModel::create($request->all());
                        $dato["msj"] = 'Información guardada correctamente';
                    } else {
                        $catalogo = catsubdireccionModel::findOrFail($request['id']);
                        $catalogo->update($request->all());
                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
                case 14:
                    if ($request['ID_CARGO_INFORME'] == 0) {
                        DB::statement('ALTER TABLE cat_cargosInforme AUTO_INCREMENT=1');

                        $request["ACTIVO"] = 1;
                        $catalogo = catCargosInformeModel::create($request->all());

                        $dato["msj"] = 'Información guardada correctamente';
                    } else {

                        $catalogo = catCargosInformeModel::findOrFail($request['ID_CARGO_INFORME']);
                        $catalogo->update($request->all());

                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
                case 15:

                    if ($request['ID_FORMATO'] == 0) //nuevo
                    {
                        // AUTO_INCREMENT
                        DB::statement('ALTER TABLE catFormatos_campo AUTO_INCREMENT=1');

                        $request["ACTIVO"] = 1;
                        $catalogo = catFormatosCampo::create($request->all());

                        if ($request->file('FORMATO_PDF')) {

                            $extension = $request->file('FORMATO_PDF')->getClientOriginalExtension();

                            $request['RUTA_PDF'] = $request->file('FORMATO_PDF')->storeAs('catalogos/formatosCampo', $catalogo->ID_FORMATO . '.' . $extension);

                            $catalogo->update($request->all());
                        }

                        return response()->json($catalogo);
                    } else //editar
                    {
                        $catalogo = catFormatosCampo::findOrFail($request['ID_FORMATO']);

                        if ($request->file('FORMATO_PDF')) {

                            $extension = $request->file('FORMATO_PDF')->getClientOriginalExtension();

                            $request['RUTA_PDF'] = $request->file('FORMATO_PDF')->storeAs('catalogos/formatosCampo', $catalogo->ID_FORMATO . '.' . $extension);
                        }

                        $catalogo->update($request->all());
                        return response()->json($catalogo);
                    }
                    break;
                case 16:
                    if ($request['ID_CATCONCLUSION'] == 0) {
                        DB::statement('ALTER TABLE catConclusiones AUTO_INCREMENT=1');

                        $request["ACTIVO"] = 1;
                        $catalogo = catConclusionesModel::create($request->all());

                        $dato["msj"] = 'Información guardada correctamente';
                    } else {

                        $catalogo = catConclusionesModel::findOrFail($request['ID_CATCONCLUSION']);
                        $catalogo->update($request->all());

                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;
                case 17:
                    if ($request['ID_DESCRIPCION_AREA'] == 0) {
                        DB::statement('ALTER TABLE cat_descripcionarea AUTO_INCREMENT=1');

                        $request["ACTIVO"] = 1;
                        $catalogo = cat_descripcionarea::create($request->all());

                        $dato["msj"] = 'Información guardada correctamente';
                    } else {

                        $catalogo = cat_descripcionarea::findOrFail($request['ID_DESCRIPCION_AREA']);
                        $catalogo->update($request->all());

                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;

                case 18:
                    if ($request['ID_SISTEMA_ILUMINACION'] == 0) {
                        DB::statement('ALTER TABLE cat_sistema_iluminacion AUTO_INCREMENT=1');

                        $request["ACTIVO"] = 1;
                        $catalogo = cat_sistemailuminacionModel::create($request->all());

                        $dato["msj"] = 'Información guardada correctamente';
                    } else {

                        $catalogo = cat_sistemailuminacionModel::findOrFail($request['ID_SISTEMA_ILUMINACION']);
                        $catalogo->update($request->all());

                        $dato["msj"] = 'Información modificada correctamente';
                    }
                    break;

                case 19:
                    if ($request['ID_PROTECCION'] == 0) {
                        DB::statement('ALTER TABLE catProteccion_auditiva AUTO_INCREMENT=1');

                        $request["ACTIVO"] = 1;
                        $catalogo = catProteccion_auditivaModel::create($request->all());

                        $dato["msj"] = 'Información guardada correctamente';
                        if ($request->file('FICHA_PDF')) {

                            $extension = $request->file('FICHA_PDF')->getClientOriginalExtension();

                            $request['RUTA_PDF'] = $request->file('FICHA_PDF')->storeAs('catalogos/catProteccionAuditiva/' . $catalogo->ID_PROTECCION, 'fichaTecnica' . '.' . $extension);

                            $catalogo->update($request->all());
                        }
                        if ($request->file('foto_proteccion')) {


                            $extension = $request->file('foto_proteccion')->getClientOriginalExtension();

                            $request['RUTA_IMG'] = $request->file('foto_proteccion')->storeAs('catalogos/catProteccionAuditiva/' . $catalogo->ID_PROTECCION, 'fotoProteccion' . '.' . $extension);

                            $catalogo->update($request->all());
                        }
                        return response()->json($catalogo);
                    } else {

                        $catalogo = catProteccion_auditivaModel::findOrFail($request['ID_PROTECCION']);
                        if ($request->file('FICHA_PDF')) {

                            $extension = $request->file('FICHA_PDF')->getClientOriginalExtension();

                            $request['RUTA_PDF'] = $request->file('FICHA_PDF')->storeAs('catalogos/catProteccionAuditiva/' . $catalogo->ID_PROTECCION, 'fichaTecnica' . '.' . $extension);
                        }
                        if ($request->file('foto_proteccion')) {

                            if (Storage::exists($catalogo->foto_proteccion)) {
                                Storage::delete($catalogo->foto_proteccion);
                            }

                            $extension = $request->file('foto_proteccion')->getClientOriginalExtension();
                            $request['RUTA_IMG'] = $request->file('foto_proteccion')->storeAs('catalogos/catProteccionAuditiva/' . $catalogo->ID_PROTECCION, 'fotoProteccion' . '.' . $extension);
                        }
                        $catalogo->update($request->all());
                        $dato["msj"] = 'Información modificada correctamente';
                        return response()->json($catalogo);
                    }
                    break;


            }
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }



    public function opcionesOrganizacion($id_etiqueta)
    {
        // consulta catalogo
        $lista = CatetiquetaopcionesModel::where('ETIQUETA_ID', $id_etiqueta)->get();
        $num_catalogo = 0;

        // crear campos NOMBRE Y ESTADO
        foreach ($lista as $key => $value) {
            $value['OPCIONES_NOMBRE'] = $value->NOMBRE_OPCIONES;


            if ($value->ACTIVO == 1) {
                $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_OPCIONES_ETIQUETAS . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
            } else {
                $value['CheckboxEstado'] = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_OPCIONES_ETIQUETAS . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
            }
        }


        // Respuesta
        $catalogo['data']  = $lista;
        return response()->json($catalogo);
    }






    /**
     * Display the specified resource.
     *
     * @param  int  $catalogo
     * @param  int  $registro
     * @param  int  $estado
     * @return \Illuminate\Http\Response
     */
    public function recsensorialcatalogodesactiva($catalogo, $registro, $estado)
    {
        try {
            switch (($catalogo + 0)) {
                case 0:
                    $tabla = CatetiquetaopcionesModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 1:
                    $tabla = Cat_etiquetaModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 2:
                    $tabla = catregionModel::findOrFail($registro);
                    $tabla->update(['catregion_activo' => $estado]);
                    break;
                case 3:
                    $tabla = catgerenciaModel::findOrFail($registro);
                    $tabla->update(['catgerencia_activo' => $estado]);
                    break;
                case 4:
                    $tabla = catactivoModel::findOrFail($registro);
                    $tabla->update(['catactivo_activo' => $estado]);
                    break;
                case 5:
                    $tabla = catdepartamentoModel::findOrFail($registro);
                    $tabla->update(['catdepartamento_activo' => $estado]);
                    break;
                case 6:
                    $tabla = catPartesCuerpoDescripcionModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 7:
                    $tabla = catparametroalimentocaracteristicaModel::findOrFail($registro);
                    $tabla->update(['catparametroalimentocaracteristica_activo' => $estado]);
                    break;
                case 8:
                    $tabla = catparametroaguacaracteristicaModel::findOrFail($registro);
                    $tabla->update(['catparametroaguacaracteristica_activo' => $estado]);
                    break;
                case 9:
                    $tabla = catparametrocalidadairecaracteristicaModel::findOrFail($registro);
                    $tabla->update(['catparametrocalidadairecaracteristica_activo' => $estado]);
                    break;
                case 10:
                    $tabla = Cat_pruebaModel::findOrFail($registro);
                    $tabla->update(['catPrueba_Activo' => $estado]);
                    break;
                case 11:
                    $tabla = catparametrosuperficiecaracteristicaModel::findOrFail($registro);
                    $tabla->update(['catparametrosuperficiecaracteristica_activo' => $estado]);
                    break;
                case 12:
                    $tabla = catparametrohielocaracteristicaModel::findOrFail($registro);
                    $tabla->update(['catparametrohielocaracteristica_activo' => $estado]);
                    break;
                case 13:
                    $tabla = catsubdireccionModel::findOrFail($registro);
                    $tabla->update(['catsubdireccion_activo' => $estado]);
                    break;
                case 14:
                    $tabla = catCargosInformeModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 16:
                    $tabla = catConclusionesModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 17:
                    $tabla = cat_descripcionarea::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 18:
                    $tabla = cat_sistemailuminacionModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 19:
                    $tabla = catProteccion_auditivaModel::findOrFail($registro);
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
}

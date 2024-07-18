<?php

namespace App\Http\Controllers\proyecto;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use DB;
use Image;

// plugins PDF
use Barryvdh\DomPDF\Facade as PDF;
use PDFMerger;

// Modelos
use App\modelos\proyecto\proyectoModel;
use App\modelos\proyecto\proyectoevidenciadocumentoModel;
use App\modelos\proyecto\proyectoevidenciafotoModel;
use App\modelos\proyecto\proyectoevidenciaplanoModel;

use App\modelos\proyecto\proyectoordentrabajoModel;

use App\modelos\proyecto\proyectoevidenciabitacoraModel;
use App\modelos\proyecto\proyectoevidenciabitacorapersonalModel;
use App\modelos\proyecto\proyectoevidenciabitacorafotoModel;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class proyectoevidenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Reportes,ApoyoTecnico,Financiero,Cadista,Externo');
        // $this->middleware('roles:Superusuario,Administrador,Proyecto,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Reportes,Externo');
    }


    /**
     * Display a listing of the resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciaparametros($proyecto_id)
    {
        try {
            $nombre_rol = 'Administrador';
            $where_adicional = '';

            if (auth()->user()->hasRoles(['Ergónomo', 'CoordinadorErgonómico'])) {
                $where_adicional = 'AND proyectoproveedores.proyectoproveedores_agente NOT LIKE "%PSICO%"';
                $nombre_rol = 'Ergónomo';
            }

            if (auth()->user()->hasRoles(['Psicólogo', 'CoordinadorPsicosocial'])) {
                $where_adicional = 'AND proyectoproveedores.proyectoproveedores_agente LIKE "%PSICO%"';
                $nombre_rol = 'Psicólogo';
            }

            if (auth()->user()->hasRoles(['Externo'])) {
                $where_adicional = 'AND proyectoproveedores.proveedor_id = ' . auth()->user()->empleado_id;
            }

            $sql = DB::select('SELECT
                                    TABLA.catprueba_id,
                                    TABLA.proyectoproveedores_agente
                                FROM
                                    (
                                        (
                                            SELECT
                                                0 tipo,
                                                proyectoproveedores.proyectoproveedores_tipoadicional,
                                                proyectoproveedores.catprueba_id,
                                                proyectoproveedores.proyectoproveedores_agente
                                            FROM
                                                proyectoproveedores
                                            WHERE
                                                proyectoproveedores.proyecto_id = ' . $proyecto_id . '
                                                ' . $where_adicional . '
                                                AND proyectoproveedores.proyectoproveedores_tipoadicional < 2
                                                AND proyectoproveedores.catprueba_id != 15
                                        )
                                        UNION ALL
                                        (
                                            SELECT
                                                1 AS tipo,
                                                proyectoproveedores.proyectoproveedores_tipoadicional,
                                                15 AS catprueba_id,
                                                "Químicos" AS proyectoproveedores_agente
                                            FROM
                                                proyectoproveedores
                                            WHERE
                                                proyectoproveedores.proyecto_id = ' . $proyecto_id . '
                                                ' . $where_adicional . '
                                                AND proyectoproveedores.proyectoproveedores_tipoadicional < 2
                                                AND proyectoproveedores.catprueba_id = 15
                                            LIMIT 1
                                        )
                                    ) AS TABLA
                                ORDER BY
                                    TABLA.tipo ASC,
                                    TABLA.proyectoproveedores_agente ASC');

            if (count($sql) > 0) {
                $opciones_menu = '';

                $opciones_menu .= '<li class="nav-item" style="border-bottom: 1px #F0F0F0 solid; padding: 0px;">
                                        <span class="nav-link menulista_evidencia" style="margin: 2px 0px; padding: 8px; cursor: pointer;" onclick="consulta_evidencias(' . $proyecto_id . ', 1000, \'Puntos reales evaluados\', this, \'' . $nombre_rol . '\');">
                                            • <b style="font-weight: bold; color: #000000;">Puntos reales evaluados</b>
                                        </span>
                                    </li>';

                $opciones_menu .= '<li class="nav-item" style="border-bottom: 1px #F0F0F0 solid; padding: 0px;">
                                        <span class="nav-link menulista_evidencia" style="margin: 2px 0px; padding: 8px; cursor: pointer;" onclick="consulta_evidencias(' . $proyecto_id . ', 0, \'Información general\', this, \'' . $nombre_rol . '\');">
                                            • <b style="font-weight: bold; color: #000000;">Información general</b>
                                        </span>
                                    </li>';

                $opciones_menu .= '<li class="nav-item" style="border-bottom: 1px #F0F0F0 solid; padding: 0px;">
                                        <span class="nav-link menulista_evidencia" style="margin: 2px 0px; padding: 8px; cursor: pointer;" onclick="consulta_evidencias(' . $proyecto_id . ', 2000, \'Bitácora de muestreo\', this, \'' . $nombre_rol . '\');">
                                            • <b style="font-weight: bold; color: #000000;">Bitácora de muestreo</b>
                                        </span>
                                    </li>';

                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI'])) {
                    foreach ($sql as $key => $value) {
                        $opciones_menu .= '<li class="nav-item" style="border-bottom: 1px #F0F0F0 solid; padding: 0px;">
                                                <span class="nav-link menulista_evidencia" style="margin: 2px 0px; padding: 8px; cursor: pointer;" onclick="consulta_evidencias(' . $proyecto_id . ', ' . $value->catprueba_id . ', \'' . $value->proyectoproveedores_agente . '\', this, \'' . $nombre_rol . '\');">
                                                    • ' . $value->proyectoproveedores_agente . '
                                                </span>';
                        if (($value->catprueba_id + 0) == 15) //Hay quimicos
                        {
                            $opciones_menu .= '<span style="color:#999999; line-height: 12px;">';
                            $lista_quimicos = DB::select('SELECT
                                                                                            proyectoproveedores.proyectoproveedores_agente
                                                                                        FROM
                                                                                            proyectoproveedores
                                                                                        WHERE
                                                                                            proyectoproveedores.proyecto_id = ' . $proyecto_id . '
                                                                                            ' . $where_adicional . '
                                                                                            -- AND proyectoproveedores.proyectoproveedores_tipoadicional = 0
                                                                                            AND proyectoproveedores.catprueba_id = 15
                                                                                        ORDER BY
                                                                                            proyectoproveedores.proyectoproveedores_agente ASC');

                            foreach ($lista_quimicos as $key_quimico => $value_quimico) {
                                $opciones_menu .= '&nbsp;&nbsp;&nbsp;&nbsp;• ' . $value_quimico->proyectoproveedores_agente . '<br>';
                            }

                            $opciones_menu .= '</span>';
                        }
                        $opciones_menu .= '</li>';
                    }
                }
            } else {
                $opciones_menu = '<li class="nav-item" style="border-bottom: 1px #F0F0F0 solid;">
                                    <b class="text-danger" style="text-aling: center">Proveedores sin asignar</b>
                                </li>';
            }


            //===================================================
            // QUIMICOS CATALOGO PARTIDAS CLIENTE

            $proyecto = proyectoModel::findOrFail($proyecto_id);

            $partidas = DB::select('SELECT
                                        cc.cliente_id, 
                                        c.id, 
                                        c.catprueba_id, 
                                        c.clientepartidas_tipo, 
                                        c.clientepartidas_nombre, 
                                        c.clientepartidas_descripcion
                                    FROM
                                        contratos_partidas c
                                        LEFT JOIN contratos_clientes cc ON cc.ID_CONTRATO = c.CONTRATO_ID
                                    WHERE
                                        c.CONTRATO_ID = ?
                                        AND c.clientepartidas_tipo = 2 -- 1 = RECONOCIMIENTO, 2 = INFORME
                                        AND c.catprueba_id = 15 -- QUIMICOS
                                    ORDER BY
                                        c.id ASC,
                                        c.clientepartidas_descripcion ASC', [$proyecto->contrato_id]);


            $dato['quimicoscatpartidas_opciones'] = '<option value=""></option>';
            foreach ($partidas as $key => $value) {
                $dato['quimicoscatpartidas_opciones'] .= '<option value="' . $value->id . '">' . $value->clientepartidas_descripcion . '</option>';
            }



            // // respuesta
            $dato['opciones_total'] = count($sql);
            $dato['opciones'] = $opciones_menu;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['quimicoscatpartidas_opciones'] = '<option value=""></option>';
            $dato['opciones_total'] = 0;
            $dato['opciones'] = $opciones_menu;
            return response()->json($dato);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @param  int  $proyecto_id
     * @param  int  $agente_id
     * @param  $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciadocumentos($proyecto_id, $agente_id, $agente_nombre)
    {
        try {
            // Proyecto
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            $where_proveedor = '';
            if (auth()->user()->hasRoles(['Externo']) && $agente_nombre != 'Información general') {
                $where_proveedor = 'AND proyectoevidenciadocumento.proveedor_id = ' . auth()->user()->empleado_id;
            }

            $sql = DB::select('SELECT
                                proyectoevidenciadocumento.id,
                                proyectoevidenciadocumento.proyecto_id,
                                proyectoevidenciadocumento.agente_id,
                                proyectoevidenciadocumento.agente_nombre,
                                proyectoevidenciadocumento.proyectoevidenciadocumento_nombre,
                                proyectoevidenciadocumento.proyectoevidenciadocumento_archivo,
                                proyectoevidenciadocumento.created_at 
                            FROM
                                proyectoevidenciadocumento
                            WHERE
                                proyectoevidenciadocumento.proyecto_id = ' . $proyecto_id . '
                                ' . $where_proveedor . '
                                AND proyectoevidenciadocumento.agente_nombre = "' . $agente_nombre . '"');

            if (count($sql) > 0) {
                $documentos_lista = '';
                foreach ($sql as $key => $value) {
                    $documentos_lista .= '<tr>
                                            <td>' . ($key + 1) . '</td>
                                            <td>' . $value->proyectoevidenciadocumento_nombre . '</td>
                                            <td>' . $value->created_at . '</td>
                                            <td><button type="button" class="btn btn-info btn-circle" onclick="evidencia_documento_descargar(' . $value->id . ');"><i class="fa fa-download"></i></button></td>';

                    // if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']) || (auth()->user()->hasRoles(['Ergónomo']) && $value->agente_nombre == 'Riesgos ergonómicos') || (auth()->user()->hasRoles(['Psicólogo']) && $value->agente_nombre == 'Factores psicosociales'))
                    if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']) && ($proyecto->proyecto_concluido + 0) == 0) {
                        $documentos_lista .= '
                                                <td><button type="button" class="btn btn-warning btn-circle" onclick="evidencia_documento_editar(' . $value->id . ', \'' . $value->proyectoevidenciadocumento_nombre . '\');"><i class="fa fa-pencil"></i></button></td>
                                                <td><button type="button" class="btn btn-danger btn-circle" onclick="evidencia_documento_eliminar(' . $value->id . ');"><i class="fa fa-trash"></i></button></td>';
                    } else if (auth()->user()->hasRoles(['Coordinador']) && ($proyecto->proyecto_concluido + 0) == 0) {
                        $documentos_lista .= '
                                                <td><button type="button" class="btn btn-warning btn-circle" onclick="evidencia_documento_editar(' . $value->id . ', \'' . $value->proyectoevidenciadocumento_nombre . '\');"><i class="fa fa-pencil"></i></button></td>
                                                <td><button type="button" class="btn btn-secondary btn-circle" onclick="opcion_nodisponible();"><i class="fa fa-ban"></i></button></td>';
                    } else {
                        $documentos_lista .= '
                                                <td><button type="button" class="btn btn-secondary btn-circle" onclick="opcion_nodisponible();"><i class="fa fa-ban"></i></button></td>
                                                <td><button type="button" class="btn btn-secondary btn-circle" onclick="opcion_nodisponible();"><i class="fa fa-ban"></i></button></td>';
                    }

                    $documentos_lista .= '</tr>';
                }
            } else {
                $documentos_lista = '<tr><td colspan="6">No hay documentos que mostrar</td></tr>';
            }

            // // respuesta
            $dato['documentos_total'] = count($sql);
            $dato['documentos'] = $documentos_lista;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['documentos_total'] = 0;
            $dato['documentos'] = $documentos_lista;
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $documento_id
     * @param  int  $documento_opcion
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciadocumentodescargar($documento_id, $documento_opcion)
    {
        $documento = proyectoevidenciadocumentoModel::findOrFail($documento_id);

        // Reemplazar acentos en el nombre del documento a descargar
        $documento_nombre = str_replace(
            array('Á', 'á', 'É', 'é', 'Í', 'í', 'Ó', 'ó', 'Ú', 'ú', 'Ñ', 'ñ'),
            array('A', 'a', 'E', 'e', 'I', 'i', 'O', 'o', 'U', 'u', 'N', 'n'),
            $documento->proyectoevidenciadocumento_nombre
        );

        if ($documento_opcion == 0) {
            return Storage::response($documento->proyectoevidenciadocumento_archivo, $documento_nombre . $documento->proyectoevidenciadocumento_extension);
        } else {
            return Storage::download($documento->proyectoevidenciadocumento_archivo, $documento_nombre . $documento->proyectoevidenciadocumento_extension);
        }

        //Ruta del archivo, nombre del archivo de descarga.extensión
        // return Storage::response($documento->proyectoevidenciadocumento_archivo);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $documento_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciadocumentoeliminar($documento_id)
    {
        try {
            // Buscar registro
            $documento = proyectoevidenciadocumentoModel::findOrFail($documento_id);

            // Eliminar documento
            if (Storage::exists($documento->proyectoevidenciadocumento_archivo)) {
                Storage::delete($documento->proyectoevidenciadocumento_archivo);
            }

            // Eliminar registro
            $documento = proyectoevidenciadocumentoModel::where('id', $documento_id)->delete();

            // respuesta
            $dato["msj"] = 'Información eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @param  int  $proyecto_id
     * @param  int  $agente_id
     * @param  $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciafotos($proyecto_id, $agente_id, $agente_nombre)
    {
        try {
            // Proyecto
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            $where_proveedor = '';
            if (auth()->user()->hasRoles(['Externo']) && $agente_nombre != 'Información general') {
                $where_proveedor = 'AND proyectoevidenciafoto.proveedor_id = ' . auth()->user()->empleado_id;
            }

            $sql = DB::select('SELECT
                                    proyectoevidenciafoto.id,
                                    proyectoevidenciafoto.proyecto_id,
                                    proyectoevidenciafoto.agente_id,
                                    proyectoevidenciafoto.agente_nombre,
                                    proyectoevidenciafoto.proyectoevidenciafoto_carpeta,
                                    proyectoevidenciafoto.proyectoevidenciafoto_nopunto,
                                    proyectoevidenciafoto.proyectoevidenciafoto_archivo,
                                    proyectoevidenciafoto.proyectoevidenciafoto_descripcion,
                                    proyectoevidenciafoto.created_at,
                                    proyectoevidenciafoto.updated_at
                                FROM
                                    proyectoevidenciafoto
                                WHERE
                                    proyectoevidenciafoto.proyecto_id = ' . $proyecto_id . '
                                    ' . $where_proveedor . '
                                    AND proyectoevidenciafoto.agente_nombre = "' . $agente_nombre . '"
                                ORDER BY
                                    proyectoevidenciafoto.proyectoevidenciafoto_carpeta ASC,
                                    proyectoevidenciafoto.proyectoevidenciafoto_nopunto ASC');


            $galeria = '';
            $carpeta = 'XXXx';
            foreach ($sql as $key => $value) {
                // NOMBRE CARPETA
                if ($value->proyectoevidenciafoto_carpeta) {
                    if ($value->proyectoevidenciafoto_carpeta != $carpeta) {
                        $carpeta = $value->proyectoevidenciafoto_carpeta;

                        if (($value->agente_id + 0) != 15) {
                            $galeria .= '<div class="col-12">
                                            <ol class="breadcrumb m-b-10" style="background: none; padding: 0px;">
                                                <i class="fa fa-folder-open fa-2x text-warning" style="float: left; margin-top: 8px; font-size: 20px;"></i>
                                                <span class="text-warning" style="float: left; margin-top: 3px; font-size: 20px; font-family: Calibri;">&nbsp;&nbsp;' . $carpeta . '</span>';

                            if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']) && ($proyecto->proyecto_concluido + 0) == 0) {
                                $galeria .= '<i class="fa fa-trash fa-2x text-danger" style="float: right; margin-left: 10px; cursor: pointer;" data-toggle="tooltip" title="Eliminar carpeta de fotos" onclick="eliminar_carpeta_fotos(' . $proyecto_id . ', \'' . $value->agente_nombre . '\', \'' . $carpeta . '\');"></i>';
                            }

                            if ($value->agente_nombre == "Información general") {
                                // if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'CoordinadorHI']))
                                // {
                                $galeria .= '<i class="fa fa-pencil fa-2x text-warning" style="float: right; margin-left: 10px; cursor: pointer;" data-toggle="tooltip" title="Editar el nombre de la carpeta" onclick="editar_nombrecarpeta(1, \'' . $carpeta . '\', 0);"></i>
                                                                    <i class="fa fa-plus-circle fa-2x text-info" style="float: right; cursor: pointer;" data-toggle="tooltip" title="Agregar más fotos a esta carpeta" onclick="agregar_fotos_carpeta(\'' . $carpeta . '\');"></i>';
                                // }
                            } else {
                                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador'])) {
                                    $galeria .= '<i class="fa fa-pencil fa-2x text-warning" style="float: right; margin-left: 10px; cursor: pointer;" data-toggle="tooltip" title="Editar el nombre de la carpeta" onclick="editar_nombrecarpeta(1, \'' . $carpeta . '\', 0);"></i>
                                                                    <i class="fa fa-plus-circle fa-2x text-info" style="float: right; cursor: pointer;" data-toggle="tooltip" title="Agregar más fotos a esta carpeta" onclick="agregar_fotos_carpeta(\'' . $carpeta . '\');"></i>';
                                }
                            }

                            $galeria .= '</ol><hr>
                                        </div>';
                        } else {
                            $galeria .= '<div class="col-12">
                                            <ol class="breadcrumb m-b-10" style="background: none; padding: 0px;">
                                                <i class="fa fa-folder-open fa-2x text-warning" style="float: left; margin-top: 8px; font-size: 20px;"></i>
                                                <span class="text-warning" style="float: left; margin-top: 3px; font-size: 20px; font-family: Calibri;">&nbsp;&nbsp;' . $carpeta . '</span>
                                            </ol><hr>
                                        </div>';
                        }
                    }
                }

                // GALERIA DE FOTOS
                // if (($value->agente_id + 0) == 15 || ($value->agente_id + 0) == 0 || ($value->agente_id + 0) == 13 || ($value->agente_id + 0) == 14) // QUIMICOS, INFO GENERAL, ERGONOMOS, PSICOLOGOS
                if (($value->agente_id + 0) == 0 || ($value->agente_id + 0) == 13 || ($value->agente_id + 0) == 14) // QUIMICOS, INFO GENERAL, ERGONOMOS, PSICOLOGOS
                {
                    $galeria .= '<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 foto_galeria">';

                    // if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']) || (auth()->user()->hasRoles(['Ergónomo']) && $value->agente_nombre == 'Riesgos ergonómicos') || (auth()->user()->hasRoles(['Psicólogo']) && $value->agente_nombre == 'Factores psicosociales'))
                    if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']) && ($proyecto->proyecto_concluido + 0) == 0) {
                        $galeria .= '<i class="fa fa-trash text-danger" style="font-size: 26px; text-shadow: 2px 2px 4px #000000; position: absolute; opacity: 0;" data-toggle="tooltip" title="Eliminar" onclick="evidencia_foto_eliminar(' . $value->id . ');"></i>';
                    }

                    $galeria .= '<i class="fa fa-download text-success" style="font-size: 26px; text-shadow: 2px 2px 4px #000000; position: absolute; opacity: 0; margin-left: 40px;" data-toggle="tooltip" title="Descargar" onclick="evidencia_foto_descargar(1, ' . $value->id . ');"></i>
                                    <a href="/proyectoevidenciafotomostrar/0/' . $value->id . '" data-effect="mfp-3d-unfold">
                                        <img class="d-block img-fluid" src="/proyectoevidenciafotomostrar/0/' . $value->id . '" style="margin: 0px 0px 20px 0px;" data-toggle="tooltip" title="Click para mostrar"/>
                                    </a>
                                </div>';
                } else // FISICOS
                {
                    $galeria .= '<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 foto_galeria">
                                    <div class="evidenciafoto" style="border: 0px #F00 solid; cursor: pointer;">';

                    // if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']) || (auth()->user()->hasRoles(['Ergónomo']) && $value->agente_nombre == 'Riesgos ergonómicos') || (auth()->user()->hasRoles(['Psicólogo']) && $value->agente_nombre == 'Factores psicosociales'))
                    if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']) && ($proyecto->proyecto_concluido + 0) == 0) {
                        $galeria .= '<i class="fa fa-trash text-danger" style="position: absolute; font-size: 26px; text-shadow: 0 0 3px #000000, 0 0 3px #000000;;  opacity: 0;" data-toggle="tooltip" title="Eliminar" onclick="evidencia_foto_eliminar(' . $value->id . ');"></i>';
                    }

                    if (($value->agente_id + 0) != 15) {
                        $galeria .= '<i class="fa fa-download text-success" style="font-size: 26px; text-shadow: 2px 2px 4px #000000; position: absolute; opacity: 0; margin-top: 36px;" data-toggle="tooltip" title="Descargar" onclick="evidencia_foto_descargar(1, ' . $value->id . ');"></i>
                                        <span style="position: absolute; right: 20px; font-size: 14px; color: #FFFFFF; text-shadow: 0 0 3px #000000, 0 0 3px #000000;">Punto ' . $value->proyectoevidenciafoto_nopunto . '</span>
                                        <img class="d-block img-fluid" src="/proyectoevidenciafotomostrar/0/' . $value->id . '" style="margin: 0px 0px 20px 0px;" data-toggle="tooltip" title="Click para mostrar" onclick="evidenciafoto_mostrardatos(' . $value->id . ', \'' . $value->proyectoevidenciafoto_archivo . '\', ' . $value->proyectoevidenciafoto_nopunto . ', \'' . $value->proyectoevidenciafoto_descripcion . '\', ' . $value->agente_id . ');"/>
                                    </div>
                                </div>';
                    } else {
                        $galeria .= '<i class="fa fa-download text-success" style="font-size: 26px; text-shadow: 2px 2px 4px #000000; position: absolute; opacity: 0; margin-top: 36px;" data-toggle="tooltip" title="Descargar" onclick="evidencia_foto_descargar(1, ' . $value->id . ');"></i>
                                        <img class="d-block img-fluid" src="/proyectoevidenciafotomostrar/0/' . $value->id . '" style="margin: 0px 0px 20px 0px;" data-toggle="tooltip" title="Click para mostrar" onclick="evidenciafoto_mostrardatos(' . $value->id . ', \'' . $value->proyectoevidenciafoto_archivo . '\', ' . $value->proyectoevidenciafoto_nopunto . ', \'' . $value->proyectoevidenciafoto_descripcion . '\', ' . $value->agente_id . ');"/>
                                    </div>
                                </div>';
                    }
                }
            }

            // respuesta
            $dato['fotos_total'] = count($sql);
            // $dato['fotos'] = $sql;
            $dato['fotos'] = $galeria;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['fotos_total'] = 0;
            $dato['fotos'] = null;
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $foto_opcion
     * @param  int  $foto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciafotomostrar($foto_opcion, $foto_id)
    {
        // $foto = proyectoevidenciafotoModel::findOrFail($foto_id);
        // return Storage::download($foto->proyectoevidenciafoto_archivo);
        // return Storage::response($foto->proyectoevidenciafoto_archivo);

        $foto = proyectoevidenciafotoModel::findOrFail($foto_id);

        if (($foto_opcion + 0) == 0) {
            return Storage::response($foto->proyectoevidenciafoto_archivo);
        } else {
            return Storage::download($foto->proyectoevidenciafoto_archivo);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $foto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciafotoeliminar($foto_id)
    {
        try {
            // Buscar registro
            $foto = proyectoevidenciafotoModel::findOrFail($foto_id);

            // Eliminar foto
            if (Storage::exists($foto->proyectoevidenciafoto_archivo)) {
                Storage::delete($foto->proyectoevidenciafoto_archivo);
            }

            // Eliminar registro
            $foto = proyectoevidenciafotoModel::where('id', $foto_id)->delete();

            // respuesta
            $dato["msj"] = 'Foto eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @param  $agente_nombre
     * @param  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciafotoeliminarcarpeta($proyecto_id, $agente_nombre, $carpeta)
    {
        try {
            // Eliminar carpeta
            Storage::deleteDirectory('proyecto/' . $proyecto_id . '/evidencias_campo/' . $agente_nombre . '/fotos/' . $carpeta);

            // Eliminar registros carpeta
            $carpeta = proyectoevidenciafotoModel::where('proyecto_id', $proyecto_id)
                ->where('agente_nombre', $agente_nombre)
                ->where('proyectoevidenciafoto_carpeta', $carpeta)
                ->delete();

            // respuesta
            $dato["msj"] = 'carpeta eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @param  int  $proyecto_id
     * @param  int  $agente_id
     * @param  $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciaplanos($proyecto_id, $agente_id, $agente_nombre)
    {
        try {
            // Proyecto
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            $where_proveedor = '';
            if (auth()->user()->hasRoles(['Externo']) && $agente_nombre != 'Información general') {
                $where_proveedor = 'AND proyectoevidenciaplano.proveedor_id = ' . auth()->user()->empleado_id;
            }

            $sql = DB::select('SELECT
                                    proyectoevidenciaplano.id,
                                    proyectoevidenciaplano.proyecto_id,
                                    proyectoevidenciaplano.agente_id,
                                    proyectoevidenciaplano.agente_nombre,
                                    proyectoevidenciaplano.proyectoevidenciaplano_carpeta,
                                    IFNULL(proyectoevidenciaplano.catreportequimicospartidas_id, 0) AS catreportequimicospartidas_id,
                                    proyectoevidenciaplano.proyectoevidenciaplano_archivo,
                                    proyectoevidenciaplano.created_at,
                                    proyectoevidenciaplano.updated_at
                                FROM
                                    proyectoevidenciaplano
                                WHERE
                                    proyectoevidenciaplano.proyecto_id = ' . $proyecto_id . '
                                    ' . $where_proveedor . '
                                    AND proyectoevidenciaplano.agente_nombre = "' . $agente_nombre . '"
                                ORDER BY
                                    proyectoevidenciaplano.proyectoevidenciaplano_carpeta ASC,
                                    proyectoevidenciaplano.id ASC');

            $galeria = '';
            $carpeta = 'XXXx';
            foreach ($sql as $key => $value) {
                // CARPETA PARA QUIMICOS
                if ($value->proyectoevidenciaplano_carpeta) {
                    if ($value->proyectoevidenciaplano_carpeta != $carpeta) {
                        $carpeta = $value->proyectoevidenciaplano_carpeta;

                        $galeria .= '<div class="col-12">
                                        <ol class="breadcrumb m-b-10" style="background: none; padding: 0px;">
                                            <i class="fa fa-folder-open fa-2x text-warning" style="float: left; margin-top: 8px; font-size: 20px;"></i>
                                            <span class="text-warning" style="float: left; margin-top: 3px; font-size: 20px; font-family: Calibri;">&nbsp;&nbsp;' . $carpeta . '</span>';

                        // if (!auth()->user()->hasRoles(['Externo']))
                        if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']) && ($proyecto->proyecto_concluido + 0) == 0) {
                            $galeria .= '<i class="fa fa-trash fa-2x text-danger" style="float: right; margin-left: 10px; cursor: pointer;" data-toggle="tooltip" title="Eliminar toda carpeta completa" onclick="eliminar_carpeta_planos(' . $proyecto_id . ', \'' . $value->agente_nombre . '\', \'' . $carpeta . '\');"></i>';
                        }

                        if ($value->agente_nombre == "Información general") {
                            if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador'])) {
                                $galeria .= '<i class="fa fa-pencil fa-2x text-warning" style="float: right; margin-left: 10px; cursor: pointer;" data-toggle="tooltip" title="Editar el nombre de la carpeta" onclick="editar_nombrecarpeta(2, \'' . $carpeta . '\', 0);"></i>
                                                    <i class="fa fa-plus-circle fa-2x text-info" style="float: right; cursor: pointer;" data-toggle="tooltip" title="Agregar más planos a esta carpeta" onclick="agregar_planos_carpeta(\'' . $carpeta . '\', 0);"></i>';
                            }
                        } else {
                            if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador'])) {
                                $galeria .= '<i class="fa fa-pencil fa-2x text-warning" style="float: right; margin-left: 10px; cursor: pointer;" data-toggle="tooltip" title="Editar el nombre de la carpeta" onclick="editar_nombrecarpeta(2, \'' . $carpeta . '\', ' . $value->catreportequimicospartidas_id . ');"></i>
                                                    <i class="fa fa-plus-circle fa-2x text-info" style="float: right; cursor: pointer;" data-toggle="tooltip" title="Agregar más planos a esta carpeta" onclick="agregar_planos_carpeta(\'' . $carpeta . '\', ' . $value->catreportequimicospartidas_id . ');"></i>';
                            }
                        }

                        $galeria .= '</ol><hr>
                                    </div>';
                    }
                }

                $galeria .= '<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 plano_galeria">';

                // if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']) || (auth()->user()->hasRoles(['Ergónomo']) && $value->agente_nombre == 'Riesgos ergonómicos') || (auth()->user()->hasRoles(['Psicólogo']) && $value->agente_nombre == 'Factores psicosociales'))
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']) && ($proyecto->proyecto_concluido + 0) == 0) {
                    $galeria .= '<i class="fa fa-trash text-danger" style="font-size: 26px; text-shadow: 2px 2px 4px #000000; position: absolute; opacity: 0;" data-toggle="tooltip" title="Eliminar" onclick="evidencia_plano_eliminar(' . $value->id . ');"></i>';
                }

                $galeria .= '<i class="fa fa-download text-success" style="font-size: 26px; text-shadow: 2px 2px 4px #000000; position: absolute; opacity: 0; margin-left: 40px;" data-toggle="tooltip" title="Descargar" onclick="evidencia_plano_descargar(1, ' . $value->id . ');"></i>
                                <a href="/proyectoevidenciaplanosmostrar/0/' . $value->id . '" data-effect="mfp-3d-unfold">
                                    <img class="d-block img-fluid" src="/proyectoevidenciaplanosmostrar/0/' . $value->id . '" style="margin: 0px 0px 20px 0px;" data-toggle="tooltip" title="Click para mostrar"/>
                                </a>
                            </div>';
            }

            // respuesta
            $dato['planos_total'] = count($sql);
            $dato['planos'] = $galeria;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['planos_total'] = 0;
            $dato['planos'] = null;
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $foto_opcion
     * @param  int  $foto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciaplanosmostrar($foto_opcion, $foto_id)
    {
        $foto = proyectoevidenciaplanoModel::findOrFail($foto_id);

        if (($foto_opcion + 0) == 0) {
            return Storage::response($foto->proyectoevidenciaplano_archivo);
        } else {
            return Storage::download($foto->proyectoevidenciaplano_archivo);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $foto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciaplanoeliminar($foto_id)
    {
        try {
            // Buscar registro
            $foto = proyectoevidenciaplanoModel::findOrFail($foto_id);

            // Eliminar foto
            if (Storage::exists($foto->proyectoevidenciaplano_archivo)) {
                Storage::delete($foto->proyectoevidenciaplano_archivo);
            }

            // Eliminar registro
            $foto = proyectoevidenciaplanoModel::where('id', $foto_id)->delete();

            // respuesta
            $dato["msj"] = 'Foto eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @param  $agente_nombre
     * @param  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciaplanoeliminarcarpeta($proyecto_id, $agente_nombre, $carpeta)
    {
        try {
            // Eliminar carpeta
            Storage::deleteDirectory('proyecto/' . $proyecto_id . '/evidencias_campo/' . $agente_nombre . '/planos/' . $carpeta);

            // Eliminar registros carpeta
            $carpeta = proyectoevidenciaplanoModel::where('proyecto_id', $proyecto_id)
                ->where('agente_nombre', $agente_nombre)
                ->where('proyectoevidenciaplano_carpeta', $carpeta)
                ->delete();

            // respuesta
            $dato["msj"] = 'carpeta eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciabitacoratabla($proyecto_id)
    {
        try {
            $signatarios = DB::select('SELECT
                                            proyectosignatariosactual.proyecto_id,
                                            proyectosignatariosactual.proveedor_id,
                                            proveedor.proveedor_NombreComercial,
                                            proyectosignatariosactual.signatario_id,
                                            signatario.signatario_Nombre 
                                        FROM
                                            proyectosignatariosactual
                                            LEFT JOIN proveedor ON proyectosignatariosactual.proveedor_id = proveedor.id
                                            LEFT JOIN signatario ON proyectosignatariosactual.signatario_id = signatario.id
                                        WHERE
                                            proyectosignatariosactual.proyecto_id = ' . $proyecto_id . ' 
                                        ORDER BY
                                            signatario.signatario_Nombre ASC');


            $dato['signatarios_opciones'] = $signatarios;


            //============================================================


            $parametros = DB::select('SELECT
                                            TABLA.id,
                                            TABLA.proyecto_id,
                                            TABLA.ot_folio,
                                            TABLA.ot_revision,
                                            TABLA.ot_autorizada,
                                            TABLA.ot_cancelada,
                                            -- TABLA.provedor_id,
                                            TABLA.agente_tipo,
                                            TABLA.agente_id,
                                            TABLA.agente_nombre
                                            -- TABLA.gente_puntos,
                                        FROM
                                            (
                                                SELECT
                                                    proyectoordentrabajo.id,
                                                    proyectoordentrabajo.proyecto_id,
                                                    proyectoordentrabajo.proyectoordentrabajo_folio AS ot_folio,
                                                    proyectoordentrabajo.proyectoordentrabajo_revision AS ot_revision,
                                                    proyectoordentrabajo.proyectoordentrabajo_autorizado AS ot_autorizada,
                                                    proyectoordentrabajo.proyectoordentrabajo_cancelado AS ot_cancelada,
                                                    proyectoordentrabajodatos.proyectoordentrabajodatos_proveedorid AS provedor_id,
                                                    IF(proyectoordentrabajodatos.proyectoordentrabajodatos_agenteid = 15, 1, 0) AS agente_tipo,
                                                    proyectoordentrabajodatos.proyectoordentrabajodatos_agenteid AS agente_id,
                                                    -- proyectoordentrabajodatos.proyectoordentrabajodatos_agentenombre,
                                                    IF(proyectoordentrabajodatos.proyectoordentrabajodatos_agenteid = 15, "Químico", proyectoordentrabajodatos.proyectoordentrabajodatos_agentenombre) AS agente_nombre,
                                                    proyectoordentrabajodatos.proyectoordentrabajodatos_agentepuntos AS gente_puntos 
                                                FROM
                                                    proyectoordentrabajo
                                                    RIGHT JOIN proyectoordentrabajodatos ON proyectoordentrabajo.id = proyectoordentrabajodatos.proyectoordentrabajo_id
                                                WHERE
                                                    proyectoordentrabajo.id = (SELECT
                                                                                    proyectoordentrabajo.id 
                                                                                FROM
                                                                                    proyectoordentrabajo
                                                                                WHERE
                                                                                    proyectoordentrabajo.proyecto_id = ' . $proyecto_id . '
                                                                                ORDER BY
                                                                                    proyectoordentrabajo.proyectoordentrabajo_revision DESC
                                                                                LIMIT 1) 
                                            ) AS TABLA
                                        GROUP BY
                                            TABLA.id,
                                            TABLA.proyecto_id,
                                            TABLA.ot_folio,
                                            TABLA.ot_revision,
                                            TABLA.ot_autorizada,
                                            TABLA.ot_cancelada,
                                            -- TABLA.provedor_id,
                                            TABLA.agente_tipo,
                                            TABLA.agente_id,
                                            TABLA.agente_nombre
                                            -- TABLA.gente_puntos
                                        ORDER BY
                                            TABLA.agente_tipo ASC,
                                            TABLA.agente_nombre ASC');


            $dato['parametros_opciones'] = $parametros;


            //============================================================


            $dato['bitacora_responsable_id'] = auth()->user()->id;
            $dato['bitacora_responsable_nombre'] = auth()->user()->empleado->empleado_nombre . ' ' . auth()->user()->empleado->empleado_apellidopaterno . ' ' . auth()->user()->empleado->empleado_apellidomaterno;


            //============================================================


            $proyecto = proyectoModel::findOrFail($proyecto_id);
            $dato["bitacoramuestreo_estado"] = ($proyecto->proyecto_bitacoraactivo + 0);


            $where_proveedor = '';
            if (auth()->user()->hasRoles(['Externo'])) {
                $where_proveedor = 'AND signatario.proveedor_id = ' . auth()->user()->empleado_id;
            }


            $bitacora = DB::select('SELECT
                                        proyectoevidenciabitacora.proyecto_id,
                                        proyectoevidenciabitacora.id,
                                        proyectoevidenciabitacora.usuario_id,
                                        IFNULL(CONCAT(empleado.empleado_nombre, " ", empleado.empleado_apellidopaterno, " ", empleado.empleado_apellidomaterno), "NO ENCONTRADO") AS usuario_nombre,
                                        -- empleado.empleado_nombre,
                                        -- empleado.empleado_apellidopaterno,
                                        -- empleado.empleado_apellidomaterno,
                                        proyectoevidenciabitacora.proyectoevidenciabitacora_fecha,
                                        proyectoevidenciabitacora.proyectoevidenciabitacora_observacion,
                                        proyectoevidenciabitacora.created_at,
                                        proyectoevidenciabitacora.updated_at,
                                        proyectoevidenciabitacorapersonal.signatario_id,
                                        -- proyectoevidenciabitacorapersonal.signatario_nombre,
                                        IF(IFNULL(signatario.signatario_Nombre, "") = "", proyectoevidenciabitacorapersonal.signatario_nombre, signatario.signatario_Nombre) AS signatario_nombre,
                                        proyectoevidenciabitacorapersonal.agente_id,
                                        proyectoevidenciabitacorapersonal.agente_nombre,
                                        proyectoevidenciabitacorapersonal.agente_puntos,
                                        proyectoevidenciabitacorapersonal.signatario_observacion,
                                        IFNULL((
                                            SELECT
                                                IF(COUNT(proyectoevidenciabitacorafoto.id) > 0, 1, 0)
                                            FROM
                                                proyectoevidenciabitacorafoto
                                            WHERE
                                                proyectoevidenciabitacorafoto.proyectoevidenciabitacora_id = proyectoevidenciabitacora.id
                                        ), 0) AS adjunto
                                    FROM
                                        proyectoevidenciabitacora
                                        RIGHT JOIN proyectoevidenciabitacorapersonal ON proyectoevidenciabitacora.id = proyectoevidenciabitacorapersonal.proyectoevidenciabitacora_id
                                        LEFT JOIN usuario ON proyectoevidenciabitacora.usuario_id = usuario.id
                                        LEFT JOIN empleado ON usuario.empleado_id = empleado.id
                                        LEFT JOIN signatario ON proyectoevidenciabitacorapersonal.signatario_id = signatario.id 
                                    WHERE
                                        proyectoevidenciabitacora.proyecto_id = ' . $proyecto_id . ' 
                                        ' . $where_proveedor . ' 
                                    ORDER BY
                                        proyectoevidenciabitacora.proyectoevidenciabitacora_fecha ASC,
                                        proyectoevidenciabitacorapersonal.signatario_id DESC');


            $numero_registro = 0;
            $dia = 'XXXX';
            $total_puntos = 0;
            foreach ($bitacora as $key => $value) {
                if ($dia != $value->proyectoevidenciabitacora_fecha) {
                    $numero_registro += 1;
                    $value->numero_registro = $numero_registro;
                    $dia = $value->proyectoevidenciabitacora_fecha;
                } else {
                    $value->numero_registro = $numero_registro;
                }


                $value->dia_adjunto = '<b>' . $value->proyectoevidenciabitacora_fecha . '</b>';
                if (($value->adjunto + 0) > 0) {
                    $value->dia_adjunto = '<b>' . $value->proyectoevidenciabitacora_fecha . '</b><br><i class="fa fa-paperclip" data-toggle="tooltip" title="foto(s) adjuntas"></i>';
                }

                $total_puntos += ($value->agente_puntos + 0);


                $value->signatarioparametro = '<b style="color: #999999;">' . $value->signatario_nombre . '</b><br>' . $value->agente_nombre;

                $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';

                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']) && ($proyecto->proyecto_bitacoraactivo + 0) == 1) {
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';

                } else {


                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" disabled><i class="fa fa-ban fa-2x"></i></button>';
                }
            }


            //============================================================


            $dato['bitacora_resumen'] = '<tr>
                                            <td colspan="2">No hay datos que mostrar</td>
                                        </tr>';


            $bitacora_resumen = DB::select('SELECT
                                                TABLA.agente_nombre,
                                                SUM(TABLA.agente_puntos) AS total
                                            FROM
                                                (
                                                    SELECT
                                                        proyectoevidenciabitacora.proyecto_id,
                                                        proyectoevidenciabitacora.id,
                                                        proyectoevidenciabitacora.usuario_id,
                                                        IFNULL(CONCAT(empleado.empleado_nombre, " ", empleado.empleado_apellidopaterno, " ", empleado.empleado_apellidomaterno), "NO ENCONTRADO") AS usuario_nombre,
                                                        -- empleado.empleado_nombre,
                                                        -- empleado.empleado_apellidopaterno,
                                                        -- empleado.empleado_apellidomaterno,
                                                        proyectoevidenciabitacora.proyectoevidenciabitacora_fecha,
                                                        proyectoevidenciabitacora.proyectoevidenciabitacora_observacion,
                                                        proyectoevidenciabitacora.created_at,
                                                        proyectoevidenciabitacora.updated_at,
                                                        proyectoevidenciabitacorapersonal.signatario_id,
                                                        -- proyectoevidenciabitacorapersonal.signatario_nombre,
                                                        IF(IFNULL(signatario.signatario_Nombre, "") = "", proyectoevidenciabitacorapersonal.signatario_nombre, signatario.signatario_Nombre) AS signatario_nombre,
                                                        proyectoevidenciabitacorapersonal.agente_id,
                                                        proyectoevidenciabitacorapersonal.agente_nombre,
                                                        proyectoevidenciabitacorapersonal.agente_puntos,
                                                        proyectoevidenciabitacorapersonal.signatario_observacion
                                                    FROM
                                                        proyectoevidenciabitacora
                                                        RIGHT JOIN proyectoevidenciabitacorapersonal ON proyectoevidenciabitacora.id = proyectoevidenciabitacorapersonal.proyectoevidenciabitacora_id
                                                        LEFT JOIN usuario ON proyectoevidenciabitacora.usuario_id = usuario.id
                                                        LEFT JOIN empleado ON usuario.empleado_id = empleado.id
                                                        LEFT JOIN signatario ON proyectoevidenciabitacorapersonal.signatario_id = signatario.id 
                                                    WHERE
                                                        proyectoevidenciabitacora.proyecto_id = ' . $proyecto_id . ' 
                                                        ' . $where_proveedor . ' 
                                                        AND proyectoevidenciabitacorapersonal.agente_puntos > 0
                                                    ORDER BY
                                                        proyectoevidenciabitacora.proyectoevidenciabitacora_fecha ASC,
                                                        proyectoevidenciabitacorapersonal.signatario_id DESC
                                                ) AS TABLA
                                            GROUP BY
                                                TABLA.agente_nombre
                                            ORDER BY
                                                TABLA.agente_nombre ASC');


            if (count($bitacora_resumen) > 0) {
                $dato['bitacora_resumen'] = '';


                foreach ($bitacora_resumen as $key => $value) {
                    $dato['bitacora_resumen'] .= '<tr>
                                                        <td width="">' . $value->agente_nombre . '</td>
                                                        <td width="150">' . $value->total . '</td>
                                                    </tr>';
                }
            }


            //============================================================


            $dato['data'] = $bitacora;
            $dato['total_puntos'] = $total_puntos;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['bitacora_resumen'] = '<tr>
                                            <td colspan="2">Error al consultar los datos</td>
                                        </tr>';

            $dato['data'] = 0;
            $dato['total_puntos'] = 0;
            $dato['signatarios_opciones'] = '<option value="">Error al consultar</option>';
            $dato['parametros_opciones'] = '<option value="">Error al consultar</option>';
            $dato['bitacora_responsable_id'] = 0;
            $dato['bitacora_responsable_nombre'] = 'Error al consultar Información';
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @param  int  $bitacora_id
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciabitacorafotos($bitacora_id, $proyecto_id)
    {
        try {
            $proyecto = proyectoModel::findOrFail($proyecto_id);


            $bitacora_fotos = DB::select('SELECT
                                                proyectoevidenciabitacorafoto.id,
                                                proyectoevidenciabitacorafoto.proyectoevidenciabitacora_id,
                                                proyectoevidenciabitacorafoto.proyectoevidenciabitacorafoto_ruta 
                                            FROM
                                                proyectoevidenciabitacorafoto
                                            WHERE
                                                proyectoevidenciabitacorafoto.proyectoevidenciabitacora_id = ' . $bitacora_id . ' 
                                            ORDER BY
                                                proyectoevidenciabitacorafoto.proyectoevidenciabitacorafoto_ruta ASC');

            $dato["fotos"] = '';
            if (count($bitacora_fotos) > 0) {
                $col = 'col-12';
                $height = '100';
                $margin = '0px auto';
                if (count($bitacora_fotos) > 1) {
                    $col = 'col-6';
                    $height = '80';
                    $margin = '10px auto';
                }

                foreach ($bitacora_fotos as $key => $value) {
                    $dato["fotos"] .= '<div class="' . $col . ' text-center" style="border: 0px #f00 solid;">
                                            <span>
                                                <i class="fa fa-download text-success" style="margin-left: -28px;" data-toggle="tooltip" title="Descargar" onclick="bitacorafoto_descargar(' . $value->id . ', 1);"></i>';

                    if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']) && ($proyecto->proyecto_bitacoraactivo + 0) == 1) {
                        $dato["fotos"] .= '<i class="fa fa-trash text-danger" style="margin-left: 5px;" data-toggle="tooltip" title="Eliminar" onclick="bitacorafoto_eliminar(' . $value->id . ', ' . $value->proyectoevidenciabitacora_id . ');"></i>';
                    }

                    $dato["fotos"] .= '<img class="d-block" src="/proyectoevidenciabitacorafotomostrar/' . $value->id . '/0" height="' . $height . '" style="max-width: 140px; margin: ' . $margin . ';" data-toggle="tooltip" title="Click para mostrar" onclick="bitacorafoto_mostrar(' . $value->id . ');"/>
                                            </span>
                                        </div>';
                }
            } else {
                $dato["fotos"] .= '<div class="col-12 text-center" style="border: 0px #f00 solid;">
                                        No hay fotos que mostar
                                    </div>';
            }


            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $bitacorafoto_id
     * @param  int $bitacorafoto_opcion
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciabitacorafotomostrar($bitacorafoto_id, $bitacorafoto_opcion)
    {
        $foto = proyectoevidenciabitacorafotoModel::findOrFail($bitacorafoto_id);

        if (($bitacorafoto_opcion + 0) == 0) {
            return Storage::response($foto->proyectoevidenciabitacorafoto_ruta);
        } else {
            return Storage::download($foto->proyectoevidenciabitacorafoto_ruta);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $bitacorafoto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciabitacorafotoeliminar($bitacorafoto_id)
    {
        try {
            // Buscar registro
            $foto = proyectoevidenciabitacorafotoModel::findOrFail($bitacorafoto_id);

            // Eliminar foto
            if (Storage::exists($foto->proyectoevidenciabitacorafoto_ruta)) {
                Storage::delete($foto->proyectoevidenciabitacorafoto_ruta);
            }

            // Eliminar registro
            $foto = proyectoevidenciabitacorafotoModel::where('id', $bitacorafoto_id)->delete();

            // respuesta
            $dato["msj"] = 'Foto eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
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
    public function proyectoevidenciabitacora(Request $request)
    {
        try {
            // dd($request->all());
            if (($request->bitacoramuestreo_id + 0) == 0) //NUEVO
            {
                DB::statement('ALTER TABLE proyectoevidenciabitacora AUTO_INCREMENT = 1;');
                $bitacora = proyectoevidenciabitacoraModel::create($request->all());


                if ($request->bitacora_signatario_id) {
                    DB::statement('ALTER TABLE proyectoevidenciabitacorapersonal AUTO_INCREMENT = 1;');

                    foreach ($request->bitacora_signatario_id as $key => $value) {
                        $signatario = proyectoevidenciabitacorapersonalModel::create([
                            'proyectoevidenciabitacora_id' => $bitacora->id, 'signatario_id' => $value, 'signatario_nombre' => $request['bitacora_signatario_nombre'][$key], 'signatario_observacion' => $request['bitacora_signatario_observacion'][$key], 'agente_id' => $request['bitacora_agente_id'][$key], 'agente_nombre' => $request['bitacora_agente_nombre'][$key], 'agente_puntos' => $request['bitacora_agente_puntos'][$key]
                        ]);
                    }
                }


                if ($request->file('proyectoevidenciabitacorafotos')) {
                    DB::statement('ALTER TABLE proyectoevidenciabitacorafoto AUTO_INCREMENT = 1;');

                    // Ruta destino archivo
                    $destinoPath = 'proyecto/' . $request->proyecto_id . '/evidencias_campo/Bitácora de muestreo/' . $bitacora->id;

                    // Crear directorio Ruta
                    Storage::makeDirectory($destinoPath);

                    // Obtener Lista de fotos quimicos y separar
                    $fotos = explode("*,", $request->evidenciabitacora_fotos);

                    // Convertir fotos a FILE y guardar
                    for ($i = 0; $i < count($fotos); $i++) {
                        $foto = proyectoevidenciabitacorafotoModel::create([
                            'proyectoevidenciabitacora_id' => $bitacora->id, 'proyectoevidenciabitacorafoto_ruta' => $destinoPath . '/foto_' . ($i + 1) . '.jpg'
                        ]);

                        // Codificar imagen recibida como tipo base64
                        $imagen_recibida = explode(',', str_replace("*", "", $fotos[$i])); //Quitar los [*] de la cadena string
                        $imagen_nueva = base64_decode($imagen_recibida[1]);

                        // GUardar Foto
                        Storage::put($destinoPath . "/foto_" . ($i + 1) . ".jpg", $imagen_nueva); // Guardar en storage
                    }
                }


                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            } else {
                $bitacora = proyectoevidenciabitacoraModel::findOrFail($request->bitacoramuestreo_id);
                $bitacora->update($request->all());


                if ($request->bitacora_signatario_id) {
                    $eliminar_signatarios = proyectoevidenciabitacorapersonalModel::where('proyectoevidenciabitacora_id', $request->bitacoramuestreo_id)->delete();

                    DB::statement('ALTER TABLE proyectoevidenciabitacorapersonal AUTO_INCREMENT = 1;');

                    foreach ($request->bitacora_signatario_id as $key => $value) {
                        $signatario = proyectoevidenciabitacorapersonalModel::create([
                            'proyectoevidenciabitacora_id' => $bitacora->id, 'signatario_id' => $value, 'signatario_nombre' => $request['bitacora_signatario_nombre'][$key], 'signatario_observacion' => $request['bitacora_signatario_observacion'][$key], 'agente_id' => $request['bitacora_agente_id'][$key], 'agente_nombre' => $request['bitacora_agente_nombre'][$key], 'agente_puntos' => $request['bitacora_agente_puntos'][$key]
                        ]);
                    }
                }


                if ($request->file('proyectoevidenciabitacorafotos')) {
                    $eliminar_fotos = proyectoevidenciabitacorafotoModel::where('proyectoevidenciabitacora_id', $request->bitacoramuestreo_id)->delete();

                    DB::statement('ALTER TABLE proyectoevidenciabitacorafoto AUTO_INCREMENT = 1;');

                    // Ruta destino archivo
                    $destinoPath = 'proyecto/' . $request->proyecto_id . '/evidencias_campo/Bitácora de muestreo/' . $bitacora->id;

                    // Eliminar carpeta anterior
                    if (Storage::exists($destinoPath)) {
                        Storage::deleteDirectory($destinoPath);
                    }

                    // Crear directorio Ruta
                    Storage::makeDirectory($destinoPath);

                    // Obtener Lista de fotos quimicos y separar
                    $fotos = explode("*,", $request->evidenciabitacora_fotos);

                    // Convertir fotos a FILE y guardar
                    for ($i = 0; $i < count($fotos); $i++) {
                        $foto = proyectoevidenciabitacorafotoModel::create([
                            'proyectoevidenciabitacora_id' => $bitacora->id, 'proyectoevidenciabitacorafoto_ruta' => $destinoPath . '/foto_' . ($i + 1) . '.jpg'
                        ]);

                        // Codificar imagen recibida como tipo base64
                        $imagen_recibida = explode(',', str_replace("*", "", $fotos[$i])); //Quitar los [*] de la cadena string
                        $imagen_nueva = base64_decode($imagen_recibida[1]);

                        // GUardar Foto
                        Storage::put($destinoPath . "/foto_" . ($i + 1) . ".jpg", $imagen_nueva); // Guardar en storage
                    }
                }


                // Mensaje
                $dato["msj"] = 'Datos modificaodos correctamente';
            }


            // Respuesta
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $bitacora_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciabitacoraeliminar($bitacora_id)
    {
        try {
            // Buscar registro
            $bitacora = proyectoevidenciabitacoraModel::findOrFail($bitacora_id);


            $eliminar_signatarios = proyectoevidenciabitacorapersonalModel::where('proyectoevidenciabitacora_id', $bitacora_id)->delete();


            $eliminar_fotos = proyectoevidenciabitacorafotoModel::where('proyectoevidenciabitacora_id', $bitacora_id)->delete();


            // Eliminar carpeta fotos completa
            $destinoPath = 'proyecto/' . $bitacora->proyecto_id . '/evidencias_campo/Bitácora de muestreo/' . $bitacora_id;
            if (Storage::exists($destinoPath)) {
                Storage::deleteDirectory($destinoPath);
            }


            // Eliminar registro
            $bitacora = proyectoevidenciabitacoraModel::where('id', $bitacora_id)->delete();


            // respuesta
            $dato["msj"] = 'Datos eliminados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciabitacoraimprimir($proyecto_id)
    {
        try {
            $proyecto = proyectoModel::findOrFail($proyecto_id);
            $ordentrabajo = proyectoordentrabajoModel::where('proyecto_id', $proyecto_id)->orderBy('proyectoordentrabajo_revision', 'DESC')->limit(1)->get();
            $bitacora = proyectoevidenciabitacoraModel::where('proyecto_id', $proyecto_id)->get();


            $ordentrabajo_folio = "No disponible";
            if (count($ordentrabajo) > 0) {
                $ordentrabajo_folio = $ordentrabajo[0]->proyectoordentrabajo_folio;
            }


            return \PDF::loadView('reportes.proyecto.reporteproyectobitacoramuestreo', compact('proyecto', 'bitacora', 'ordentrabajo_folio'))->stream('Bitácora de muestreo ' . $proyecto->proyecto_folio . '.pdf');

            // respuesta
            $dato["msj"] = 'Datos eliminados correctamente';
            // return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciabitacoraactivo($proyecto_id)
    {
        try {
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            if (($proyecto->proyecto_bitacoraactivo + 0) == 0) {
                $proyecto->update([
                    'proyecto_bitacoraactivo' => 1
                ]);


                // Mensaje
                $dato["bitacoramuestreo_estado"] = 1;
                $dato["msj"] = 'Bitácora de muestreo desbloqueado para edición';
            } else {
                $proyecto->update([
                    'proyecto_bitacoraactivo' => 0
                ]);

                // Mensaje
                $dato["bitacoramuestreo_estado"] = 0;
                $dato["msj"] = 'Bitácora de muestreo bloqueado para edición';
            }

            // Respuesta
            return response()->json($dato);
        } catch (Exception $e) {
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
        try {
            // dd($request->all());

            // Obtener Proveedor
            $proveedor_id = 0;
            if (auth()->user()->hasRoles(['Externo'])) {
                $proveedor_id = auth()->user()->empleado_id;
            }


            // DOCUMENTOS
            if ($request->opcion == 1) {
                if ($request->evidenciadocumento_id == 0) // NUEVO
                {
                    // AUTO_INCREMENT
                    DB::statement('ALTER TABLE proyectoevidenciadocumento AUTO_INCREMENT = 1;');
                    $documento = proyectoevidenciadocumentoModel::create([
                        'proyecto_id' => $request->proyecto_id, 'proveedor_id' => $proveedor_id, 'agente_id' => $request->agente_id, 'agente_nombre' => $request->agente_nombre, 'proyectoevidenciadocumento_nombre' => str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->proyectoevidenciadocumento_nombre)
                    ]);

                    // si envia archivo
                    if ($request->file('evidenciadocumento')) {
                        // $nombre_archivo = $request->file('evidenciadocumento')->getClientOriginalName();
                        $extension = $request->file('evidenciadocumento')->getClientOriginalExtension();

                        // Copiar archivo
                        $request->proyectoevidenciadocumento_archivo = $request->file('evidenciadocumento')->storeAs('proyecto/' . $request->proyecto_id . '/evidencias_campo/' . $request->agente_nombre . '/documentos', 'archivo_' . $documento->id . '.' . $extension);

                        // Modificar ARCHIVO
                        $documento->update([
                            'proyectoevidenciadocumento_archivo' => $request->proyectoevidenciadocumento_archivo, 'proyectoevidenciadocumento_extension' => '.' . $extension
                        ]);
                    }

                    // Mensaje
                    $dato["msj"] = 'Información guardada correctamente';
                } else // EDITAR
                {
                    $documento = proyectoevidenciadocumentoModel::findOrFail($request->evidenciadocumento_id);

                    // Modificar
                    $documento->update([
                        'proyectoevidenciadocumento_nombre' => str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->proyectoevidenciadocumento_nombre)
                    ]);

                    // si envia archivo
                    if ($request->file('evidenciadocumento')) {
                        // $nombre_archivo = $request->file('evidenciadocumento')->getClientOriginalName();
                        $extension = $request->file('evidenciadocumento')->getClientOriginalExtension();

                        if ('.' . $request->file('evidenciadocumento')->getClientOriginalExtension() == $documento->proyectoevidenciadocumento_extension) //mismo tipo de archivo
                        {
                            // Copiar documento
                            $request->proyectoevidenciadocumento_archivo = $request->file('evidenciadocumento')->storeAs('proyecto/' . $request->proyecto_id . '/evidencias_campo/' . $documento->agente_nombre . '/documentos', 'archivo_' . $documento->id . '.' . $extension);
                        } else {
                            // Eliminar documento
                            if (Storage::exists($documento->proyectoevidenciadocumento_archivo)) {
                                Storage::delete($documento->proyectoevidenciadocumento_archivo);
                            }

                            // Copiar documento
                            $request->proyectoevidenciadocumento_archivo = $request->file('evidenciadocumento')->storeAs('proyecto/' . $request->proyecto_id . '/evidencias_campo/' . $documento->agente_nombre . '/documentos', 'archivo_' . $documento->id . '.' . $extension);

                            // Modificar registro
                            $documento->update([
                                'proyectoevidenciadocumento_archivo' => $request->proyectoevidenciadocumento_archivo, 'proyectoevidenciadocumento_extension' => '.' . $extension
                            ]);
                        }
                    }

                    // Mensaje
                    $dato["msj"] = 'Información modificada correctamente';
                }
            }


            // FOTOS
            if ($request->opcion == 2) {
                // AUTO_INCREMENT
                DB::statement('ALTER TABLE proyectoevidenciafoto AUTO_INCREMENT = 1;');


                if ($request->proyectoevidenciafoto_nopunto == null) {
                    $request->proyectoevidenciafoto_nopunto = $request->catreportequimicospartidas_id;
                }


                // Datos foto fisicos
                if (($request->evidenciafotos_id + 0) > 0) // FOTO REGISTRO EXISTENTE
                {
                    // Buscar registro
                    $foto = proyectoevidenciafotoModel::findOrFail($request->evidenciafotos_id);

                    if ($request->file('inputevidenciafotofisicos')) // Si envia FOTO
                    {
                        // Eliminar foto
                        if (Storage::exists($foto->proyectoevidenciafoto_archivo)) {
                            Storage::delete($foto->proyectoevidenciafoto_archivo);
                        }

                        // Eliminar registro
                        $foto = proyectoevidenciafotoModel::where('id', $request->evidenciafotos_id)->delete();

                        // Creamos nuevamente el registro
                        $archivo = proyectoevidenciafotoModel::create([
                            'proyecto_id' => $request->proyecto_id, 'proveedor_id' => $proveedor_id, 'agente_id' => $request->agente_id, 'agente_nombre' => $request->agente_nombre, 'proyectoevidenciafoto_carpeta' => str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->proyectoevidenciafoto_carpeta), 'proyectoevidenciafoto_nopunto' => $request->proyectoevidenciafoto_nopunto, 'proyectoevidenciafoto_descripcion' => $request->proyectoevidenciafoto_descripcion
                        ]);

                        // // Guardamos la nueva foto
                        // $extension = $request->file('inputevidenciafotofisicos')->getClientOriginalExtension();
                        // $archivo_foto = $request->file('inputevidenciafotofisicos')->storeAs('proyecto/'.$request->proyecto_id.'/evidencias_campo/'.$request->agente_nombre.'/fotos', 'foto_'.$archivo->id.'.'.$extension);

                        // // Actualizar ruta foto
                        // $archivo->update([
                        //     'proyectoevidenciafoto_archivo' => $archivo_foto
                        // ]);

                        //====================================

                        // // Ruta destino archivo
                        // $destinoPath = 'proyecto/'.$request->proyecto_id.'/evidencias_campo/'.$request->agente_nombre.'/fotos';

                        // // Crear directorio Ruta
                        // Storage::makeDirectory($destinoPath);

                        // // Obtener extension
                        // $extension = $request->file('inputevidenciafotofisicos')->getClientOriginalExtension();

                        // // Redimensionar imagen a [1200 x 900] y GUARDAR
                        // Image::make($request->file('inputevidenciafotofisicos')->path())->resize(1200, null, function ($constraint) {$constraint->aspectRatio();})->save(storage_path('app/'.$destinoPath.'/foto_'.$archivo->id.'.'.$extension));

                        // // Actualizar ruta foto
                        // $archivo->update([
                        //     'proyectoevidenciafoto_archivo' => $destinoPath.'/foto_'.$archivo->id.'.'.$extension
                        // ]);

                        //====================================

                        // Codificar imagen recibida como tipo base64
                        $imagen_recibida = explode(',', $request->foto_resize_fisicos); //Archivo foto tipo base64
                        $imagen_nueva = base64_decode($imagen_recibida[1]);

                        // // Ruta destino archivo
                        $destinoPath = 'proyecto/' . $request->proyecto_id . '/evidencias_campo/' . $request->agente_nombre . '/fotos/foto_' . $archivo->id . '.jpg';

                        // GUardar Foto
                        Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                        // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                        // Actualizar ruta foto
                        $archivo->update([
                            'proyectoevidenciafoto_archivo' => $destinoPath
                        ]);
                    } else {
                        // Actualizar datos
                        $foto->update([
                            'proyectoevidenciafoto_nopunto' => $request->proyectoevidenciafoto_nopunto, 'proyectoevidenciafoto_descripcion' => $request->proyectoevidenciafoto_descripcion, 'proyectoevidenciafoto_carpeta' => str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->proyectoevidenciafoto_carpeta)
                        ]);
                    }
                } else // NUEVA FOTO
                {
                    // Foto fisicos
                    if ($request->file('inputevidenciafotofisicos')) {
                        $archivo = proyectoevidenciafotoModel::create([
                            'proyecto_id' => $request->proyecto_id, 'proveedor_id' => $proveedor_id, 'agente_id' => $request->agente_id, 'agente_nombre' => $request->agente_nombre, 'proyectoevidenciafoto_carpeta' => str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->proyectoevidenciafoto_carpeta), 'proyectoevidenciafoto_nopunto' => $request->proyectoevidenciafoto_nopunto, 'proyectoevidenciafoto_descripcion' => $request->proyectoevidenciafoto_descripcion
                        ]);

                        // $extension = $request->file('inputevidenciafotofisicos')->getClientOriginalExtension();
                        // $archivo_foto = $request->file('inputevidenciafotofisicos')->storeAs('proyecto/'.$request->proyecto_id.'/evidencias_campo/'.$request->agente_nombre.'/fotos', 'foto_'.$archivo->id.'.'.$extension);

                        // // Actualizar ruta foto
                        // $archivo->update([
                        //     'proyectoevidenciafoto_archivo' => $archivo_foto
                        // ]);

                        //====================================

                        // // Ruta destino archivo
                        // $destinoPath = 'proyecto/'.$request->proyecto_id.'/evidencias_campo/'.$request->agente_nombre.'/fotos';

                        // // Crear directorio Ruta
                        // Storage::makeDirectory($destinoPath);

                        // // Obtener extension
                        // $extension = $request->file('inputevidenciafotofisicos')->getClientOriginalExtension();

                        // // Redimensionar imagen a [1200 x 900] y GUARDAR
                        // Image::make($request->file('inputevidenciafotofisicos')->path())->resize(1200, null, function ($constraint) {$constraint->aspectRatio();})->save(storage_path('app/'.$destinoPath.'/foto_'.$archivo->id.'.'.$extension));

                        // // Actualizar ruta foto
                        // $archivo->update([
                        //     'proyectoevidenciafoto_archivo' => $destinoPath.'/foto_'.$archivo->id.'.'.$extension
                        // ]);

                        //====================================

                        // Codificar imagen recibida como tipo base64
                        $imagen_recibida = explode(',', $request->foto_resize_fisicos); //Archivo foto tipo base64
                        $imagen_nueva = base64_decode($imagen_recibida[1]);

                        // // Ruta destino archivo
                        $destinoPath = 'proyecto/' . $request->proyecto_id . '/evidencias_campo/' . $request->agente_nombre . '/fotos/foto_' . $archivo->id . '.jpg';

                        // GUardar Foto
                        Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                        // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                        // Actualizar ruta foto
                        $archivo->update([
                            'proyectoevidenciafoto_archivo' => $destinoPath
                        ]);
                    }

                    // Fotos quimicos
                    if ($request->file('inputevidenciafotosquimicos')) {
                        // Ruta destino archivo
                        $destinoPath = 'proyecto/' . $request->proyecto_id . '/evidencias_campo/' . $request->agente_nombre . '/fotos/' . str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->proyectoevidenciafoto_carpeta);

                        // Crear directorio Ruta
                        Storage::makeDirectory($destinoPath);

                        // // Recorrer lista de fotos
                        // foreach ($request->file('inputevidenciafotosquimicos') as $key => $foto)
                        // {
                        //     $archivo = proyectoevidenciafotoModel::create([
                        //           'proyecto_id' => $request->proyecto_id
                        //         , 'proveedor_id' => $proveedor_id
                        //         , 'agente_id' => $request->agente_id
                        //         , 'agente_nombre' => $request->agente_nombre
                        //         , 'proyectoevidenciafoto_carpeta' => str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->proyectoevidenciafoto_carpeta)
                        //         // , 'proyectoevidenciafoto_nopunto' => $request->proyectoevidenciafoto_nopunto
                        //         // , 'proyectoevidenciafoto_descripcion' => $request->proyectoevidenciafoto_descripcion
                        //     ]);

                        //     // // Archivo
                        //     // $extension = $foto->getClientOriginalExtension();
                        //     // $archivo_foto = $foto->storeAs('proyecto/'.$request->proyecto_id.'/evidencias_campo/'.$request->agente_nombre.'/fotos/'.str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->proyectoevidenciafoto_carpeta), 'foto_'.$archivo->id.'.'.$extension);

                        //     // // Actualizar ruta foto
                        //     // $archivo->update([
                        //     //     'proyectoevidenciafoto_archivo' => $archivo_foto
                        //     // ]);

                        //     //====================================

                        //     // // Obtener extension
                        //     // $extension = $foto->getClientOriginalExtension();

                        //     // // Redimensionar imagen a [1200 x 900] y GUARDAR
                        //     // Image::make($foto->path())->resize(1200, null, function ($constraint) {$constraint->aspectRatio();})->save(storage_path('app/'.$destinoPath.'/foto_'.$archivo->id.'.'.$extension));

                        //     // // Actualizar ruta foto
                        //     // $archivo->update([
                        //     //     'proyectoevidenciafoto_archivo' => $destinoPath.'/foto_'.$archivo->id.'.'.$extension
                        //     // ]);
                        // }


                        // Obtener Lista de fotos quimicos y separar
                        $imagenes_quimicos = explode("*,", $request->foto_resize_quimicos);

                        // Convertir fotos a FILE y guardar
                        for ($i = 0; $i < count($imagenes_quimicos); $i++) {
                            $archivo = proyectoevidenciafotoModel::create([
                                'proyecto_id' => $request->proyecto_id, 'proveedor_id' => $proveedor_id, 'agente_id' => $request->agente_id, 'agente_nombre' => $request->agente_nombre, 'proyectoevidenciafoto_carpeta' => str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->proyectoevidenciafoto_carpeta)
                                // , 'proyectoevidenciafoto_nopunto' => $request->proyectoevidenciafoto_nopunto
                                // , 'proyectoevidenciafoto_descripcion' => $request->proyectoevidenciafoto_descripcion
                            ]);

                            // Codificar imagen recibida como tipo base64
                            $imagen_recibida = explode(',', str_replace("*", "", $imagenes_quimicos[$i])); //Quitar los [*] de la cadena string
                            $imagen_nueva = base64_decode($imagen_recibida[1]);

                            // GUardar Foto
                            Storage::put($destinoPath . "/foto_" . $archivo->id . ".jpg", $imagen_nueva); // Guardar en storage

                            // Actualizar ruta foto
                            $archivo->update([
                                'proyectoevidenciafoto_archivo' => $destinoPath . '/foto_' . $archivo->id . '.jpg'
                            ]);
                        }
                    }
                }

                // Mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }


            // PLANOS
            if ($request->opcion == 3) {
                // AUTO_INCREMENT
                DB::statement('ALTER TABLE proyectoevidenciaplano AUTO_INCREMENT = 1;');


                // if ($request->catreportequimicospartidas_id == null)
                // {
                //     $request->catreportequimicospartidas_id = $request->catreportequimicospartidas_id;
                // }


                // Fotos plano
                if ($request->file('inputevidenciaplanos')) {
                    // Ruta destino archivo
                    $destinoPath = 'proyecto/' . $request->proyecto_id . '/evidencias_campo/' . $request->agente_nombre . '/planos/' . str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->proyectoevidenciaplano_carpeta);

                    // Crear directorio Ruta
                    Storage::makeDirectory($destinoPath);

                    // foreach ($request->file('inputevidenciaplanos') as $key => $foto)
                    // {
                    //     $archivo = proyectoevidenciaplanoModel::create([
                    //           'proyecto_id' => $request->proyecto_id
                    //         , 'proveedor_id' => $proveedor_id
                    //         , 'agente_id' => $request->agente_id
                    //         , 'agente_nombre' => $request->agente_nombre
                    //         , 'proyectoevidenciaplano_carpeta' => str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->proyectoevidenciaplano_carpeta)
                    //     ]);

                    //     // // Archivo
                    //     // $extension = $foto->getClientOriginalExtension();
                    //     // $archivo_foto = $foto->storeAs('proyecto/'.$request->proyecto_id.'/evidencias_campo/'.$request->agente_nombre.'/planos/'.str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->proyectoevidenciaplano_carpeta), 'plano_'.$archivo->id.'.'.$extension);

                    //     // // Actualizar ruta foto
                    //     // $archivo->update([
                    //     //     'proyectoevidenciaplano_archivo' => $archivo_foto
                    //     // ]);

                    //     //====================================

                    //     // // Obtener extension
                    //     // $extension = $foto->getClientOriginalExtension();

                    //     // // Redimensionar imagen a [1200 x 900] y GUARDAR
                    //     // Image::make($foto->path())->resize(1200, null, function ($constraint) {$constraint->aspectRatio();})->save(storage_path('app/'.$destinoPath.'/foto_'.$archivo->id.'.'.$extension));

                    //     // // Actualizar ruta foto
                    //     // $archivo->update([
                    //     //     'proyectoevidenciaplano_archivo' => $destinoPath.'/foto_'.$archivo->id.'.'.$extension
                    //     // ]);
                    // }


                    // Obtener Lista de fotos quimicos y separar
                    $imagenes_planos = explode("*,", $request->foto_resize_planos);

                    // Convertir fotos a FILE y guardar
                    for ($i = 0; $i < count($imagenes_planos); $i++) {
                        $archivo = proyectoevidenciaplanoModel::create([
                            'proyecto_id' => $request->proyecto_id, 'proveedor_id' => $proveedor_id, 'agente_id' => $request->agente_id, 'agente_nombre' => $request->agente_nombre, 'proyectoevidenciaplano_carpeta' => str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request->proyectoevidenciaplano_carpeta), 'catreportequimicospartidas_id' => $request->catreportequimicospartidas_id
                        ]);

                        // Codificar imagen recibida como tipo base64
                        $imagen_recibida = explode(',', str_replace("*", "", $imagenes_planos[$i])); //Quitar los [*] de la cadena string
                        $imagen_nueva = base64_decode($imagen_recibida[1]);

                        // GUardar Foto
                        Storage::put($destinoPath . "/plano_" . $archivo->id . ".jpg", $imagen_nueva); // Guardar en storage

                        // Actualizar ruta foto
                        $archivo->update([
                            'proyectoevidenciaplano_archivo' => $destinoPath . '/plano_' . $archivo->id . '.jpg'
                        ]);
                    }
                }

                // Mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }


            // RENOMBRAR NOMBRE CARPETA FOTOS Y PLANOS
            if ($request->opcion == 4) {
                $ruta_raiz = 'proyecto/' . $request->proyecto_id . '/evidencias_campo/' . $request->agente_nombre;

                if ($request->proyectoevidencia_nombrecarpetatipo == 1) {
                    Storage::move($ruta_raiz . '/fotos/' . $request->proyectoevidencia_nombrecarpetaoriginal, $ruta_raiz . '/fotos/' . $request->proyectoevidencia_nombrecarpetarenombrar);

                    $registros = proyectoevidenciafotoModel::where('proyecto_id', $request->proyecto_id)
                        ->where('agente_nombre', $request->agente_nombre)
                        ->where('proyectoevidenciafoto_carpeta', $request->proyectoevidencia_nombrecarpetaoriginal)
                        ->get();

                    foreach ($registros as $key => $value) {
                        $registros[$key]->update([
                            'proyectoevidenciafoto_carpeta' => $request->proyectoevidencia_nombrecarpetarenombrar, 'catreportequimicospartidas_id' => $request->catreportequimicospartidas_id, 'proyectoevidenciafoto_archivo' => str_replace($request->proyectoevidencia_nombrecarpetaoriginal, $request->proyectoevidencia_nombrecarpetarenombrar, $value->proyectoevidenciafoto_archivo)
                        ]);
                    }
                } else {
                    Storage::move($ruta_raiz . '/planos/' . $request->proyectoevidencia_nombrecarpetaoriginal, $ruta_raiz . '/planos/' . $request->proyectoevidencia_nombrecarpetarenombrar);

                    $registros = proyectoevidenciaplanoModel::where('proyecto_id', $request->proyecto_id)
                        ->where('agente_nombre', $request->agente_nombre)
                        ->where('proyectoevidenciaplano_carpeta', $request->proyectoevidencia_nombrecarpetaoriginal)
                        ->get();

                    foreach ($registros as $key => $value) {
                        $registros[$key]->update([
                            'proyectoevidenciaplano_carpeta' => $request->proyectoevidencia_nombrecarpetarenombrar, 'catreportequimicospartidas_id' => $request->catreportequimicospartidas_id, 'proyectoevidenciaplano_archivo' => str_replace($request->proyectoevidencia_nombrecarpetaoriginal, $request->proyectoevidencia_nombrecarpetarenombrar, $value->proyectoevidenciaplano_archivo)
                        ]);
                    }
                }

                // Mensaje
                $dato["msj"] = 'Carpeta renombrada correctamente';
            }


            // respuesta
            return response()->json($dato);
        } catch (\Exception $e) {
            $dato["msj"] = 'Error en el controlador: ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function proyectoevidenciaredimencionarfotos()
    {
        try {
            $sql = DB::select('SELECT
                                    proyectoevidenciafoto.id,
                                    proyectoevidenciafoto.proyecto_id,
                                    proyectoevidenciafoto.agente_nombre,
                                    proyectoevidenciafoto.proyectoevidenciafoto_carpeta,
                                    proyectoevidenciafoto.proyectoevidenciafoto_archivo 
                                FROM
                                    proyectoevidenciafoto
                                WHERE
                                    proyectoevidenciafoto.id = 8');

            foreach ($sql as $key => $value) {
                // $size = Storage::size($value->proyectoevidenciafoto_archivo);
                // $height = Image::make(Storage::path($value->proyectoevidenciafoto_archivo))->height();
                $width = Image::make(Storage::path($value->proyectoevidenciafoto_archivo))->width();

                if (($width + 0) > 8000) {
                    Image::make(Storage::path($value->proyectoevidenciafoto_archivo))->resize(5000, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save(Storage::path($value->proyectoevidenciafoto_archivo));
                } else {
                    Image::make(Storage::path($value->proyectoevidenciafoto_archivo))->resize(1200, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save(Storage::path($value->proyectoevidenciafoto_archivo));
                }
            }

            // respuesta
            // echo 'correctamente '.$width.' - '.$height;
            echo 'correcto';
            // return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            // return response()->json($dato);
        }
    }
}

<?php

namespace App\Http\Controllers\recsensorialquimicos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\recsensorialquimicos\recsensorialquimicosinventarioModel;
use App\modelos\recsensorial\recsensorialcategoriaModel;
use App\modelos\recsensorial\recsensorialareacategoriasModel;
use App\modelos\recsensorialquimicos\catcategoriapeligrosaludModel;
use App\modelos\recsensorialquimicos\catgradoriesgosaludModel;
use App\modelos\recsensorialquimicos\catunidadmedidasustaciaModel;
use App\modelos\recsensorialquimicos\catestadofisicosustanciaModel;
use App\modelos\recsensorialquimicos\catviaingresoorganismoModel;
use App\modelos\recsensorialquimicos\catvolatilidadModel;
use App\modelos\recsensorialquimicos\catsustanciaModel;
use App\modelos\recsensorialquimicos\gruposDeExposicionModel;
use App\modelos\recsensorialquimicos\evaluacionBeisModel;
use DB;

class recsensorialquimicosinventarioController extends Controller
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
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialquimicosinventariotabla($recsensorial_id)
    {
        try {
            // Reconocimiento
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);


            $inventariosustancias = DB::select('SELECT
                                                    recsensorialquimicosinventario.recsensorial_id,
                                                    recsensorialquimicosinventario.id,
                                                    recsensorialquimicosinventario.recsensorialarea_id,
                                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                                    recsensorialquimicosinventario.recsensorialcategoria_id,
                                                    IFNULL(recsensorialcategoria.recsensorialcategoria_nombrecategoria, "Sin dato") AS recsensorialcategoria_nombrecategoria,
                                                    ##recsensorialcategoria.recsensorialcategoria_funcioncategoria,
                                                    IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) AS recsensorialcategoria_tiempoexpo,
                                                    IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0) AS recsensorialcategoria_frecuenciaexpo,
                                                    recsensorialquimicosinventario.catsustancia_id,
                                                    IFNULL(catsustancia.catsustancia_nombre, "Sin dato") AS catsustancia_nombre,
                                                    (
                                                            SELECT
                                                                    REPLACE ( CONCAT( "● ", REPLACE ( GROUP_CONCAT( sus.SUSTANCIA_QUIMICA ), ",", "<br>● " ) ), "ˏ", ",")
                                                            FROM
                                                                    catHojasSeguridad_SustanciasQuimicas hoja
                                                            LEFT JOIN catsustancias_quimicas sus ON hoja.SUSTANCIA_QUIMICA_ID = sus.ID_SUSTANCIA_QUIMICA
                                                            WHERE
                                                                    hoja.HOJA_SEGURIDAD_ID = recsensorialquimicosinventario.catsustancia_id
                                                    ) AS sustancia_componentes,
                                                    
                                                    catestadofisicosustancia.catestadofisicosustancia_estado,
                                                    recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad,
                                                    catunidadmedidasustacia.catunidadmedidasustacia_abreviacion,
                                                    catvolatilidad.catvolatilidad_tipo,
                                                    catviaingresoorganismo.catviaingresoorganismo_viaingreso,
                                                    catsustancia.catcategoriapeligrosalud_id,
                                                    catgradoriesgosalud.catgradoriesgosalud_clasificacion,
                                                    catsustancia.catgradoriesgosalud_id,
                                                    catcategoriapeligrosalud.catcategoriapeligrosalud_codigo 
                                            FROM
                                                    recsensorialquimicosinventario
                                                    LEFT JOIN recsensorialarea ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialarea.id
                                                    LEFT JOIN recsensorialcategoria ON recsensorialquimicosinventario.recsensorialcategoria_id = recsensorialcategoria.id
                                                    LEFT JOIN catunidadmedidasustacia ON recsensorialquimicosinventario.catunidadmedidasustacia_id = catunidadmedidasustacia.id
                                                    LEFT JOIN catsustancia ON recsensorialquimicosinventario.catsustancia_id = catsustancia.id
                                                    LEFT JOIN catestadofisicosustancia ON catsustancia.catestadofisicosustancia_id = catestadofisicosustancia.id
                                                    LEFT JOIN catvolatilidad ON catsustancia.catvolatilidad_id = catvolatilidad.id
                                                    LEFT JOIN catviaingresoorganismo ON catsustancia.catviaingresoorganismo_id = catviaingresoorganismo.id
                                                    LEFT JOIN catgradoriesgosalud ON catsustancia.catgradoriesgosalud_id = catgradoriesgosalud.id
                                                    LEFT JOIN catcategoriapeligrosalud ON catsustancia.catcategoriapeligrosalud_id = catcategoriapeligrosalud.id 
                                            WHERE
                                                    recsensorialquimicosinventario.recsensorial_id = ' . $recsensorial_id . '
                                            GROUP BY
                                                    recsensorialquimicosinventario.recsensorial_id,
                                                    recsensorialquimicosinventario.id,
                                                    recsensorialquimicosinventario.recsensorialarea_id,
                                                    recsensorialarea.recsensorialarea_nombre,
                                                    recsensorialquimicosinventario.recsensorialcategoria_id,
                                                    recsensorialcategoria.recsensorialcategoria_nombrecategoria,
                                                    ##recsensorialcategoria.recsensorialcategoria_funcioncategoria,
                                                    recsensorialquimicosinventario.catsustancia_id,
                                                    catsustancia.catsustancia_nombre,
                                                    recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo,
                                                    recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo,
                                                    catestadofisicosustancia.catestadofisicosustancia_estado,
                                                    recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad,
                                                    catunidadmedidasustacia.catunidadmedidasustacia_abreviacion,
                                                    catvolatilidad.catvolatilidad_tipo,
                                                    catviaingresoorganismo.catviaingresoorganismo_viaingreso,
                                                    catsustancia.catcategoriapeligrosalud_id,
                                                    catgradoriesgosalud.catgradoriesgosalud_clasificacion,
                                                    catsustancia.catgradoriesgosalud_id,
                                                    catcategoriapeligrosalud.catcategoriapeligrosalud_codigo
                                            ORDER BY
                                                    recsensorialarea.recsensorialarea_nombre ASC,
                                                    recsensorialcategoria.recsensorialcategoria_nombrecategoria ASC,
                                                    catsustancia.catsustancia_nombre ASC');


            // Formatear filas
            $numero_registro = 0;
            foreach ($inventariosustancias as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                // Datos
                $value->categoria_nombre = $value->recsensorialcategoria_nombrecategoria;
                // $value->categoria_nombre = $listacategorias[0]->lista;
                $value->componentes = $value->sustancia_componentes;
                $value->sustancia_cantidad = $value->recsensorialquimicosinventario_cantidad . ' ' . $value->catunidadmedidasustacia_abreviacion;
                $value->categ_peligro = '<b>[' . $value->catcategoriapeligrosalud_id . ']</b> ' . substr($value->catcategoriapeligrosalud_codigo, 0, 12) . '...';
                $value->categ_riesgo = '<b>[' . $value->catgradoriesgosalud_id . ']</b> ' . substr($value->catgradoriesgosalud_clasificacion, 0, 12) . '...';

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'Operativo HI']) && ($recsensorial->recsensorial_bloqueado + 0) == 0 && ($recsensorial->recsensorial_quimicosimprimirbloqueado + 0) == 0) {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" id="editaarea_' . $value->recsensorialarea_id . '" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                    $value->boton_eliminar = '<button type="button" id="eliminaarea_' . $value->recsensorialarea_id . '" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                } else {
                    $value->accion_activa = 0;
                    $value->boton_editar = '<button type="button" id="editaarea_' . $value->recsensorialarea_id . '" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                    $value->boton_eliminar = '<button type="button" id="eliminaarea_' . $value->recsensorialarea_id . '" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                }
            }


            // ---------------------------- 


            $areasquimicos = DB::select('SELECT
                                            recsensorialquimicosinventario.recsensorial_id,
                                            recsensorialquimicosinventario.recsensorialarea_id,
                                            recsensorialarea.recsensorialarea_nombre
                                        FROM
                                            recsensorialquimicosinventario
                                            INNER JOIN recsensorialarea ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialarea.id
                                        WHERE
                                            recsensorialquimicosinventario.recsensorial_id = ' . $recsensorial_id . ' 
                                        GROUP BY
                                            recsensorialquimicosinventario.recsensorial_id,
                                            recsensorialquimicosinventario.recsensorialarea_id,
                                            recsensorialarea.recsensorialarea_nombre
                                        ORDER BY
                                            recsensorialarea.recsensorialarea_nombre ASC');


            $dato['optionselect_areasquimicos'] = '<option value=""></option>';
            foreach ($areasquimicos as $key => $value) {
                $dato['optionselect_areasquimicos'] .= '<option value="' . $value->recsensorialarea_id . '">' . $value->recsensorialarea_nombre . '</option>';
            }



            // ----------------------------


            // respuesta
            $dato['data'] = $inventariosustancias;
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['data'] = 0;
            return response()->json($dato);
        }
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorialarea_id
     * @param  int  $recsensorialcategoria_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialquimicoscatsustancias($recsensorialarea_id, $recsensorialcategoria_id)
    {
        try {
            // Catalogo unidad de medida
            $umedida_opciones = '<option value="">&nbsp;</option>';
            $unidadmedida = catunidadmedidasustaciaModel::where('catunidadmedidasustacia_activo', 1)->get();
            foreach ($unidadmedida as $key => $value) {
                $umedida_opciones .= '<option value="' . $value->id . '">' . $value->catunidadmedidasustacia_descripcion . '</option>';
            }

            // Catalogo de sustancias quimicas
            $catsustancias = DB::select('SELECT
                                            catsustancia.id,
                                            catsustancia.catsustancia_nombre,
                                            catestadofisicosustancia.catestadofisicosustancia_estado,
                                            catsustancia.catsustancia_activo
                                        FROM
                                            catsustancia
                                            LEFT JOIN catestadofisicosustancia ON catsustancia.catestadofisicosustancia_id = catestadofisicosustancia.id 
                                        WHERE
                                            catsustancia.catsustancia_activo = 1
                                        ORDER BY
                                            catsustancia.catsustancia_nombre ASC');

            $opciones = '<option value="">Buscar</option>';
            foreach ($catsustancias as $key => $value) {
                $opciones .= '<option value="' . $value->id . '">' . $value->catsustancia_nombre . ' [' . $value->catestadofisicosustancia_estado . ']</option>';
            }

            // respuesta
            // $dato['catsustancias_filas'] = $filas;
            $dato['catsustancia_opciones'] = $opciones;
            $dato['umedida_opciones'] = $umedida_opciones;
            // $dato['data'] = $catsustancias;
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['catsustancias'] = 0;
            return response()->json($dato);
        }
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorialarea_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialselectcategoriasxareaquimicos($recsensorialarea_id)
    {
        try {
            // Catalogo de sustancias quimicas
            $catsustancias = DB::select('SELECT
                                                catsustancia.id,
                                                catsustancia.catsustancia_nombre,
                                                catestadofisicosustancia.catestadofisicosustancia_estado,
                                                (
                                                    SELECT
                                                        REPLACE(GROUP_CONCAT(catsustanciacomponente.catsustanciacomponente_nombre), ",", ", ") AS componente
                                                    FROM
                                                        catsustanciacomponente
                                                    WHERE
                                                        catsustanciacomponente.catsustancia_id = catsustancia.id
                                                ) AS componentes
                                            FROM
                                                catsustancia
                                                LEFT JOIN catestadofisicosustancia ON catsustancia.catestadofisicosustancia_id = catestadofisicosustancia.id
                                            WHERE
                                                catsustancia.catsustancia_activo = 1
                                            ORDER BY
                                                catsustancia.catsustancia_nombre ASC');


            // Categorias del area
            $categorias = DB::select('SELECT
                                            recsensorialareacategorias.recsensorialarea_id,
                                            recsensorialareacategorias.recsensorialcategoria_id,
                                            recsensorialareacategorias.recsensorialareacategorias_geh,
                                            recsensorialcategoria.recsensorialcategoria_nombrecategoria
                                            ##recsensorialcategoria.recsensorialcategoria_funcioncategoria
                                        FROM
                                            recsensorialareacategorias
                                            LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                        WHERE
                                            recsensorialareacategorias.recsensorialarea_id = ' . $recsensorialarea_id . ' 
                                            AND recsensorialcategoria.recsensorialcategoria_nombrecategoria != ""
                                        ORDER BY
                                            recsensorialareacategorias.recsensorialareacategorias_geh ASC,
                                            recsensorialcategoria.recsensorialcategoria_nombrecategoria ASC');

            $categorias_opciones = array();
            foreach ($categorias as $key => $value) {
                $categorias_opciones[] = array(
                    'categoria_id' => $value->recsensorialcategoria_id,
                    'categoria_nombre' => $value->recsensorialareacategorias_geh . '.- ' . $value->recsensorialcategoria_nombrecategoria
                );
            }

            // Inventario sustancias en el area
            $area_listasustancias = '';
            $sustancias = DB::select('SELECT
                                            MAX(recsensorialquimicosinventario.id) AS id,
                                            recsensorialquimicosinventario.recsensorial_id,
                                            recsensorialquimicosinventario.recsensorialarea_id,
                                            recsensorialquimicosinventario.catsustancia_id,
                                            recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad,
                                            recsensorialquimicosinventario.catunidadmedidasustacia_id
                                        FROM
                                            recsensorialquimicosinventario
                                        WHERE
                                            recsensorialquimicosinventario.recsensorialarea_id = ' . $recsensorialarea_id . '
                                        GROUP BY
                                            recsensorialquimicosinventario.recsensorial_id,
                                            recsensorialquimicosinventario.recsensorialarea_id,
                                            recsensorialquimicosinventario.catsustancia_id,
                                            recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad,
                                            recsensorialquimicosinventario.catunidadmedidasustacia_id
                                        ORDER BY
                                            id ASC');

            //SI YA EXISTEN SUSTANCIAS GUARDADAS EN EL AREA
            if (count($sustancias) > 0) {

                foreach ($sustancias as $key => $value) {

                    // Categorias del area
                    $categorias = DB::select('SELECT
                                                    recsensorialareacategorias.recsensorialarea_id,
                                                    recsensorialareacategorias.recsensorialcategoria_id,
                                                    recsensorialareacategorias.recsensorialareacategorias_geh,
                                                    recsensorialcategoria.recsensorialcategoria_nombrecategoria,
                                                    ##recsensorialcategoria.recsensorialcategoria_funcioncategoria,
                                                    IFNULL((
                                                        SELECT
                                                            IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, "")
                                                        FROM
                                                            recsensorialquimicosinventario
                                                        WHERE
                                                            recsensorialquimicosinventario.recsensorialarea_id = recsensorialareacategorias.recsensorialarea_id
                                                            AND recsensorialquimicosinventario.recsensorialcategoria_id = recsensorialareacategorias.recsensorialcategoria_id
                                                            AND recsensorialquimicosinventario.catsustancia_id = ' . $value->catsustancia_id . '
                                                            AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad = ' . $value->recsensorialquimicosinventario_cantidad . '
                                                            AND recsensorialquimicosinventario.catunidadmedidasustacia_id = ' . $value->catunidadmedidasustacia_id . '
                                                        LIMIT 1
                                                    ), "") AS tiempoexpo,
                                                    IFNULL((
                                                        SELECT
                                                            IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, "")
                                                        FROM
                                                            recsensorialquimicosinventario
                                                        WHERE
                                                            recsensorialquimicosinventario.recsensorialarea_id = recsensorialareacategorias.recsensorialarea_id
                                                            AND recsensorialquimicosinventario.recsensorialcategoria_id = recsensorialareacategorias.recsensorialcategoria_id
                                                            AND recsensorialquimicosinventario.catsustancia_id = ' . $value->catsustancia_id . '
                                                            AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad = ' . $value->recsensorialquimicosinventario_cantidad . '
                                                            AND recsensorialquimicosinventario.catunidadmedidasustacia_id = ' . $value->catunidadmedidasustacia_id . '
                                                        LIMIT 1
                                                    ), "") AS frecuenciaexpo,
                                                    IFNULL((
                                                        SELECT
                                                            IF(recsensorialquimicosinventario.recsensorialcategoria_id, "checked", "")
                                                        FROM
                                                            recsensorialquimicosinventario
                                                        WHERE
                                                            recsensorialquimicosinventario.recsensorialarea_id = recsensorialareacategorias.recsensorialarea_id
                                                            AND recsensorialquimicosinventario.recsensorialcategoria_id = recsensorialareacategorias.recsensorialcategoria_id
                                                            AND recsensorialquimicosinventario.catsustancia_id = ' . $value->catsustancia_id . '
                                                            AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad = ' . $value->recsensorialquimicosinventario_cantidad . '
                                                            AND recsensorialquimicosinventario.catunidadmedidasustacia_id = ' . $value->catunidadmedidasustacia_id . '
                                                        LIMIT 1
                                                    ), "") AS checked
                                            FROM
                                                recsensorialareacategorias
                                                LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                            WHERE
                                                recsensorialareacategorias.recsensorialarea_id = ' . $recsensorialarea_id . '
                                                AND recsensorialcategoria.recsensorialcategoria_nombrecategoria != ""
                                            ORDER BY
                                                recsensorialareacategorias.recsensorialareacategorias_geh ASC,
                                                recsensorialcategoria.recsensorialcategoria_nombrecategoria ASC');


                    $sustancia_listacategorias = '';
                    $disabled_required = '';

                    foreach ($categorias as $key_categorias => $value_categorias) {
                        if ($value_categorias->checked) {
                            $disabled_required = 'required';
                        } else {
                            $disabled_required = 'disabled';
                        }

                        $sustancia_listacategorias .= '<tr>
                                                            <td style="width: 680px!important;">
                                                                <div class="form-group">
                                                                    <div class="switch" style="float: left;">
                                                                        <label>
                                                                            <input type="checkbox" name="categoria[]" value="' . $key . '~' . $value_categorias->recsensorialcategoria_id . '" onchange="activa_categoria(this, ' . ($key + 1) . $key_categorias . ');" ' . $value_categorias->checked . '>
                                                                            <span class="lever switch-col-light-blue"></span>
                                                                        </label>
                                                                    </div>
                                                                    <label class="demo-switch-title" style="float: left;">' . $value_categorias->recsensorialareacategorias_geh . '.- ' . $value_categorias->recsensorialcategoria_nombrecategoria . '</label>
                                                                </div>
                                                            </td>
                                                            <td style="width: 180px!important;">
                                                            <label><b>Exp. minutos</b></label>
                                                                <input type="number" step="any" class="form-control text-center" placeholder="Exp. minutos" id="tiempo_' . ($key + 1) . $key_categorias . '" name="tiempo[]" value="' . $value_categorias->tiempoexpo . '" ' . $disabled_required . '>
                                                            </td>
                                                            <td style="width: 180px!important;">
                                                            <label><b>Frecuencia exp.</b></label>
                                                                <input type="number" step="any" class="form-control text-center" placeholder="Frecuencia exp." id="frecuencia_' . ($key + 1) . $key_categorias . '" value="' . $value_categorias->frecuenciaexpo . '" name="frecuencia[]" ' . $disabled_required . '>
                                                            </td>
                                                        </tr>';
                    }


                    // Catalogo unidad de medida
                    $unidadmedida = catunidadmedidasustaciaModel::where('catunidadmedidasustacia_activo', 1)->get();
                    $umedida_opciones = '<option value="">&nbsp;</option>';


                    foreach ($unidadmedida as $key_unidadmedida => $value_unidadmedida) {
                        if ($value->catunidadmedidasustacia_id != $value_unidadmedida->id) {
                            $umedida_opciones .= '<option value="' . $value_unidadmedida->id . '">' . $value_unidadmedida->catunidadmedidasustacia_descripcion . '</option>';
                        } else {
                            $umedida_opciones .= '<option value="' . $value_unidadmedida->id . '" selected>' . $value_unidadmedida->catunidadmedidasustacia_descripcion . '</option>';
                        }
                    }


                    $catsustancia_opciones = '<option value="">Buscar</option>';
                    foreach ($catsustancias as $key_catsustancias => $value_catsustancias) {
                        if ($value->catsustancia_id != $value_catsustancias->id) {
                            $catsustancia_opciones .= '<option value="' . $value_catsustancias->id . '">' . $value_catsustancias->catsustancia_nombre . ' [' . $value_catsustancias->catestadofisicosustancia_estado . ']</option>';
                        } else {
                            $catsustancia_opciones .= '<option value="' . $value_catsustancias->id . '" selected>' . $value_catsustancias->catsustancia_nombre . ' [' . $value_catsustancias->catestadofisicosustancia_estado . ']</option>';
                        }
                    }


                    // Formatear filas
                    $area_listasustancias .= '<tr>
                                                <td style="width: 50px!important;">
                                                    ' . ($key + 1) . '
                                                </td>
                                                <td style="width: 1040px!important;">
                                                    <table>
                                                        <tr>
                                                            <td style="width: 680px!important;">
                                                            <label><b>Nombre</b></label>
                                                                <select class="custom-select form-control select_search_sustancia" id="selectsearch_sustancia_' . $key . '" name="sustancia_catalogo[]" required>
                                                                    ' . $catsustancia_opciones . '
                                                                </select>
                                                            </td>
                                                            <td style="width: 180px!important;">
                                                            <label><b>Cantidad manejada</b></label>
                                                                <input type="number" step="any" class="form-control text-center" placeholder="Cantidad" name="cantidad[]" value="' . $value->recsensorialquimicosinventario_cantidad . '" required>
                                                            </td>
                                                            <td style="width: 180px!important;">
                                                            <label><b>Unidad medida</b></label>
                                                                <select class="custom-select form-control" name="umedida[]" required>
                                                                    ' . $umedida_opciones . '
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        ' . $sustancia_listacategorias . '
                                                    </table>
                                                </td>
                                                <td style="padding-left: 12px;" class="eliminar">
                                                    <button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>';
                }

                $area_listasustancias .= '<tr><td colspan="3" style="width: 1143px; height: 160px;">&nbsp;</td></tr>';
            } else { // SI NO EXISTEN LAS SIMULAMOS PARA QUE APARESCAN

                //BUSCAMOS TODAS AQUELLAS FUENTES GENERADORAS VINCULADAS CON EL AREA SELECCIONADA Y QUE ADEMAS SEAN FUENTES PARA QUIMICO O FISICO Y QUIMICO
                $fuentes = DB::select('SELECT recsensorialmaquinaria_quimica as ID_SUSTANCIA,
                                            recsensorialarea_id as AREA_ID,
                                            (recsensorialmaquinaria_cantidad * recsensorialmaquinaria_contenido) AS CANTIDAD,
                                            recsensorialmaquinaria_unidadMedida AS UNIDAD_MEDIDA
                                        FROM recsensorialmaquinaria 
                                        WHERE recsensorialmaquinaria_afecta <> 1 AND recsensorialarea_id = ?', [$recsensorialarea_id]);



                foreach ($fuentes as $key => $value) {


                    //CREAMOS LAS CATEGORIAS QUE ESTAN RELACIONADAS CON EL AREA SELECCIONADA
                    $categorias = DB::select('SELECT cat.recsensorialcategoria_nombrecategoria AS NOMBRE_CAT,
                                                    cat.id AS ID_CATEGORIA,
                                                    relacion.tiempoexpo_quimico,
                                                    relacion.frecuenciaexpo_quimico,
                                                    relacion.recsensorialareacategorias_geh
                                                FROM recsensorialareacategorias as relacion
                                                LEFT JOIN recsensorialcategoria as cat ON cat.id = relacion.recsensorialcategoria_id
                                                WHERE relacion.recsensorialarea_id = ?', [$recsensorialarea_id]);

                    $sustancia_listacategorias = '';
                    $disabled_required = 'disabled';

                    foreach ($categorias as $key_categorias => $value_categorias) {

                        $sustancia_listacategorias .= '<tr>
                                                            <td style="width: 680px!important;">
                                                                <div class="form-group">
                                                                    <div class="switch" style="float: left;">
                                                                        <label>
                                                                            <input type="checkbox" name="categoria[]" value="' . $key . '~' . $value_categorias->ID_CATEGORIA . '" onchange="activa_categoria(this, ' . ($key + 1) . $key_categorias . ');" >
                                                                            <span class="lever switch-col-light-blue"></span>
                                                                        </label>
                                                                    </div>
                                                                    <label class="demo-switch-title" style="float: left;">' . $value_categorias->recsensorialareacategorias_geh . '.- ' . $value_categorias->NOMBRE_CAT . '</label>
                                                                </div>
                                                            </td>
                                                            <td style="width: 180px!important;">
                                                            <label><b>Exp. minutos</b></label>
                                                                <input type="number" step="any" class="form-control text-center" placeholder="Exp. minutos" id="tiempo_' . ($key + 1) . $key_categorias . '" name="tiempo[]" value="' . $value_categorias->tiempoexpo_quimico . '" ' . $disabled_required . '>
                                                            </td>
                                                            <td style="width: 180px!important;">
                                                            <label><b>Frecuencia exp.</b></label>
                                                                <input type="number" step="any" class="form-control text-center" placeholder="Frecuencia exp." id="frecuencia_' . ($key + 1) . $key_categorias . '" value="' . $value_categorias->frecuenciaexpo_quimico . '" name="frecuencia[]" ' . $disabled_required . '>
                                                            </td>
                                                        </tr>';
                    }

                    // Creamo el select en base con catalogo unidad de medida
                    $unidadmedida = catunidadmedidasustaciaModel::where('catunidadmedidasustacia_activo', 1)->get();
                    $umedida_opciones = '<option value="">&nbsp;</option>';

                    foreach ($unidadmedida as $key_unidadmedida => $value_unidadmedida) {


                        if ($value->UNIDAD_MEDIDA != $value_unidadmedida->id) {
                            $umedida_opciones .= '<option value="' . $value_unidadmedida->id . '">' . $value_unidadmedida->catunidadmedidasustacia_descripcion . '</option>';
                        } else {

                            $umedida_opciones .= '<option value="' . $value_unidadmedida->id . '" selected>' . $value_unidadmedida->catunidadmedidasustacia_descripcion . '</option>';
                        }
                    }

                    //Creamos los select de los productos
                    $catsustancia_opciones = '<option value="">Buscar</option>';
                    foreach ($catsustancias as $key_catsustancias => $value_catsustancias) {

                        if ($value->ID_SUSTANCIA != $value_catsustancias->id) {
                            $catsustancia_opciones .= '<option value="' . $value_catsustancias->id . '">' . $value_catsustancias->catsustancia_nombre . ' [' . $value_catsustancias->catestadofisicosustancia_estado . ']</option>';
                        } else {
                            $catsustancia_opciones .= '<option value="' . $value_catsustancias->id . '" selected>' . $value_catsustancias->catsustancia_nombre . ' [' . $value_catsustancias->catestadofisicosustancia_estado . ']</option>';
                        }
                    }


                    // Formatear filas y crear esqueleto
                    $area_listasustancias .= '<tr>
                                                <td style="width: 50px!important;">
                                                    ' . ($key + 1) . '
                                                </td>
                                                <td style="width: 1040px!important;">
                                                    <table>
                                                        <tr>
                                                            <td style="width: 680px!important;">
                                                            <label><b>Nombre</b></label>
                                                                <select class="custom-select form-control select_search_sustancia" id="selectsearch_sustancia_' . $key . '" name="sustancia_catalogo[]" required>
                                                                    ' . $catsustancia_opciones . '
                                                                </select>
                                                            </td>
                                                            <td style="width: 180px!important;">
                                                            <label><b>Cantidad manejada</b></label>
                                                                <input type="number" step="any" class="form-control text-center" placeholder="Cantidad" name="cantidad[]" value="' . $value->CANTIDAD . '" required readonly>
                                                            </td>
                                                            <td style="width: 180px!important;">
                                                            <label><b>Unidad medida</b></label>
                                                                <select class="custom-select form-control" name="umedida[]" required readonly>
                                                                    ' . $umedida_opciones . '
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        ' . $sustancia_listacategorias . '
                                                    </table>
                                                </td>
                                                <td style="padding-left: 12px;" class="eliminar">
                                                    <button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>';
                }


                $area_listasustancias .= '<tr><td colspan="3" style="width: 1143px; height: 160px;">&nbsp;</td></tr>';
            }



            // respuesta
            $dato['area_listasustancias'] = $area_listasustancias;
            $dato['area_listasustancias_total'] = count($sustancias);
            $dato['categorias'] = $categorias_opciones;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['opciones'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }





    public function obtenerCategoriasReconomiento($recsensorial_id, $clasificacion, $sustancia)
    { //Antes se llamaba obtenerCategoriasEvaluadas pero se cambio todo


        function numberToRoman($num)
        {
            $n = intval($num);
            $result = '';

            $lookup = [
                'M' => 1000,
                'CM' => 900,
                'D' => 500,
                'CD' => 400,
                'C' => 100,
                'XC' => 90,
                'L' => 50,
                'XL' => 40,
                'X' => 10,
                'IX' => 9,
                'V' => 5,
                'IV' => 4,
                'I' => 1,
            ];

            foreach ($lookup as $roman => $value) {
                $matches = intval($n / $value);
                $result .= str_repeat($roman, $matches);
                $n = $n % $value;
            }

            return $result;
        }


        function numberToLetter($num)
        {
            $letters = range('A', 'Z');
            return $letters[$num % 26];
        }


        try {

            //VALIDAMOS SI ES EL PRIMER GEH QUE INGRESA
            $geh = DB::select('SELECT COUNT(*) TOTAL, IFNULL(OPCION, 0) AS OPCION
                                FROM grupos_de_exposicion 
                                WHERE RECSENSORIAL_ID = ?
                                GROUP BY OPCION', [$recsensorial_id]);

            //SELECCIONAMOS EL CONSECUTIVO DE LA CLASIFICACION DE LOS GEH
            $count = DB::select('SELECT COUNT(DISTINCT CLASIFICACION) AS num_clasificaciones,  IFNULL(OPCION, 0) AS OPCION
                                        FROM grupos_de_exposicion
                                        WHERE RECSENSORIAL_ID = ?
                                        GROUP BY OPCION', [$recsensorial_id]);

            if (!empty($count)) {

                $result = $count[0];
                $num_clasificaciones = $result->num_clasificaciones;
                $opcion = $result->OPCION;

                if ($opcion == 1) {
                    // Convierte a número romano
                    $output = numberToRoman($num_clasificaciones + 1); // Sumar 1 para obtener el siguiente número romano
                } elseif ($opcion == 2) {
                    // Convierte a letra del abecedario
                    $output = numberToLetter($num_clasificaciones); // Sumar 1 para obtener la siguiente letra del abecedario
                } else {
                    $output = "Opción no válida";
                }

                $consecutivo = $output;
            } else {

                $consecutivo = 'I';
            }


            //MANDAMOS LAS CATEGORIAS
            $categorias = DB::select('SELECT
                                        relacion.id AS ID_RELACION,  
                                        area.id AS AREA_ID,
                                        area.recsensorialarea_nombre AS AREA,
                                        cat.id AS CATEGORIA_ID,
                                        cat.recsensorialcategoria_nombrecategoria AS CATEGORIA,
                                        relacion.recsensorialareacategorias_total AS PERSONAL,
                                        CASE 
                                            WHEN EXISTS (
                                                SELECT 1
                                                FROM grupos_de_exposicion 
                                                WHERE grupos_de_exposicion.RECSENSORIAL_ID = ?
                                                AND grupos_de_exposicion.CLASIFICACION = ?
                                                AND grupos_de_exposicion.RELACION_AREA_CAT_ID = relacion.id
                                            ) 
                                            THEN 1 
                                            ELSE 0 
                                        END AS SELECCIONADO,
                                        IFNULL(grupos.POE,"") POE
                                    FROM recsensorialareacategorias AS relacion
                                    LEFT JOIN recsensorialarea AS area ON area.id = relacion.recsensorialarea_id
                                    LEFT JOIN recsensorialcategoria AS cat ON cat.id = relacion.recsensorialcategoria_id
                                    LEFT JOIN grupos_de_exposicion grupos ON grupos.RELACION_AREA_CAT_ID = relacion.id
                                    WHERE area.recsensorial_id = ?
                                    GROUP BY relacion.id, 
                                            area.id,
                                            area.recsensorialarea_nombre,
                                            cat.id,
                                            cat.recsensorialcategoria_nombrecategoria,
                                            relacion.recsensorialareacategorias_total,
                                            SELECCIONADO, POE', [$recsensorial_id, $clasificacion, $recsensorial_id]);


            //RECUPERAMOS TODAS AQUELLAS SUSTANCIAS QUE EN SU  PONDERACION SEAN MAYORES O IGUALES A 8 (Moderada)
            $sustancias = DB::select('CALL sp_obtenerSustancias_GEH_b(?,?)', [$recsensorial_id, $sustancia]);

            $opciones = '';
            foreach ($sustancias as $key => $value) {
                if ($value->ID_HOJA_SUSTANCIA == $sustancia) {
                    $opciones .= '<option value="' . $value->ID_HOJA_SUSTANCIA . '"  data-componente = ' . $value->ID_SUSTANCIA_QUIMICA . ' data-producto = ' . $value->ID_HOJA . ' selected>' . $value->COMPONENTE . '[ ' . $value->PRODUCTO . ' ] '  . '</option>';
                } else {
                    $opciones .= '<option value="' . $value->ID_HOJA_SUSTANCIA . '"  data-componente = ' . $value->ID_SUSTANCIA_QUIMICA . ' data-producto = ' . $value->ID_HOJA . ' >' . $value->COMPONENTE . '[ ' . $value->PRODUCTO . ' ] '  . '</option>';
                }
            }



            // respuesta
            $dato['componentes'] = $opciones;
            $dato['categorias'] = $categorias;
            $dato['GEH'] = $geh;
            $dato['CONSECUTIVO'] = $consecutivo;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {

            $dato['data'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @param  int  $numero_tabla
     * @return \Illuminate\Http\Response
     */
    public function recsensorialquimicosresumen($recsensorial_id, $numero_tabla)
    {
        try {
            switch ($numero_tabla) {
                case 1:

                    $tabla = DB::select("CALL sp_ponderacion1_tabla8_1_b(?)", [$recsensorial_id]);


                    $numero_registro = 0;
                    foreach ($tabla as $key => $value) {
                        $value->numero_registro = $numero_registro += 1;

                        switch (($value->SUMA_PONDERACIONES)) {

                            case ($value->SUMA_PONDERACIONES) >= 10:
                                $value->PRIORIDAD = '<p class="text-danger"><b>' . $value->PRIORIDAD . '</b></p>';
                                break;
                            case ($value->SUMA_PONDERACIONES) >= 8:
                                $value->PRIORIDAD = '<p class="text-warning"><b>' . $value->PRIORIDAD . '</b></p>';
                                break;
                            default:
                                $value->PRIORIDAD = '<p class="text-success"><b>' . $value->PRIORIDAD . '</b></p>';
                                break;
                        }

                        $value->SUMA_PONDERACIONES = '<b style="color:#000000;">' . $value->SUMA_PONDERACIONES . '</b>';
                    }
                    break;

                case 2:

                    $tabla = DB::select('CALL sp_obtenerDeterminacionGEH_b(?) ', [$recsensorial_id]);


                    $numero_registro = 0;
                    foreach ($tabla as $key => $value) {

                        $value->numero_registro = $numero_registro += 1;

                        switch (($value->SUMA_PONDERACIONES)) {
                            case ($value->SUMA_PONDERACIONES) >= 9:
                                $value->PRIORIDAD = '<p class="text-danger"><b>' . $value->PRIORIDAD . '</b></p>';
                                break;
                            case ($value->SUMA_PONDERACIONES) >= 4:
                                $value->PRIORIDAD = '<p class="text-warning"><b>' . $value->PRIORIDAD . '</b></p>';
                                break;
                            default:
                                $value->PRIORIDAD = '<p class="text-success"><b>' . $value->PRIORIDAD . '</b></p>';
                                break;
                        }

                        $value->TOTAL = '<b style="color:#000000;">' . $value->SUMA_PONDERACIONES . '</b>';
                    }
                    break;

                case 3:

                    $tabla = DB::select('CALL sp_obtenerGEH_b(?) ', [$recsensorial_id]);

                    $numero_registro = 0;
                    foreach ($tabla as $key => $value) {

                        $value->numero_registro = $numero_registro += 1;
                        $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle editar" id="editargeh_' . $value->CLASIFICACION . '"><i class="fa fa-pencil"></i></button>';
                    }

                    break;
                case 4:

                    $tabla = DB::select('CALL sp_puntos_de_muestreoPOE_informe_b(?)', [$recsensorial_id]);


                    $numero_registro = 0;
                    foreach ($tabla as $key => $value) {
                        $value->numero_registro = $numero_registro += 1;
                        $value->TOTAL_MUESTREOS = '<b style="color:#000000;">' . $value->TOTAL_MUESTREOS . '</b>';
                    }
                    break;
                case 5: //OBTENCIOS DE SUSTANCIAS QUIMICAS PARA LA EVALUACION DE BEIs

                    $tablaSQL = DB::select('CALL sp_obtener_sustancia_evaluar_bei_b(?)', [$recsensorial_id]);


                    $tabla = '<option value=""></option>';
                    foreach ($tablaSQL as $value) {
                        $tabla .= '<option value="' . $value->ID_SUSTANCIA_QUIMICA . '">' . $value->SUSTANCIA_PRODUCTO . '</option>';
                    }
                    break;
                case 6: //TABLA DE EVALUACION DE BEIS

                    $tabla = DB::select('CALL sp_obtener_tabla_evaluacion_bei(?)', [$recsensorial_id]);


                    $numero_registro = 0;
                    foreach ($tabla as $key => $value) {

                        $value->numero_registro = $numero_registro += 1;
                        $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle editar" id="editargeh_' . $value->ID_RECSENSORIAL_BEI . '"><i class="fa fa-pencil"></i></button>';
                    }
                    break;
                default:
                    // Sin información;
                    break;
            }

            // respuesta
            $dato['data'] = $tabla;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
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

            if ($request['api'] == 1) {  //GUARDAMOS EL INVENTARIO DE SUSTANCIAS QUIMICAS

                // Eliminar inventario en el area
                $sustancia = recsensorialquimicosinventarioModel::where('recsensorial_id', $request['recsensorial_id'])
                    ->where('recsensorialarea_id', $request['recsensorialarea_id'])
                    ->delete();

                // AUTO_INCREMENT
                DB::statement('ALTER TABLE recsensorialquimicosinventario AUTO_INCREMENT=1');
                foreach ($request->sustancia_catalogo as $key1 => $value_sustancia) {
                    foreach ($request->categoria as $key => $value_categoria) {
                        $valor = explode("~", $value_categoria);

                        if (($valor[0] + 0) == ($key1 + 0)) //Index sustancia
                        {
                            $sustancia = recsensorialquimicosinventarioModel::create([
                                'recsensorial_id' => $request->recsensorial_id,
                                'recsensorialarea_id' => $request->recsensorialarea_id,
                                'catsustancia_id' => $value_sustancia,
                                'recsensorialquimicosinventario_cantidad' => $request->cantidad[$key1],
                                'catunidadmedidasustacia_id' => $request->umedida[$key1],
                                'recsensorialcategoria_id' => $valor[1],
                                'recsensorialcategoria_tiempoexpo' => $request->tiempo[$key],
                                'recsensorialcategoria_frecuenciaexpo' => $request->frecuencia[$key]
                            ]);
                        }
                    }
                }

                // mensaje
                $dato["msj"] = 'Información modificada correctamente';
                $dato['recsensorial_id'] = $request['recsensorial_id'];

            } else  if ($request['api'] == 2){ //GUARDAMOS LOS GRUPOS DE EXPOSICION HOMOGENEA

                // Verfificamos que el tipo de clasificacion llegue si no lo obtenemos de la base de datos
                if (isset($request['TIPO_CLASIFICACION'])) {
                    $opcion = $request['TIPO_CLASIFICACION'];
                } else {
                    $registro  =  gruposDeExposicionModel::where('RECSENSORIAL_ID', $request['RECSENSORIAL_ID'])->first();
                    $opcion =  $registro->OPCION;
                }

                if ($request['NUEVO'] == 1) { //Creamos nuevos registros

                    foreach ($request->ID_RELACION as $index => $val) {
                        gruposDeExposicionModel::create([
                            'RECSENSORIAL_ID' => $request['RECSENSORIAL_ID'],
                            'OPCION' => $opcion,
                            'CLASIFICACION' => $request['CLASIFICACION_GRUPO'],
                            'RELACION_HOJA_SUS_ID' => $request['COMPONENTE_ID'],
                            'RELACION_AREA_CAT_ID' => $val,
                            'AREA_ID' => $request->ID_AREA_GRUPO[$index],
                            'CATEGORIA_ID' => $request->ID_CATEGORIA_GRUPO[$index],
                            'POE' => $request->NUM_TRABAJADORES_NUEVO[$index],
                        ]);
                    }

                    // mensaje
                    $dato["msj"] = 'Grupo creado correctamente';
                    $dato['recsensorial_id'] = $request['RECSENSORIAL_ID'];
                } else {

                    //Eliminar registros que esten relacionados con el mismo grupo y el mismo reconocimiento.
                    gruposDeExposicionModel::where('CLASIFICACION', $request['CLASIFICACION_GRUPO'])->where('RECSENSORIAL_ID', $request['RECSENSORIAL_ID'])->delete();

                    foreach ($request->ID_RELACION as $index => $val) {
                        gruposDeExposicionModel::create([
                            'RECSENSORIAL_ID' => $request['RECSENSORIAL_ID'],
                            'OPCION' => $opcion,
                            'CLASIFICACION' => $request['CLASIFICACION_GRUPO'],
                            'RELACION_HOJA_SUS_ID' => $request['COMPONENTE_ID'],
                            'RELACION_AREA_CAT_ID' => $val,
                            'AREA_ID' => $request->ID_AREA_GRUPO[$index],
                            'CATEGORIA_ID' => $request->ID_CATEGORIA_GRUPO[$index],
                            'POE' => $request->NUM_TRABAJADORES_NUEVO[$index],
                        ]);
                    }

                    // mensaje
                    $dato["msj"] = 'Grupo editado correctamente';
                    $dato['recsensorial_id'] = $request['RECSENSORIAL_ID'];
                }
            
            } else if($request['api'] == 3){ //Guardamos la evaluacion de los BEIS
                
                if ($request['ID_RECSENSORIAL_BEI'] == 0) {

                    $bei = evaluacionBeisModel::create($request->all());
                    $dato["msj"] = 'Guardado correctamente';
                    $dato["info"] = $bei;

                } else {
                    $bei = evaluacionBeisModel::findOrFail($request['ID_RECSENSORIAL_BEI']);
                    $bei->update($request->all());

                    $dato["msj"] = 'Editado correctamente';
                    $dato["info"] = $bei;
                }

            }

            // respuesta
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['sustancia'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @param  int  $recsensorialarea_id
     * @param  int  $recsensorialcategoria_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialquimicosinventarioeliminar($recsensorial_id, $recsensorialarea_id, $recsensorialcategoria_id)
    {
        try {
            $sustancia = recsensorialquimicosinventarioModel::where('recsensorial_id', $recsensorial_id)
                ->where('recsensorialarea_id', $recsensorialarea_id)
                // ->where('recsensorialcategoria_id', $recsensorialcategoria_id)
                ->delete();

            // respuesta
            $dato["msj"] = 'Información eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }
}

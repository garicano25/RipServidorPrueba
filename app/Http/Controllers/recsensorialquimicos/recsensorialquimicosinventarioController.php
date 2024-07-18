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
                                                            <input type="number" step="any" class="form-control" placeholder="Exp. minutos" id="tiempo_' . ($key + 1) . $key_categorias . '" name="tiempo[]" value="' . $value_categorias->tiempoexpo . '" ' . $disabled_required . '>
                                                        </td>
                                                        <td style="width: 180px!important;">
                                                            <input type="number" step="any" class="form-control" placeholder="Frecuencia exp." id="frecuencia_' . ($key + 1) . $key_categorias . '" value="' . $value_categorias->frecuenciaexpo . '" name="frecuencia[]" ' . $disabled_required . '>
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
                                                            <select class="custom-select form-control select_search_sustancia" id="selectsearch_sustancia_' . $key . '" name="sustancia_catalogo[]" required>
                                                                ' . $catsustancia_opciones . '
                                                            </select>
                                                        </td>
                                                        <td style="width: 180px!important;">
                                                            <input type="number" step="any" class="form-control" placeholder="Cantidad" name="cantidad[]" value="' . $value->recsensorialquimicosinventario_cantidad . '" required>
                                                        </td>
                                                        <td style="width: 180px!important;">
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
                    $tabla = DB::select('SELECT
                                                TABLA2.area_nombre,
                                                -- TABLA2.categoria_nombre,
                                                TABLA2.sustancia_nombre,
                                                TABLA2.ponderacion_cantidad,
                                                TABLA2.ponderacion_riesgo,
                                                TABLA2.ponderacion_volatilidad,
                                                TABLA2.TOTAL,
                                                TABLA2.PRIORIDAD,
                                                TABLA2.COLOR
                                            FROM
                                                (
                                                    SELECT
                                                        area_id,
                                                        IFNULL(area_nombre, "Sin dato") AS area_nombre,
                                                        ##IFNULL(CONCAT(categoria_nombre, " (", categoria_funcion, ")"), "Sin dato") AS categoria_nombre,
                                                        id,
                                                        IFNULL(sustancia_nombre, "Sin dato") AS sustancia_nombre,
                                                        IF(cancerigeno = 0, ponderacion_cantidad, 5) AS ponderacion_cantidad,
                                                        ponderacion_riesgo,
                                                        ponderacion_volatilidad,
                                                        (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) AS TOTAL,
                                                        (
                                                            CASE
                                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 12 THEN "Muy alta"
                                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 10 THEN "Alta"
                                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 8 THEN "Moderada"
                                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 5 THEN "Baja"
                                                                ELSE "Muy baja"
                                                            END
                                                        ) AS PRIORIDAD,
                                                        (
                                                            CASE
                                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 10 THEN "#E74C3C"
                                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 8 THEN "#F1C40F"
                                                                ELSE "#2ECC71"
                                                            END
                                                        ) AS COLOR
                                                    FROM
                                                        (
                                                            SELECT
                                                                recsensorialquimicosinventario.recsensorial_id,
                                                                recsensorialquimicosinventario.id,
                                                                recsensorialquimicosinventario.recsensorialarea_id AS area_id,
                                                                recsensorialarea.recsensorialarea_nombre AS area_nombre,
                                                                recsensorialquimicosinventario.recsensorialcategoria_id AS categoria_id,
                                                                recsensorialcategoria.recsensorialcategoria_nombrecategoria AS categoria_nombre,
                                                                ##recsensorialcategoria.recsensorialcategoria_funcioncategoria AS categoria_funcion,
                                                                recsensorialareacategorias.recsensorialareacategorias_actividad AS categoria_actividad,
                                                                recsensorialareacategorias.recsensorialareacategorias_geh AS categoria_geh,
                                                                ##recsensorialcategoria.recsensorialcategoria_horasjornada AS horas_jornada,
                                                                recsensorialareacategorias.recsensorialareacategorias_total AS tot_trabajadores,
                                                                -- recsensorialareacategorias.recsensorialareacategorias_tiempoexpo AS tiempo_expo,
                                                                -- recsensorialareacategorias.recsensorialareacategorias_frecuenciaexpo AS frecuencia_expo,
                                                                IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) AS tiempo_expo,
                                                                IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0) AS frecuencia_expo,
                                                                ##recsensorialcategoria.recsensorialcategoria_horarioentrada AS horario_entrada,
                                                                ##recsensorialcategoria.recsensorialcategoria_horariosalida AS horario_salida,
                                                                recsensorialquimicosinventario.catsustancia_id AS sustancia_id,
                                                                catsustancia.catsustancia_nombre AS sustancia_nombre,
                                                                recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad AS sustancia_cantidad,
                                                                recsensorialquimicosinventario.catunidadmedidasustacia_id AS Umedida_ID,
                                                                catunidadmedidasustacia.catunidadmedidasustacia_descripcion AS Umedida,
                                                                catunidadmedidasustacia.catunidadmedidasustacia_abreviacion AS UmedidaAbrev,
                                                                catunidadmedidasustacia.catunidadmedidasustacia_ponderacion AS PONDERACION_ORIGINAL,
                                                                IFNULL((
                                                                    SELECT
                                                                        COUNT(catsustanciacomponente.catsustanciacomponente_connotacion)
                                                                    FROM
                                                                        catsustanciacomponente 
                                                                    WHERE
                                                                        catsustanciacomponente.catsustancia_id = recsensorialquimicosinventario.catsustancia_id
                                                                        AND (catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A1%" OR 
                                                                        catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A2%" OR 
                                                                        catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Teratogenica%" OR 
                                                                        catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Mutagenica%")
                                                                ), 0) AS cancerigeno,
                                                                (
                                                                    CASE
                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 6 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 4 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 3 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 1 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                        ELSE 0
                                                                    END
                                                                ) AS ponderacion_cantidad,
                                                                catgradoriesgosalud.catgradoriesgosalud_ponderacion AS ponderacion_riesgo,
                                                                catvolatilidad.catvolatilidad_ponderacion AS ponderacion_volatilidad,
                                                                catviaingresoorganismo.catviaingresoorganismo_ponderacion AS tot_ingresoorganismo,
                                                                (
                                                                    CASE
                                                                        WHEN recsensorialareacategorias.recsensorialareacategorias_total > 100 THEN 8
                                                                        WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 25 THEN 4
                                                                        WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 5 THEN 2
                                                                        ELSE 1
                                                                    END
                                                                ) AS tot_personalexposicion,
                                                                (
                                                                    CASE
                                                                        WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 7 THEN 8
                                                                        WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 3 THEN 4
                                                                        WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 1 THEN 2
                                                                        ELSE 1
                                                                    END
                                                                ) AS tot_tiempoexposicion,
                                                                (IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0)) AS suma_tiempoexposicion
                                                                -- ,catsustancia.catestadofisicosustancia_id,
                                                                -- catestadofisicosustancia.catestadofisicosustancia_estado,
                                                                -- catsustancia.catvolatilidad_id,
                                                                -- catvolatilidad.catvolatilidad_tipo,
                                                                -- catsustancia.catviaingresoorganismo_id,
                                                                -- catviaingresoorganismo.catviaingresoorganismo_viaingreso,
                                                                -- catsustancia.catcategoriapeligrosalud_id,
                                                                -- catcategoriapeligrosalud.id,
                                                                -- catcategoriapeligrosalud.catcategoriapeligrosalud_codigo,
                                                                -- catsustancia.catgradoriesgosalud_id,
                                                                -- catgradoriesgosalud.catgradoriesgosalud_clasificacion
                                                            FROM
                                                                recsensorialquimicosinventario
                                                                LEFT JOIN recsensorialarea ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialarea.id
                                                                LEFT JOIN recsensorialareacategorias ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialareacategorias.recsensorialarea_id 
                                                                AND recsensorialquimicosinventario.recsensorialcategoria_id = recsensorialareacategorias.recsensorialcategoria_id
                                                                LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                                                LEFT JOIN catsustancia ON recsensorialquimicosinventario.catsustancia_id = catsustancia.id
                                                                LEFT JOIN catunidadmedidasustacia ON recsensorialquimicosinventario.catunidadmedidasustacia_id = catunidadmedidasustacia.id
                                                                LEFT JOIN catestadofisicosustancia ON catsustancia.catestadofisicosustancia_id = catestadofisicosustancia.id
                                                                LEFT JOIN catvolatilidad ON catsustancia.catvolatilidad_id = catvolatilidad.id
                                                                LEFT JOIN catviaingresoorganismo ON catsustancia.catviaingresoorganismo_id = catviaingresoorganismo.id
                                                                LEFT JOIN catcategoriapeligrosalud ON catsustancia.catcategoriapeligrosalud_id = catcategoriapeligrosalud.id
                                                                LEFT JOIN catgradoriesgosalud ON catsustancia.catgradoriesgosalud_id = catgradoriesgosalud.id 
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
                                                                recsensorialareacategorias.recsensorialareacategorias_actividad,
                                                                recsensorialareacategorias.recsensorialareacategorias_geh,
                                                                ##recsensorialcategoria.recsensorialcategoria_horasjornada,
                                                                recsensorialareacategorias.recsensorialareacategorias_total,
                                                                ##recsensorialcategoria.recsensorialcategoria_horarioentrada,
                                                                ##recsensorialcategoria.recsensorialcategoria_horariosalida,
                                                                recsensorialquimicosinventario.catsustancia_id,
                                                                catsustancia.catsustancia_nombre,
                                                                recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad,
                                                                recsensorialquimicosinventario.catunidadmedidasustacia_id,
                                                                recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo,
                                                                recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo,
                                                                catunidadmedidasustacia.catunidadmedidasustacia_descripcion,
                                                                catunidadmedidasustacia.catunidadmedidasustacia_abreviacion,
                                                                catunidadmedidasustacia.catunidadmedidasustacia_ponderacion,
                                                                catgradoriesgosalud.catgradoriesgosalud_ponderacion,
                                                                catvolatilidad.catvolatilidad_ponderacion,
                                                                catviaingresoorganismo.catviaingresoorganismo_ponderacion
                                                        ) AS TABLA1
                                                ) AS TABLA2
                                            GROUP BY
                                                TABLA2.area_id,
                                                TABLA2.area_nombre,
                                                -- TABLA2.categoria_nombre,
                                                -- TABLA2.id,
                                                TABLA2.sustancia_nombre,
                                                TABLA2.ponderacion_cantidad,
                                                TABLA2.ponderacion_riesgo,
                                                TABLA2.ponderacion_volatilidad,
                                                TABLA2.TOTAL,
                                                TABLA2.PRIORIDAD,
                                                TABLA2.COLOR
                                            ORDER BY
                                                TABLA2.area_id ASC
                                                -- ,TABLA2.id ASC
                                            ');

                    $numero_registro = 0;
                    foreach ($tabla as $key => $value) {
                        $value->numero_registro = $numero_registro += 1;

                        switch (($value->TOTAL)) {
                            case ($value->TOTAL) >= 10:
                                $value->PRIORIDAD = '<p class="text-danger"><b>' . $value->PRIORIDAD . '</b></p>';
                                break;
                            case ($value->TOTAL) >= 8:
                                $value->PRIORIDAD = '<p class="text-warning"><b>' . $value->PRIORIDAD . '</b></p>';
                                break;
                            default:
                                $value->PRIORIDAD = '<p class="text-success"><b>' . $value->PRIORIDAD . '</b></p>';
                                break;
                        }

                        $value->TOTAL = '<b style="color:#000000;">' . $value->TOTAL . '</b>';
                    }
                    break;
                case 2:
                    $tabla = DB::select('SELECT
                                                -- *,
                                                area_id,
                                                IFNULL(area_nombre, "Sin dato") AS area_nombre,
                                                categoria_id,
                                                IFNULL(CONCAT(categoria_geh, ".- ", categoria_nombre), "Sin dato") AS categoria_nombre,
                                                id,
                                                IFNULL(sustancia_nombre, "Sin dato") AS sustancia_nombre,
                                                tot_ingresoorganismo,
                                                tot_personalexposicion,
                                                tot_tiempoexposicion,
                                                (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) AS TOTAL2,
                                                (
                                                    CASE
                                                        WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 13 THEN "Muy alta"
                                                        WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 9 THEN "Alta"
                                                        WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 4 THEN "Moderada"
                                                        ELSE "Baja"
                                                    END
                                                ) AS PRIORIDAD2
                                            FROM
                                                (
                                                    SELECT
                                                        *,
                                                        (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) AS TOTAL,
                                                        (
                                                            CASE
                                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 12 THEN "Muy alta"
                                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 10 THEN "Alta"
                                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 8 THEN "Moderada"
                                                                WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 5 THEN "Baja"
                                                                ELSE "Muy baja"
                                                            END
                                                        ) AS PRIORIDAD
                                                    FROM
                                                        (
                                                            SELECT
                                                                recsensorialquimicosinventario.recsensorial_id,
                                                                recsensorialquimicosinventario.id,
                                                                recsensorialquimicosinventario.recsensorialarea_id AS area_id,
                                                                recsensorialarea.recsensorialarea_nombre AS area_nombre,
                                                                recsensorialquimicosinventario.recsensorialcategoria_id AS categoria_id,
                                                                recsensorialcategoria.recsensorialcategoria_nombrecategoria AS categoria_nombre,
                                                                ##recsensorialcategoria.recsensorialcategoria_funcioncategoria AS categoria_funcion,
                                                                recsensorialareacategorias.recsensorialareacategorias_actividad AS categoria_actividad,
                                                                recsensorialareacategorias.recsensorialareacategorias_geh AS categoria_geh,
                                                                ##recsensorialcategoria.recsensorialcategoria_horasjornada AS horas_jornada,
                                                                recsensorialareacategorias.recsensorialareacategorias_total AS tot_trabajadores,
                                                                -- recsensorialareacategorias.recsensorialareacategorias_tiempoexpo AS tiempo_expo,
                                                                -- recsensorialareacategorias.recsensorialareacategorias_frecuenciaexpo AS frecuencia_expo,
                                                                IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) AS tiempo_expo,
                                                                IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0) AS frecuencia_expo,
                                                                ##recsensorialcategoria.recsensorialcategoria_horarioentrada AS horario_entrada,
                                                                ##recsensorialcategoria.recsensorialcategoria_horariosalida AS horario_salida,
                                                                recsensorialquimicosinventario.catsustancia_id AS sustancia_id,
                                                                catsustancia.catsustancia_nombre AS sustancia_nombre,
                                                                recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad AS sustancia_cantidad,
                                                                recsensorialquimicosinventario.catunidadmedidasustacia_id AS Umedida_ID,
                                                                catunidadmedidasustacia.catunidadmedidasustacia_descripcion AS Umedida,
                                                                catunidadmedidasustacia.catunidadmedidasustacia_abreviacion AS UmedidaAbrev,
                                                                catunidadmedidasustacia.catunidadmedidasustacia_ponderacion AS PONDERACION_ORIGINAL,
                                                                IFNULL((
                                                                    SELECT
                                                                        COUNT(catsustanciacomponente.catsustanciacomponente_connotacion)
                                                                    FROM
                                                                        catsustanciacomponente 
                                                                    WHERE
                                                                        catsustanciacomponente.catsustancia_id = recsensorialquimicosinventario.catsustancia_id
                                                                        AND (catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A1%" OR 
                                                                        catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A2%" OR 
                                                                        catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Teratogenica%" OR 
                                                                        catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Mutagenica%")
                                                                ), 0) AS cancerigeno,
                                                                (
                                                                    CASE
                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 6 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 4 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 3 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                        WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 1 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                        ELSE 0
                                                                    END
                                                                ) AS ponderacion_cantidad,
                                                                catgradoriesgosalud.catgradoriesgosalud_ponderacion AS ponderacion_riesgo,
                                                                catvolatilidad.catvolatilidad_ponderacion AS ponderacion_volatilidad,
                                                                catviaingresoorganismo.catviaingresoorganismo_ponderacion AS tot_ingresoorganismo,
                                                                (
                                                                    CASE
                                                                        WHEN recsensorialareacategorias.recsensorialareacategorias_total > 100 THEN 8
                                                                        WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 25 THEN 4
                                                                        WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 5 THEN 2
                                                                        ELSE 1
                                                                    END
                                                                ) AS tot_personalexposicion,
                                                                (
                                                                    CASE
                                                                        WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 7 THEN 8
                                                                        WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 3 THEN 4
                                                                        WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 1 THEN 2
                                                                        ELSE 1
                                                                    END
                                                                ) AS tot_tiempoexposicion,
                                                                (IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0)) AS suma_tiempoexposicion
                                                                -- ,catsustancia.catestadofisicosustancia_id,
                                                                -- catestadofisicosustancia.catestadofisicosustancia_estado,
                                                                -- catsustancia.catvolatilidad_id,
                                                                -- catvolatilidad.catvolatilidad_tipo,
                                                                -- catsustancia.catviaingresoorganismo_id,
                                                                -- catviaingresoorganismo.catviaingresoorganismo_viaingreso,
                                                                -- catsustancia.catcategoriapeligrosalud_id,
                                                                -- catcategoriapeligrosalud.id,
                                                                -- catcategoriapeligrosalud.catcategoriapeligrosalud_codigo,
                                                                -- catsustancia.catgradoriesgosalud_id,
                                                                -- catgradoriesgosalud.catgradoriesgosalud_clasificacion
                                                            FROM
                                                                recsensorialquimicosinventario
                                                                LEFT JOIN recsensorialarea ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialarea.id
                                                                LEFT JOIN recsensorialareacategorias ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialareacategorias.recsensorialarea_id 
                                                                AND recsensorialquimicosinventario.recsensorialcategoria_id = recsensorialareacategorias.recsensorialcategoria_id
                                                                LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                                                LEFT JOIN catsustancia ON recsensorialquimicosinventario.catsustancia_id = catsustancia.id
                                                                LEFT JOIN catunidadmedidasustacia ON recsensorialquimicosinventario.catunidadmedidasustacia_id = catunidadmedidasustacia.id
                                                                LEFT JOIN catestadofisicosustancia ON catsustancia.catestadofisicosustancia_id = catestadofisicosustancia.id
                                                                LEFT JOIN catvolatilidad ON catsustancia.catvolatilidad_id = catvolatilidad.id
                                                                LEFT JOIN catviaingresoorganismo ON catsustancia.catviaingresoorganismo_id = catviaingresoorganismo.id
                                                                LEFT JOIN catcategoriapeligrosalud ON catsustancia.catcategoriapeligrosalud_id = catcategoriapeligrosalud.id
                                                                LEFT JOIN catgradoriesgosalud ON catsustancia.catgradoriesgosalud_id = catgradoriesgosalud.id 
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
                                                                recsensorialareacategorias.recsensorialareacategorias_actividad,
                                                                recsensorialareacategorias.recsensorialareacategorias_geh,
                                                                ##recsensorialcategoria.recsensorialcategoria_horasjornada,
                                                                recsensorialareacategorias.recsensorialareacategorias_total,
                                                                ##recsensorialcategoria.recsensorialcategoria_horarioentrada,
                                                                ##recsensorialcategoria.recsensorialcategoria_horariosalida,
                                                                recsensorialquimicosinventario.catsustancia_id,
                                                                catsustancia.catsustancia_nombre,
                                                                recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad,
                                                                recsensorialquimicosinventario.catunidadmedidasustacia_id,
                                                                recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo,
                                                                recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo,
                                                                catunidadmedidasustacia.catunidadmedidasustacia_descripcion,
                                                                catunidadmedidasustacia.catunidadmedidasustacia_abreviacion,
                                                                catunidadmedidasustacia.catunidadmedidasustacia_ponderacion,
                                                                catgradoriesgosalud.catgradoriesgosalud_ponderacion,
                                                                catvolatilidad.catvolatilidad_ponderacion,
                                                                catviaingresoorganismo.catviaingresoorganismo_ponderacion
                                                            -- ORDER BY
                                                                -- area_nombre ASC,
                                                                -- categoria_nombre ASC,
                                                                -- sustancia_nombre ASC
                                                        ) AS TABLA1
                                                    ORDER BY
                                                            area_nombre ASC,
                                                            categoria_nombre ASC,
                                                            TOTAL DESC
                                                ) AS TABLA2
                                            WHERE
                                                TOTAL >= 8
                                            ORDER BY
                                                area_id ASC,
                                                categoria_id ASC,
                                                id ASC');

                    $numero_registro = 0;
                    foreach ($tabla as $key => $value) {
                        $value->numero_registro = $numero_registro += 1;

                        switch (($value->TOTAL2)) {
                            case ($value->TOTAL2) >= 9:
                                $value->PRIORIDAD2 = '<p class="text-danger"><b>' . $value->PRIORIDAD2 . '</b></p>';
                                break;
                            case ($value->TOTAL2) >= 4:
                                $value->PRIORIDAD2 = '<p class="text-warning"><b>' . $value->PRIORIDAD2 . '</b></p>';
                                break;
                            default:
                                $value->PRIORIDAD2 = '<p class="text-success"><b>' . $value->PRIORIDAD2 . '</b></p>';
                                break;
                        }

                        $value->TOTAL2 = '<b style="color:#000000;">' . $value->TOTAL2 . '</b>';
                    }
                    break;
                case 3:
                    $tabla = DB::select('SELECT
                                                    *
                                                FROM
                                                    (
                                                        SELECT
                                                            -- *,
                                                            area_id,
                                                            IFNULL(area_nombre, "Sin dato") AS area_nombre,
                                                            categoria_id,
                                                            IFNULL(CONCAT(categoria_geh, ".- ", categoria_nombre), "Sin dato") AS categoria_nombre,
                                                            id,
                                                            sustancia_id,
                                                            IFNULL(sustancia_nombre, "Sin dato") AS sustancia_nombre,
                                                            tot_trabajadores,
                                                            tiempo_expo,
                                                            frecuencia_expo,
                                                            suma_tiempoexposicion,
                                                            ##horas_jornada,
                                                            (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) AS TOTAL2,
                                                            (
                                                                CASE
                                                                    WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 13 THEN "Muy alta"
                                                                    WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 9 THEN "Alta"
                                                                    WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 4 THEN "Moderada"
                                                                    ELSE "Baja"
                                                                END
                                                            ) AS PRIORIDAD2,
                                                            (
                                                                CASE
                                                                    WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 13 THEN
                                                                        CASE
                                                                            WHEN tot_trabajadores > 100 THEN 20
                                                                            WHEN tot_trabajadores >= 51 THEN 15
                                                                            WHEN tot_trabajadores >= 26 THEN 8
                                                                            WHEN tot_trabajadores >= 16 THEN 5
                                                                            WHEN tot_trabajadores >= 9 THEN 3
                                                                            WHEN tot_trabajadores >= 3 THEN 2
                                                                            ELSE 1
                                                                        END
                                                                    ELSE 
                                                                        CASE
                                                                            WHEN tot_trabajadores > 100 THEN 10
                                                                            WHEN tot_trabajadores >= 51 THEN 7
                                                                            WHEN tot_trabajadores >= 31 THEN 5
                                                                            WHEN tot_trabajadores >= 21 THEN 4
                                                                            WHEN tot_trabajadores >= 11 THEN 3
                                                                            WHEN tot_trabajadores >= 6 THEN 2
                                                                            ELSE 1
                                                                        END
                                                                END
                                                            ) AS NUMERO_MUESTREOS
                                                        FROM
                                                            (
                                                                SELECT
                                                                    *,
                                                                    (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) AS TOTAL,
                                                                    (
                                                                        CASE
                                                                            WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 12 THEN "Muy alta"
                                                                            WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 10 THEN "Alta"
                                                                            WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 8 THEN "Moderada"
                                                                            WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 5 THEN "Baja"
                                                                            ELSE "Muy baja"
                                                                        END
                                                                    ) AS PRIORIDAD
                                                                FROM
                                                                    (
                                                                        SELECT
                                                                            recsensorialquimicosinventario.recsensorial_id,
                                                                            recsensorialquimicosinventario.id,
                                                                            recsensorialquimicosinventario.recsensorialarea_id AS area_id,
                                                                            recsensorialarea.recsensorialarea_nombre AS area_nombre,
                                                                            recsensorialquimicosinventario.recsensorialcategoria_id AS categoria_id,
                                                                            recsensorialcategoria.recsensorialcategoria_nombrecategoria AS categoria_nombre,
                                                                            ##recsensorialcategoria.recsensorialcategoria_funcioncategoria AS categoria_funcion,
                                                                            recsensorialareacategorias.recsensorialareacategorias_actividad AS categoria_actividad,
                                                                            recsensorialareacategorias.recsensorialareacategorias_geh AS categoria_geh,
                                                                            ##recsensorialcategoria.recsensorialcategoria_horasjornada AS horas_jornada,
                                                                            recsensorialareacategorias.recsensorialareacategorias_total AS tot_trabajadores,
                                                                            -- recsensorialareacategorias.recsensorialareacategorias_tiempoexpo AS tiempo_expo,
                                                                            -- recsensorialareacategorias.recsensorialareacategorias_frecuenciaexpo AS frecuencia_expo,
                                                                            IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) AS tiempo_expo,
                                                                            IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0) AS frecuencia_expo,
                                                                            ##recsensorialcategoria.recsensorialcategoria_horarioentrada AS horario_entrada,
                                                                            ##recsensorialcategoria.recsensorialcategoria_horariosalida AS horario_salida,
                                                                            recsensorialquimicosinventario.catsustancia_id AS sustancia_id,
                                                                            catsustancia.catsustancia_nombre AS sustancia_nombre,
                                                                            recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad AS sustancia_cantidad,
                                                                            recsensorialquimicosinventario.catunidadmedidasustacia_id AS Umedida_ID,
                                                                            catunidadmedidasustacia.catunidadmedidasustacia_descripcion AS Umedida,
                                                                            catunidadmedidasustacia.catunidadmedidasustacia_abreviacion AS UmedidaAbrev,
                                                                            catunidadmedidasustacia.catunidadmedidasustacia_ponderacion AS PONDERACION_ORIGINAL,
                                                                            IFNULL((
                                                                                SELECT
                                                                                    COUNT(catsustanciacomponente.catsustanciacomponente_connotacion)
                                                                                FROM
                                                                                    catsustanciacomponente 
                                                                                WHERE
                                                                                    catsustanciacomponente.catsustancia_id = recsensorialquimicosinventario.catsustancia_id
                                                                                    AND (catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A1%" OR 
                                                                                    catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A2%" OR 
                                                                                    catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Teratogenica%" OR 
                                                                                    catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Mutagenica%")
                                                                            ), 0) AS cancerigeno,
                                                                            (
                                                                                CASE
                                                                                    WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 6 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                    WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                                                    WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                    WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 4 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                    WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 3 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                    WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                                                    WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                    WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 1 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                    ELSE 0
                                                                                END
                                                                            ) AS ponderacion_cantidad,
                                                                            catgradoriesgosalud.catgradoriesgosalud_ponderacion AS ponderacion_riesgo,
                                                                            catvolatilidad.catvolatilidad_ponderacion AS ponderacion_volatilidad,
                                                                            catviaingresoorganismo.catviaingresoorganismo_ponderacion AS tot_ingresoorganismo,
                                                                            (
                                                                                CASE
                                                                                    WHEN recsensorialareacategorias.recsensorialareacategorias_total > 100 THEN 8
                                                                                    WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 25 THEN 4
                                                                                    WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 5 THEN 2
                                                                                    ELSE 1
                                                                                END
                                                                            ) AS tot_personalexposicion,
                                                                            (
                                                                                CASE
                                                                                    WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 7 THEN 8
                                                                                    WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 3 THEN 4
                                                                                    WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 1 THEN 2
                                                                                    ELSE 1
                                                                                END
                                                                            ) AS tot_tiempoexposicion,
                                                                            (IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0)) AS suma_tiempoexposicion
                                                                            -- ,catsustancia.catestadofisicosustancia_id,
                                                                            -- catestadofisicosustancia.catestadofisicosustancia_estado,
                                                                            -- catsustancia.catvolatilidad_id,
                                                                            -- catvolatilidad.catvolatilidad_tipo,
                                                                            -- catsustancia.catviaingresoorganismo_id,
                                                                            -- catviaingresoorganismo.catviaingresoorganismo_viaingreso,
                                                                            -- catsustancia.catcategoriapeligrosalud_id,
                                                                            -- catcategoriapeligrosalud.id,
                                                                            -- catcategoriapeligrosalud.catcategoriapeligrosalud_codigo,
                                                                            -- catsustancia.catgradoriesgosalud_id,
                                                                            -- catgradoriesgosalud.catgradoriesgosalud_clasificacion
                                                                        FROM
                                                                            recsensorialquimicosinventario
                                                                            LEFT JOIN recsensorialarea ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialarea.id
                                                                            LEFT JOIN recsensorialareacategorias ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialareacategorias.recsensorialarea_id 
                                                                            AND recsensorialquimicosinventario.recsensorialcategoria_id = recsensorialareacategorias.recsensorialcategoria_id
                                                                            LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                                                            LEFT JOIN catsustancia ON recsensorialquimicosinventario.catsustancia_id = catsustancia.id
                                                                            LEFT JOIN catunidadmedidasustacia ON recsensorialquimicosinventario.catunidadmedidasustacia_id = catunidadmedidasustacia.id
                                                                            LEFT JOIN catestadofisicosustancia ON catsustancia.catestadofisicosustancia_id = catestadofisicosustancia.id
                                                                            LEFT JOIN catvolatilidad ON catsustancia.catvolatilidad_id = catvolatilidad.id
                                                                            LEFT JOIN catviaingresoorganismo ON catsustancia.catviaingresoorganismo_id = catviaingresoorganismo.id
                                                                            LEFT JOIN catcategoriapeligrosalud ON catsustancia.catcategoriapeligrosalud_id = catcategoriapeligrosalud.id
                                                                            LEFT JOIN catgradoriesgosalud ON catsustancia.catgradoriesgosalud_id = catgradoriesgosalud.id 
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
                                                                            recsensorialareacategorias.recsensorialareacategorias_actividad,
                                                                            recsensorialareacategorias.recsensorialareacategorias_geh,
                                                                            ##recsensorialcategoria.recsensorialcategoria_horasjornada,
                                                                            recsensorialareacategorias.recsensorialareacategorias_total,
                                                                            ##recsensorialcategoria.recsensorialcategoria_horarioentrada,
                                                                            ##recsensorialcategoria.recsensorialcategoria_horariosalida,
                                                                            recsensorialquimicosinventario.catsustancia_id,
                                                                            catsustancia.catsustancia_nombre,
                                                                            recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad,
                                                                            recsensorialquimicosinventario.catunidadmedidasustacia_id,
                                                                            recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo,
                                                                            recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo,
                                                                            catunidadmedidasustacia.catunidadmedidasustacia_descripcion,
                                                                            catunidadmedidasustacia.catunidadmedidasustacia_abreviacion,
                                                                            catunidadmedidasustacia.catunidadmedidasustacia_ponderacion,
                                                                            catgradoriesgosalud.catgradoriesgosalud_ponderacion,
                                                                            catvolatilidad.catvolatilidad_ponderacion,
                                                                            catviaingresoorganismo.catviaingresoorganismo_ponderacion
                                                                        -- ORDER BY
                                                                            -- area_nombre ASC,
                                                                            -- categoria_nombre ASC,
                                                                            -- sustancia_nombre ASC
                                                                    ) AS TABLA1
                                                                ORDER BY
                                                                    area_nombre ASC,
                                                                    categoria_nombre ASC,
                                                                    TOTAL DESC
                                                            ) AS TABLA2
                                                        WHERE
                                                            TOTAL >= 8
                                                        ORDER BY
                                                            area_id ASC,
                                                            categoria_id ASC,
                                                            id ASC
                                                    ) AS TABLA3
                                                WHERE
                                                    TOTAL2 >= 4
                                                ORDER BY
                                                    area_id ASC,
                                                    categoria_id ASC,
                                                    id ASC');

                    $numero_registro = 0;
                    foreach ($tabla as $key => $value) {
                        $value->numero_registro = $numero_registro += 1;

                        switch (($value->TOTAL2)) {
                            case ($value->TOTAL2) >= 9:
                                $value->PRIORIDAD2 = '<p class="text-danger"><b>' . $value->PRIORIDAD2 . '</b></p>';
                                break;
                            case ($value->TOTAL2) >= 4:
                                $value->PRIORIDAD2 = '<p class="text-warning"><b>' . $value->PRIORIDAD2 . '</b></p>';
                                break;
                            default:
                                $value->PRIORIDAD2 = '<p class="text-success"><b>' . $value->PRIORIDAD2 . '</b></p>';
                                break;
                        }

                        $value->NUMERO_MUESTREOS = '<b style="color:#000000;">' . $value->NUMERO_MUESTREOS . '</b>';
                    }
                    break;
                case 4:
                    $tabla = DB::select('SELECT
                                                    -- *,
                                                    TABLA5.categoria_id,
                                                    TABLA5.categoria_nombre,
                                                    TABLA5.componente,
                                                    IF(MAX(TABLA5.MUESTREO_PPT) > 0, SUM(TABLA5.TOTAL_MUESTREOS), "ND") AS MUESTREO_PPT,
                                                    IF(MAX(TABLA5.MUESTREO_CT) > 0, SUM(TABLA5.TOTAL_MUESTREOS), "ND") AS MUESTREO_CT,
                                                    SUM(TABLA5.TOTAL_MUESTREOS) AS TOTAL_MUESTREOS
                                                FROM
                                                    (
                                                        SELECT
                                                            -- *,
                                                            TABLA4.area_id,
                                                            TABLA4.area_nombre,
                                                            TABLA4.categoria_id,
                                                            TABLA4.categoria_nombre,
                                                            TABLA4.id,
                                                            TABLA4.sustancia_nombre,
                                                            TABLA4.componente,
                                                            -- (TABLA4.componente_ponderacion_cantidad + TABLA4.ponderacion_riesgo + TABLA4.ponderacion_volatilidad) AS COMPONENTE_PONDERACION_TOTAL,       
                                                            TABLA4.TOTAL2,
                                                            TABLA4.PRIORIDAD2,
                                                            TABLA4.NUMERO_MUESTREOS,
                                                            TABLA4.MUESTREO_PPT,
                                                            TABLA4.MUESTREO_CT,
                                                            TABLA4.TOTAL_MUESTREOS
                                                        FROM
                                                            (
                                                                SELECT
                                                                    -- *,
                                                                    TABLA3.area_id,
                                                                    TABLA3.area_nombre,
                                                                    TABLA3.categoria_id,
                                                                    TABLA3.categoria_nombre,
                                                                    TABLA3.id,
                                                                    TABLA3.sustancia_nombre,
                                                                    -- datos ponderacion componente
                                                                    TABLA3.sustancia_cantidad,
                                                                    TABLA3.Umedida_ID,
                                                                    TABLA3.ponderacion_cantidad,
                                                                    TABLA3.ponderacion_riesgo,
                                                                    TABLA3.ponderacion_volatilidad,
                                                                    REPLACE(TABLA3.componente, "ˏ", ",") AS componente,
                                                                    TABLA3.cancerigeno,
                                                                    TABLA3.componente_porcentaje_real,
                                                                    TABLA3.componente_porcentaje,
                                                                    TABLA3.componente_cantidad,
                                                                    (
                                                                        CASE
                                                                            WHEN TABLA3.componente_cantidad = 0 THEN 0
                                                                            WHEN TABLA3.cancerigeno = 1 THEN 5 -- cancerigeno
                                                                            ELSE 
                                                                                CASE
                                                                                    WHEN (TABLA3.Umedida_ID = 3 || TABLA3.Umedida_ID = 6) THEN -- M3 ó toneladas
                                                                                        CASE
                                                                                            WHEN TABLA3.componente_cantidad >= 1 THEN 4
                                                                                            ELSE
                                                                                                CASE
                                                                                                    WHEN (TABLA3.componente_cantidad * 1000) >= 1 THEN
                                                                                                        CASE
                                                                                                            WHEN (TABLA3.componente_cantidad * 1000) > 250 THEN 3
                                                                                                            ELSE 2
                                                                                                        END
                                                                                                    ELSE 1
                                                                                                END
                                                                                        END
                                                                                    WHEN (TABLA3.Umedida_ID = 2 || TABLA3.Umedida_ID = 5) THEN -- Litros ó Kilos
                                                                                        CASE
                                                                                            WHEN TABLA3.componente_cantidad >= 1 THEN
                                                                                                CASE
                                                                                                    WHEN TABLA3.componente_cantidad > 250 THEN 3
                                                                                                    ELSE 2
                                                                                                END
                                                                                            ELSE 1
                                                                                        END
                                                                                    ELSE 1
                                                                                END
                                                                        END
                                                                    ) AS componente_ponderacion_cantidad,
                                                                    -- / datos ponderacion componente
                                                                    TABLA3.TOTAL2,
                                                                    TABLA3.PRIORIDAD2,
                                                                    TABLA3.NUMERO_MUESTREOS,
                                                                    IF(TABLA3.catsustanciacomponente_exposicionppt > 0, TABLA3.NUMERO_MUESTREOS, "ND") AS MUESTREO_PPT,
                                                                    IF(TABLA3.catsustanciacomponente_exposicionctop > 0, TABLA3.NUMERO_MUESTREOS, "ND") AS MUESTREO_CT,
                                                                    (IF(TABLA3.catsustanciacomponente_exposicionppt > 0, TABLA3.NUMERO_MUESTREOS, IF(TABLA3.catsustanciacomponente_exposicionctop > 0, TABLA3.NUMERO_MUESTREOS, 0))) AS TOTAL_MUESTREOS
                                                                FROM
                                                                    (
                                                                        SELECT
                                                                            -- *,
                                                                            TABLA2.area_id,
                                                                            IFNULL(area_nombre, "Sin dato") AS area_nombre,
                                                                            TABLA2.categoria_id,
                                                                            -- IFNULL(CONCAT(categoria_geh, ".- ", categoria_nombre, " (", categoria_funcion, ")"), "Sin dato") AS categoria_nombre,
                                                                            IFNULL(CONCAT(categoria_geh, ".- ", categoria_nombre), "Sin dato") AS categoria_nombre,
                                                                            TABLA2.id,
                                                                            IFNULL(sustancia_nombre, "Sin dato") AS sustancia_nombre,
                                                                            -- datos ponderacion componente
                                                                            TABLA2.sustancia_cantidad,
                                                                            TABLA2.Umedida_ID,
                                                                            TABLA2.ponderacion_cantidad,
                                                                            TABLA2.ponderacion_riesgo,
                                                                            TABLA2.ponderacion_volatilidad,
                                                                            catsustanciacomponente.catsustanciacomponente_nombre AS componente,
                                                                            TABLA2.cancerigeno,
                                                                            IFNULL(catsustanciacomponente.catsustanciacomponente_porcentaje, 0) AS componente_porcentaje_real,
                                                                            IF(IFNULL(catsustanciacomponente.catsustanciacomponente_porcentaje, 0) >= 1, ROUND((catsustanciacomponente.catsustanciacomponente_porcentaje / 100), 4), 0) AS componente_porcentaje,
                                                                            ROUND((IFNULL(TABLA2.sustancia_cantidad, 0) * IF(IFNULL(catsustanciacomponente.catsustanciacomponente_porcentaje, 0) >= 1, ROUND((catsustanciacomponente.catsustanciacomponente_porcentaje / 100), 4), 0)), 1) AS componente_cantidad,
                                                                            -- / datos ponderacion componente
                                                                            catsustanciacomponente.catsustanciacomponente_exposicionppt,
                                                                            catsustanciacomponente.catsustanciacomponente_exposicionctop,
                                                                            (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) AS TOTAL2,
                                                                            (
                                                                                CASE
                                                                                    WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 13 THEN "Muy alta"
                                                                                    WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 9 THEN "Alta"
                                                                                    WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 4 THEN "Moderada"
                                                                                    ELSE "Baja"
                                                                                END
                                                                            ) AS PRIORIDAD2,
                                                                            (
                                                                                CASE
                                                                                    WHEN (tot_ingresoorganismo + tot_personalexposicion + tot_tiempoexposicion) >= 13 THEN
                                                                                        CASE
                                                                                            WHEN tot_trabajadores > 100 THEN 20
                                                                                            WHEN tot_trabajadores >= 51 THEN 15
                                                                                            WHEN tot_trabajadores >= 26 THEN 8
                                                                                            WHEN tot_trabajadores >= 16 THEN 5
                                                                                            WHEN tot_trabajadores >= 9 THEN 3
                                                                                            WHEN tot_trabajadores >= 3 THEN 2
                                                                                            ELSE 1
                                                                                        END
                                                                                    ELSE 
                                                                                        CASE
                                                                                            WHEN tot_trabajadores > 100 THEN 10
                                                                                            WHEN tot_trabajadores >= 51 THEN 7
                                                                                            WHEN tot_trabajadores >= 31 THEN 5
                                                                                            WHEN tot_trabajadores >= 21 THEN 4
                                                                                            WHEN tot_trabajadores >= 11 THEN 3
                                                                                            WHEN tot_trabajadores >= 6 THEN 2
                                                                                            ELSE 1
                                                                                        END
                                                                                END
                                                                            ) AS NUMERO_MUESTREOS
                                                                        FROM
                                                                            (
                                                                                SELECT
                                                                                    *,
                                                                                    (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) AS TOTAL,
                                                                                    (
                                                                                        CASE
                                                                                            WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 12 THEN "Muy alta"
                                                                                            WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 10 THEN "Alta"
                                                                                            WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 8 THEN "Moderada"
                                                                                            WHEN (IF(cancerigeno = 0, ponderacion_cantidad, 5) + ponderacion_riesgo + ponderacion_volatilidad) >= 5 THEN "Baja"
                                                                                            ELSE "Muy baja"
                                                                                        END
                                                                                    ) AS PRIORIDAD
                                                                                FROM
                                                                                    (
                                                                                        SELECT
                                                                                            recsensorialquimicosinventario.recsensorial_id,
                                                                                            recsensorialquimicosinventario.id,
                                                                                            recsensorialquimicosinventario.recsensorialarea_id AS area_id,
                                                                                            recsensorialarea.recsensorialarea_nombre AS area_nombre,
                                                                                            recsensorialquimicosinventario.recsensorialcategoria_id AS categoria_id,
                                                                                            recsensorialcategoria.recsensorialcategoria_nombrecategoria AS categoria_nombre,
                                                                                            ##recsensorialcategoria.recsensorialcategoria_funcioncategoria AS categoria_funcion,
                                                                                            recsensorialareacategorias.recsensorialareacategorias_actividad AS categoria_actividad,
                                                                                            recsensorialareacategorias.recsensorialareacategorias_geh AS categoria_geh,
                                                                                            ##recsensorialcategoria.recsensorialcategoria_horasjornada AS horas_jornada,
                                                                                            recsensorialareacategorias.recsensorialareacategorias_total AS tot_trabajadores,
                                                                                            IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) AS tiempo_expo,
                                                                                            IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0) AS frecuencia_expo,
                                                                                            ##recsensorialcategoria.recsensorialcategoria_horarioentrada AS horario_entrada,
                                                                                            ##recsensorialcategoria.recsensorialcategoria_horariosalida AS horario_salida,
                                                                                            recsensorialquimicosinventario.catsustancia_id AS sustancia_id,
                                                                                            catsustancia.catsustancia_nombre AS sustancia_nombre,
                                                                                            recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad AS sustancia_cantidad,
                                                                                            recsensorialquimicosinventario.catunidadmedidasustacia_id AS Umedida_ID,
                                                                                            catunidadmedidasustacia.catunidadmedidasustacia_descripcion AS Umedida,
                                                                                            catunidadmedidasustacia.catunidadmedidasustacia_abreviacion AS UmedidaAbrev,
                                                                                            catunidadmedidasustacia.catunidadmedidasustacia_ponderacion AS PONDERACION_ORIGINAL,
                                                                                            IFNULL((
                                                                                                SELECT
                                                                                                    COUNT(catsustanciacomponente.catsustanciacomponente_connotacion)
                                                                                                FROM
                                                                                                    catsustanciacomponente 
                                                                                                WHERE
                                                                                                    catsustanciacomponente.catsustancia_id = recsensorialquimicosinventario.catsustancia_id
                                                                                                    AND (catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A1%" OR 
                                                                                                    catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%A2%" OR 
                                                                                                    catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Teratogenica%" OR 
                                                                                                    catsustanciacomponente.catsustanciacomponente_connotacion LIKE "%Mutagenica%")
                                                                                            ), 0) AS cancerigeno,
                                                                                            (
                                                                                                CASE
                                                                                                    WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 6 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                                    WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                                                                    WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 5 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                                    WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 4 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                                    WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 3 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                                    WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 AND recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad > 250 THEN 3
                                                                                                    WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 2 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                                    WHEN recsensorialquimicosinventario.catunidadmedidasustacia_id = 1 THEN catunidadmedidasustacia.catunidadmedidasustacia_ponderacion
                                                                                                    ELSE 0
                                                                                                END
                                                                                            ) AS ponderacion_cantidad,
                                                                                            catgradoriesgosalud.catgradoriesgosalud_ponderacion AS ponderacion_riesgo,
                                                                                            catvolatilidad.catvolatilidad_ponderacion AS ponderacion_volatilidad,
                                                                                            catviaingresoorganismo.catviaingresoorganismo_ponderacion AS tot_ingresoorganismo,
                                                                                            (
                                                                                                CASE
                                                                                                    WHEN recsensorialareacategorias.recsensorialareacategorias_total > 100 THEN 8
                                                                                                    WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 25 THEN 4
                                                                                                    WHEN recsensorialareacategorias.recsensorialareacategorias_total >= 5 THEN 2
                                                                                                    ELSE 1
                                                                                                END
                                                                                            ) AS tot_personalexposicion,
                                                                                            (
                                                                                                CASE
                                                                                                    WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 7 THEN 8
                                                                                                    WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 3 THEN 4
                                                                                                    WHEN ((IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0))/60) >= 1 THEN 2
                                                                                                    ELSE 1
                                                                                                END
                                                                                            ) AS tot_tiempoexposicion,
                                                                                            (IFNULL(recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo, 0) * IFNULL(recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo, 0)) AS suma_tiempoexposicion
                                                                                            -- ,catsustancia.catestadofisicosustancia_id,
                                                                                            -- catestadofisicosustancia.catestadofisicosustancia_estado,
                                                                                            -- catsustancia.catvolatilidad_id,
                                                                                            -- catvolatilidad.catvolatilidad_tipo,
                                                                                            -- catsustancia.catviaingresoorganismo_id,
                                                                                            -- catviaingresoorganismo.catviaingresoorganismo_viaingreso,
                                                                                            -- catsustancia.catcategoriapeligrosalud_id,
                                                                                            -- catcategoriapeligrosalud.id,
                                                                                            -- catcategoriapeligrosalud.catcategoriapeligrosalud_codigo,
                                                                                            -- catsustancia.catgradoriesgosalud_id,
                                                                                            -- catgradoriesgosalud.catgradoriesgosalud_clasificacion
                                                                                        FROM
                                                                                            recsensorialquimicosinventario
                                                                                            LEFT JOIN recsensorialarea ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialarea.id
                                                                                            LEFT JOIN recsensorialareacategorias ON recsensorialquimicosinventario.recsensorialarea_id = recsensorialareacategorias.recsensorialarea_id 
                                                                                            AND recsensorialquimicosinventario.recsensorialcategoria_id = recsensorialareacategorias.recsensorialcategoria_id
                                                                                            LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                                                                            LEFT JOIN catsustancia ON recsensorialquimicosinventario.catsustancia_id = catsustancia.id
                                                                                            LEFT JOIN catunidadmedidasustacia ON recsensorialquimicosinventario.catunidadmedidasustacia_id = catunidadmedidasustacia.id
                                                                                            LEFT JOIN catestadofisicosustancia ON catsustancia.catestadofisicosustancia_id = catestadofisicosustancia.id
                                                                                            LEFT JOIN catvolatilidad ON catsustancia.catvolatilidad_id = catvolatilidad.id
                                                                                            LEFT JOIN catviaingresoorganismo ON catsustancia.catviaingresoorganismo_id = catviaingresoorganismo.id
                                                                                            LEFT JOIN catcategoriapeligrosalud ON catsustancia.catcategoriapeligrosalud_id = catcategoriapeligrosalud.id
                                                                                            LEFT JOIN catgradoriesgosalud ON catsustancia.catgradoriesgosalud_id = catgradoriesgosalud.id 
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
                                                                                            recsensorialareacategorias.recsensorialareacategorias_actividad,
                                                                                            recsensorialareacategorias.recsensorialareacategorias_geh,
                                                                                            ##recsensorialcategoria.recsensorialcategoria_horasjornada,
                                                                                            recsensorialareacategorias.recsensorialareacategorias_total,
                                                                                            ##recsensorialcategoria.recsensorialcategoria_horarioentrada,
                                                                                            ##recsensorialcategoria.recsensorialcategoria_horariosalida,
                                                                                            recsensorialquimicosinventario.catsustancia_id,
                                                                                            catsustancia.catsustancia_nombre,
                                                                                            recsensorialquimicosinventario.recsensorialquimicosinventario_cantidad,
                                                                                            recsensorialquimicosinventario.catunidadmedidasustacia_id,
                                                                                            recsensorialquimicosinventario.recsensorialcategoria_tiempoexpo,
                                                                                            recsensorialquimicosinventario.recsensorialcategoria_frecuenciaexpo,
                                                                                            catunidadmedidasustacia.catunidadmedidasustacia_descripcion,
                                                                                            catunidadmedidasustacia.catunidadmedidasustacia_abreviacion,
                                                                                            catunidadmedidasustacia.catunidadmedidasustacia_ponderacion,
                                                                                            catgradoriesgosalud.catgradoriesgosalud_ponderacion,
                                                                                            catvolatilidad.catvolatilidad_ponderacion,
                                                                                            catviaingresoorganismo.catviaingresoorganismo_ponderacion
                                                                                    ) AS TABLA1
                                                                                ORDER BY
                                                                                    area_id ASC,
                                                                                    categoria_id ASC,
                                                                                    TOTAL DESC
                                                                            ) AS TABLA2
                                                                            RIGHT JOIN catsustanciacomponente ON catsustanciacomponente.catsustancia_id = sustancia_id
                                                                        WHERE
                                                                            TOTAL >= 8
                                                                        ORDER BY
                                                                            TABLA2.area_id ASC,
                                                                            TABLA2.categoria_id ASC,
                                                                            TABLA2.id ASC
                                                                    ) AS TABLA3
                                                                WHERE
                                                                    TOTAL2 >= 4
                                                                GROUP BY
                                                                    TABLA3.area_id,
                                                                    TABLA3.area_nombre,
                                                                    TABLA3.categoria_id,
                                                                    TABLA3.categoria_nombre,
                                                                    TABLA3.id,
                                                                    TABLA3.sustancia_nombre,
                                                                    -- datos ponderacion componente
                                                                    TABLA3.sustancia_cantidad,
                                                                    TABLA3.Umedida_ID,
                                                                    TABLA3.ponderacion_cantidad,
                                                                    TABLA3.ponderacion_riesgo,
                                                                    TABLA3.ponderacion_volatilidad,
                                                                    TABLA3.componente,
                                                                    TABLA3.cancerigeno,
                                                                    TABLA3.componente_porcentaje_real,
                                                                    TABLA3.componente_porcentaje,
                                                                    TABLA3.componente_cantidad,
                                                                    -- / datos ponderacion componente
                                                                    TABLA3.TOTAL2,
                                                                    TABLA3.PRIORIDAD2,
                                                                    TABLA3.NUMERO_MUESTREOS,
                                                                    TABLA3.catsustanciacomponente_exposicionppt,
                                                                    TABLA3.catsustanciacomponente_exposicionctop
                                                                ORDER BY
                                                                    TABLA3.area_id ASC,
                                                                    TABLA3.categoria_id ASC,
                                                                    TABLA3.id ASC
                                                            ) AS TABLA4
                                                        WHERE
                                                            TABLA4.TOTAL_MUESTREOS > 0
                                                            -- AND (TABLA4.componente_ponderacion_cantidad + TABLA4.ponderacion_riesgo + TABLA4.ponderacion_volatilidad) >= 8
                                                    ) AS TABLA5
                                                GROUP BY
                                                    TABLA5.categoria_id,
                                                    TABLA5.categoria_nombre,
                                                    TABLA5.componente
                                                    -- TABLA5.MUESTREO_PPT,
                                                    -- TABLA5.MUESTREO_CT,
                                                    -- TABLA5.TOTAL_MUESTREOS
                                                ORDER BY
                                                    TABLA5.categoria_id ASC,
                                                    TABLA5.categoria_nombre ASC,
                                                    TABLA5.componente ASC');

                    $numero_registro = 0;
                    foreach ($tabla as $key => $value) {
                        $value->numero_registro = $numero_registro += 1;
                        $value->TOTAL_MUESTREOS = '<b style="color:#000000;">' . $value->TOTAL_MUESTREOS . '</b>';
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
                            'recsensorial_id' => $request->recsensorial_id, 'recsensorialarea_id' => $request->recsensorialarea_id, 'catsustancia_id' => $value_sustancia, 'recsensorialquimicosinventario_cantidad' => $request->cantidad[$key1], 'catunidadmedidasustacia_id' => $request->umedida[$key1], 'recsensorialcategoria_id' => $valor[1], 'recsensorialcategoria_tiempoexpo' => $request->tiempo[$key], 'recsensorialcategoria_frecuenciaexpo' => $request->frecuencia[$key]
                        ]);
                    }
                }
            }

            // mensaje
            $dato["msj"] = 'Información modificada correctamente';



            // respuesta
            $dato['recsensorial_id'] = $request['recsensorial_id'];
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

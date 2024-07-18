<?php

namespace App\Http\Controllers\catalogos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\catalogos\ProveedorModel;
use App\modelos\catalogos\AcreditacionalcanceModel;
use App\modelos\catalogos\Cat_pruebaModel;
use App\modelos\catalogos\Cat_pruebanormaModel;
use DB;

class acreditacionalcanceController extends Controller
{




    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo,Coordinador');
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
    public function tablaacreditacionalcances($proveedor_id)
    {
        try {
            // Proveedor
            $proveedor = ProveedorModel::findOrFail($proveedor_id);

            $tabla = AcreditacionalcanceModel::with(['proveedor', 'acreditacion', 'prueba', 'prueba.pruebanorma'])
                ->where('proveedor_id', $proveedor_id)
                ->where('acreditacionAlcance_Eliminado', 0)
                // ->orderBy('acreditacion_id', 'DESC')
                // ->orderBy('acreditacionAlcance_tipo', 'ASC')
                // ->orderBy('acreditacionAlcance_agente', 'ASC')
                ->orderBy('id', 'ASC')
                ->get();

            // Formatear filas
            $numero_registro = 0;
            foreach ($tabla  as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                // Acreditacion a la que pertenece
                if ($value->acreditacion) {
                    $value->acreditacion_numero = $value->acreditacion->acreditacion_Entidad . '<br>' . $value->acreditacion->acreditacion_Numero;
                } else {
                    $value->acreditacion_numero = 'N/A';
                }

                // Tipo Agente
                if ($value->acreditacionAlcance_agentetipo) {
                    $value->agente = $value->acreditacionAlcance_agente . " (" . $value->acreditacionAlcance_agentetipo . ")";
                } else {
                    $value->agente = $value->acreditacionAlcance_agente;
                }

                $value->normas = "<li>" . str_replace(",", "</li><li>", $value->acreditacionAlcance_Norma) . "</li>";

                // Botones
                if (auth()->user()->hasRoles  (['Superusuario', 'Administrador','Compras']) && ($proveedor->proveedor_Bloqueado + 0) == 0) {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                } else {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-success btn-circle"><i class="fa fa-eye"></i></button>';
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
     * @param  int  $proveedor_id
     * @param  int  $alcanceservicio_id
     * @return \Illuminate\Http\Response
     */
    public function proveedoralcanceservicioslista($proveedor_id, $alcanceservicio_id)
    {
        try {
            $opciones_select = '<option value="">&nbsp;</option>';
            $alcances = DB::select('SELECT
                                        acreditacionalcance.proveedor_id,
                                        acreditacionalcance.id,
                                        acreditacionalcance.acreditacion_id,
                                        IF(acreditacionalcance.acreditacion_id > 0, CONCAT(cat_tipoacreditacion.catTipoAcreditacion_Nombre, ": ",acreditacion.acreditacion_Numero), "N/A") AS acreditacion,
                                        CONCAT("[", acreditacionalcance.acreditacionAlcance_tipo, "] ", acreditacionalcance.acreditacionAlcance_agente, IFNULL(CONCAT(" (", acreditacionalcance.acreditacionAlcance_agentetipo, ")"), "")) AS agenteservicio
                                    FROM
                                        acreditacionalcance
                                        LEFT JOIN acreditacion ON acreditacionalcance.acreditacion_id = acreditacion.id
                                        LEFT JOIN cat_tipoacreditacion ON acreditacion.acreditacion_Tipo = cat_tipoacreditacion.id 
                                    WHERE
                                        acreditacionalcance.proveedor_id = ' . $proveedor_id . ' 
                                        AND acreditacionalcance.acreditacionAlcance_Eliminado = 0 
                                    ORDER BY
                                        acreditacionalcance.acreditacionAlcance_tipo ASC,
                                        acreditacionalcance.acreditacionAlcance_agente ASC,
                                        acreditacionalcance.acreditacionAlcance_agentetipo ASC');

            foreach ($alcances as $key => $value) {
                if ($value->id == $alcanceservicio_id) {
                    $opciones_select .= '<option value="' . $value->id . '" selected>' . $value->acreditacion . ' - ' . $value->agenteservicio . '</option>';
                } else {
                    $opciones_select .= '<option value="' . $value->id . '">' . $value->acreditacion . ' - ' . $value->agenteservicio . '</option>';
                }
            }

            // // respuesta
            $dato['opciones'] = $opciones_select;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['opciones'] = '<option value="">&nbsp;</option>';
            return response()->json($dato);
        }
    }












    /**
     * Display a listing of the resource.
     *
     * @param  int  $agente_tipo
     * @param  int  $agente_seleccionado
     * @return \Illuminate\Http\Response
     */
    public function acreditacionalcancetipoagente($agente_tipo, $agente_seleccionado)
    {
        try {


            $opciones_select = '<option value="">&nbsp;</option>';
            // $recsensorial = DB::select('');
            $agentes = Cat_pruebaModel::select('id', 'catPrueba_Nombre')->where('catPrueba_Tipo', $agente_tipo)->where('catPrueba_Activo', 1)->orderBy('catPrueba_Nombre', 'ASC')->get();

            foreach ($agentes as $key => $value) {
                if ($value->id == $agente_seleccionado) {
                    $opciones_select .= '<option value="' . $value->id . '" selected>' . $value->catPrueba_Nombre . '</option>';
                } else {
                    $opciones_select .= '<option value="' . $value->id . '">' . $value->catPrueba_Nombre . '</option>';
                }
            }

            // // respuesta
            $dato['opciones'] = $opciones_select;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['opciones'] = $opciones_select;
            return response()->json($dato);
        }
    }











    /**
     * Display a listing of the resource.
     *
     * @param  int  $agente_id
     * @return \Illuminate\Http\Response
     */
    public function acreditacionalcanceagentenormas($agente_id)
    {
        try {
            // $normas = Cat_pruebanormaModel::select('catpruebanorma_numero')->where('cat_prueba_id', $agente_id)->get();

            $normas = DB::select('SELECT
                                    cat_pruebanorma.cat_prueba_id,
                                    cat_pruebanorma.catpruebanorma_tipo,
                                    cat_pruebanorma.catpruebanorma_numero,
                                    cat_pruebanorma.catpruebanorma_tipo as tipo
                                FROM
                                    cat_pruebanorma
                                WHERE
                                    cat_pruebanorma.cat_prueba_id = ' . $agente_id . '
                                ORDER BY
                                    cat_pruebanorma.catpruebanorma_tipo ASC');

            // $no = 0;
            // $ListadeMetodos = 0;
            // $listanormas = 'N/A';
            // foreach ($normas as $key => $value) {
            //     if ($value->catpruebanorma_numero != 'N/A') {
            //         if ($no == 0) {
            //             $listanormas = '';
            //             $listanormas .= $value->catpruebanorma_numero;
            //         } else {
            //             $listanormas .= ', ' . $value->catpruebanorma_numero;
            //         }
            //         $no += 1;
            //     }

            //     if ($value->esMetodo == 'SonMetodos') {
            //         $ListadeMetodos = 1;
            //     }
            // }

            // // respuesta
            $dato['normas'] = $normas;        
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['normas'] = '';
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
        // validar si el campo ID es mayor a cero, edita, sino guarda
        try {
            // Guardar
            if ($request['alcance_id'] == 0) {
                // Valida si es fisico o quimico
                if ($request['acreditacionAlcance_tipo'] === "Químico") {
                    $request['prueba_id'] = 15;
                } else {

                    $id_prueba = intval($request['prueba_id']);
                    $alcance = DB::select('SELECT
                                    p.catPrueba_Nombre
                                FROM
                                    cat_prueba p
                                WHERE
                                    p.id = ' . $id_prueba . '
                                    AND p.catPrueba_Activo = 1');


                    // $request['acreditacionAlcance_agente'] = $alcance[0]->catPrueba_Nombre;;
                }

                

                //Valida si llega norma o un metodo
                $request['acreditacion_id'] = $request['acreditacion'];
                $request['alcace_aprovacion_id'] = $request['aprovacion'];

                
                $request['acreditacionAlcance_Norma'] = $request['Norma'];
                $request['acreditacionAlcance_Procedimiento'] = $request['Procedimiento'];
                $request['acreditacionAlcance_Metodo'] = $request['Metodo'];


                // AUTO_INCREMENT
                DB::statement('ALTER TABLE acreditacionalcance AUTO_INCREMENT=1');
                $proveedor = AcreditacionalcanceModel::create($request->all());
                return response()->json($proveedor);
            } else {
                $proveedor = AcreditacionalcanceModel::findOrFail($request['alcance_id']);
                $proveedor->update($request->all());
                return response()->json($proveedor);
            }
        } catch (Exception $e) {
            return response()->json('Error al guardar');
        }
    }
}


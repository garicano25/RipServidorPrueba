<?php

namespace App\Http\Controllers\catalogos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\catalogos\ProveedorModel;
use App\modelos\catalogos\AcreditacionModel;
use App\modelos\recsensorial\recsensorialanexoModel;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTime;
use DB;

class acreditacionController extends Controller
{




    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo,Coordinador');
    }






    /**
     * Display the specified resource.
     *
     * @param  int  $proveedor_id
     * @return \Illuminate\Http\Response
     */
    public function tablaproveedoracreditacion($proveedor_id)
    {
        try {
            // Proveedor
            $proveedor = ProveedorModel::findOrFail($proveedor_id);

            $acreditaciones = DB::select('SELECT
                                            acreditacion.proveedor_id,
                                            acreditacion.id,
                                            -- acreditacion.acreditacion_Servicio,
                                            -- cat_servicioacreditacion.catServicioAcreditacion_Nombre,
                                            acreditacion.acreditacion_Tipo,
                                            cat_tipoacreditacion.catTipoAcreditacion_Nombre,
                                            acreditacion.acreditacion_Entidad,
                                            acreditacion.acreditacion_Numero,
                                            acreditacion.cat_area_id,
                                            cat_area.catArea_Nombre,
                                            acreditacion.acreditacion_Expedicion,
                                            acreditacion.acreditacion_Vigencia,
                                            acreditacion.acreditacion_SoportePDF,
                                            acreditacion.acreditacion_Eliminado
                                        FROM
                                            acreditacion
                                            -- LEFT JOIN cat_servicioacreditacion ON acreditacion.acreditacion_Servicio = cat_servicioacreditacion.id
                                            LEFT JOIN cat_tipoacreditacion ON acreditacion.acreditacion_Tipo = cat_tipoacreditacion.id
                                            LEFT JOIN cat_area ON acreditacion.cat_area_id = cat_area.id
                                        WHERE
                                            acreditacion.proveedor_id = ' . $proveedor_id . '
                                            AND acreditacion.acreditacion_Eliminado = 0');


            $numero_registro = 0;
            // recorrer las acreditaciones para formatear fechas
            foreach ($acreditaciones  as $key => $value) {
                $numero_registro += 1;

                // Formato fecha
                // $value->acreditacion_Expedicion = Carbon::createFromFormat('Y-m-d', $value->acreditacion_Expedicion)->format('d-m-Y');
                // $value->acreditacion_Vigencia = Carbon::createFromFormat('Y-m-d', $value->acreditacion_Vigencia)->format('d-m-Y');

                // determinar los dias faltantes para vigencia
                $datetime1 = date_create(date('Y-m-d'));
                $datetime2 = date_create($value->acreditacion_Vigencia);
                $interval = date_diff($datetime1, $datetime2);

                $agentes = DB::select('SELECT
                                            acreditacionalcance.acreditacion_id,
                                            CONCAT("[", acreditacionalcance.acreditacionAlcance_tipo, "] ", acreditacionalcance.acreditacionAlcance_agente, IFNULL(CONCAT(" (",acreditacionalcance.acreditacionAlcance_agentetipo, ")"), "")) AS agente
                                        FROM
                                            acreditacionalcance
                                            LEFT JOIN acreditacion ON acreditacionalcance.acreditacion_id = acreditacion.id 
                                        WHERE
                                            acreditacionalcance.acreditacion_id = ' . $value->id . ' 
                                            AND acreditacionalcance.acreditacionAlcance_Eliminado = 0 
                                        ORDER BY
                                            acreditacionalcance.acreditacion_id ASC,
                                            acreditacionalcance.acreditacionAlcance_tipo ASC,
                                            acreditacionalcance.acreditacionAlcance_agente ASC');

                $listaagentesalcance = "";
                foreach ($agentes as $key => $agente) {
                    $listaagentesalcance .= "<li>" . $agente->agente . "</li>";
                }

                // alertas en los dias de la vigencia
                switch (($interval->format('%R%a') + 0)) {
                    case (($interval->format('%R%a') + 0) <= 30):
                        $value->numero_registro = $numero_registro;
                        $value->servicio_tipo = '<b class="text-danger">' . $value->catTipoAcreditacion_Nombre . '</b>';
                        $value->entidad_numero = '<b class="text-danger">' . $value->acreditacion_Entidad . '<br>No: ' . $value->acreditacion_Numero . '</b>';
                        $value->area = '<b class="text-danger">' . $value->catArea_Nombre . '</b>';
                        $value->Vigencia_texto = '<b class="text-danger">' . $value->acreditacion_Vigencia . ' (' . ($interval->format('%R%a') + 0) . ' d)</b>';
                        $value->alcance = '<b class="text-danger">' . $listaagentesalcance . '</b>';
                        break;
                    case (($interval->format('%R%a') + 0) <= 90):
                        $value->numero_registro = $numero_registro;
                        $value->servicio_tipo = '<b class="text-warning">' . $value->catTipoAcreditacion_Nombre . '</b>';
                        $value->entidad_numero = '<b class="text-warning">' . $value->acreditacion_Entidad . '<br>No: ' . $value->acreditacion_Numero . '</b>';
                        $value->area = '<b class="text-warning">' . $value->catArea_Nombre . '</b>';
                        $value->Vigencia_texto = '<b class="text-warning">' . $value->acreditacion_Vigencia . ' (' . ($interval->format('%R%a') + 0) . ' d)</b>';
                        $value->alcance = '<b class="text-warning">' . $listaagentesalcance . '</b>';
                        break;
                    default:
                        $value->numero_registro = $numero_registro;
                        $value->servicio_tipo = $value->catTipoAcreditacion_Nombre;
                        $value->entidad_numero = $value->acreditacion_Entidad . '<br>No: ' . $value->acreditacion_Numero;
                        $value->area = $value->catArea_Nombre;
                        $value->Vigencia_texto = $value->acreditacion_Vigencia;
                        $value->alcance = $listaagentesalcance;
                        break;
                }

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
                } else {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-success btn-circle"><i class="fa fa-eye"></i></button>';
                }
            }

            // devolver datos
            $listado['data'] = $acreditaciones;
            return response()->json($listado);
        } catch (exception $e) {
            $listado['data'] = 0;
            return response()->json($listado);
        }
    }







    /**
     * Display the specified resource.
     *
     * @param  int  $acreditacion_id
     * @return \Illuminate\Http\Response
     */
    public function mostrarpdf($acreditacion_id, $tipo) {   
        if ($tipo == 0) {

            $documento = AcreditacionModel::findOrFail($acreditacion_id);

            return Storage::response($documento->acreditacion_SoportePDF);
        }else{
            $documento1 = recsensorialanexoModel::where('contrato_anexo_id',$acreditacion_id)->first();

            return Storage::response($documento1->ruta_anexo);

        }
    }












    /**
     * Display the specified resource.
     *
     * @param  int  $proveedor_id
     * @param  int  $acreditacion_id
     * @return \Illuminate\Http\Response
     */
    //PARA EL SELECT DE ACREDITACIONES
    public function proveedoracreditacionlista($proveedor_id, $acreditacion_id)
    {
        try {

            $esAcreditacion = 0;
            $opciones_select = '<option disabled selected>Seleccione una acreditación...</option><option value="0">N/A</option>';
            $acreditaciones = DB::select('SELECT
                                                acreditacion.id,
                                                cat_tipoacreditacion.catTipoAcreditacion_Nombre,
                                                cat_area.catArea_Nombre,
                                                acreditacion.acreditacion_Entidad,
                                                acreditacion.acreditacion_Numero 
                                            FROM
                                                acreditacion
                                                LEFT JOIN cat_area ON acreditacion.cat_area_id = cat_area.id
                                                LEFT JOIN cat_tipoacreditacion ON acreditacion.acreditacion_Tipo = cat_tipoacreditacion.id 
                                            WHERE
                                                acreditacion.proveedor_id = ' . $proveedor_id . '
                                                AND acreditacion.acreditacion_Eliminado = 0 
                                                AND cat_tipoacreditacion.id = 1
                                            GROUP BY
                                                acreditacion.id,
                                                cat_tipoacreditacion.catTipoAcreditacion_Nombre,
                                                cat_area.catArea_Nombre,
                                                acreditacion.acreditacion_Entidad,
                                                acreditacion.acreditacion_Numero
                                            ORDER BY
                                                cat_tipoacreditacion.catTipoAcreditacion_Nombre ASC,
                                                cat_area.catArea_Nombre ASC,
                                                acreditacion.acreditacion_Entidad ASC,
                                                acreditacion.acreditacion_Numero ASC');

            foreach ($acreditaciones as $key => $value) {
                if ($value->id == $acreditacion_id) {
                    $esAcreditacion = 1; 
                    $opciones_select .= '<option value="' . $value->id . '" selected>' . $value->catTipoAcreditacion_Nombre . ' [' . $value->acreditacion_Numero . '], ' . $value->acreditacion_Entidad . '</option>';
                } else {
                    $opciones_select .= '<option value="' . $value->id . '">' . $value->catTipoAcreditacion_Nombre . ' [' . $value->acreditacion_Numero . '], ' . $value->acreditacion_Entidad . '</option>';
                }
            }

            // // respuesta
            $dato['esAcreditacion'] = $esAcreditacion;
            $dato['opciones'] = $opciones_select;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['opciones'] = '<option value="">&nbsp;</option>';
            return response()->json($dato);
        }
    }


    //PARA EL  SELECT DE APROVACIONES
    public function proveedorAprovacionlista($proveedor_id, $acreditacion_id)
    {
        try {

            $esAprovacion = 0;
            $opciones_select = '<option disabled selected>Seleccione una Aprobación... </option><option value="0">N/A</option>';
            $acreditaciones = DB::select('SELECT
                                                acreditacion.id,
                                                cat_tipoacreditacion.catTipoAcreditacion_Nombre,
                                                cat_area.catArea_Nombre,
                                                acreditacion.acreditacion_Entidad,
                                                acreditacion.acreditacion_Numero 
                                            FROM
                                                acreditacion
                                                LEFT JOIN cat_area ON acreditacion.cat_area_id = cat_area.id
                                                LEFT JOIN cat_tipoacreditacion ON acreditacion.acreditacion_Tipo = cat_tipoacreditacion.id 
                                            WHERE
                                                acreditacion.proveedor_id = ' . $proveedor_id . '
                                                AND acreditacion.acreditacion_Eliminado = 0 
                                                AND cat_tipoacreditacion.id = 2
                                            GROUP BY
                                                acreditacion.id,
                                                cat_tipoacreditacion.catTipoAcreditacion_Nombre,
                                                cat_area.catArea_Nombre,
                                                acreditacion.acreditacion_Entidad,
                                                acreditacion.acreditacion_Numero
                                            ORDER BY
                                                cat_tipoacreditacion.catTipoAcreditacion_Nombre ASC,
                                                cat_area.catArea_Nombre ASC,
                                                acreditacion.acreditacion_Entidad ASC,
                                                acreditacion.acreditacion_Numero ASC');

            foreach ($acreditaciones as $key => $value) {
                if ($value->id == $acreditacion_id) {
                    $esAprovacion = 1;
                    $opciones_select .= '<option value="' . $value->id . '" selected>' . $value->catTipoAcreditacion_Nombre . ' [' . $value->acreditacion_Numero . '], ' . $value->acreditacion_Entidad . '</option>';
                } else {
                    $opciones_select .= '<option value="' . $value->id . '">' . $value->catTipoAcreditacion_Nombre . ' [' . $value->acreditacion_Numero . '], ' . $value->acreditacion_Entidad . '</option>';
                }
            }

            // // respuesta
            $dato['esAprovacion'] = $esAprovacion;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // formatear campos fechas antes de guardar
        // $request['acreditacion_Expedicion'] = Carbon::createFromFormat('d-m-Y', $request['acreditacion_Expedicion'])->format('Y-m-d');
        // $request['acreditacion_Vigencia'] = Carbon::createFromFormat('d-m-Y', $request['acreditacion_Vigencia'])->format('Y-m-d');

        try {

            if ($request['acreditacion_Eliminado'] == 0) //valida eliminacion
            {
                if ($request['acreditacion_id'] == 0) //nuevo
                {
                    // AUTO_INCREMENT
                    DB::statement('ALTER TABLE acreditacion AUTO_INCREMENT=1');
                    $acreditacion = AcreditacionModel::create($request->all());

                    if ($request->file('documentopdf')) {
                        $extension = $request->file('documentopdf')->getClientOriginalExtension();
                        $request['acreditacion_SoportePDF'] = $request->file('documentopdf')->storeAs('proveedores/' . $request['proveedor_id'] . '/acreditaciones', $acreditacion->id . '.' . $extension);

                        $acreditacion->update($request->all());
                    }

                    return response()->json($acreditacion);
                } else //editar
                {
                    $acreditacion = AcreditacionModel::findOrFail($request['acreditacion_id']);

                    if ($request->file('documentopdf')) {
                        $extension = $request->file('documentopdf')->getClientOriginalExtension();
                        $request['acreditacion_SoportePDF'] = $request->file('documentopdf')->storeAs('proveedores/' . $request['proveedor_id'] . '/acreditaciones', $acreditacion->id . '.' . $extension);
                    }

                    // $msj["mensaje"] = "Gabriel";
                    $acreditacion->update($request->all());
                    return response()->json($acreditacion);
                }
            } else //eliminar
            {
                $acreditacion = AcreditacionModel::findOrFail($request['acreditacion_id']);
                $acreditacion->update($request->all());
                return response()->json($acreditacion);
            }
        } catch (Exception $e) {
            return response()->json('Error al guardar');
        }
    }
}

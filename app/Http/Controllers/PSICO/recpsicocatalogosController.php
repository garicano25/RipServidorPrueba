<?php

namespace App\Http\Controllers\PSICO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\modelos\reconocimientopsico\catalogosguiaspsicoModel;
use App\modelos\reconocimientopsico\catcargos_psicoModel;
use App\modelos\reconocimientopsico\catintroducciones_psicoModel;
use App\modelos\reconocimientopsico\catdefiniciones_psicoModel;
use App\modelos\reconocimientopsico\catrecomendaciones_psicoModel;
use App\modelos\reconocimientopsico\catconclusiones_psicoModel;



use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Support\Facades\Storage;

class recpsicocatalogosController extends Controller
{ //
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,ApoyoTecnico,Reportes,Externo');
        $this->middleware('roles:Superusuario,Administrador,Coordinador,Psicólogo');
    }
    
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('catalogos.psico.recpsicocatalogos');
    }

        /**
     * Display the specified resource.
     *
     * @param  int  $num_catalogo
     * @return \Illuminate\Http\Response
     */
    public function tablaCatalogoGuia($num_catalogo)
    {
        switch (($num_catalogo + 0)) {
            case 1:
                $lista = DB::select('SELECT
                                        ID_GUIAPREGUNTA,
                                        TIPOGUIA,
                                        PREGUNTA_ID,
                                        PREGUNTA_NOMBRE,
                                        PREGUNTA_EXPLICACION,
                                        ACTIVO
                                    FROM
                                        catalogoguiaspsico
                                    WHERE 
                                        TIPOGUIA = 1');

                $COUNT = 0;
                foreach ($lista as $key => $value) {
                    $COUNT++;
                    $value->COUNT = $COUNT;

                    $value->boton_editar = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_registro_guia();"><i class="fa fa-pencil"></i></button>';
                    if ($value->ACTIVO == 1) {
                        $value->CheckboxEstado = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_GUIAPREGUNTA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value->CheckboxEstado = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_GUIAPREGUNTA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
            break;
            case 2:
                $lista = DB::select('SELECT
                                        ID_GUIAPREGUNTA,
                                        TIPOGUIA,
                                        PREGUNTA_ID,
                                        PREGUNTA_NOMBRE,
                                        PREGUNTA_EXPLICACION,
                                        ACTIVO
                                    FROM
                                        catalogoguiaspsico
                                    WHERE 
                                        TIPOGUIA = 2');

                $COUNT = 0;
                foreach ($lista as $key => $value) {
                    $COUNT++;
                    $value->COUNT = $COUNT;

                    $value->boton_editar = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_registro_guia();"><i class="fa fa-pencil"></i></button>';
                    if ($value->ACTIVO == 1) {
                        $value->CheckboxEstado = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_GUIAPREGUNTA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value->CheckboxEstado = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_GUIAPREGUNTA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    }
                }
            break;
            case 3:
                $lista = DB::select('SELECT
                                        ID_GUIAPREGUNTA,
                                        TIPOGUIA,
                                        PREGUNTA_ID,
                                        PREGUNTA_NOMBRE,
                                        PREGUNTA_EXPLICACION,
                                        ACTIVO
                                    FROM
                                        catalogoguiaspsico
                                    WHERE 
                                        TIPOGUIA = 3');

                $COUNT = 0;
                foreach ($lista as $key => $value) {
                    $COUNT++;
                    $value->COUNT = $COUNT;

                    $value->boton_editar = '<button type="button" class="btn btn-danger btn-circle" onclick="editar_registro_guia();"><i class="fa fa-pencil"></i></button>';
                    if ($value->ACTIVO == 1) {
                        $value->CheckboxEstado = '<div class="switch"><label><input type="checkbox" checked onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_GUIAPREGUNTA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
                    } else {
                        $value->CheckboxEstado = '<div class="switch"><label><input type="checkbox" onclick="estado_registro(' . $num_catalogo . ', ' . $value->ID_GUIAPREGUNTA . ', this);"><span class="lever switch-col-light-blue"></span></label></div>';
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
        try {
            switch (($request['catalogo'] + 0)) {
               
                case 1:
                    if ($request['ID_GUIAPREGUNTA'] == 0) {

                    } else {
                        $catalogo = catalogosguiaspsicoModel::findOrFail($request['ID_GUIAPREGUNTA']);
                        $catalogo->update($request->all());
                        $dato["msj"] = 'Información modificada correctamente';
                    }
                break;
                case 2:
                    if ($request['ID_GUIAPREGUNTA'] == 0) {

                    } else {
                        $catalogo = catalogosguiaspsicoModel::findOrFail($request['ID_GUIAPREGUNTA']);
                        $catalogo->update($request->all());
                        $dato["msj"] = 'Información modificada correctamente';
                    }
                break;
                case 3:
                    if ($request['ID_GUIAPREGUNTA'] == 0) {

                    } else {
                        $catalogo = catalogosguiaspsicoModel::findOrFail($request['ID_GUIAPREGUNTA']);
                        $catalogo->update($request->all());
                        $dato["msj"] = 'Información modificada correctamente';
                    }
                break;
            }
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
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
    public function recpsicocatalogodesactiva($catalogo, $registro, $estado)
    {
        try {
            switch (($catalogo + 0)) {
                case 1:
                    $tabla = catalogosguiaspsicoModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 2:
                    $tabla = catalogosguiaspsicoModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 3:
                    $tabla = catalogosguiaspsicoModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 4:
                    $tabla = catcargos_psicoModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 5:
                    $tabla = catintroducciones_psicoModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 6:
                    $tabla = catdefiniciones_psicoModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 7:
                    $tabla = catrecomendaciones_psicoModel::findOrFail($registro);
                    $tabla->update(['ACTIVO' => $estado]);
                    break;
                case 8:
                    $tabla = catconclusiones_psicoModel::findOrFail($registro);
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

<?php

namespace App\Http\Controllers\ERGO;


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

//MODELOS
use App\modelos\proyecto\proyectoModel;
use App\modelos\reconocimientoergo\reconocimientoergoModel;
use App\modelos\recsensorial\catdepartamentoModel;
use App\modelos\recsensorial\catmovilfijoModel;

use App\modelos\reconocimientoergo\catergo_regimencontractualModel;
use App\modelos\reconocimientoergo\catergo_jornada;
use App\modelos\reconocimientoergo\catergo_turnoModel;

use App\modelos\reconocimientoergo\catergo_definicionesModel;

class reconocimientoergoController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Reportes,ApoyoTecnico,Financiero,Cadista,Externo');
        // $this->middleware('roles:Superusuario,Administrador,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,ApoyoTecnico,Reportes');

        // $this->middleware('asignacionUser')->only('store');
    }


    
    public function index()
    { //vista RECONOCIMIENTO SENSORIAL

        $catdepartamento = catdepartamentoModel::where('catdepartamento_activo', 1)->orderBy('catdepartamento_nombre', 'ASC')->get();
        $catmovilfijo = catmovilfijoModel::where('catmovilfijo_activo', 1)->get();


        $catregimen = catergo_regimencontractualModel::where('ACTIVO', 1)->get();
        $catjornada = catergo_jornada::where('ACTIVO', 1)->get();
        $caturno = catergo_turnoModel::where('ACTIVO', 1)->get();
        $catdefiniciones = catergo_definicionesModel::where('USO_DEFINICIONES', "Reconocimiento")
            ->orderBy('CONCEPTO_DEFINICION', 'ASC')
            ->get();

        return view('catalogos.ergo.reconocimiento_ergo', compact('catdepartamento', 'catmovilfijo', 'catregimen', 'catjornada', 'caturno', 'catdefiniciones'));
    }


    public function folioproyectoergp($proyecto_folio)
    {
        try {
            $opciones_select = '<option value="">&nbsp;</option>';

            $proyectos = DB::select(" SELECT 
                                    p.id, 
                                    p.proyecto_folio,
                                    p.proyecto_clienteinstalacion,
                                    p.proyecto_clientedireccionservicio,
                                    p.recsensorial_id
                                FROM 
                                    proyecto p
                                LEFT JOIN 
                                    serviciosProyecto sp ON p.id = sp.PROYECTO_ID
                                WHERE 
                                    sp.ERGO = 1
                                    AND sp.ERGO_RECONOCIMIENTO = 1
                                    AND (p.reconocimiento_ergo_id IS NULL OR p.proyecto_folio = ?) ", [$proyecto_folio]);

            foreach ($proyectos as $key => $value) {
                $displayText = '[' . htmlspecialchars($value->proyecto_folio) . '] ' . htmlspecialchars($value->proyecto_clienteinstalacion);

                if ($value->proyecto_folio == $proyecto_folio) {
                    $opciones_select .= '<option value="' . htmlspecialchars($value->proyecto_folio) . '" selected>' . $displayText . '</option>';
                } else {
                    $opciones_select .= '<option value="' . htmlspecialchars($value->proyecto_folio) . '">' . $displayText . '</option>';
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


    public function estructuraproyectosergo($FOLIO)
    {
        try {

            $estructura = DB::select("SELECT p.proyecto_folio,
                                        p.id,
                                        ce.NOMBRE_ETIQUETA,
                                        ce.ID_ETIQUETA,
                                        co.NOMBRE_OPCIONES,
                                        ep.OPCION_ID, 
                                        ep.NIVEL
                                FROM  proyecto p
                                LEFT JOIN estructuraProyectos as ep ON p.id = ep.PROYECTO_ID
                                LEFT JOIN cat_etiquetas as ce ON ep.ETIQUETA_ID = ce.ID_ETIQUETA
                                LEFT JOIN catetiquetas_opciones as co ON ep.OPCION_ID = co.ID_OPCIONES_ETIQUETAS
                                WHERE p.proyecto_folio = ? ", [$FOLIO]);


            $infoProyecto = DB::select('SELECT p.proyecto_clienteinstalacion AS INSTALACION,
            p.proyecto_clientedireccionservicio AS DIRRECCION,
            p.proyecto_clientepersonacontacto AS REPRESENTANTE,
            p.proyecto_clienterfc AS RFC,
            p.proyecto_clienterazonsocial AS RAZON_SOCIAL,
            IFNULL(p.cliente_id, (SELECT CLIENTE_ID FROM contratos_clientes WHERE ID_CONTRATO = p.contrato_id)) AS CLIENTE_ID,
            IFNULL(p.contrato_id, 0) AS CONTRATO_ID,
            IF(p.requiereContrato = 0, "No aplica", 
                CASE p.tipoServicioCliente
                    WHEN 1 THEN "Contrato"
                    WHEN 2 THEN "O.S / O.C"
                    ELSE "Cotización aceptada"
                END) AS TIPO_SERVICIO,
            IF(p.contrato_id IS NULL, 
                "El proyecto seleccionado no tiene un contrato.", 
                (SELECT CONCAT(IF(NUMERO_CONTRATO IS NULL, "", CONCAT("[ ", NUMERO_CONTRATO, " ] ")), DESCRIPCION_CONTRATO)
                    FROM contratos_clientes 
                    WHERE ID_CONTRATO = p.contrato_id)) AS NOMBRE_CONTRATO,
            -- Campos adicionales si HI_RECONOCIMIENTO es 1
                    IF(sp.HI_RECONOCIMIENTO = 1, rec.id, NULL) AS ID,
                    IF(sp.HI_RECONOCIMIENTO = 1, rec.recsensorial_representantelegal, NULL) AS REPRESENTANTE_LEGAL,
                    IF(sp.HI_RECONOCIMIENTO = 1, rec.recsensorial_coordenadas, NULL) AS COORDENADAS,
                    IF(sp.HI_RECONOCIMIENTO = 1, rec.recsensorial_ordenservicio, NULL) AS ORDENSERVICIO,
                    IF(sp.HI_RECONOCIMIENTO = 1, rec.recsensorial_codigopostal, NULL) AS CODIGOPOSTAL,
                    IF(sp.HI_RECONOCIMIENTO = 1, rec.recsensorial_actividadprincipal, NULL) AS ACTIVIDADPRINCIPAL,
                    IF(sp.HI_RECONOCIMIENTO = 1, rec.recsensorial_descripcionproceso, NULL) AS DESCRIPCIONPROCESO,
                    IF(sp.HI_RECONOCIMIENTO = 1, rec.recsensorial_obscategorias, NULL) AS OBSERVACION,
                    IF(sp.HI_RECONOCIMIENTO = 1, rec.recsensorial_fechainicio, NULL) AS FECHAINICIO,
                    IF(sp.HI_RECONOCIMIENTO = 1, rec.recsensorial_fechafin, NULL) AS FECHAFIN,
                    IF(sp.HI_RECONOCIMIENTO = 1, rec.recsensorial_fotoplano, NULL) AS FOTOPLANO,
                    IF(sp.HI_RECONOCIMIENTO = 1, rec.recsensorial_fotoubicacion, NULL) AS FOTOUBICACION,
                    IF(sp.HI_RECONOCIMIENTO = 1, rec.recsensorial_fotoinstalacion, NULL) AS FOTOINSTALACION
            FROM proyecto p
            LEFT JOIN cliente c ON c.id = p.cliente_id
            LEFT JOIN contratos_clientes cc ON cc.ID_CONTRATO = p.CONTRATO_ID
            LEFT JOIN serviciosProyecto sp ON sp.PROYECTO_ID = p.id
            LEFT JOIN recsensorial rec ON rec.id = p.recsensorial_id -- Aquí se asume que recsensorial tiene un campo proyecto_id
            WHERE p.proyecto_folio = ?', [$FOLIO]);

            $higiene = DB::select("SELECT sp.HI_RECONOCIMIENTO 
                                    FROM proyecto p LEFT JOIN serviciosProyecto sp ON sp.PROYECTO_ID = p.id 
                                    WHERE p.proyecto_folio = ?", [$FOLIO]);

            $dato['HIGIENE'] = $higiene;
            $dato['data'] = $estructura;
            $dato['info'] = $infoProyecto;
            $dato["msj"] = 'Informacion consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {

            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['data'] = 0;
            return response()->json($dato);
        }
    }


    public function tablareconocimientoergo()
    {
        try {
            // Obtener solo los campos de la tabla principal sin cargar relaciones
            $recsensorial = reconocimientoergoModel::all(); // Obtiene todos los registros de la tabla principal

            // Formatear las filas
            $numero_registro = 0;
            foreach ($recsensorial as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;
                $value->boton_mostrar = '<button type="button" class="btn btn-info btn-circle" style="padding: 0px;"><i class="fa fa-eye fa-2x"></i></button>';
            }

            // BOTON MOSTRAR [reconocimiento Bloqueado]


            // Respuesta en JSON
            $dato['data'] = $recsensorial;
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
     * @param  int  $archivo_opcion
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function mostrarmapaubicacionergo($archivo_opcion, $recsensorial_id)
    {
        $recsensorial = reconocimientoergoModel::findOrFail($recsensorial_id);

        if (($archivo_opcion + 0) == 0) {
            return Storage::response($recsensorial->fotoubicacion);
        } else {
            return Storage::download($recsensorial->fotoubicacion);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $archivo_opcion
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function mostraplanoergo($archivo_opcion, $recsensorial_id)
    {
        $recsensorial = reconocimientoergoModel::findOrFail($recsensorial_id);

        if (($archivo_opcion + 0) == 0) {
            return Storage::response($recsensorial->fotoplano);
        } else {
            return Storage::download($recsensorial->fotoplano);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $archivo_opcion
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function mostrafotoinstalacionergo($archivo_opcion, $recsensorial_id)
    {
        $recsensorial = reconocimientoergoModel::findOrFail($recsensorial_id);

        if (($archivo_opcion + 0) == 0) {
            return Storage::response($recsensorial->fotoinstalacion);
        } else {
            return Storage::download($recsensorial->fotoinstalacion);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $archivo_opcion
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function mostrarmapariesgoergo($archivo_opcion, $recsensorial_id)
    {
        $recsensorial = reconocimientoergoModel::findOrFail($recsensorial_id);

        if (($archivo_opcion + 0) == 0) {
            return Storage::response($recsensorial->fotomapariesgo);
        } else {
            return Storage::download($recsensorial->fotomapariesgo);
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // dd($request->all());


            if (($request->opcion + 0) == 1) // DATOS DEL RECONOCIMIENTO
            {
                $ano = (date('y')) + 0;
                $recsensorial_activo = 0;

                if (($request->recsensorial_id + 0) == 0) //nuevo
                {

                    DB::statement('ALTER TABLE reconocimientoergo AUTO_INCREMENT=1');

                    //Verificamos si el reconocimiento requiere contrato de no requerir autorizado lo ponemos como 0 para que deba ser autorizado
                    if (intval($request->requiere_contrato) == 1) {
                        $request['autorizado'] = 1;
                        $request['recsensorial_bloqueado'] = 0;
                    } else {
                        $request['recsensorial_bloqueado'] = 1;
                        $request['autorizado'] = 0;
                        $request['contrato_id'] = 0;
                    }

                    $reconocimientoergo = reconocimientoergoModel::create($request->all());
                    // $recsensorial->recsensorialpruebas()->sync($request->parametro); // SE COMENTO PORQUE YA SON DOS ARREGLOS DE PRUEBAS ENTONCES SI HIZO APARTE

                    //UNA VEZ GUARDADO TODO LO DE RECONOCIMIENTO PROCEDEMOS A VINCULAR EL  ID DEL RECONOCIMIENTO CON EL PROYECTO
                    $proyecto = proyectoModel::where('proyecto_folio', $request["proyecto_folio"])->first();
                    $proyecto->reconocimiento_ergo_id = $reconocimientoergo->id;
                    $proyecto->save();


                    // mensaje
                    $dato["msj"] = 'Información guardada correctamente y vinculado con el proyecto: ' . $request["proyecto_folio"];
                    $recsensorial_activo = 1;


                    // si envia archivo FOTO ubicacion

                    // if ($request->file('inputfotomapa')) {
                    //     $extension = $request->file('inputfotomapa')->getClientOriginalExtension();
                    //     $request['fotoubicacion'] = $request->file('inputfotomapa')->storeAs('reconocimiento_ergo/' . $reconocimientoergo->id . '/mapa', $reconocimientoergo->id . '.' . $extension);
                    //     $reconocimientoergo->update($request->all());
                    // } else {
                    //     $recsensorial_extension = $request['hidden_fotomapa_extension'];
                    //     $recsensorial_id = $request['hidden_fotomapa'];
                    //     $rutaOriginal = 'recsensorial/' . $recsensorial_id . '/mapa/' . $recsensorial_id . $recsensorial_extension;

                    //     if (Storage::exists($rutaOriginal)) {
                    //         // Asegúrate de crear el directorio si no existe
                    //         $nuevaRuta = 'reconocimiento_ergo/' . $reconocimientoergo->id . '/mapa/' . $reconocimientoergo->id . '.' . pathinfo($rutaOriginal, PATHINFO_EXTENSION);

                    //         Storage::makeDirectory('reconocimiento_ergo/' . $reconocimientoergo->id . '/mapa');

                    //         // Copiar la imagen a la nueva ubicación
                    //         Storage::copy($rutaOriginal, $nuevaRuta);

                    //         // Actualiza la base de datos con la nueva ruta
                    //         $reconocimientoergo->fotoubicacion = $nuevaRuta;
                    //         $reconocimientoergo->update($request->all());
                    //     } else {
                    //         // Manejar caso en el que la imagen original no existe
                    //         // Puedes lanzar una excepción o asignar un valor predeterminado
                    //         throw new Exception("La imagen original no existe en la ruta: " . $rutaOriginal);
                    //     }
                    // }


                    if ($request->file('inputfotomapa')) {

                        $extension = $request->file('inputfotomapa')->getClientOriginalExtension();

                        $request['fotoubicacion'] = $request->file('inputfotomapa')
                            ->storeAs('reconocimiento_ergo/' . $reconocimientoergo->id . '/mapa', $reconocimientoergo->id . '.' . $extension);

                        $reconocimientoergo->update($request->all());
                    } else {

                        if (!empty($request['hidden_fotomapa']) && !empty($request['hidden_fotomapa_extension'])) {

                            $recsensorial_id = $request['hidden_fotomapa'];
                            $recsensorial_extension = $request['hidden_fotomapa_extension'];

                            $rutaOriginal = 'recsensorial/' . $recsensorial_id . '/mapa/' . $recsensorial_id . $recsensorial_extension;

                            if (Storage::exists($rutaOriginal)) {

                                $nuevaRuta = 'reconocimiento_ergo/' . $reconocimientoergo->id . '/mapa/' . $reconocimientoergo->id . '.' . pathinfo($rutaOriginal, PATHINFO_EXTENSION);

                                Storage::makeDirectory('reconocimiento_ergo/' . $reconocimientoergo->id . '/mapa');
                                Storage::copy($rutaOriginal, $nuevaRuta);

                                $reconocimientoergo->fotoubicacion = $nuevaRuta;
                                $reconocimientoergo->save();
                            }
                        }
                    }




                    // si envia archivo FOTO plano
                    // if ($request->file('inputfotoplano')) {
                    //     $extension = $request->file('inputfotoplano')->getClientOriginalExtension();
                    //     $request['fotoplano'] = $request->file('inputfotoplano')->storeAs('reconocimiento_ergo/' . $reconocimientoergo->id . '/plano', $reconocimientoergo->id . '.' . $extension);
                    //     $reconocimientoergo->update($request->all());
                    // } else {
                    //     $recsensorial_extension = $request['hidden_fotoplano_extension'];
                    //     $recsensorial_id = $request['hidden_fotoplano'];
                    //     $rutaOriginal = 'recsensorial/' . $recsensorial_id . '/plano/' . $recsensorial_id . $recsensorial_extension;

                    //     if (Storage::exists($rutaOriginal)) {
                    //         // Asegúrate de crear el directorio si no existe
                    //         $nuevaRuta = 'reconocimiento_ergo/' . $reconocimientoergo->id . '/plano/' . $reconocimientoergo->id . '.' . pathinfo($rutaOriginal, PATHINFO_EXTENSION);

                    //         Storage::makeDirectory('reconocimiento_ergo/' . $reconocimientoergo->id . '/plano');

                    //         // Copiar la imagen a la nueva ubicación
                    //         Storage::copy($rutaOriginal, $nuevaRuta);

                    //         // Actualiza la base de datos con la nueva ruta
                    //         $reconocimientoergo->fotoplano = $nuevaRuta;
                    //         $reconocimientoergo->update($request->all());
                    //     } else {
                    //         // Manejar caso en el que la imagen original no existe
                    //         // Puedes lanzar una excepción o asignar un valor predeterminado
                    //         throw new Exception("La imagen original no existe en la ruta: " . $rutaOriginal);
                    //     }
                    // }

                    if ($request->file('inputfotoplano')) {

                        $extension = $request->file('inputfotoplano')->getClientOriginalExtension();

                        $request['fotoplano'] = $request->file('inputfotoplano')
                            ->storeAs('reconocimiento_ergo/' . $reconocimientoergo->id . '/plano', $reconocimientoergo->id . '.' . $extension);

                        $reconocimientoergo->update($request->all());
                    } else {

                        if (!empty($request['hidden_fotoplano']) && !empty($request['hidden_fotoplano_extension'])) {

                            $recsensorial_id = $request['hidden_fotoplano'];
                            $recsensorial_extension = $request['hidden_fotoplano_extension'];

                            $rutaOriginal = 'recsensorial/' . $recsensorial_id . '/plano/' . $recsensorial_id . $recsensorial_extension;

                            if (Storage::exists($rutaOriginal)) {

                                $nuevaRuta = 'reconocimiento_ergo/' . $reconocimientoergo->id . '/plano/' . $reconocimientoergo->id . '.' . pathinfo($rutaOriginal, PATHINFO_EXTENSION);

                                Storage::makeDirectory('reconocimiento_ergo/' . $reconocimientoergo->id . '/plano');
                                Storage::copy($rutaOriginal, $nuevaRuta);

                                $reconocimientoergo->fotoplano = $nuevaRuta;
                                $reconocimientoergo->save();
                            }
                        }
                    }



                    // si envia archivo FOTO instalacion


                    // if ($request->file('inputfotoinstalacion')) {
                    //     $extension = $request->file('inputfotoinstalacion')->getClientOriginalExtension();
                    //     $request['fotoinstalacion'] = $request->file('inputfotoinstalacion')->storeAs('reconocimiento_ergo/' . $reconocimientoergo->id . '/instalacion', $reconocimientoergo->id . '.' . $extension);
                    //     $reconocimientoergo->update($request->all());
                    // } else {
                    //     $recsensorial_extension = $request['hidden_fotoinstalacion_extension'];
                    //     $recsensorial_id = $request['hidden_fotoinstalacion'];
                    //     $rutaOriginal = 'recsensorial/' . $recsensorial_id . '/instalacion/' . $recsensorial_id . $recsensorial_extension;

                    //     if (Storage::exists($rutaOriginal)) {
                    //         // Asegúrate de crear el directorio si no existe
                    //         $nuevaRuta = 'reconocimiento_ergo/' . $reconocimientoergo->id . '/instalacion/' . $reconocimientoergo->id . '.' . pathinfo($rutaOriginal, PATHINFO_EXTENSION);

                    //         Storage::makeDirectory('reconocimiento_ergo/' . $reconocimientoergo->id . '/instalacion');

                    //         // Copiar la imagen a la nueva ubicación
                    //         Storage::copy($rutaOriginal, $nuevaRuta);

                    //         // Actualiza la base de datos con la nueva ruta
                    //         $reconocimientoergo->fotoinstalacion = $nuevaRuta;
                    //         $reconocimientoergo->update($request->all());
                    //     } else {
                    //         // Manejar caso en el que la imagen original no existe
                    //         // Puedes lanzar una excepción o asignar un valor predeterminado
                    //         throw new Exception("La imagen original no existe en la ruta: " . $rutaOriginal);
                    //     }
                    // }


                    if ($request->file('inputfotoinstalacion')) {

                        $extension = $request->file('inputfotoinstalacion')->getClientOriginalExtension();

                        $request['fotoinstalacion'] = $request->file('inputfotoinstalacion')
                            ->storeAs('reconocimiento_ergo/' . $reconocimientoergo->id . '/instalacion', $reconocimientoergo->id . '.' . $extension);

                        $reconocimientoergo->update($request->all());
                    } else {

                        if (!empty($request['hidden_fotoinstalacion']) && !empty($request['hidden_fotoinstalacion_extension'])) {

                            $recsensorial_id = $request['hidden_fotoinstalacion'];
                            $recsensorial_extension = $request['hidden_fotoinstalacion_extension'];

                            $rutaOriginal = 'recsensorial/' . $recsensorial_id . '/instalacion/' . $recsensorial_id . $recsensorial_extension;

                            if (Storage::exists($rutaOriginal)) {

                                $nuevaRuta = 'reconocimiento_ergo/' . $reconocimientoergo->id . '/instalacion/' . $reconocimientoergo->id . '.' . pathinfo($rutaOriginal, PATHINFO_EXTENSION);

                                Storage::makeDirectory('reconocimiento_ergo/' . $reconocimientoergo->id . '/instalacion');
                                Storage::copy($rutaOriginal, $nuevaRuta);

                                $reconocimientoergo->fotoinstalacion = $nuevaRuta;
                                $reconocimientoergo->save();
                            }
                        }
                    }


                    // // si envia archivo MAPA DE RIESGO

                    // if ($request->file('inputfotomapaderiesgo')) {
                    //     $extension = $request->file('inputfotomapaderiesgo')->getClientOriginalExtension();
                    //     $request['fotomapariesgo'] = $request->file('inputfotomapaderiesgo')->storeAs('reconocimiento_ergo/' . $reconocimientoergo->id . '/mapa de riesgo', $reconocimientoergo->id . '.' . $extension);
                    //     $reconocimientoergo->update($request->all());
                    // } else {
                    //     $recsensorial_extension = $request['hidden_fotomapariesgo_extension'];
                    //     $recsensorial_id = $request['hidden_fotomapariesgo'];
                    //     $rutaOriginal = 'recsensorial/' . $recsensorial_id . '/mapa de riesgo/' . $recsensorial_id . $recsensorial_extension;

                    //     if (Storage::exists($rutaOriginal)) {
                    //         // Asegúrate de crear el directorio si no existe
                    //         $nuevaRuta = 'reconocimiento_ergo/' . $reconocimientoergo->id . '/mapa de riesgo/' . $reconocimientoergo->id . '.' . pathinfo($rutaOriginal, PATHINFO_EXTENSION);

                    //         Storage::makeDirectory('reconocimiento_ergo/' . $reconocimientoergo->id . '/mapa de riesgo');

                    //         // Copiar la imagen a la nueva ubicación
                    //         Storage::copy($rutaOriginal, $nuevaRuta);

                    //         // Actualiza la base de datos con la nueva ruta
                    //         $reconocimientoergo->fotomapariesgo = $nuevaRuta;
                    //         $reconocimientoergo->update($request->all());
                    //     } else {
                    //         // Manejar caso en el que la imagen original no existe
                    //         // Puedes lanzar una excepción o asignar un valor predeterminado
                    //         throw new Exception("La imagen original no existe en la ruta: " . $rutaOriginal);
                    //     }
                    // }


                    if ($request->file('inputfotomapaderiesgo')) {

                        $extension = $request->file('inputfotomapaderiesgo')->getClientOriginalExtension();

                        $request['fotomapariesgo'] = $request->file('inputfotomapaderiesgo')
                            ->storeAs('reconocimiento_ergo/' . $reconocimientoergo->id . '/mapa de riesgo', $reconocimientoergo->id . '.' . $extension);

                        $reconocimientoergo->update($request->all());
                    } else {

                        if (!empty($request['hidden_fotomapariesgo']) && !empty($request['hidden_fotomapariesgo_extension'])) {

                            $recsensorial_id = $request['hidden_fotomapariesgo'];
                            $recsensorial_extension = $request['hidden_fotomapariesgo_extension'];

                            $rutaOriginal = 'recsensorial/' . $recsensorial_id . '/mapa de riesgo/' . $recsensorial_id . $recsensorial_extension;

                            if (Storage::exists($rutaOriginal)) {

                                $nuevaRuta = 'reconocimiento_ergo/' . $reconocimientoergo->id . '/mapa de riesgo/' . $reconocimientoergo->id . '.' . pathinfo($rutaOriginal, PATHINFO_EXTENSION);

                                Storage::makeDirectory('reconocimiento_ergo/' . $reconocimientoergo->id . '/mapa de riesgo');
                                Storage::copy($rutaOriginal, $nuevaRuta);

                                $reconocimientoergo->fotomapariesgo = $nuevaRuta;
                                $reconocimientoergo->save();
                            }
                        }
                    }



                } else { //EDITAR 

                    // Obtener registro
                    $reconocimientoergo = reconocimientoergoModel::findOrFail($request->recsensorial_id);

                    // consultar ID ultimo registro de la tabla
                    $reconocimientoergo_idmax = DB::select('SELECT
                                                            MAX( reconocimientoergo.id ) AS reconocimientoergo_idmax
                                                        FROM
                                                            reconocimientoergo');

                    // Validar que sea el ultimo ID, y permita editar folios

                    $reconocimientoergo->update($request->all());
                    // $recsensorial->recsensorialpruebas()->sync($request->parametro);

                    ///VERIFICAMOS QUE EL FOLIO DEL PROYECTO QUE ENVIA SEA EL MISMO
                    if ($reconocimientoergo->proyecto_folio == $request['proyecto_folio']) {

                        $proyecto = proyectoModel::where('proyecto_folio', $request["proyecto_folio"])->first();
                        $proyecto->reconocimiento_ergo_id = $reconocimientoergo->id;
                        $proyecto->save();
                    } else {


                        $proyecto = proyectoModel::where('proyecto_folio', $reconocimientoergo->proyecto_folio)->first();
                        $proyecto->reconocimiento_ergo_id = null;
                        $proyecto->save();


                        $proyecto = proyectoModel::where('proyecto_folio', $request["proyecto_folio"])->first();
                        $proyecto->reconocimiento_ergo_id = $reconocimientoergo->id;
                        $proyecto->save();
                    }



                   

                    function eliminarArchivoAntiguo($id, $folder)
                    {
                        // Definir la ruta del directorio
                        $directory = 'reconocimiento_ergo/' . $id . '/' . $folder;

                        // Buscar y eliminar cualquier archivo con el mismo nombre, pero con diferente extensión
                        $files = Storage::files($directory);
                        foreach ($files as $file) {
                            // Verificar si el archivo coincide con el nombre, independientemente de la extensión
                            if (pathinfo($file, PATHINFO_FILENAME) == $id) {
                                Storage::delete($file);
                            }
                        }
                    }

                    if ($request->file('inputfotomapa')) {
                        $extension = $request->file('inputfotomapa')->getClientOriginalExtension();
                        $folder = 'mapa';
                        $path = 'reconocimiento_ergo/' . $reconocimientoergo->id . '/' . $folder . '/' . $reconocimientoergo->id . '.' . $extension;

                        // Eliminar cualquier archivo antiguo, sin importar la extensión
                        eliminarArchivoAntiguo($reconocimientoergo->id, $folder);

                        // Guardar la nueva foto
                        $request['fotoubicacion'] = $request->file('inputfotomapa')->storeAs('reconocimiento_ergo/' . $reconocimientoergo->id . '/' . $folder, $reconocimientoergo->id . '.' . $extension);
                        $reconocimientoergo->update($request->all());
                    }

                    // Para el archivo FOTO plano
                    if ($request->file('inputfotoplano')) {
                        $extension = $request->file('inputfotoplano')->getClientOriginalExtension();
                        $folder = 'plano';
                        $path = 'reconocimiento_ergo/' . $reconocimientoergo->id . '/' . $folder . '/' . $reconocimientoergo->id . '.' . $extension;

                        eliminarArchivoAntiguo($reconocimientoergo->id, $folder);

                        $request['fotoplano'] = $request->file('inputfotoplano')->storeAs('reconocimiento_ergo/' . $reconocimientoergo->id . '/' . $folder, $reconocimientoergo->id . '.' . $extension);
                        $reconocimientoergo->update($request->all());
                    }

                    // Para el archivo FOTO instalación
                    if ($request->file('inputfotoinstalacion')) {
                        $extension = $request->file('inputfotoinstalacion')->getClientOriginalExtension();
                        $folder = 'instalacion';
                        $path = 'reconocimiento_ergo/' . $reconocimientoergo->id . '/' . $folder . '/' . $reconocimientoergo->id . '.' . $extension;

                        eliminarArchivoAntiguo($reconocimientoergo->id, $folder);

                        $request['fotoinstalacion'] = $request->file('inputfotoinstalacion')->storeAs('reconocimiento_ergo/' . $reconocimientoergo->id . '/' . $folder, $reconocimientoergo->id . '.' . $extension);
                        $reconocimientoergo->update($request->all());
                    }

                    // // Para el archivo FOTO instalación
                    if ($request->file('inputfotomapaderiesgo')) {
                        $extension = $request->file('inputfotomapaderiesgo')->getClientOriginalExtension();
                        $folder = 'mapa de riesgo';
                        $path = 'reconocimiento_ergo/' . $reconocimientoergo->id . '/' . $folder . '/' . $reconocimientoergo->id . '.' . $extension;

                        eliminarArchivoAntiguo($reconocimientoergo->id, $folder);

                        $request['fotomapariesgo'] = $request->file('inputfotomapaderiesgo')->storeAs('reconocimiento_ergo/' . $reconocimientoergo->id . '/' . $folder, $reconocimientoergo->id . '.' . $extension);
                        $reconocimientoergo->update($request->all());
                    }
                    
                    // mensaje
                    $dato["msj"] = 'Información modificada correctamente';
                }


                // respuesta
                $dato['recsensorial_activo'] = $recsensorial_activo;
                $dato['recsensorial'] = $reconocimientoergo;
            }



            if (($request->opcion + 0) == 3) // RESPONSABLES DEL RECONOCIMIENTO
            {

                $reconocimientoergo = reconocimientoergoModel::findOrFail($request->recsensorial_id);

                // dd($recsensorial->all());

                if ($request->NOMBRE_TECNICO) // RESPONSABLES DEL RECONOCIMIENTO
                {
                    if ($request->file('TECNICO_DOC_IMG')) {
                        $extension = $request->file('TECNICO_DOC_IMG')->getClientOriginalExtension();
                        $request['TECNICO_DOC'] = $request->file('TECNICO_DOC_IMG')->storeAs('reconocimiento_ergo/' . $request->recsensorial_id . '/responsables', 'rep_tecnico.' . $extension);
                    }

                    if ($request->file('CONTRATO_DOC_IMG')) {
                        $extension = $request->file('CONTRATO_DOC_IMG')->getClientOriginalExtension();
                        $request['CONTRATO_DOC'] = $request->file('CONTRATO_DOC_IMG')->storeAs('reconocimiento_ergo/' . $request->recsensorial_id . '/responsables', 'rep_admin.' . $extension);
                    }
                } else {
                    // Eliminar carpeta si acaso existio
                    Storage::deleteDirectory('reconocimiento_ergo/' . $request->recsensorial_id . '/responsables');

                    $request['NOMBRE_TECNICO'] = NULL;
                    $request['NOMBRE_CONTRATO'] = NULL;
                    $request['CARGO_TECNICO'] = NULL;
                    $request['CARGO_CONTRATO'] = NULL;
                    $request['TECNICO_DOC'] = NULL;
                    $request['CONTRATO_DOC'] = NULL;
                }

                $reconocimientoergo->update($request->all());

                // respuesta
                $dato["msj"] = 'Datos de los responsables guardado correctamente';
                $dato['recsensorial'] = $reconocimientoergo;
            }
            if (($request->opcion + 0) == 5) // PREGUNTAS DE GUIA 5
            {
                $dato['code'] = 200;
                $dato["msj"] = 'Información modificada correctamente';
            }

            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['recsensorial'] = 0;
            $dato["recsensorial_bloqueado"] = 0;
            return response()->json($dato);
        }
    }



    }

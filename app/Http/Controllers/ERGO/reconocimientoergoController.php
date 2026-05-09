<?php

namespace App\Http\Controllers\ERGO;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Image;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;

use DB;
use Artisan;
use Exception;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\TemplateProcessor;




// Plugins
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Element\Chart;

use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\TablePosition;

use ZipArchive;




//MODELOS
use App\modelos\proyecto\proyectoModel;
use App\modelos\reconocimientoergo\reconocimientoergoModel;
use App\modelos\recsensorial\catdepartamentoModel;
use App\modelos\recsensorial\catmovilfijoModel;

use App\modelos\reconocimientoergo\catergo_regimencontractualModel;
use App\modelos\reconocimientoergo\catergo_jornada;
use App\modelos\reconocimientoergo\catergo_turnoModel;

use App\modelos\reconocimientoergo\catergo_definicionesModel;


use App\modelos\reconocimientoergo\recursosPortadaRecoErgoModel;

use App\modelos\reconocimientoergo\catergo_introduccionModel;


use App\modelos\reconocimientoergo\datosgeneralesinformeRecoModel;


use App\modelos\reconocimientoergo\definicionesinformeergoModel;


use App\modelos\reconocimientoergo\recoergocategoriasModel;
use App\modelos\reconocimientoergo\recoergoareasModel;

use App\modelos\reconocimientoergo\catergo_conclusionModel;

use App\modelos\reconocimientoergo\catergo_recomendacionesModel;


use App\modelos\reconocimientoergo\recomendacionesinformeergoModel;



use App\modelos\reconocimientoergo\versionesrecoergoModel;


use App\modelos\clientes\clientecontratoModel;

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
        $catdefiniciones = catergo_definicionesModel::whereIn('USO_DEFINICIONES', ['Reconocimiento', 'Ambos'])
            ->orderBy('CONCEPTO_DEFINICION', 'ASC')
            ->get();


        $catintroduccion = catergo_introduccionModel::where('ACTIVO', 1)->get();

        $catconclusion = catergo_conclusionModel::where('ACTIVO', 1)->get();

        $catrecomendaciones = catergo_recomendacionesModel::whereIn('USO_RECOMENDACIONES', ['Reconocimiento', 'Ambos'])->get();
        
        return view('catalogos.ergo.reconocimiento_ergo', compact('catdepartamento', 'catmovilfijo', 'catregimen', 'catjornada', 'caturno', 'catdefiniciones', 'catintroduccion', 'catconclusion', 'catrecomendaciones'));
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
            $recsensorial = reconocimientoergoModel::all(); 

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


    /////// INFORME ERGO /////////


    public function getGraficaErgo($reco_id)
    {
        $datos = DB::table('recoergo_fichastecnicas as f')
            ->join('recoergocategorias as c', 'f.CATEGORIA_ID_FICHA', '=', 'c.ID_CATEGORIA_ERGO')
            ->select(
                'f.CATEGORIA_ID_FICHA',
                'c.NOMBRE_CATEGORIA_ERGO',
                DB::raw("
                CASE 
                    WHEN SUM(CASE WHEN f.P1_CARGA_MAYOR_3KG = 'SI' THEN 1 ELSE 0 END) > 0 
                    THEN 'SI' 
                    ELSE 'NO' 
                END as RESULTADO
            ")
            )
            ->where('f.RECO_ID', $reco_id)
            ->where('f.ACTIVO', 1)
            ->groupBy('f.CATEGORIA_ID_FICHA', 'c.NOMBRE_CATEGORIA_ERGO')
            ->orderBy('c.NOMBRE_CATEGORIA_ERGO', 'ASC')
            ->get();

        return response()->json($datos);
    }



    public function obtenerDatosInformesRecoergo($ID)
    {
        try {

            $opciones_select = '<option value="">&nbsp;</option>';
            $html  = '<option value="">&nbsp;</option>';

            $info = DB::select('SELECT 
                                ID_RECURSO_INFORME,
                                RECO_ID,
                                AGENTE_ID,
                                NORMA_ID,
                                RUTA_IMAGEN_PORTADA,
                                OPCION_PORTADA1,
                                OPCION_PORTADA2,
                                OPCION_PORTADA3,
                                OPCION_PORTADA4,
                                OPCION_PORTADA5,
                                OPCION_PORTADA6,                                        
                                NIVEL1,
                                NIVEL2,
                                NIVEL3,
                                NIVEL4,
                                NIVEL5,
                                INFORME_MES,
                                INFORME_ANIO
                            FROM recursosPortadasRecoErgo
                            WHERE RECO_ID = ?', [$ID]);



            $niveles = DB::select('

            SELECT 
                "Instalación" ETIQUETA,
                p.proyecto_clienteinstalacion OPCION,
                0 NIVEL
            FROM reconocimientoergo re
            LEFT JOIN proyecto p 
                ON p.proyecto_folio = re.proyecto_folio
            WHERE re.id = ?

            UNION

            SELECT
                IFNULL(ce.NOMBRE_ETIQUETA, "NO") AS ETIQUETA,
                IFNULL(co.NOMBRE_OPCIONES , "NO") AS OPCION, 
                IFNULL(ep.NIVEL, 0) NIVEL
            FROM reconocimientoergo re
            LEFT JOIN proyecto p 
                ON p.proyecto_folio = re.proyecto_folio
            LEFT JOIN estructuraProyectos ep 
                ON p.id = ep.PROYECTO_ID
            LEFT JOIN cat_etiquetas ce 
                ON ep.ETIQUETA_ID = ce.ID_ETIQUETA
            LEFT JOIN catetiquetas_opciones co 
                ON ep.OPCION_ID = co.ID_OPCIONES_ETIQUETAS
            WHERE re.id = ?

            UNION

            SELECT 
                "Folio" ETIQUETA,
                p.proyecto_folio OPCION,
                0 NIVEL
            FROM reconocimientoergo re
            LEFT JOIN proyecto p 
                ON p.proyecto_folio = re.proyecto_folio
            WHERE re.id = ?

            UNION

            SELECT
                "Razón social" ETIQUETA,
                p.proyecto_clienterazonsocial OPCION,
                0 NIVEL
            FROM reconocimientoergo re
            LEFT JOIN proyecto p 
                ON p.proyecto_folio = re.proyecto_folio
            WHERE re.id = ?

            UNION

            SELECT 
                "Nombre comercial" ETIQUETA,
                c.cliente_NombreComercial OPCION,
                0 NIVEL
            FROM reconocimientoergo re
            LEFT JOIN proyecto p 
                ON p.proyecto_folio = re.proyecto_folio
            LEFT JOIN cliente c 
                ON p.cliente_id = c.id
            WHERE re.id = ?

            ORDER BY NIVEL

        ', [$ID, $ID, $ID, $ID, $ID]);



            foreach ($niveles as $key => $value) {

                if ($value->ETIQUETA == 'NO') {

                    $opciones_select .= '<option value="" disabled>
                                        Proyecto vinculado sin Estructura organizacional para mostrar
                                     </option>';
                } else {

                    if ($value->NIVEL == 0) {

                        $opciones_select .= '<option value="' . $value->OPCION . '"  >
                                            ' . $value->ETIQUETA . ' : ' . $value->OPCION  . '
                                         </option>';
                    } else {

                        $opciones_select .= '<option value="' . $value->OPCION . '"  >
                                            ' . $value->ETIQUETA . ' : ' . $value->OPCION . '
                                            [ Nivel ' . $value->NIVEL . ']
                                         </option>';
                    }
                }
            }



            foreach ($niveles as $key => $value) {

                if ($value->ETIQUETA == 'Instalación' || $value->NIVEL != 0) {

                    $html .= '<option value="' . $value->OPCION . '">'
                        . $value->ETIQUETA . ' : ' . $value->OPCION;

                    if ($value->NIVEL != 0) {

                        $html .= ' [ Nivel ' . $value->NIVEL . ']';
                    }

                    $html .= '</option>';
                }
            }


            $dato["opciones"] = $opciones_select;
            $dato["checks"] = $html;


            if ($info) {

                $dato["data"] = $info;
                return response()->json($dato);
            } else {

                $dato["data"] = 'No se encontraron datos';
                return response()->json($dato);
            }
        } catch (Exception $e) {

            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato, 500);
        }
    }





    public function guardarPortadaRecoErgo(Request $request)
    {
        try {

            DB::beginTransaction();


            //---------------------------------------
            // BUSCAR REGISTRO
            //---------------------------------------

            $recurso = recursosPortadaRecoErgoModel::where(
                'RECO_ID',
                $request->RECO_ID
            )->first();



            //---------------------------------------
            // SI NO EXISTE
            //---------------------------------------

            if (!$recurso) {

                $recurso = new recursosPortadaRecoErgoModel ();

                $recurso->RECO_ID = $request->RECO_ID;

                $recurso->save();
            }



            //---------------------------------------
            // DATOS
            //---------------------------------------

            $recurso->NIVEL1 = $request->NIVEL1;
            $recurso->NIVEL2 = $request->NIVEL2;
            $recurso->NIVEL3 = $request->NIVEL3;
            $recurso->NIVEL4 = $request->NIVEL4;
            $recurso->NIVEL5 = $request->NIVEL5;

            $recurso->OPCION_PORTADA1 = $request->OPCION_PORTADA1;
            $recurso->OPCION_PORTADA2 = $request->OPCION_PORTADA2;
            $recurso->OPCION_PORTADA3 = $request->OPCION_PORTADA3;
            $recurso->OPCION_PORTADA4 = $request->OPCION_PORTADA4;
            $recurso->OPCION_PORTADA5 = $request->OPCION_PORTADA5;
            $recurso->OPCION_PORTADA6 = $request->OPCION_PORTADA6;

            $recurso->INFORME_MES = $request->INFORME_MES;
            $recurso->INFORME_ANIO = $request->INFORME_ANIO;



            //---------------------------------------
            // SI ENVIA IMAGEN
            //---------------------------------------

            if ($request->file('RUTA_IMAGEN_PORTADA')) {

                $extension = $request->file('RUTA_IMAGEN_PORTADA')
                    ->getClientOriginalExtension();



                //---------------------------------------
                // GUARDAR ARCHIVO EN STORAGE
                //---------------------------------------

                $ruta = $request->file('RUTA_IMAGEN_PORTADA')->storeAs(

                    'reconocimiento_ergo/' .
                        $request->RECO_ID .
                        '/foto_portada',

                    $request->RECO_ID . '.' . $extension

                );



                //---------------------------------------
                // GUARDAR RUTA EN BD
                //---------------------------------------

                $recurso->RUTA_IMAGEN_PORTADA = $ruta;
            }



            //---------------------------------------
            // SAVE
            //---------------------------------------

            $recurso->save();



            DB::commit();


            return response()->json([
                'msj' => 'Información guardada correctamente'
            ]);
        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'msj' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function mostrarportadarecoergo($archivo_opcion, $reco_id)
    {
        $recurso = recursosPortadaRecoErgoModel::where(
            'RECO_ID',
            $reco_id
        )->firstOrFail();



        if (($archivo_opcion + 0) == 0) {

            return Storage::response(
                $recurso->RUTA_IMAGEN_PORTADA
            );
        } else {

            return Storage::download(
                $recurso->RUTA_IMAGEN_PORTADA
            );
        }
    }

    public function obtenerDatosGeneralesInformeReco($RECO_ID)
    {
        try {

            $dato = datosgeneralesinformeRecoModel::where(
                'RECO_ID',
                $RECO_ID
            )->first();



            if ($dato) {

                return response()->json($dato);
            } else {

                return response()->json([
                    'msj' => 'No se encontraron datos'
                ]);
            }
        } catch (Exception $e) {

            return response()->json([
                'msj' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function guardarIntroduccionRecoErgo(Request $request)
    {
        try {

            DB::beginTransaction();



            $dato = datosgeneralesinformeRecoModel::where(
                'RECO_ID',
                $request->RECO_ID
            )->first();
           
            if (!$dato) {

                $dato = new datosgeneralesinformeRecoModel();

                $dato->RECO_ID = $request->RECO_ID;
            }



    
            $dato->SELECT_INTRODUCCION =
                $request->SELECT_INTRODUCCION;

            $dato->INFORME_INTRODUCCION =
                $request->INFORME_INTRODUCCION;


            $dato->save();

            DB::commit();


            return response()->json([
                'msj' => 'Introducción guardada correctamente'
            ]);
        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'msj' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function guardarDefinicionesInformeErgo(Request $request)
    {
        try {

            DB::beginTransaction();



            //---------------------------------------
            // ELIMINAR ANTERIORES DEL RECO_ID
            //---------------------------------------

            definicionesinformeergoModel::where(
                'RECO_ID',
                $request->RECO_ID
            )->delete();



            //---------------------------------------
            // SI VIENEN DEFINICIONES
            //---------------------------------------

            if ($request->DEFINICONES_INFORME) {

                foreach ($request->DEFINICONES_INFORME as $definicion) {

                    $dato = new definicionesinformeergoModel();

                    $dato->RECO_ID = $request->RECO_ID;

                    $dato->CATALOGO_DEFINICIONES_ID = $definicion;

                    $dato->save();
                }
            }



            DB::commit();



            return response()->json([
                'msj' => 'Definiciones guardadas correctamente'
            ]);
        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'msj' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function obtenerDefinicionesInformeErgo($RECO_ID)
    {

        $datos = definicionesinformeergoModel::where(
            'RECO_ID',
            $RECO_ID
        )->get();



        return response()->json($datos);
    }

    public function guardarObjetivoGeneralRecoErgo(Request $request)
    {
        try {

            DB::beginTransaction();



            $dato = datosgeneralesinformeRecoModel::where(
                'RECO_ID',
                $request->RECO_ID
            )->first();



            if (!$dato) {

                $dato = new datosgeneralesinformeRecoModel();

                $dato->RECO_ID = $request->RECO_ID;
            }



            $dato->INFORME_OBJETIVOGENERALES =
                $request->INFORME_OBJETIVOGENERALES;



            $dato->save();



            DB::commit();



            return response()->json([
                'msj' => 'Objetivo general guardado correctamente'
            ]);
        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'msj' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function guardarObjetivoEspecificoRecoErgo(Request $request)
    {
        try {

            DB::beginTransaction();



            $dato = datosgeneralesinformeRecoModel::where(
                'RECO_ID',
                $request->RECO_ID
            )->first();



            if (!$dato) {

                $dato = new datosgeneralesinformeRecoModel();

                $dato->RECO_ID = $request->RECO_ID;
            }



            $dato->INFORME_OBJETIVOSESPECIFICOS =
                $request->INFORME_OBJETIVOSESPECIFICOS;



            $dato->save();



            DB::commit();



            return response()->json([
                'msj' => 'Objetivos específicos guardados correctamente'
            ]);
        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'msj' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    public function guardarUbicacionRecoErgo(Request $request)
    {
        try {

            DB::beginTransaction();


            //---------------------------------------
            // BUSCAR REGISTRO
            //---------------------------------------

            $dato = datosgeneralesinformeRecoModel::where(
                'RECO_ID',
                $request->RECO_ID
            )->first();



            //---------------------------------------
            // SI NO EXISTE
            //---------------------------------------

            if (!$dato) {

                $dato = new datosgeneralesinformeRecoModel();

                $dato->RECO_ID = $request->RECO_ID;

                $dato->save();
            }



            //---------------------------------------
            // TEXTO
            //---------------------------------------

            $dato->INFORME_UBICACIONINSTALACION =
                $request->INFORME_UBICACIONINSTALACION;



            //---------------------------------------
            // SI ENVIA IMAGEN
            //---------------------------------------

            if ($request->file('RUTA_IMAGEN_UBICACION')) {

                $extension = $request->file('RUTA_IMAGEN_UBICACION')
                    ->getClientOriginalExtension();



                //---------------------------------------
                // GUARDAR ARCHIVO EN STORAGE
                //---------------------------------------

                $ruta = $request->file('RUTA_IMAGEN_UBICACION')->storeAs(

                    'reconocimiento_ergo/' .
                        $request->RECO_ID .
                        '/foto_ubicacion',

                    $request->RECO_ID . '.' . $extension

                );



                //---------------------------------------
                // GUARDAR RUTA EN BD
                //---------------------------------------

                $dato->RUTA_IMAGEN_UBICACION = $ruta;
            }



            //---------------------------------------
            // SAVE
            //---------------------------------------

            $dato->save();



            DB::commit();



            return response()->json([
                'msj' => 'Ubicación guardada correctamente'
            ]);
        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'msj' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }




    public function mostrarubicacionrecoergo(
        $archivo_opcion,
        $reco_id,
        $extension
    ) {

        $recurso = datosgeneralesinformeRecoModel::where(
            'RECO_ID',
            $reco_id
        )->firstOrFail();



        if (($archivo_opcion + 0) == 0) {

            return Storage::response(
                $recurso->RUTA_IMAGEN_UBICACION
            );
        } else {

            return Storage::download(
                $recurso->RUTA_IMAGEN_UBICACION
            );
        }
    }





    public function guardarProcesoInstalacionRecoErgo(Request $request)
    {
        try {

            DB::beginTransaction();

            $dato = datosgeneralesinformeRecoModel::where(
                'RECO_ID',
                $request->RECO_ID
            )->first();

            if (!$dato) {

                $dato = new datosgeneralesinformeRecoModel();

                $dato->RECO_ID = $request->RECO_ID;

                $dato->save();
            }


            $dato->INFORME_PROCESOINSTALACION =
                $request->INFORME_PROCESOINSTALACION;



            $dato->INFORME_ACTIVIDADPRINCIPAL =
                $request->INFORME_ACTIVIDADPRINCIPAL;


            $dato->save();



            DB::commit();



            return response()->json([
                'msj' => 'Proceso de instalación guardado correctamente'
            ]);
        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'msj' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }



    public function tablaReporteCategoriasErgo(Request $request)
    {

        $categorias = recoergocategoriasModel::where(
            'RECO_ID',
            $request->ergoid
        )
            ->where('ACTIVO', 1)
            ->get();



        $numero = 1;



        foreach ($categorias as $categoria) {

            $categoria->NUMERO = $numero;

            $numero++;
        }



        return response()->json([
            'data' => $categorias
        ]);
    }


    public function tablaReporteAreasErgo(Request $request)
    {

        $categorias = recoergocategoriasModel::where(
            'RECO_ID',
            $request->ergoid
        )
            ->where('ACTIVO', 1)
            ->get();



        $data = [];

        $numero = 1;



        foreach ($categorias as $categoria) {

            if ($categoria->CATEGORIA_AREAS_ID) {

                foreach ($categoria->CATEGORIA_AREAS_ID as $area_id) {

                    $area = recoergoareasModel::find($area_id);



                    if ($area) {

                        $obj = new \stdClass();

                        $obj->NUMERO = $numero;

                        $obj->AREA =
                            $area->NOMBRE_AREA_ERGO;

                        $obj->CATEGORIA =
                            $categoria->NOMBRE_CATEGORIA_ERGO;



                        $data[] = $obj;

                        $numero++;
                    }
                }
            }
        }



        return response()->json([
            'data' => $data
        ]);
    }



    public function guardarConclusionRecoErgo(Request $request)
    {
        try {

            DB::beginTransaction();



            //---------------------------------------
            // BUSCAR REGISTRO
            //---------------------------------------

            $dato = datosgeneralesinformeRecoModel::where(
                'RECO_ID',
                $request->RECO_ID
            )->first();



            //---------------------------------------
            // SI NO EXISTE
            //---------------------------------------

            if (!$dato) {

                $dato = new datosgeneralesinformeRecoModel();

                $dato->RECO_ID = $request->RECO_ID;

                $dato->save();
            }



            //---------------------------------------
            // DATOS
            //---------------------------------------

            $dato->SELECT_CONCLUSION =
                $request->SELECT_CONCLUSION;



            $dato->INFORME_CONCLUSION =
                $request->INFORME_CONCLUSION;



            //---------------------------------------
            // SAVE
            //---------------------------------------

            $dato->save();



            DB::commit();



            return response()->json([
                'msj' => 'Conclusión guardada correctamente'
            ]);
        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'msj' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }



    public function guardarRecomendacionesInformeErgo(
        Request $request
    ) {

        try {

            DB::beginTransaction();



            //---------------------------------------
            // ELIMINAR ANTERIORES
            //---------------------------------------

            recomendacionesinformeergoModel::where(
                'RECO_ID',
                $request->RECO_ID
            )->delete();



            //---------------------------------------
            // SI VIENEN RECOMENDACIONES
            //---------------------------------------

            if ($request->DESCRIPCION_RECOMENDACIONES) {

                foreach (
                    $request->DESCRIPCION_RECOMENDACIONES
                    as $recomendacion
                ) {

                    $dato =
                        new recomendacionesinformeergoModel();

                    $dato->RECO_ID =
                        $request->RECO_ID;

                    $dato->CATALOGO_RECOMENDACIONES_ID =
                        $recomendacion;

                    $dato->save();
                }
            }



            DB::commit();



            return response()->json([
                'msj' =>
                'Recomendaciones guardadas correctamente'
            ]);
        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'msj' =>
                'Error: ' . $e->getMessage()
            ], 500);
        }
    }



    public function obtenerRecomendacionesInformeErgo(
        $RECO_ID
    ) {

        $datos =
            recomendacionesinformeergoModel::where(
                'RECO_ID',
                $RECO_ID
            )->get();



        return response()->json($datos);
    }





    public function guardarResponsablesInformeRecoErgo(
        Request $request
    ) {

        try {

            DB::beginTransaction();



            //---------------------------------------
            // BUSCAR REGISTRO
            //---------------------------------------

            $dato = datosgeneralesinformeRecoModel::where(
                'RECO_ID',
                $request->RECO_ID
            )->first();



            //---------------------------------------
            // SI NO EXISTE
            //---------------------------------------

            if (!$dato) {

                $dato = new datosgeneralesinformeRecoModel();

                $dato->RECO_ID = $request->RECO_ID;

                $dato->save();
            }



            //---------------------------------------
            // DATOS
            //---------------------------------------

            $dato->INFORME_RESPONSABLE1 =
                $request->INFORME_RESPONSABLE1;

            $dato->INFORME_RESPONSABLE1CARGO =
                $request->INFORME_RESPONSABLE1CARGO;



            $dato->INFORME_RESPONSABLE2 =
                $request->INFORME_RESPONSABLE2;

            $dato->INFORME_RESPONSABLE2CARGO =
                $request->INFORME_RESPONSABLE2CARGO;



            //---------------------------------------
            // DOCUMENTO RESPONSABLE 1
            //---------------------------------------

            if ($request->file('INFORME_RESPONSABLE1DOCUMENTO')) {

                $extension =
                    $request->file(
                        'INFORME_RESPONSABLE1DOCUMENTO'
                    )->getClientOriginalExtension();



                $ruta =
                    $request->file(
                        'INFORME_RESPONSABLE1DOCUMENTO'
                    )->storeAs(

                        'reconocimiento_ergo/' .
                            $request->RECO_ID .
                            '/responsables_informe',

                        'responsable1.' . $extension

                    );



                $dato->INFORME_RESPONSABLE1DOCUMENTO =
                    $ruta;
            }



            //---------------------------------------
            // DOCUMENTO RESPONSABLE 2
            //---------------------------------------

            if ($request->file('INFORME_RESPONSABLE2DOCUMENTO')) {

                $extension =
                    $request->file(
                        'INFORME_RESPONSABLE2DOCUMENTO'
                    )->getClientOriginalExtension();



                $ruta =
                    $request->file(
                        'INFORME_RESPONSABLE2DOCUMENTO'
                    )->storeAs(

                        'reconocimiento_ergo/' .
                            $request->RECO_ID .
                            '/responsables_informe',

                        'responsable2.' . $extension

                    );



                $dato->INFORME_RESPONSABLE2DOCUMENTO =
                    $ruta;
            }



            //---------------------------------------
            // SAVE
            //---------------------------------------

            $dato->save();



            DB::commit();



            return response()->json([
                'msj' =>
                'Responsables guardados correctamente'
            ]);
        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'msj' =>
                'Error: ' . $e->getMessage()
            ], 500);
        }
    }



    public function mostrarresponsable1recoergo(
        $archivo_opcion,
        $reco_id,
        $extension
    ) {

        $recurso =
            datosgeneralesinformeRecoModel::where(
                'RECO_ID',
                $reco_id
            )->firstOrFail();



        if (($archivo_opcion + 0) == 0) {

            return Storage::response(
                $recurso->INFORME_RESPONSABLE1DOCUMENTO
            );
        } else {

            return Storage::download(
                $recurso->INFORME_RESPONSABLE1DOCUMENTO
            );
        }
    }



    public function mostrarresponsable2recoergo(
        $archivo_opcion,
        $reco_id,
        $extension
    ) {

        $recurso =
            datosgeneralesinformeRecoModel::where(
                'RECO_ID',
                $reco_id
            )->firstOrFail();



        if (($archivo_opcion + 0) == 0) {

            return Storage::response(
                $recurso->INFORME_RESPONSABLE2DOCUMENTO
            );
        } else {

            return Storage::download(
                $recurso->INFORME_RESPONSABLE2DOCUMENTO
            );
        }
    }




    public function validarEdicionRecoErgo($reco_id)
{

    $revision =
        versionesrecoergoModel::where(
            'RECO_ID',
            $reco_id
        )
        ->orderByDesc('NUMERO_REVISION')
        ->first();



    //---------------------------------------
    // SI NO EXISTE REVISION
    //---------------------------------------

    if (!$revision) {

        return response()->json([

            'permite_guardar' => 1,

            'finalizado' => 0,

            'cancelado' => 0

        ]);
    }



    //---------------------------------------
    // SI ESTA FINALIZADO
    //---------------------------------------

    if ($revision->FINALIZADO == 1 &&
        $revision->CANCELADO == 0) {

        return response()->json([

            'permite_guardar' => 0,

            'finalizado' => 1,

            'cancelado' => 0

        ]);
    }



    //---------------------------------------
    // SI ESTA CANCELADO
    //---------------------------------------

    if ($revision->CANCELADO == 1) {

        return response()->json([

            'permite_guardar' => 1,

            'finalizado' => 0,

            'cancelado' => 1

        ]);
    }



    //---------------------------------------
    // NORMAL
    //---------------------------------------

    return response()->json([

        'permite_guardar' => 1,

        'finalizado' => 0,

        'cancelado' => 0

    ]);

}

    public function crearRevisionRecoErgo(
        Request $request
    ) {

        try {

            DB::beginTransaction();



            //---------------------------------------
            // ULTIMA REVISION
            //---------------------------------------

            $ultima =
                versionesrecoergoModel::where(
                    'RECO_ID',
                    $request->RECO_ID
                )
                ->orderByDesc('NUMERO_REVISION')
                ->first();



            //---------------------------------------
            // VALIDAR SI YA ESTA FINALIZADA
            //---------------------------------------

            if (
                $ultima &&
                $ultima->FINALIZADO == 1 &&
                $ultima->CANCELADO == 0
            ) {

                return response()->json([

                    'msj' =>
                    'La revisión ya fue finalizada'

                ], 500);
            }



            //---------------------------------------
            // NUMERO REVISION
            //---------------------------------------

            $numero =
                $ultima
                ? $ultima->NUMERO_REVISION + 1
                : 0;



            //---------------------------------------
            // GENERAR WORD
            //---------------------------------------

            $rutaDocumento =
                'pendiente.docx';



            //---------------------------------------
            // CREAR REGISTRO
            //---------------------------------------

            $revision =
                new versionesrecoergoModel();



            $revision->RECO_ID =
                $request->RECO_ID;

            $revision->NUMERO_REVISION =
                $numero;

            $revision->FINALIZADO = 1;

            $revision->FINALIZADO_POR =
                Auth::user()->id;

            $revision->FECHA_FINALIZADO =
                now();

            $revision->RUTA_DOCUMENTO =
                $rutaDocumento;



            $revision->save();



            DB::commit();



            return response()->json([

                'msj' =>
                'Revisión generada correctamente'

            ]);
        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([

                'msj' =>
                'Error: ' . $e->getMessage()

            ], 500);
        }
    }




    public function cancelarRevisionRecoErgo(
        Request $request
    ) {

        try {

            DB::beginTransaction();



            $revision =
                versionesrecoergoModel::findOrFail(
                    $request->ID_VERSION_RECO_ERGO
                );



            $revision->CANCELADO = 1;

            $revision->CANCELADO_POR =
                Auth::user()->id;

            $revision->FECHA_CANCELADO =
                now();

            $revision->MOTIVO_CANCELACION =
                $request->MOTIVO_CANCELACION;



            $revision->save();



            DB::commit();



            return response()->json([

                'msj' =>
                'Revisión cancelada correctamente'

            ]);
        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([

                'msj' =>
                'Error: ' . $e->getMessage()

            ], 500);
        }
    }



    public function tablaVersionesRecoErgo($reco_id)
    {

        $datos = DB::select("

        SELECT

            vr.*,

            CONCAT(
                ef.empleado_nombre,
                ' ',
                ef.empleado_apellidopaterno
            ) AS FINALIZADO_NOMBRE,

            CONCAT(
                ec.empleado_nombre,
                ' ',
                ec.empleado_apellidopaterno
            ) AS CANCELADO_NOMBRE

        FROM versionesrecoergo vr

        LEFT JOIN usuario uf
            ON uf.id = vr.FINALIZADO_POR

        LEFT JOIN empleado ef
            ON ef.id = uf.empleado_id

        LEFT JOIN usuario uc
            ON uc.id = vr.CANCELADO_POR

        LEFT JOIN empleado ec
            ON ec.id = uc.empleado_id

        WHERE vr.RECO_ID = ?

        ORDER BY vr.NUMERO_REVISION DESC

    ", [$reco_id]);



        foreach ($datos as $key => $value) {



            //---------------------------------------
            // ESTADO
            //---------------------------------------

            if ($value->CANCELADO == 1) {

                $value->ESTADO =
                    '<span class="badge badge-danger">
                    Cancelado
                 </span>';
            } else {

                $value->ESTADO =
                    '<span class="badge badge-success">
                    Finalizado
                 </span>';
            }



            //---------------------------------------
            // CHECKBOX CANCELADO
            //---------------------------------------

            $checked =
                ($value->CANCELADO == 1)
                ? 'checked'
                : '';

            $value->CHECKBOX_CANCELADO = '

            <div class="switch">

                <label>

                    <input
                        type="checkbox"

                        class="checkbox_cancelado_revision"

                        ' . $checked . '

                        onchange="cancelarRevisionRecoErgo(
                            ' . $value->ID_VERSION_RECO_ERGO . ',
                            this
                        )">

                    <span class="lever switch-col-red"></span>

                </label>

            </div>

            ';


            //---------------------------------------
            // BOTON DESCARGA
            //---------------------------------------

        

            $value->BOTON_DESCARGAR = '

    <button
        type="button"

        class="btn btn-success btn-circle"

        data-toggle="tooltip"

        title="Descargar informe"

        onclick="descargarRevisionRecoErgo(
            ' . $value->RECO_ID . '
        )">

        <i class="fa fa-download"></i>

    </button>

';

        }



        return response()->json([
            'data' => $datos
        ]);
    }





    public function descargarRevisionRecoErgo(
        $RECO_ID
    ) {

        try {

            //---------------------------------------
            // CONSULTA RECONOCIMIENTO
            //---------------------------------------

            $reco = reconocimientoergoModel::findOrFail(
                $RECO_ID
            );



            //---------------------------------------
            // CONSULTA PROYECTO
            //---------------------------------------

            $proyecto = proyectoModel::where(
                'reconocimiento_ergo_id',
                $RECO_ID
            )->first();



            $contrato = clientecontratoModel::find(
                $proyecto->contrato_id
            );



            //---------------------------------------
            // CONSULTA RECURSOS PORTADA
            //---------------------------------------

            $recursos =
                recursosPortadaRecoErgoModel::where(
                    'RECO_ID',
                    $RECO_ID
                )
                ->get();



            //---------------------------------------
            // PLANTILLA
            //---------------------------------------

            $rutaPlantilla =
                storage_path(
                    'app/plantillas_reportes/plantilla_ergo/Plantilla_informe_ergonomia.docx'
                );



            $plantillaword =
                new TemplateProcessor(
                    $rutaPlantilla
                );



            //---------------------------------------
            // ${proyecto_portada}
            //---------------------------------------

            $numeroContrato =
                $contrato->NUMERO_CONTRATO
                ?? 'No cargado';



            $plantillaword->setValue(

                'proyecto_portada',

                'Evaluación del Factor de Riesgo Ergonómico
NOM-036-1-STPS-2018 ' .
                    $numeroContrato

            );



            //---------------------------------------
            // ${folio_portada}
            //---------------------------------------

            $plantillaword->setValue(

                'folio_portada',

                $reco->proyecto_folio
                    ?? 'No cargado'

            );



            //---------------------------------------
            // ${razon_social_portada}
            //---------------------------------------

            $plantillaword->setValue(

                'razon_social_portada',

                $proyecto->proyecto_clienterazonsocial
                    ?? 'No cargado'

            );



            //---------------------------------------
            // ${instalación_portada}
            //---------------------------------------

            $plantillaword->setValue(

                'instalación_portada',

                $reco->instalacion
                    ?? 'No cargado'

            );



            //---------------------------------------
            // ${lugar_fecha_portada}
            //---------------------------------------

            $mes =
                $recursos[0]->INFORME_MES
                ?? 'No cargado';



            $anio =
                $recursos[0]->INFORME_ANIO
                ?? 'No cargado';



            $direccion =
                $reco->direccion
                ?? 'No cargado';



            $plantillaword->setValue(

                'lugar_fecha_portada',

                $direccion .
                    ', ' .
                    $mes .
                    ' del ' .
                    $anio

            );



            //---------------------------------------
            // ${foto_portada}
            //---------------------------------------

            if (
                isset($recursos[0]) &&
                $recursos[0]->RUTA_IMAGEN_PORTADA
            ) {

                if (

                    file_exists(

                        storage_path(
                            'app/' .
                                $recursos[0]->RUTA_IMAGEN_PORTADA
                        )

                    )

                ) {

                    $plantillaword->setImageValue(

                        'foto_portada',

                        array(

                            'path' => storage_path(
                                'app/' .
                                    $recursos[0]->RUTA_IMAGEN_PORTADA
                            ),

                            'width' => 969,

                            'height' => 689,

                            'ratio' => true,

                            'borderColor' => '000000'

                        )

                    );
                } else {

                    $plantillaword->setValue(

                        'foto_portada',

                        'LA IMAGEN NO HA SIDO ENCONTRADA'

                    );
                }
            } else {

                $plantillaword->setValue(

                    'foto_portada',

                    'LA IMAGEN DE LA PORTADA NO HA SIDO CARGADA'

                );
            }





            //---------------------------------------
            // ${LOGO_IZQUIERDO}
            //---------------------------------------

            if (
                $contrato &&
                $contrato->CONTRATO_PLANTILLA_LOGOIZQUIERDO
            ) {

                if (

                    file_exists(

                        storage_path(
                            'app/' .
                                $contrato->CONTRATO_PLANTILLA_LOGOIZQUIERDO
                        )

                    )

                ) {

                    $plantillaword->setImageValue(

                        'LOGO_IZQUIERDO',

                        array(

                            'path' => storage_path(
                                'app/' .
                                    $contrato->CONTRATO_PLANTILLA_LOGOIZQUIERDO
                            ),

                            'width' => 120,

                            'height' => 150,

                            'ratio' => true,

                            'borderColor' => '000000'

                        )

                    );
                } else {

                    $plantillaword->setValue(

                        'LOGO_IZQUIERDO',

                        'IMAGEN NO ENCONTRADA'

                    );
                }
            } else {

                $plantillaword->setValue(

                    'LOGO_IZQUIERDO',

                    'SIN FOTO'

                );
            }





            //---------------------------------------
            // ${LOGO_DERECHO}
            //---------------------------------------

            if (
                $contrato &&
                $contrato->CONTRATO_PLANTILLA_LOGODERECHO
            ) {

                if (

                    file_exists(

                        storage_path(
                            'app/' .
                                $contrato->CONTRATO_PLANTILLA_LOGODERECHO
                        )

                    )

                ) {

                    $plantillaword->setImageValue(

                        'LOGO_DERECHO',

                        array(

                            'path' => storage_path(
                                'app/' .
                                    $contrato->CONTRATO_PLANTILLA_LOGODERECHO
                            ),

                            'width' => 120,

                            'height' => 150,

                            'ratio' => true,

                            'borderColor' => '000000'

                        )

                    );
                } else {

                    $plantillaword->setValue(

                        'LOGO_DERECHO',

                        'IMAGEN NO ENCONTRADA'

                    );
                }
            } else {

                $plantillaword->setValue(

                    'LOGO_DERECHO',

                    'SIN FOTO'

                );
            }





            //---------------------------------------
            // ${PIE_PAGINA}
            //---------------------------------------

            $plantillaword->setValue(

                'PIE_PAGINA',

                $contrato->CONTRATO_PLANTILLA_PIEPAGINA
                    ?? 'SIN PIE DE PAGINA'

            );


            //---------------------------------------
            // ${INSTALACION_NOMBRE}
            //---------------------------------------

            $niveles = [];



            if (isset($recursos[0])) {

                if (!empty($recursos[0]->NIVEL1)) {
                    $niveles[] = $recursos[0]->NIVEL1;
                }

                if (!empty($recursos[0]->NIVEL2)) {
                    $niveles[] = $recursos[0]->NIVEL2;
                }

                if (!empty($recursos[0]->NIVEL3)) {
                    $niveles[] = $recursos[0]->NIVEL3;
                }

                if (!empty($recursos[0]->NIVEL4)) {
                    $niveles[] = $recursos[0]->NIVEL4;
                }

                if (!empty($recursos[0]->NIVEL5)) {
                    $niveles[] = $recursos[0]->NIVEL5;
                }
            }



            $textoInstalacion =

                count($niveles)

                ? implode(
                    '</w:t><w:br/><w:t>',
                    $niveles
                )

                : 'No cargado';



            $plantillaword->setValue(

                'INSTALACION_NOMBRE',

                $textoInstalacion

            );



            //---------------------------------------
            // CONSULTA DATOS GENERALES
            //---------------------------------------

            $datosGenerales =
                datosgeneralesinformeRecoModel::where(
                    'RECO_ID',
                    $RECO_ID
                )
                ->first();




            //---------------------------------------
            // ${INTRODUCCION}
            //---------------------------------------

            $plantillaword->setValue(

                'INTRODUCCION',

                $datosGenerales->INFORME_INTRODUCCION
                    ?? 'No cargado'

            );




            //---------------------------------------
// DEFINICIONES
//---------------------------------------

$definiciones = DB::table('definicionesinformeergo as di')

    ->join(
        'catergo_definiciones as cd',
        'cd.ID_DEFINICIONES',
        '=',
        'di.CATALOGO_DEFINICIONES_ID'
    )

    ->where(
        'di.RECO_ID',
        $RECO_ID
    )

    ->orderBy(
        'cd.CONCEPTO_DEFINICION',
        'ASC'
    )

    ->select(
        'cd.CONCEPTO_DEFINICION',
        'cd.DESCRIPCION_DEFINICION',
        'cd.FUENTE_DEFINICION'
    )

    ->get();




//---------------------------------------
// TEXO DEFINICIONES
//---------------------------------------

$textoDefiniciones = '';



//---------------------------------------
// FUENTES SIN REPETIR
//---------------------------------------

$fuentes = [];



foreach ($definiciones as $key => $value) {

    //---------------------------------------
    // DEFINICION
    //---------------------------------------

    $textoDefiniciones .=

        '<w:p>

            <w:r>
                <w:rPr>
                    <w:b/>
                </w:rPr>

                <w:t>' .

                    htmlspecialchars(
                        $value->CONCEPTO_DEFINICION
                    ) .

                ':</w:t>

            </w:r>

            <w:r>

                <w:t xml:space="preserve">

                    ' .

                    htmlspecialchars(
                        $value->DESCRIPCION_DEFINICION
                    ) .

                '

                </w:t>

            </w:r>

        </w:p>';



    //---------------------------------------
    // FUENTES
    //---------------------------------------

    if (
        $value->FUENTE_DEFINICION
        &&
        !in_array(
            $value->FUENTE_DEFINICION,
            $fuentes
        )
    ) {

        $fuentes[] =
            $value->FUENTE_DEFINICION;
    }
}




//---------------------------------------
// SI NO HAY DEFINICIONES
//---------------------------------------

if ($textoDefiniciones == '') {

    $textoDefiniciones =
        'No cargado';
}




//---------------------------------------
// TEXTO FUENTES
//---------------------------------------

$textoFuentes =

    count($fuentes)

    ? implode(
        '</w:t><w:br/><w:t>',
        $fuentes
    )

    : 'No cargado';




//---------------------------------------
// MARCADORES WORD
//---------------------------------------

$plantillaword->setValue(
    'DEFINICIONES',
    $textoDefiniciones
);



$plantillaword->setValue(
    'DEFINICIONES_FUENTES',
    $textoFuentes
);




            //---------------------------------------
            // ${OBJETIVO_GENERAL}
            //---------------------------------------

            $plantillaword->setValue(

                'OBJETIVO_GENERAL',

                $datosGenerales->INFORME_OBJETIVOGENERALES
                    ?? 'No cargado'

            );




            //---------------------------------------
            // ${OBJETIVOS_ESPECIFICOS}
            //---------------------------------------

            $textoObjetivos = '';



            if (
                !empty($datosGenerales->INFORME_OBJETIVOSESPECIFICOS)
            ) {

                //---------------------------------------
                // SEPARAR POR LINEAS
                //---------------------------------------

                $objetivos = preg_split(

                    "/\r\n|\n|\r/",

                    $datosGenerales->INFORME_OBJETIVOSESPECIFICOS

                );



                foreach ($objetivos as $objetivo) {

                    //---------------------------------------
                    // LIMPIAR TEXTO
                    //---------------------------------------

                    $objetivo = trim($objetivo);

                    $objetivo = ltrim($objetivo, '•- ');



                    //---------------------------------------
                    // SI TIENE TEXTO
                    //---------------------------------------

                    if ($objetivo != '') {

                        $textoObjetivos .=

                            '• ' .

                            $objetivo .

                            '</w:t><w:br/><w:t>';
                    }
                }
            } else {

                $textoObjetivos = 'No cargado';
            }




            //---------------------------------------
            // MARCADOR
            //---------------------------------------

            $plantillaword->setValue(

                'OBJETIVOS_ESPECIFICOS',

                $textoObjetivos

            );



            //---------------------------------------
            // ${UBICACION_TEXTO}
            //---------------------------------------

            $plantillaword->setValue(

                'UBICACION_TEXTO',

                $datosGenerales->INFORME_UBICACIONINSTALACION
                    ?? 'No cargado'

            );




            //---------------------------------------
            // ${UBICACION_FOTO}
            //---------------------------------------

            if ($datosGenerales->RUTA_IMAGEN_UBICACION) {

                if (
                    file_exists(
                        storage_path(
                            'app/' .
                                $datosGenerales->RUTA_IMAGEN_UBICACION
                        )
                    )
                ) {

                    $plantillaword->setImageValue(

                        'UBICACION_FOTO',

                        array(

                            'path' => storage_path(
                                'app/' .
                                    $datosGenerales->RUTA_IMAGEN_UBICACION
                            ),

                            'width' => 580,

                            'height' => 400,

                            'ratio' => true,

                            'borderColor' => '000000'

                        )

                    );
                } else {

                    $plantillaword->setValue(

                        'UBICACION_FOTO',

                        'FALTA CARGAR IMAGEN DESDE EL SISTEMA.'

                    );
                }
            } else {

                $plantillaword->setValue(

                    'UBICACION_FOTO',

                    'FALTA CARGAR IMAGEN DESDE EL SISTEMA.'

                );
            }



            //---------------------------------------
            // ${PROCESO_INSTALACION}
            //---------------------------------------

            $plantillaword->setValue(

                'PROCESO_INSTALACION',

                $datosGenerales->INFORME_PROCESOINSTALACION
                    ?? 'No cargado'

            );




            //---------------------------------------
            // ${ACTIVIDAD_INSTALACION}
            //---------------------------------------

            $plantillaword->setValue(

                'ACTIVIDAD _INSTALACION',

                $datosGenerales->INFORME_ACTIVIDADPRINCIPAL
                    ?? 'No cargado'

            );




            //================================================================================
            // TABLA 5.3 CATEGORÍAS
            //================================================================================


           
            //---------------------------------------
// CONSULTAR CATEGORÍAS
//---------------------------------------

$categorias = recoergocategoriasModel::where(
    'RECO_ID',
    $RECO_ID
)
->where('ACTIVO', 1)
->orderBy('NOMBRE_CATEGORIA_ERGO', 'ASC')
->get();




//---------------------------------------
// ESTILOS
//---------------------------------------

$fuente = 'Poppins';



$encabezado_texto = array(
    'name' => $fuente,
    'size' => 11,
    'bold' => true,
    'color' => 'FFFFFF'
);



$texto = array(
    'name' => $fuente,
    'size' => 10,
    'color' => '000000'
);



$centrado = array(
    'alignment' => 'center',
    'valign' => 'center'
);




$encabezado_celda = array(
    'bgColor' => '0F3D63',
    'valign' => 'center'
);



$celda = array(
    'valign' => 'center'
);




//---------------------------------------
// ANCHO
//---------------------------------------

$ancho_col_1 = 9200;




//---------------------------------------
// CREAR TABLA
//---------------------------------------

$table = new Table(array(

    'name' => $fuente,

    'borderSize' => 1,

    'borderColor' => '000000',

    'cellMargin' => 80,

    'unit' => TblWidth::TWIP

));




//---------------------------------------
// ENCABEZADO
//---------------------------------------

$table->addRow(500);



$table->addCell(
    $ancho_col_1,
    $encabezado_celda
)->addTextRun($centrado)->addText(
    'Categoría',
    $encabezado_texto
);




//---------------------------------------
// FILAS
//---------------------------------------

if (count($categorias) > 0) {

    foreach ($categorias as $categoria) {

        $table->addRow();



        $table->addCell(
            $ancho_col_1,
            $celda
        )->addTextRun($centrado)->addText(
            $categoria->NOMBRE_CATEGORIA_ERGO,
            $texto
        );

    }

} else {

    $table->addRow();



    $table->addCell(
        $ancho_col_1,
        $celda
    )->addTextRun($centrado)->addText(
        'No hay categorías registradas',
        $texto
    );

}




//---------------------------------------
// INSERTAR EN WORD
//---------------------------------------

$plantillaword->setComplexBlock(
    'TABLA_5_3',
    $table
);



            //---------------------------------------
            // CONSULTAR ÁREAS Y CATEGORÍAS
            //---------------------------------------

            $categorias = recoergocategoriasModel::where(
                'RECO_ID',
                $RECO_ID
            )
                ->where('ACTIVO', 1)
                ->get();




            $data = [];



            foreach ($categorias as $categoria) {

                //---------------------------------------
                // VALIDAR ÁREAS
                //---------------------------------------

                if (
                    $categoria->CATEGORIA_AREAS_ID
                    &&
                    is_array($categoria->CATEGORIA_AREAS_ID)
                ) {

                    foreach (
                        $categoria->CATEGORIA_AREAS_ID
                        as $area_id
                    ) {

                        $area = recoergoareasModel::find(
                            $area_id
                        );



                        if ($area) {

                            $obj = new \stdClass();

                            $obj->AREA =
                                $area->NOMBRE_AREA_ERGO;

                            $obj->CATEGORIA =
                                $categoria->NOMBRE_CATEGORIA_ERGO;



                            $data[] = $obj;
                        }
                    }
                }
            }




            //---------------------------------------
            // ORDENAR POR ÁREA
            //---------------------------------------

            usort($data, function ($a, $b) {

                return strcmp(
                    $a->AREA,
                    $b->AREA
                );
            });




            //---------------------------------------
            // ESTILOS
            //---------------------------------------

            $fuente = 'Poppins';



            $encabezado_texto = array(
                'name' => $fuente,
                'size' => 11,
                'bold' => true,
                'color' => 'FFFFFF'
            );



            $texto = array(
                'name' => $fuente,
                'size' => 10,
                'color' => '000000'
            );



            $centrado = array(
                'alignment' => 'center',
                'valign' => 'center'
            );



            $encabezado_celda = array(
                'bgColor' => '0F3D63',
                'valign' => 'center'
            );



            $celda = array(
                'valign' => 'center'
            );




            //---------------------------------------
            // ANCHOS
            //---------------------------------------

            $ancho_area = 4500;

            $ancho_categoria = 4700;




            //---------------------------------------
            // CREAR TABLA
            //---------------------------------------

            $table = new Table(array(

                'name' => $fuente,

                'borderSize' => 1,

                'borderColor' => '000000',

                'cellMargin' => 80,

                'unit' => TblWidth::TWIP

            ));




            //---------------------------------------
            // ENCABEZADO
            //---------------------------------------

            $table->addRow(500);



            $table->addCell(
                $ancho_area,
                $encabezado_celda
            )->addTextRun($centrado)->addText(
                'Área',
                $encabezado_texto
            );



            $table->addCell(
                $ancho_categoria,
                $encabezado_celda
            )->addTextRun($centrado)->addText(
                'Categoría',
                $encabezado_texto
            );




            //---------------------------------------
            // AGRUPAR ÁREAS
            //---------------------------------------

            $areasAgrupadas = [];



            foreach ($data as $fila) {

                if (!isset($areasAgrupadas[$fila->AREA])) {

                    $areasAgrupadas[$fila->AREA] = [];
                }



                $areasAgrupadas[$fila->AREA][] =
                    $fila->CATEGORIA;
            }




            //---------------------------------------
            // FILAS AGRUPADAS
            //---------------------------------------

            if (count($areasAgrupadas) > 0) {

                foreach ($areasAgrupadas as $area => $categoriasArea) {

                    foreach ($categoriasArea as $index => $categoria) {

                        $table->addRow();



                        //---------------------------------------
                        // ÁREA AGRUPADA
                        //---------------------------------------

                        if ($index == 0) {

                            $table->addCell(

                                $ancho_area,

                                array(
                                    'vMerge' => 'restart',
                                    'valign' => 'center'
                                )

                            )->addTextRun($centrado)->addText(
                                $area,
                                $texto
                            );
                        } else {

                            //---------------------------------------
                            // CONTINUAR MERGE
                            //---------------------------------------

                            $table->addCell(

                                $ancho_area,

                                array(
                                    'vMerge' => 'continue',
                                    'valign' => 'center'
                                )

                            );
                        }



                        //---------------------------------------
                        // CATEGORÍA
                        //---------------------------------------

                        $table->addCell(
                            $ancho_categoria,
                            $celda
                        )->addTextRun($centrado)->addText(
                            $categoria,
                            $texto
                        );
                    }
                }
            } else {

                $table->addRow();



                $table->addCell(
                    null,
                    array(
                        'gridSpan' => 2,
                        'valign' => 'center'
                    )
                )->addTextRun($centrado)->addText(
                    'No hay áreas registradas',
                    $texto
                );
            }




            //---------------------------------------
            // INSERTAR EN WORD
            //---------------------------------------

            $plantillaword->setComplexBlock(
                'TABLA_5_3_1',
                $table
            );




            //---------------------------------------
            // CONCLUSIÓN
            //---------------------------------------

            $plantillaword->setValue(

                'CONCLUSION',

                $datosGenerales->INFORME_CONCLUSION
                    ?
                    htmlspecialchars(
                    $datosGenerales->INFORME_CONCLUSION
                    )
                    :
                    'No cargado'

            );




            //---------------------------------------
            // CONSULTAR RECOMENDACIONES
            //---------------------------------------

            $recomendaciones = DB::table(
                'recomendacionesinformeergo as ri'
            )

                ->join(
                    'catergo_recomendaciones as cr',
                    'cr.ID_RECOMENDACIONES',
                    '=',
                    'ri.CATALOGO_RECOMENDACIONES_ID'
                )

                ->where(
                    'ri.RECO_ID',
                    $RECO_ID
                )

                ->select(
                    'cr.DESCRIPCION_RECOMENDACIONES'
                )

                ->get();




            //---------------------------------------
            // ARMAR TEXTO
            //---------------------------------------

            $texto_recomendaciones = '';



            if (count($recomendaciones) > 0) {

                foreach ($recomendaciones as $recomendacion) {

                    $texto_recomendaciones .=

                        trim(
                            strip_tags(
                                $recomendacion->DESCRIPCION_RECOMENDACIONES
                            )
                        )

                        . '</w:t><w:br/><w:br/><w:t>';
                }
            } else {

                $texto_recomendaciones =
                    'No cargado';
            }




            //---------------------------------------
            // INSERTAR EN WORD
            //---------------------------------------

            $plantillaword->setValue(

                'RECOMENDACIONES',

                $texto_recomendaciones

            );




            //---------------------------------------
            // RESPONSABLE 1 - DOCUMENTO
            //---------------------------------------

            if ($datosGenerales->INFORME_RESPONSABLE1DOCUMENTO) {

                if (

                    file_exists(

                        storage_path(
                            'app/' .
                                $datosGenerales->INFORME_RESPONSABLE1DOCUMENTO
                        )

                    )

                ) {

                    $plantillaword->setImageValue(

                        'REPONSABLE1_DOCUMENTO',

                        array(

                            'path' => storage_path(
                                'app/' .
                                    $datosGenerales->INFORME_RESPONSABLE1DOCUMENTO
                            ),

                            'height' => 300,

                            'width' => 580,

                            'ratio' => true,

                            'borderColor' => '000000'

                        )

                    );
                } else {

                    $plantillaword->setValue(
                        'REPONSABLE1_DOCUMENTO',
                        'FALTA CARGAR IMAGEN DESDE EL SISTEMA.'
                    );
                }
            } else {

                $plantillaword->setValue(
                    'REPONSABLE1_DOCUMENTO',
                    'FALTA CARGAR IMAGEN DESDE EL SISTEMA.'
                );
            }




            //---------------------------------------
            // RESPONSABLE 1 - NOMBRE Y CARGO
            //---------------------------------------

            $plantillaword->setValue(

                'REPONSABLE1',

                htmlspecialchars(

                    ($datosGenerales->INFORME_RESPONSABLE1
                        ?
                        $datosGenerales->INFORME_RESPONSABLE1
                        :
                        'No cargado')

                )

                    .

                    '</w:t><w:br/><w:t>'

                    .

                    htmlspecialchars(

                        ($datosGenerales->INFORME_RESPONSABLE1CARGO
                            ?
                            $datosGenerales->INFORME_RESPONSABLE1CARGO
                            :
                            'No cargado')

                    )

            );




            //---------------------------------------
            // RESPONSABLE 2 - DOCUMENTO
            //---------------------------------------

            if ($datosGenerales->INFORME_RESPONSABLE2DOCUMENTO) {

                if (

                    file_exists(

                        storage_path(
                            'app/' .
                                $datosGenerales->INFORME_RESPONSABLE2DOCUMENTO
                        )

                    )

                ) {

                    $plantillaword->setImageValue(

                        'REPONSABLE2_DOCUMENTO',

                        array(

                            'path' => storage_path(
                                'app/' .
                                    $datosGenerales->INFORME_RESPONSABLE2DOCUMENTO
                            ),

                            'height' => 300,

                            'width' => 580,

                            'ratio' => true,

                            'borderColor' => '000000'

                        )

                    );
                } else {

                    $plantillaword->setValue(
                        'REPONSABLE2_DOCUMENTO',
                        'FALTA CARGAR IMAGEN DESDE EL SISTEMA.'
                    );
                }
            } else {

                $plantillaword->setValue(
                    'REPONSABLE2_DOCUMENTO',
                    'FALTA CARGAR IMAGEN DESDE EL SISTEMA.'
                );
            }




            //---------------------------------------
            // RESPONSABLE 2 - NOMBRE Y CARGO
            //---------------------------------------

            $plantillaword->setValue(

                'REPONSABLE2',

                htmlspecialchars(

                    ($datosGenerales->INFORME_RESPONSABLE2
                        ?
                        $datosGenerales->INFORME_RESPONSABLE2
                        :
                        'No cargado')

                )

                    .

                    '</w:t><w:br/><w:t>'

                    .

                    htmlspecialchars(

                        ($datosGenerales->INFORME_RESPONSABLE2CARGO
                            ?
                            $datosGenerales->INFORME_RESPONSABLE2CARGO
                            :
                            'No cargado')

                    )

            );
            //---------------------------------------
            // GUARDAR WORD
            //---------------------------------------

            $nombreWord =
                'Informe_Ergonomia_' .
                $RECO_ID .
                '.docx';



            $rutaWord =
                storage_path(
                    'app/temp/' .
                        $nombreWord
                );



            $plantillaword->saveAs(
                $rutaWord
            );



            //---------------------------------------
            // DESCARGAR
            //---------------------------------------

            return response()->download(
                $rutaWord
            )->deleteFileAfterSend(true);
        } catch (Exception $e) {

            return response()->json([

                'msj' =>
                'Error: ' .
                    $e->getMessage()

            ], 500);
        }
    }


    

    }




    
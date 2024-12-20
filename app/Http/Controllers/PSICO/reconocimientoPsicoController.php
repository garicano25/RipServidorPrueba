<?php

namespace App\Http\Controllers\PSICO;

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
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\reconocimientopsico\reconocimientopsicoModel;
use App\modelos\recsensorial\catdepartamentoModel;
use App\modelos\recsensorial\catmovilfijoModel;
use App\modelos\reconocimientopsico\catcargos_psicoModel;


class reconocimientoPsicoController extends Controller
{
    //
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
    { //vista RECONOCIMIENTO SENSORIAL

        $catdepartamento = catdepartamentoModel::where('catdepartamento_activo', 1)->orderBy('catdepartamento_nombre', 'ASC')->get();
        $catmovilfijo = catmovilfijoModel::where('catmovilfijo_activo', 1)->get();
        $cargos = catcargos_psicoModel::where('ACTIVO', 1)->get();

        return view('catalogos.psico.reconocimiento_psicosocial', compact('catdepartamento','catmovilfijo','cargos'));
      
    }

    
    public function folioproyecto($proyecto_folio)
    {
        try {
            $opciones_select = '<option value="">&nbsp;</option>';

            // $proyectos = DB::select("SELECT 
            //                         p.id, 
            //                         p.proyecto_folio,
            //                         p.proyecto_clienteinstalacion,
            //                         proyecto_clientedireccionservicio,
            //                         p.recsensorial_id
            //                     FROM 
            //                         proyecto p
            //                     LEFT JOIN 
            //                         serviciosProyecto sp ON p.id = sp.PROYECTO_ID
            //                     WHERE 
            //                         sp.PSICO = 1
            //                         AND sp.PSICO_RECONOCIMIENTO = 1
            //                         AND (p.reconocimiento_psico_id IS NULL OR p.proyecto_folio = ?) ", [$proyecto_folio]);

            $proyectos = DB::select("SELECT 
                                    p.id, 
                                    p.proyecto_folio,
                                    p.proyecto_clienteinstalacion,
                                    proyecto_clientedireccionservicio,
                                    p.recsensorial_id
                                FROM 
                                    proyecto p
                                LEFT JOIN 
                                    serviciosProyecto sp ON p.id = sp.PROYECTO_ID
                                WHERE 
                                    sp.PSICO = 1
                                    AND sp.PSICO_RECONOCIMIENTO = 1
                                    AND (p.reconocimiento_psico_id IS NULL OR p.proyecto_folio = ?)
									AND p.id IN (SELECT PROYECTO_ID FROM proyectoUsuarios GROUP BY PROYECTO_ID)", [$proyecto_folio]);

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

  
    public function estructuraproyectos($FOLIO)
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

                        DB::statement('ALTER TABLE reconocimientopsico AUTO_INCREMENT=1');

                        //Verificamos si el reconocimiento requiere contrato de no requerir autorizado lo ponemos como 0 para que deba ser autorizado
                        if (intval($request->requiere_contrato) == 1) {
                            $request['autorizado'] = 1;
                            $request['recsensorial_bloqueado'] = 0;
                        } else {
                            $request['recsensorial_bloqueado'] = 1;
                            $request['autorizado'] = 0;
                            $request['contrato_id'] = 0;
                        }

                        $reconocimientopsico = reconocimientopsicoModel::create($request->all());
                        // $recsensorial->recsensorialpruebas()->sync($request->parametro); // SE COMENTO PORQUE YA SON DOS ARREGLOS DE PRUEBAS ENTONCES SI HIZO APARTE

                        //UNA VEZ GUARDADO TODO LO DE RECONOCIMIENTO PROCEDEMOS A VINCULAR EL  ID DEL RECONOCIMIENTO CON EL PROYECTO
                        $proyecto = proyectoModel::where('proyecto_folio', $request["proyecto_folio"])->first();
                        $proyecto->reconocimiento_psico_id = $reconocimientopsico->id;
                        $proyecto->save();


                        // mensaje
                        $dato["msj"] = 'Información guardada correctamente y vinculado con el proyecto: ' . $request["proyecto_folio"];
                        $recsensorial_activo = 1;


                        // si envia archivo FOTO ubicacion
                    if ($request->file('inputfotomapa')) {
                        $extension = $request->file('inputfotomapa')->getClientOriginalExtension();
                        $request['fotoubicacion'] = $request->file('inputfotomapa')->storeAs('reconocimiento_psico/' . $reconocimientopsico->id . '/mapa', $reconocimientopsico->id . '.' . $extension);
                        $reconocimientopsico->update($request->all());
                    }else{
                        $recsensorial_extension = $request['hidden_fotomapa_extension'];
                        $recsensorial_id = $request['hidden_fotomapa'];
                        $rutaOriginal = 'recsensorial/' . $recsensorial_id . '/mapa/' . $recsensorial_id . $recsensorial_extension;

                        if (Storage::exists($rutaOriginal)) {
                            // Asegúrate de crear el directorio si no existe
                            $nuevaRuta = 'reconocimiento_psico/' . $reconocimientopsico->id . '/mapa/' . $reconocimientopsico->id . '.' . pathinfo($rutaOriginal, PATHINFO_EXTENSION);
        
                            Storage::makeDirectory('reconocimiento_psico/' . $reconocimientopsico->id . '/mapa');
        
                            // Copiar la imagen a la nueva ubicación
                            Storage::copy($rutaOriginal, $nuevaRuta);
                            
                            // Actualiza la base de datos con la nueva ruta
                            $reconocimientopsico->fotoubicacion = $nuevaRuta;
                            $reconocimientopsico->update($request->all());
                        } else {
                            // Manejar caso en el que la imagen original no existe
                            // Puedes lanzar una excepción o asignar un valor predeterminado
                            throw new Exception("La imagen original no existe en la ruta: " . $rutaOriginal);
                        }
                    }

                    // si envia archivo FOTO plano
                    if ($request->file('inputfotoplano')) {
                        $extension = $request->file('inputfotoplano')->getClientOriginalExtension();
                        $request['fotoplano'] = $request->file('inputfotoplano')->storeAs('reconocimiento_psico/' . $reconocimientopsico->id . '/plano', $reconocimientopsico->id . '.' . $extension);
                        $reconocimientopsico->update($request->all());
                    }else{
                        $recsensorial_extension = $request['hidden_fotoplano_extension'];
                        $recsensorial_id = $request['hidden_fotoplano'];
                        $rutaOriginal = 'recsensorial/' . $recsensorial_id . '/plano/' . $recsensorial_id . $recsensorial_extension;

                        if (Storage::exists($rutaOriginal)) {
                            // Asegúrate de crear el directorio si no existe
                            $nuevaRuta = 'reconocimiento_psico/' . $reconocimientopsico->id . '/plano/' . $reconocimientopsico->id . '.' . pathinfo($rutaOriginal, PATHINFO_EXTENSION);
        
                            Storage::makeDirectory('reconocimiento_psico/' . $reconocimientopsico->id . '/plano');
        
                            // Copiar la imagen a la nueva ubicación
                            Storage::copy($rutaOriginal, $nuevaRuta);
                            
                            // Actualiza la base de datos con la nueva ruta
                            $reconocimientopsico->fotoplano = $nuevaRuta;
                            $reconocimientopsico->update($request->all());
                        } else {
                            // Manejar caso en el que la imagen original no existe
                            // Puedes lanzar una excepción o asignar un valor predeterminado
                            throw new Exception("La imagen original no existe en la ruta: " . $rutaOriginal);
                        }
                    }

                    // si envia archivo FOTO instalacion
                    if ($request->file('inputfotoinstalacion')) {
                        $extension = $request->file('inputfotoinstalacion')->getClientOriginalExtension();
                        $request['fotoinstalacion'] = $request->file('inputfotoinstalacion')->storeAs('reconocimiento_psico/' . $reconocimientopsico->id . '/instalacion', $reconocimientopsico->id . '.' . $extension);
                        $reconocimientopsico->update($request->all());
                    }else{
                        $recsensorial_extension = $request['hidden_fotoinstalacion_extension'];
                        $recsensorial_id = $request['hidden_fotoinstalacion'];
                        $rutaOriginal = 'recsensorial/' . $recsensorial_id . '/instalacion/' . $recsensorial_id . $recsensorial_extension;

                        if (Storage::exists($rutaOriginal)) {
                            // Asegúrate de crear el directorio si no existe
                            $nuevaRuta = 'reconocimiento_psico/' . $reconocimientopsico->id . '/instalacion/' . $reconocimientopsico->id . '.' . pathinfo($rutaOriginal, PATHINFO_EXTENSION);
        
                            Storage::makeDirectory('reconocimiento_psico/' . $reconocimientopsico->id . '/instalacion');
        
                            // Copiar la imagen a la nueva ubicación
                            Storage::copy($rutaOriginal, $nuevaRuta);
                            
                            // Actualiza la base de datos con la nueva ruta
                            $reconocimientopsico->fotoinstalacion = $nuevaRuta;
                            $reconocimientopsico->update($request->all());
                        } else {
                            // Manejar caso en el que la imagen original no existe
                            // Puedes lanzar una excepción o asignar un valor predeterminado
                            throw new Exception("La imagen original no existe en la ruta: " . $rutaOriginal);
                        }
                    }
                } else { //EDITAR 

                    // Obtener registro
                    $reconocimientopsico = reconocimientopsicoModel::findOrFail($request->recsensorial_id);

                    // consultar ID ultimo registro de la tabla
                    $reconocimientopsico_idmax = DB::select('SELECT
                                                            MAX( reconocimientopsico.id ) AS reconocimientopsico_idmax
                                                        FROM
                                                            reconocimientopsico');

                    // Validar que sea el ultimo ID, y permita editar folios

                    $reconocimientopsico->update($request->all());
                    // $recsensorial->recsensorialpruebas()->sync($request->parametro);

                    ///VERIFICAMOS QUE EL FOLIO DEL PROYECTO QUE ENVIA SEA EL MISMO
                    if ($reconocimientopsico->proyecto_folio == $request['proyecto_folio']) {

                        $proyecto = proyectoModel::where('proyecto_folio', $request["proyecto_folio"])->first();
                        $proyecto->reconocimiento_psico_id = $reconocimientopsico->id;
                        $proyecto->save();
                    } else {


                        $proyecto = proyectoModel::where('proyecto_folio', $reconocimientopsico->proyecto_folio)->first();
                        $proyecto->reconocimiento_psico_id = null;
                        $proyecto->save();


                        $proyecto = proyectoModel::where('proyecto_folio', $request["proyecto_folio"])->first();
                        $proyecto->reconocimiento_psico_id = $reconocimientopsico->id;
                        $proyecto->save();
                    }



                    // if ($request->file('inputfotomapa')) {
                    //     $extension = $request->file('inputfotomapa')->getClientOriginalExtension();
                    //     $request['fotoubicacion'] = $request->file('inputfotomapa')->storeAs('reconocimiento_psico/' . $reconocimientopsico->id . '/mapa', $reconocimientopsico->id . '.' . $extension);
                    //     $reconocimientopsico->update($request->all());
                    // }

                    // // si envia archivo FOTO plano
                    // if ($request->file('inputfotoplano')) {
                    //     $extension = $request->file('inputfotoplano')->getClientOriginalExtension();
                    //     $request['fotoplano'] = $request->file('inputfotoplano')->storeAs('reconocimiento_psico/' . $reconocimientopsico->id . '/plano', $reconocimientopsico->id . '.' . $extension);
                    //     $reconocimientopsico->update($request->all());
                    // }

                    // // si envia archivo FOTO instalacion
                    // if ($request->file('inputfotoinstalacion')) {
                    //     $extension = $request->file('inputfotoinstalacion')->getClientOriginalExtension();
                    //     $request['fotoinstalacion'] = $request->file('inputfotoinstalacion')->storeAs('reconocimiento_psico/' . $reconocimientopsico->id . '/instalacion', $reconocimientopsico->id . '.' . $extension);
                    //     $reconocimientopsico->update($request->all());
                    // }

                    function eliminarArchivoAntiguo($id, $folder) {
                        // Definir la ruta del directorio
                        $directory = 'reconocimiento_psico/' . $id . '/' . $folder;
                    
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
                        $path = 'reconocimiento_psico/' . $reconocimientopsico->id . '/' . $folder . '/' . $reconocimientopsico->id . '.' . $extension;
                    
                        // Eliminar cualquier archivo antiguo, sin importar la extensión
                        eliminarArchivoAntiguo($reconocimientopsico->id, $folder);
                    
                        // Guardar la nueva foto
                        $request['fotoubicacion'] = $request->file('inputfotomapa')->storeAs('reconocimiento_psico/' . $reconocimientopsico->id . '/' . $folder, $reconocimientopsico->id . '.' . $extension);
                        $reconocimientopsico->update($request->all());
                    }
                    
                    // Para el archivo FOTO plano
                    if ($request->file('inputfotoplano')) {
                        $extension = $request->file('inputfotoplano')->getClientOriginalExtension();
                        $folder = 'plano';
                        $path = 'reconocimiento_psico/' . $reconocimientopsico->id . '/' . $folder . '/' . $reconocimientopsico->id . '.' . $extension;
                    
                        eliminarArchivoAntiguo($reconocimientopsico->id, $folder);
                    
                        $request['fotoplano'] = $request->file('inputfotoplano')->storeAs('reconocimiento_psico/' . $reconocimientopsico->id . '/' . $folder, $reconocimientopsico->id . '.' . $extension);
                        $reconocimientopsico->update($request->all());
                    }
                    
                    // Para el archivo FOTO instalación
                    if ($request->file('inputfotoinstalacion')) {
                        $extension = $request->file('inputfotoinstalacion')->getClientOriginalExtension();
                        $folder = 'instalacion';
                        $path = 'reconocimiento_psico/' . $reconocimientopsico->id . '/' . $folder . '/' . $reconocimientopsico->id . '.' . $extension;
                    
                        eliminarArchivoAntiguo($reconocimientopsico->id, $folder);
                    
                        $request['fotoinstalacion'] = $request->file('inputfotoinstalacion')->storeAs('reconocimiento_psico/' . $reconocimientopsico->id . '/' . $folder, $reconocimientopsico->id . '.' . $extension);
                        $reconocimientopsico->update($request->all());
                    }


                    // mensaje
                    $dato["msj"] = 'Información modificada correctamente';
                }

                



                // respuesta
                $dato['recsensorial_activo'] = $recsensorial_activo;
                $dato['recsensorial'] = $reconocimientopsico;
            }
            if (($request->opcion + 0) == 3) // RESPONSABLES DEL RECONOCIMIENTO
            {

                $reconocimientopsico = reconocimientopsicoModel::findOrFail($request->recsensorial_id);

                // dd($recsensorial->all());

                if ($request->NOMBRE_TECNICO) // RESPONSABLES DEL RECONOCIMIENTO
                {
                    if ($request->file('TECNICO_DOC_IMG')) {
                        $extension = $request->file('TECNICO_DOC_IMG')->getClientOriginalExtension();
                        $request['TECNICO_DOC'] = $request->file('TECNICO_DOC_IMG')->storeAs('reconocimiento_psico/' . $request->recsensorial_id . '/responsables', 'rep_tecnico.' . $extension);
                    }

                    if ($request->file('CONTRATO_DOC_IMG')) {
                        $extension = $request->file('CONTRATO_DOC_IMG')->getClientOriginalExtension();
                        $request['CONTRATO_DOC'] = $request->file('CONTRATO_DOC_IMG')->storeAs('reconocimiento_psico/' . $request->recsensorial_id . '/responsables', 'rep_admin.' . $extension);
                    }
                } else {
                    // Eliminar carpeta si acaso existio
                    Storage::deleteDirectory('reconocimiento_psico/' . $request->recsensorial_id . '/responsables');

                    $request['NOMBRE_TECNICO'] = NULL;
                    $request['NOMBRE_CONTRATO'] = NULL;
                    $request['CARGO_TECNICO'] = NULL;
                    $request['CARGO_CONTRATO'] = NULL;
                    $request['TECNICO_DOC'] = NULL;
                    $request['CONTRATO_DOC'] = NULL;
                }

                $reconocimientopsico->update($request->all());

                // respuesta
                $dato["msj"] = 'Datos de los responsables guardado correctamente';
                $dato['recsensorial'] = $reconocimientopsico;
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


  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tablareconocimientopsico(){
    try {
        // Obtener solo los campos de la tabla principal sin cargar relaciones
        $recsensorial = reconocimientopsicoModel::all(); // Obtiene todos los registros de la tabla principal

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
     * @param  int  $reconocimientopsico_id
     * @return \Illuminate\Http\Response
     */
    public function mostrarmapapsico($archivo_opcion, $reconocimientopsico_id)
    {
        $reconocimientopsico = reconocimientopsicoModel::findOrFail($reconocimientopsico_id);

        if (($archivo_opcion + 0) == 0) {
            return Storage::response($reconocimientopsico->fotoubicacion);
        } else {
            return Storage::download($reconocimientopsico->fotoubicacion);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $archivo_opcion
     * @param  int  $reconocimientopsico_id
     * @return \Illuminate\Http\Response
     */
    public function mostrarplanopsico($archivo_opcion, $reconocimientopsico_id)
    {
        $reconocimientopsico = reconocimientopsicoModel::findOrFail($reconocimientopsico_id);

        if (($archivo_opcion + 0) == 0) {

            return Storage::response($reconocimientopsico->fotoplano);
        } else {

            return Storage::download($reconocimientopsico->fotoplano);
        }
    }
       /**
     * Display the specified resource.
     *
     * @param  int  $archivo_opcion
     * @param  int  $reconocimientopsico_id
     * @return \Illuminate\Http\Response
     */
    public function mostrartecnicodoc($archivo_opcion, $reconocimientopsico_id)
    {
        $reconocimientopsico = reconocimientopsicoModel::findOrFail($reconocimientopsico_id);

        if (($archivo_opcion + 0) == 0) {

            return Storage::response($reconocimientopsico->TECNICO_DOC);
        } else {

            return Storage::download($reconocimientopsico->TECNICO_DOC);
        }
    }
       /**
     * Display the specified resource.
     *
     * @param  int  $archivo_opcion
     * @param  int  $reconocimientopsico_id
     * @return \Illuminate\Http\Response
     */
    public function mostrarcontratodoc($archivo_opcion, $reconocimientopsico_id)
    {
        $reconocimientopsico = reconocimientopsicoModel::findOrFail($reconocimientopsico_id);

        if (($archivo_opcion + 0) == 0) {

            return Storage::response($reconocimientopsico->CONTRATO_DOC);
        } else {

            return Storage::download($reconocimientopsico->CONTRATO_DOC);
        }
    }

        /**
     * Display the specified resource.
     *
     * @param  int  $archivo_opcion
     * @param  int  $reconocimientopsico_id
     * @return \Illuminate\Http\Response
     */
    public function mostrarfotoinstalacionpsico($archivo_opcion, $reconocimientopsico_id)
    {
        $reconocimientopsico = reconocimientopsicoModel::findOrFail($reconocimientopsico_id);

        if (($archivo_opcion + 0) == 0) {
            return Storage::response($reconocimientopsico->fotoinstalacion);
        } else {
            return Storage::download($reconocimientopsico->fotoinstalacion);
        }
    }

}

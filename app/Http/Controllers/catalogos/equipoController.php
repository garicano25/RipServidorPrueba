<?php

namespace App\Http\Controllers\catalogos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\catalogos\ProveedorModel;
use App\modelos\catalogos\EquipoModel;
use App\modelos\catalogos\EquiposDocumentosModel;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTime;
use DB;
use Exception;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;
//Recursos para abrir el Excel
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class equipoController extends Controller

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
    public function tablaproveedorequipo($proveedor_id)
    {
        try {
            // Proveedor
            $proveedor = ProveedorModel::findOrFail($proveedor_id);

            $equipos = EquipoModel::where('proveedor_id', $proveedor_id)
                ->where('equipo_Eliminado', 0)
                ->OrderBy('id', 'ASC')
                ->get();

            $numero_registro = 0;

            // recorrer la lista de equipos para formatear fechas
            foreach ($equipos  as $key => $value) {
                $numero_registro += 1;

                if ($value->equipo_EstadoActivo == 1) {
                    $value->EstadoActivo_texto = "Activo";
                } else {
                    $value->EstadoActivo_texto = '<p class="text-danger"><b>Inactivo</b></p>';
                }
                // Validar si el equipo esta asociado con un AGENTE
                // if (($value->acreditacionalcance_id + 0) > 0)
                // {
                //     $gente = $value->acreditacionalcance->prueba->catPrueba_Nombre;
                // }
                // else
                // {
                //     $gente = 'N/A';
                // }

                // Validar si el equipo cuenta con fecha de vigencia
                if ($value->equipo_VigenciaCalibracion) {
                    $vigencia = $value->equipo_VigenciaCalibracion;
                } else {
                    $vigencia = 'N/A';
                }

                // determinar los dias faltantes para vigencia
                $datetime1 = date_create(date('Y-m-d'));
                $datetime2 = date_create($value->equipo_VigenciaCalibracion);
                $interval = date_diff($datetime1, $datetime2);

                // alertas en los dias de la vigencia
                switch (($interval->format('%R%a') + 0)) {
                    case (($interval->format('%R%a') + 0) <= 30):
                        $value->numero_registro = $numero_registro;
                        // $value->agente = '<b class="text-danger">'.$gente.'</b>';
                        $value->descripcion = '<b class="text-danger">' . $value->equipo_Descripcion . '</b>';
                        $value->marca = '<b class="text-danger">' . $value->equipo_Marca . '</b>';
                        $value->modelo = '<b class="text-danger">' . $value->equipo_Modelo . '</b>';
                        $value->serie = '<b class="text-danger">' . $value->equipo_Serie . '</b>';
                        $value->Vigencia_texto = '<b class="text-danger">' . $value->equipo_VigenciaCalibracion . ' (' . ($interval->format('%R%a') + 0) . ' d)</b>';
                        break;
                    case (($interval->format('%R%a') + 0) <= 90):
                        $value->numero_registro = $numero_registro;
                        // $value->agente = '<b class="text-warning">'.$gente.'</b>';
                        $value->descripcion = '<b class="text-warning">' . $value->equipo_Descripcion . '</b>';
                        $value->marca = '<b class="text-warning">' . $value->equipo_Marca . '</b>';
                        $value->modelo = '<b class="text-warning">' . $value->equipo_Modelo . '</b>';
                        $value->serie = '<b class="text-warning">' . $value->equipo_Serie . '</b>';
                        $value->Vigencia_texto = '<b class="text-warning">' . $value->equipo_VigenciaCalibracion . ' (' . ($interval->format('%R%a') + 0) . ' d)</b>';
                        break;
                    default:
                        $value->numero_registro = $numero_registro;
                        // $value->agente = $gente;
                        $value->descripcion = $value->equipo_Descripcion;
                        $value->marca = $value->equipo_Marca;
                        $value->modelo = $value->equipo_Modelo;
                        $value->serie = $value->equipo_Serie;
                        $value->Vigencia_texto = $vigencia;
                        break;
                }

                // Validar si contiene archivo PDF
                // if ($value->equipo_CertificadoPDF) {
                //     $value->certificado_pdf = '<button type="button" class="btn btn-info btn-circle certificadopdf"><i class="fa fa-file-pdf-o"></i></button>';
                // } else {
                //     $value->certificado_pdf = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                // }


                // // Validar si contiene carta PDF
                // if ($value->equipo_cartaPDF) {
                //     $value->carta_pdf = '<button type="button" class="btn btn-success btn-circle cartapdf"><i class="fa fa-file-pdf-o"></i></button>';
                // } else {
                //     $value->carta_pdf = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                // }


                // Formato fecha
                // $value->equipo_FechaCalibracion = Carbon::createFromFormat('Y-m-d', $value->equipo_FechaCalibracion)->format('d-m-Y');
                // $value->equipo_VigenciaCalibracion = Carbon::createFromFormat('Y-m-d', $value->equipo_VigenciaCalibracion)->format('d-m-Y');

                // Valida perfil
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador'])) {
                    $value->perfil = 1;
                } else {
                    $value->perfil = 0;
                }

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador','Almacén']) && ($proveedor->proveedor_Bloqueado + 0) == 0) {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                } else {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-success btn-circle"><i class="fa fa-eye"></i></button>';
                }
            }

            // devolver datos
            $listado['data'] = $equipos;
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
     * @param  int  $acreditacion_id
     * @return \Illuminate\Http\Response
     */
    public function actualizacampoarea($proveedor_id, $acreditacion_id)
    {
        $listado['cat_areaacreditacion'] = "<option value=''></option>";
        // $listado['cat_areaacreditacion'] = NULL;

        $lista = DB::select('SELECT
                                acreditacion.id,
                                acreditacion.cat_area_id,
                                cat_area.catArea_Nombre,
                                acreditacion.acreditacion_Numero 
                            FROM
                                acreditacion
                                LEFT JOIN cat_area ON acreditacion.cat_area_id = cat_area.id 
                            WHERE
                                acreditacion.proveedor_id = ' . $proveedor_id . '
                            GROUP BY
                                acreditacion.id,
                                acreditacion.cat_area_id,
                                cat_area.catArea_Nombre,
                                acreditacion.acreditacion_Numero 
                            ORDER BY
                                acreditacion.cat_area_id ASC');

        foreach ($lista as $key => $value) {
            if ($value->id == $acreditacion_id) {
                $listado['cat_areaacreditacion'] .= '<option value="' . $value->id . '" selected>' . $value->catArea_Nombre . ' - [ Acreditación ' . $value->acreditacion_Numero . ' ]</option>';
            } else {
                $listado['cat_areaacreditacion'] .= '<option value="' . $value->id . '">' . $value->catArea_Nombre . ' - [ Acreditación ' . $value->acreditacion_Numero . ' ]</option>';
            }
        }

        return response()->json($listado);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $proveedor_id
     * @param  int  $acreditacion_id
     * @param  int  $prueba_id
     * @return \Illuminate\Http\Response
     */
    public function actualizacampoprueba($proveedor_id, $acreditacion_id, $prueba_id)
    {
        $listado['cat_prueba'] = "<option value=''></option>";

        $lista = DB::select('SELECT
                                acreditacion.proveedor_id,
                                acreditacionalcance.acreditacion_id,
                                acreditacion.cat_area_id,
                                cat_area.catArea_Nombre,
                                acreditacionalcance.prueba_id,
                                cat_prueba.catPrueba_Nombre 
                            FROM
                                acreditacionalcance
                                LEFT JOIN acreditacion ON acreditacionalcance.acreditacion_id = acreditacion.id
                                LEFT JOIN cat_area ON acreditacion.cat_area_id = cat_area.id
                                LEFT JOIN cat_prueba ON acreditacionalcance.prueba_id = cat_prueba.id
                            WHERE
                                acreditacion.proveedor_id = ' . $proveedor_id . ' AND acreditacionalcance.acreditacion_id = ' . $acreditacion_id . ' AND acreditacionalcance.acreditacionAlcance_Eliminado = 0
                            GROUP BY
                                acreditacion.proveedor_id,
                                acreditacionalcance.acreditacion_id,
                                acreditacion.cat_area_id,
                                cat_area.catArea_Nombre,
                                acreditacionalcance.prueba_id,
                                cat_prueba.catPrueba_Nombre 
                            ORDER BY
                                acreditacionalcance.prueba_id ASC');

        foreach ($lista as $key => $value) {
            if ($value->prueba_id == $prueba_id) {
                $listado['cat_prueba'] .= '<option value="' . $value->prueba_id . '" selected>' . $value->catPrueba_Nombre . '</option>';
            } else {
                $listado['cat_prueba'] .= '<option value="' . $value->prueba_id . '">' . $value->catPrueba_Nombre . '</option>';
            }
        }

        return response()->json($listado);
    }

    public function tablaequipodocumento($equipo_id)
    {
        try {
            // Equipo
            $equipo = EquipoModel::with(['proveedor'])->findOrFail($equipo_id);

            //Consulta tabla documentos
            $documentos = EquiposDocumentosModel::where('EQUIPO_ID', $equipo_id)
                ->where('ACTIVO', 1)
                ->get();

            $numero_registro = 0;
            foreach ($documentos  as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                // Valida perfil
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador'])) {
                    $value->perfil = 1;
                } else {
                    $value->perfil = 0;
                }

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador','Almacén']) && ($equipo->proveedor->proveedor_Bloqueado + 0) == 0) {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                } else {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-success btn-circle"><i class="fa fa-eye"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                }
            }

            $listado['data'] = $documentos;
            return response()->json($listado);
        } catch (exception $e) {
            $listado['data'] = 0;
            return response()->json($listado);
        }
    }


    public function mostrarpdf($documento_id)
    {
        $documento = EquiposDocumentosModel::findOrFail($documento_id);
        return Storage::response($documento->RUTA_DOCUMENTO);
    }



    public function mostrarFotoEquipo($equipo_id)
    {
        $foto = EquipoModel::findOrFail($equipo_id);
        return Storage::response($foto->equipo_imagen);
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
            // formatear campos fechas antes de guardar
            // $request['equipo_FechaCalibracion'] = Carbon::createFromFormat('d-m-Y', $request['equipo_FechaCalibracion'])->format('Y-m-d');
            // $request['equipo_VigenciaCalibracion'] = Carbon::createFromFormat('d-m-Y', $request['equipo_VigenciaCalibracion'])->format('Y-m-d');

            if ($request['api'] == 1) { #GUARDAMOS LA INFORMACION DEL EQUIPO

                if ($request['equipo_Eliminado'] == 0) //SI ES 0 VA A GUARDAR/EDITAR SI ES 1 VA A ELIMINAR
                {
                    if ($request['equipo_id'] == 0) //nuevo
                    {
                        // AUTO_INCREMENT
                        DB::statement('ALTER TABLE equipo AUTO_INCREMENT=1');
                        $equipo = EquipoModel::create($request->all());

                        if ($request->file('foto_equipo')) {


                            $extension = $request->file('foto_equipo')->getClientOriginalExtension();

                            $request['equipo_imagen'] = $request->file('foto_equipo')->storeAs('proveedores/' . $request['proveedor_id'] . '/equipos/' . $equipo->id, $equipo->id . '.' . $extension);

                            $equipo->update($request->all());
                        }



                        return response()->json($equipo);
                    } else //editar
                    {
                        $equipo = EquipoModel::findOrFail($request['equipo_id']);

                        if ($request->file('foto_equipo')) {

                            if (Storage::exists($equipo->equipo_imagen)) {
                                Storage::delete($equipo->equipo_imagen);
                            }

                            $extension = $request->file('foto_equipo')->getClientOriginalExtension();
                            $request['equipo_imagen'] = $request->file('foto_equipo')->storeAs('proveedores/' . $request['proveedor_id'] . '/equipos/' . $equipo->id, $equipo->id . '.' . $extension);
                        }




                        $equipo->update($request->all());
                        return response()->json($equipo);
                    }
                } else //eliminar
                {
                    $equipo = EquipoModel::findOrFail($request['equipo_id']);
                    $equipo->equipo_Eliminado = 1;
                    $equipo->save();
                    return response()->json($equipo);
                }
            } else if ($request['api'] == 2) { #GUARDAMOS LOS DOCUMENTOS DEL EQUIPO

                if ($request['ACTIVO'] == 1) //SI ES 1 ES PORQUE ESTA ACTIVO SI ES 0 ES PORQUE LO VAN A DESACTIVAR
                {

                    if ($request['ID_EQUIPO_DOCUMENTO'] == 0) //nuevo
                    {
                        // AUTO_INCREMENT
                        DB::statement('ALTER TABLE equipos_documentos AUTO_INCREMENT=1');
                        $request['FECHA_CARGA'] = date("Y-m-d");

                        $equipoDocumento = EquiposDocumentosModel::create($request->all());

                        if ($request->file('EQUIPO_PDF')) {

                            $extension = $request->file('EQUIPO_PDF')->getClientOriginalExtension();
                            $request['RUTA_DOCUMENTO'] = $request->file('EQUIPO_PDF')->storeAs('proveedores/' . $request['proveedor_id'] . '/equipos/' . $request['EQUIPO_ID'] . '/documentos', $equipoDocumento->ID_EQUIPO_DOCUMENTO . '.' . $extension);

                            $equipoDocumento->update($request->all());
                        }

                        return response()->json($equipoDocumento);
                    } else //editar
                    {
                        $equipoDocumento = EquiposDocumentosModel::findOrFail($request['ID_EQUIPO_DOCUMENTO']);

                        if ($request->file('EQUIPO_PDF')) {
                            $extension = $request->file('EQUIPO_PDF')->getClientOriginalExtension();
                            $request['RUTA_DOCUMENTO'] = $request->file('EQUIPO_PDF')->storeAs('proveedores/' . $request['proveedor_id'] . '/equipos/' . $request['EQUIPO_ID'] . '/documentos', $equipoDocumento->ID_EQUIPO_DOCUMENTO . '.' . $extension);
                        }

                        $equipoDocumento->update($request->all());
                        return response()->json($equipoDocumento);
                    }
                } else //eliminar
                {
                    $equipoDocumento = EquiposDocumentosModel::findOrFail($request['ID_EQUIPO_DOCUMENTO']);
                    $equipoDocumento->update($request->all());
                    return response()->json($equipoDocumento);
                }
            } else if ($request['api'] == 3) { //Guardamos los equipos por medio de un Excel

                try {
                    // Verificar si hay un archivo en la solicitud
                    if ($request->hasFile('excelEquipos')) {
                        
                        // Obtenemos el Excel de los equipo
                        $excel = $request->file('excelEquipos');

                        // Cargamos el archivo usando la libreria de PhpSpreadsheet
                        $spreadsheet = IOFactory::load($excel->getPathname());
                        $sheet = $spreadsheet->getActiveSheet();
                        $data = $sheet->toArray(null, true, true, true);

                        // Eliminar la primera fila (encabezados)
                        array_shift($data);

                        //Obtenemos todos los datos del Excel y los almacenamos datos sin imagenes
                        $datosGenerales = [];
                        foreach ($data as $row) {
                            // Verificar si la fila no está completamente vacía
                            if (!empty(array_filter($row))) {

                                array_shift($row); //Primero fila la eliminamos

                                if (count($row) > 1) {
                                    array_splice($row, -2, 1); //Penultima fila la eliminamos
                                }
                                array_pop($row); //Ultima fila la eliminamos


                                // Almacenar la fila limpia en el array
                                $datosGenerales[] = $row;
                            }
                        }

                        // Extraer y guardar imágenes
                        $drawings = $sheet->getDrawingCollection();
                        $imagenes = [];
                        foreach ($drawings as $drawing) {
                            // Determinar la celda en la que está la imagen
                            $coordinates = $drawing->getCoordinates();

                            if ($drawing instanceof MemoryDrawing) {
                                ob_start();
                                call_user_func($drawing->getRenderingFunction(), $drawing->getImageResource());
                                $imageContents = ob_get_contents();
                                ob_end_clean();

                                $extension = 'png';
                                switch ($drawing->getMimeType()) {
                                    case MemoryDrawing::MIMETYPE_PNG:
                                        $extension = 'png';
                                        break;
                                    case MemoryDrawing::MIMETYPE_GIF:
                                        $extension = 'gif';
                                        break;
                                    case MemoryDrawing::MIMETYPE_JPEG:
                                        $extension = 'jpg';
                                        break;
                                }

                                //Creamos la ruta de la imagen
                                $filename = 'equipo_' . uniqid() . '.' . $extension;
                                $path = 'proveedores/' . $request['proveedor_id'] . '/equipos/imagenesExcel/' . $filename;
                                //Almacenmos la imagen
                                Storage::put($path, $imageContents);
                            } elseif ($drawing instanceof Drawing) {
                                $zipReader = fopen($drawing->getPath(), 'r');
                                $imageContents = '';
                                while (!feof($zipReader)) {
                                    $imageContents .= fread($zipReader, 1024);
                                }
                                fclose($zipReader);

                                //Creamos la ruta de la imagen
                                $extension = $drawing->getExtension();
                                $filename = 'equipo_' . uniqid() . '.' . $extension;
                                $path = 'proveedores/' . $request['proveedor_id'] . '/equipos/imagenesExcel/' . $filename;

                                //Almacenamos la imagen
                                Storage::put($path, $imageContents);
                            }

                            // Asocia la imagen a la coordenada correspondiente
                            $imagenes[] = $path;
                        }




                        // return response()->json(["data" => $datosGenerales]);

                        //Equipos totales
                        $totalEquipos = count($datosGenerales);
                        $equipoInsertados = 0;

                        //================================= Funciones de limpieaza de datos =================================

                        //FUNCION PARA SELECCIONAR EL USO DEL EQUIPO SEGUN LO ESCRITO
                        function usoEquipo($uso)
                        {
                            $usoModificado = trim(mb_strtoupper($uso, 'UTF-8'));
                            $usoModificado = preg_replace('/\s+/', '', $usoModificado);

                            if ($usoModificado == 'MEDICION' || $usoModificado == 'MEDICIÓN') {
                                return 1;
                            } else if ($usoModificado == "MUESTREO") {
                                return 2;
                            } else if ($usoModificado == "COMUNICACION" || $usoModificado == "COMUNICACIÓN") {
                                return 3;
                            } else if ($usoModificado == "COMPUTO") {
                                return 4;
                            } else {

                                return 0;
                            }
                        }

                        //FUNCION PARA SELECCIONAR LA UNIDAD DEL EQUIPO SEGUN LA OPCION SELECCIONADA
                        function medidaEquipo($medida)
                        {

                            $medidaModificado = trim(strtoupper($medida));
                            if ($medidaModificado == 'KILOGRAMOS' || $medidaModificado == 'KILOS' || $medidaModificado == "KG" || $medidaModificado == "KILO") {
                                return 1;
                            } else if ($medidaModificado == "GRAMOS" || $medidaModificado == "G" || $medidaModificado == "GR" || $medidaModificado == "GRAMO") {
                                return 2;
                            } else if ($medidaModificado == "LIBRAS" || $medidaModificado == "LIBRA" || $medidaModificado == "LB") {
                                return 3;
                            }
                        }

                        //FUNCION PARA LIMPIAR EL COSTO DEL EQUIPO
                        function costoEquipo($costo)
                        {

                            $simbolos = array('$', ',');
                            $valor = array('', '');
                            $costoLimpio = str_replace($simbolos, $valor, $costo);

                            return floatval($costoLimpio);
                        }

                        //FUNCION PARA VALIDAR LA OPCION DE REQUIERE FACTURA O NO
                        function requiereCalibracion($opcion)
                        {

                            if (is_numeric($opcion)) {

                                $opcionLimpia = intval($opcion) == 0 ? "No" : "Si";
                            } else {
                                $opcionn = mb_strtoupper($opcion, 'UTF-8');

                                if ($opcionn == 'SI' || $opcionn == 'SÍ') {
                                    $opcionLimpia = 'Si';
                                } else {
                                    $opcionLimpia = 'No';
                                }
                            }
                            return $opcionLimpia;
                        }

                        //Validamos las fechas en diferentes formatos
                        function validarFecha($date, $formatosValidos = ['Y-m-d', 'd-m-Y', 'Y/m/d', 'd/m/Y'], $formatoFinal = 'Y-m-d')
                        {
                            foreach ($formatosValidos as $formato) {
                                $d = DateTime::createFromFormat($formato, $date);
                                if ($d && $d->format($formato) === $date) {
                                    return $d->format($formatoFinal);
                                }
                            }
                            return null;
                        }


                        function validarFotoRequerida($opcion)
                        {

                            if (is_numeric($opcion)) {

                                $opcionLimpia = intval($opcion) == 0 ? false : true;
                            } else {
                                $opcionn = mb_strtoupper($opcion, 'UTF-8');

                                if ($opcionn == 'SI' || $opcionn == 'SÍ') {
                                    $opcionLimpia = true;
                                } else {
                                    $opcionLimpia = false;
                                }
                            }
                            return $opcionLimpia;
                        }

                        $posicionImagenEquipo = 0;

                        $requiere = [];
                        //Limpiamos, Validamos y Insertamos todos los datos del Excel
                        foreach ($datosGenerales as $rowData) {

                            //Limpiamos los campos con mas complejidad
                            $usoEquipo = usoEquipo($rowData['C']);
                            $unidadMedida = medidaEquipo($rowData['I']);
                            $costoEquipo = costoEquipo($rowData['J']);
                            $requiereCalibracion = requiereCalibracion($rowData['L']);
                            $fechaCalibracion = validarFecha($rowData['N']);
                            $fechaVigencia = validarFecha($rowData['O']);


                            //VALIDAMOS SI REQUIERE FOTO EN ESTE CASO (TRUE)
                            if (validarFotoRequerida($rowData['P'])) {

                                EquipoModel::create([
                                    'proveedor_id' => $request['proveedor_id'],
                                    'equipo_Descripcion' => is_null($rowData['B']) ? "EL NOMBRE DEL EQUIPO NO FUE ESPECIFICADO EN EL EXCEL" : $rowData['B'],
                                    'equipo_uso' => $usoEquipo,
                                    'equipo_Marca' => is_null($rowData['D']) ? "LA MARCA DEL EQUIPO NO FUE ESPECIFICADA EN EL EXCEL" : $rowData['D'],
                                    'equipo_Modelo' => is_null($rowData['E']) ? "EL MODELO DEL EQUIPO NO FUE ESPECIFICADO EN EL EXCEL" : $rowData['E'],
                                    'equipo_Serie' => is_null($rowData['F']) ? "LA SERIE DEL EQUIPO NO FUE ESPECIFICADA EN EL EXCEL" : $rowData['F'],
                                    'numero_inventario' => is_null($rowData['G']) ? "EL No DE INVENTARIO NO FUE ESPECIFICADO EN EL EXCEL" : $rowData['G'],
                                    'equipo_PesoNeto' => is_null($rowData['H']) ? null : floatval($rowData['H']),
                                    'unidad_medida' => $unidadMedida,
                                    'equipo_CostoAprox' => $costoEquipo,
                                    'folio_factura' => is_null($rowData['K']) ? null : floatval($rowData['K']),
                                    'requiere_calibracion' => $requiereCalibracion,
                                    'equipo_TipoCalibracion' => is_null($rowData['M']) ? null : $rowData['M'],
                                    'equipo_FechaCalibracion' => $fechaCalibracion,
                                    'equipo_VigenciaCalibracion' => $fechaVigencia,
                                    'equipo_imagen' => $imagenes[$posicionImagenEquipo],

                                ]);

                                $posicionImagenEquipo++;
                            } else { // EN ESTE CASO (FALSE)

                                EquipoModel::create([
                                    'proveedor_id' => $request['proveedor_id'],
                                    'equipo_Descripcion' => is_null($rowData['B']) ? "EL NOMBRE DEL EQUIPO NO FUE ESPECIFICADO EN EL EXCEL" : $rowData['B'],
                                    'equipo_uso' => $usoEquipo,
                                    'equipo_Marca' => is_null($rowData['D']) ? "LA MARCA DEL EQUIPO NO FUE ESPECIFICADA EN EL EXCEL" : $rowData['D'],
                                    'equipo_Modelo' => is_null($rowData['E']) ? "EL MODELO DEL EQUIPO NO FUE ESPECIFICADO EN EL EXCEL" : $rowData['E'],
                                    'equipo_Serie' => is_null($rowData['F']) ? "LA SERIE DEL EQUIPO NO FUE ESPECIFICADA EN EL EXCEL" : $rowData['F'],
                                    'numero_inventario' => is_null($rowData['G']) ? "EL No DE INVENTARIO NO FUE ESPECIFICADO EN EL EXCEL" : $rowData['G'],
                                    'equipo_PesoNeto' => is_null($rowData['H']) ? null : floatval($rowData['H']),
                                    'unidad_medida' => $unidadMedida,
                                    'equipo_CostoAprox' => $costoEquipo,
                                    'folio_factura' => is_null($rowData['K']) ? null : floatval($rowData['K']),
                                    'requiere_calibracion' => $requiereCalibracion,
                                    'equipo_TipoCalibracion' => is_null($rowData['M']) ? null : $rowData['M'],
                                    'equipo_FechaCalibracion' => $fechaCalibracion,
                                    'equipo_VigenciaCalibracion' => $fechaVigencia,

                                ]);
                            }

                            // $requiere[] = $rowData['O']; 

                            $equipoInsertados++;
                        }

                        // Convierte el array $uso a una cadena de texto para la respuesta JSON
                        // $usoString = implode(', ', $requiere);
                        return response()->json(['msj' => 'Total de equipos agregados : ' . $equipoInsertados . ' de ' . $totalEquipos, 'code' => 200]);
                    } else {

                        return response()->json(["msj" => 'No se ha subido ningún archivo', "code" => 500]);
                    }
                } catch (Exception $e) {

                    return response()->json(['msj' => 'Se produjo un error al intentar cargar los equipos, inténtelo de nuevo o comuníquelo con el responsable ' . ' ---- ' . $e->getMessage(), 'code' => 500]);
                }
            }
        } catch (Exception $e) {

            return response()->json('Error al guardar');
        }
    }
}

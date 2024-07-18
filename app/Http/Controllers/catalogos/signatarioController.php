<?php

namespace App\Http\Controllers\catalogos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\catalogos\ProveedorModel;
use App\modelos\catalogos\SignatarioModel;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTime;
use DB;

//Recursos para abrir el Excel
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class signatarioController extends Controller
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
     * @param  int  $proveedor_id
     * @return \Illuminate\Http\Response
     */
    public function tablaproveedorsignatario($proveedor_id)
    {
        try {
            // Proveedor
            $proveedor = ProveedorModel::findOrFail($proveedor_id);

            $signatarios = SignatarioModel::with(['cat_signatariodisponibilidad'])
                ->where('proveedor_id', $proveedor_id)
                ->where('signatario_Eliminado', 0)
                ->get();

            $numero_registro = 1;
            foreach ($signatarios as $key => $value) {
                $value->numero_registro = $numero_registro;
                $numero_registro += 1;

                if ($value->signatario_EstadoActivo == 1) {
                    $value->EstadoActivo_texto = "Activo";
                } else {
                    $value->EstadoActivo_texto = '<p class="text-danger"><b>Inactivo</b></p>';
                }

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras','Almacén']) && ($proveedor->proveedor_Bloqueado + 0) == 0) {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                } else {
                    $value->accion_activa = 0;
                    $value->boton_editar = '<button type="button" class="btn btn-success btn-circle"><i class="fa fa-eye"></i></button>';
                }
            }

            $listado['data'] = $signatarios;
            return response()->json($listado);
        } catch (exception $e) {
            $listado['data'] = 0;
            return response()->json($listado);
        }
    }








    /**
     * Display the specified resource.
     *
     * @param  int  $signatario_id
     * @return \Illuminate\Http\Response
     */
    public function mostrarfoto($signatario_id)
    {
        $foto = SignatarioModel::findOrFail($signatario_id);
        return Storage::response($foto->signatario_Foto);
    }


    public function store(Request $request)
    {
        try {
            if ($request['api'] == 1){

                if ($request['signatario_Eliminado'] == 0) //valida eliminacion
                {
    
                    if ($request['signatario_id'] == 0) //nuevo
                    {
                        // AUTO_INCREMENT
                        DB::statement('ALTER TABLE signatario AUTO_INCREMENT=1');
                        $signatario = SignatarioModel::create($request->all());
    
                        if ($request->file('signatariofoto')) {
                            $extension = $request->file('signatariofoto')->getClientOriginalExtension();
                            $request['signatario_Foto'] = $request->file('signatariofoto')->storeAs('proveedores/' . $request['proveedor_id'] . '/signatarios/' . $request['signatario_id'] . '/foto', $signatario->id . '.' . $extension);
    
                            $signatario->update($request->all());
                        }
    
                        return response()->json($signatario);
                    } else //editar
                    {
                        $signatario = SignatarioModel::findOrFail($request['signatario_id']);
    
                        if ($request->file('signatariofoto')) {

                            if (Storage::exists($signatario->signatario_Foto)) {
                                Storage::delete($signatario->signatario_Foto);
                            }

                            $extension = $request->file('signatariofoto')->getClientOriginalExtension();
                            $request['signatario_Foto'] = $request->file('signatariofoto')->storeAs('proveedores/' . $request['proveedor_id'] . '/signatarios/' . $request['signatario_id'] . '/foto', $signatario->id . '.' . $extension);
                        }
    
                        $signatario->update($request->all());
                        return response()->json($signatario);
                    }
                } else //eliminar
                {
                    $signatario = SignatarioModel::findOrFail($request['signatario_id']);
                    $signatario->update($request->all());
                    return response()->json($signatario);
                }
            
            } else { //CARGAMOS LA LISTA DE PERSONALES POR MEDIO DE UN EXCEL

                try {

                    // Verificar si hay un archivo en la solicitud
                    if ($request->hasFile('excelPersonales')) {
                        
                        // Obtenemos el Excel de los personales
                        $excel = $request->file('excelPersonales');

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
                                $filename = 'personal' . uniqid() . '.' . $extension;
                                $path = 'proveedores/' . $request['proveedor_id'] . '/signatarios/imagenesExcel/' . $filename;
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
                                $filename = 'personal' . uniqid() . '.' . $extension;
                                $path = 'proveedores/' . $request['proveedor_id'] . '/signatarios/imagenesExcel/' . $filename;

                                //Almacenamos la imagen
                                Storage::put($path, $imageContents);
                            }

                            // Asocia la imagen a la coordenada correspondiente
                            $imagenes[] = $path;
                        }


                        //Personales totales
                        $totalPersonal = count($datosGenerales);
                        $personalInsertados = 0;

                        //================================= Funciones de limpieaza de datos =================================

                        //FUNCION PARA VALIDAR LA OPCION DE REQUIERE FACTURA O NO
                        function validarPersonalApoyo($opcion)
                        {

                            if (is_numeric($opcion)) {

                                $opcionLimpia = intval($opcion) == 0 ? 0 : 1;
                            } else {
                                $opcionn = mb_strtoupper($opcion, 'UTF-8');

                                if ($opcionn == 'SI' || $opcionn == 'SÍ') {
                                    $opcionLimpia = 1;
                                } else {
                                    $opcionLimpia = 0;
                                }
                            }
                            return $opcionLimpia;
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


                        $posicionImagenPersonal = 0;

                        $requiere = [];
                        //Limpiamos, Validamos y Insertamos todos los datos del Excel
                        foreach ($datosGenerales as $rowData) {

                            //Limpiamos los campos con mas complejidad
                            $pesonalApoyo = validarPersonalApoyo($rowData['I']);
                           

                            //VALIDAMOS SI REQUIERE FOTO EN ESTE CASO (TRUE)
                            if (validarFotoRequerida($rowData['O'])) {

                                SignatarioModel::create([
                                    'proveedor_id' => $request['proveedor_id'],
                                    'signatario_Nombre' => is_null($rowData['B']) ? "EL NOMBRE DEL PESONAL NO FUE ESPECIFICADO EN EL EXCEL" : $rowData['B'],
                                    'signatario_Cargo' => is_null($rowData['C']) ? "EL CARGO DEL PESONAL NO FUE ESPECIFICADO EN EL EXCEL" : $rowData['C'],
                                    'signatario_Telefono' => is_null($rowData['D']) ? null : $rowData['D'],
                                    'signatario_Correo' => is_null($rowData['E']) ? null : $rowData['E'],
                                    'signatario_Rfc' => is_null($rowData['F']) ? "EL RFC DEL SIGNATARIO NO FUE ESPECIFICADO EN EL EXCEL" : $rowData['F'],
                                    'signatario_Curp' => is_null($rowData['G']) ? "LA CURP DEL SIGNATARIO NO FUE ESPECIFICADA EN EL EXCEL" : $rowData['G'],
                                    'signatario_Nss' => is_null($rowData['H']) ? null : $rowData['H'],
                                    'signatario_apoyo' => $pesonalApoyo,
                                    'signatario_TipoSangre' => is_null($rowData['J']) ? null : $rowData['J'],
                                    'signatario_Alergias' => is_null($rowData['K']) ? "Negativo" : $rowData['K'],
                                    'signatario_telEmergencia' => is_null($rowData['L']) ? null : $rowData['L'],
                                    'signatario_NombreContacto' => is_null($rowData['M']) ? "No especificado" : $rowData['M'],
                                    'signatario_parentesco' => is_null($rowData['N']) ? "No especificado" : $rowData['N'],
                                    'signatario_Foto' => $imagenes[$posicionImagenPersonal],

                                ]);

                                $posicionImagenPersonal++;
                            } else { // EN ESTE CASO (FALSE)

                                SignatarioModel::create([
                                    'proveedor_id' => $request['proveedor_id'],
                                    'signatario_Nombre' => is_null($rowData['B']) ? "EL NOMBRE DEL PESONAL NO FUE ESPECIFICADO EN EL EXCEL" : $rowData['B'],
                                    'signatario_Cargo' => is_null($rowData['C']) ? "EL CARGO DEL PESONAL NO FUE ESPECIFICADO EN EL EXCEL" : $rowData['C'],
                                    'signatario_Telefono' => is_null($rowData['D']) ? null : $rowData['D'],
                                    'signatario_Correo' => is_null($rowData['E']) ? null : $rowData['E'],
                                    'signatario_Rfc' => is_null($rowData['F']) ? "EL RFC DEL SIGNATARIO NO FUE ESPECIFICADO EN EL EXCEL" : $rowData['F'],
                                    'signatario_Curp' => is_null($rowData['G']) ? "LA CURP DEL SIGNATARIO NO FUE ESPECIFICADA EN EL EXCEL" : $rowData['G'],
                                    'signatario_Nss' => is_null($rowData['H']) ? null : $rowData['H'],
                                    'signatario_apoyo' => $pesonalApoyo,
                                    'signatario_TipoSangre' => is_null($rowData['J']) ? null : $rowData['J'],
                                    'signatario_Alergias' => is_null($rowData['K']) ? "Negativo" : $rowData['K'],
                                    'signatario_telEmergencia' => is_null($rowData['L']) ? null : $rowData['L'],
                                    'signatario_NombreContacto' => is_null($rowData['M']) ? "No especificado" : $rowData['M'],
                                    'signatario_parentesco' => is_null($rowData['N']) ? "No especificado" : $rowData['N'],

                                ]);

                            }

                            $personalInsertados++;
                        }
                        
                        //RETORNAMOS UN MENSAJE DE CUANTOS INSERTO 
                        return response()->json(['msj' => 'Total de personales agregados : ' . $personalInsertados . ' de ' . $totalPersonal, 'code' => 200]);
                    } else {

                        return response()->json(["msj" => 'No se ha subido ningún archivo Excel', "code" => 500]);
                    }
                } catch (Exception $e) {

                    return response()->json(['msj' => 'Se produjo un error al intentar cargar los personales, inténtelo de nuevo o comuníquelo con el responsable ' . ' ---- ' . $e->getMessage(), 'code' => 500]);
                }

            }

        } catch (Exception $e) {
            return response()->json('Error al guardar');
        }
    }
}

<?php

namespace App\Http\Controllers\catalogos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\catalogos\SignatarioModel;
use App\modelos\catalogos\SignatariocursoModel;
use App\modelos\catalogos\CursosDocumentosValidacionModel;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTime;
use DB;

class signatariocursoController extends Controller
{
    




    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo');        
    }








    /**
     * Display the specified resource.
     *
     * @param  int  $signatario_id
     * @return \Illuminate\Http\Response
     */
    public function tablasignatariocurso($signatario_id)
    {        
        try
        {
            // Signatario
            $signatario = SignatarioModel::with(['proveedor'])->findOrFail($signatario_id);
            
            //Consulta tabla cursos
            $cursos = SignatariocursoModel::where('signatario_id', $signatario_id)
                                            ->where('signatarioCurso_Eliminado', 0)
                                            ->get();

            $numero_registro = 0;

            // recorrer las acreditaciones para formatear fechas
            foreach ($cursos as $key => $value) 
            {
                $numero_registro += 1;
                
                // Formatear fecha
                // $value->signatarioCurso_FechaExpedicion = Carbon::createFromFormat('Y-m-d', $value->signatarioCurso_FechaExpedicion)->format('d-m-Y');
                // $value->signatarioCurso_FechaVigencia = Carbon::createFromFormat('Y-m-d', $value->signatarioCurso_FechaVigencia)->format('d-m-Y');

                // determinar los dias faltantes para vigencia
                $datetime1 = date_create(date('Y-m-d'));
                $datetime2 = date_create($value->signatarioCurso_FechaVigencia);
                $interval = date_diff($datetime1, $datetime2);
                // $value->Vigencia_texto = ($interval->format('%R%a')+0);

                // alertas en los dias de la vigencia
                switch (($interval->format('%R%a')+0))
                {
                    case (($interval->format('%R%a')+0) <= 30):
                        $value->Vigencia_texto = '<p class="text-danger"><b>'.$value->signatarioCurso_FechaVigencia.' ('.($interval->format('%R%a')+0).' d)</b></p>';
                        break;
                    case (($interval->format('%R%a')+0) <= 90):
                        $value->Vigencia_texto = '<p class="text-warning"><b>'.$value->signatarioCurso_FechaVigencia.' ('.($interval->format('%R%a')+0).' d)</b></p>';
                        break;                    
                    default:
                        $value->Vigencia_texto = $value->signatarioCurso_FechaVigencia;
                        break;
                }

                // Color registro segun vigencia
                switch (($interval->format('%R%a')+0))
                {
                    case (($interval->format('%R%a')+0) <= 30):
                        $value->numero_registro = $numero_registro;
                        $value->curso = '<b class="text-danger">'.$value->signatarioCurso_NombreCurso.'</b>';
                        $value->expedicion = '<b class="text-danger">'.$value->signatarioCurso_FechaExpedicion.'</b>';
                        $value->Vigencia_texto = '<b class="text-danger">'.$value->signatarioCurso_FechaVigencia.' ('.($interval->format('%R%a')+0).' d)</b>';
                        break;
                    case (($interval->format('%R%a')+0) <= 90):
                        $value->numero_registro = $numero_registro;
                        $value->curso = '<b class="text-warning">'.$value->signatarioCurso_NombreCurso.'</b>';
                        $value->expedicion = '<b class="text-warning">'.$value->signatarioCurso_FechaExpedicion.'</b>';
                        $value->Vigencia_texto = '<b class="text-warning">'.$value->signatarioCurso_FechaVigencia.' ('.($interval->format('%R%a')+0).' d)</b>';
                        break;                    
                    default:
                        $value->numero_registro = $numero_registro;
                        $value->curso = $value->signatarioCurso_NombreCurso;
                        $value->expedicion = $value->signatarioCurso_FechaExpedicion;
                        $value->Vigencia_texto = $value->signatarioCurso_FechaVigencia;
                        break;
                }

                // Valida perfil
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'CoordinadorHI']))
                {
                    $value->perfil = 1;
                }
                else
                {
                    $value->perfil = 0;
                }

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras','Almacén']) && ($signatario->proveedor->proveedor_Bloqueado + 0) == 0)
                {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                    $value->boton_validacion = '<button type="button" class="btn btn-success btn-circle  " style="background-color: green; border-color: green;">
                    <i class="fa fa-files-o" aria-hidden="true" style="color: white; font-size: 20px;"></i> </button>';

                }
                else
                {
                    $value->accion_activa = 0;
                    $value->boton_editar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                    $value->boton_validacion = '<button type="button" class="btn btn-success btn-circle  " style="background-color: green; border-color: green;">
                    <i class="fa fa-files-o" aria-hidden="true" style="color: white; font-size: 20px;"></i> </button>';
                }
            }

            $listado['data'] = $cursos;
            return response()->json($listado);
        }
        catch(exception $e){
            $listado['data'] = 0;
            return response()->json($listado);
        }
    }


    //PARA LOS DOCUMENTOS DE VALIDACCION DEL CURSO
    public function tablasignatariocursovalidacion($curso_id, $signatario_id)
    {
        try {

            $signatario = SignatarioModel::with(['proveedor'])->findOrFail($signatario_id);

            
            //Consulta tabla cursos
            $cursos = CursosDocumentosValidacionModel::where('CURSO_ID', $curso_id)
                ->where('ELIMINADO', 0)
                ->get();

            $numero_registro = 0;

            // recorrer las acreditaciones para formatear fechas
            foreach ($cursos as $key => $value) {
                $numero_registro += 1;

                

                // Valida perfil
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'CoordinadorHI'])) {
                    $value->perfil = 1;
                } else {
                    $value->perfil = 0;
                }

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras','Almacén']) && ($signatario->proveedor->proveedor_Bloqueado + 0) == 0) {
                    $value->accion_activa = 1;
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                   
                } else {
                    $value->accion_activa = 0;
                    $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                    
                }
                $value->numero_registro = $numero_registro;

            }

            $listado['data'] = $cursos;
            return response()->json($listado);
        } catch (exception $e) {
            $listado['data'] = 0;
            return response()->json($listado);
        }
    }









    /**
     * Display the specified resource.
     *
     * @param  int  $curso_id
     * @return \Illuminate\Http\Response
     */
    public function mostrarpdf($curso_id)
    {
        $curso = SignatariocursoModel::findOrFail($curso_id);
        return Storage::response($curso->signatarioCurso_SoportePDF);
    }

    public function mostrarpdfvalidacion($documento_curso_id)
    {
        $curso = CursosDocumentosValidacionModel::findOrFail($documento_curso_id);
        return Storage::response($curso->RUTA_PDF);
    }



    public function store(Request $request)
    {
        //
        try
        {
            // formatear campos fechas antes de guardar
            // $request['signatarioCurso_FechaExpedicion'] = Carbon::createFromFormat('d-m-Y', $request['signatarioCurso_FechaExpedicion'])->format('Y-m-d');
            // $request['signatarioCurso_FechaVigencia'] = Carbon::createFromFormat('d-m-Y', $request['signatarioCurso_FechaVigencia'])->format('Y-m-d');

            if ($request['api'] == 1){ //CURSOS

                if ($request['signatarioCurso_Eliminado']==0)//valida eliminacion
                {

                    if ($request['curso_id']==0)//nuevo
                    {
                        // AUTO_INCREMENT
                        DB::statement('ALTER TABLE signatariocurso AUTO_INCREMENT=1');
                        $signatariocurso = SignatariocursoModel::create($request->all());

                        if ($request->file('signatariocursopdf'))
                        {
                            $extension = $request->file('signatariocursopdf')->getClientOriginalExtension();
                            $request['signatarioCurso_SoportePDF'] = $request->file('signatariocursopdf')->storeAs('proveedores/'.$request['proveedor_id'].'/signatarios/'.$request['signatario_id'].'/cursos', $signatariocurso->id.'.'.$extension);

                            $signatariocurso->update($request->all());
                        }
                        
                        return response()->json($signatariocurso);
                    }
                    else //editar
                    {
                        $signatariocurso = SignatariocursoModel::findOrFail($request['curso_id']);

                        if ($request->file('signatariocursopdf'))
                        {
                            $extension = $request->file('signatariocursopdf')->getClientOriginalExtension();
                            $request['signatarioCurso_SoportePDF'] = $request->file('signatariocursopdf')->storeAs('proveedores/'.$request['proveedor_id'].'/signatarios/'.$request['signatario_id'].'/cursos', $signatariocurso->id.'.'.$extension);
                        }
                        
                        $signatariocurso->update($request->all());
                        return response()->json($signatariocurso);
                    }
                }
                else //eliminar
                {
                    $signatariocurso = SignatariocursoModel::findOrFail($request['curso_id']);
                    $signatariocurso->update($request->all());
                    return response()->json($signatariocurso);
                }
            
            }else{ //DOCUMENTOS DE VALIDACION DEL CURSO

                if ($request['ELIMINADO'] == 0) //valida eliminacion
                {

                   
                    // AUTO_INCREMENT
                    DB::statement('ALTER TABLE cursos_documentos_validacion AUTO_INCREMENT=1');
                    $signatariocursovalidacion = CursosDocumentosValidacionModel::create($request->all());

                    if ($request->file('PDF_VALIDACION')) {
                        $extension = $request->file('PDF_VALIDACION')->getClientOriginalExtension();
                        $request['RUTA_PDF'] = $request->file('PDF_VALIDACION')->storeAs('proveedores/' . $request['PROVEEDOR_ID'] . '/signatarios/' . $request['SIGNATARIO_ID'] . '/cursos/documentos_validacion', $signatariocursovalidacion->ID_DOCUMENTO_CURSO . '.' . $extension);

                        $signatariocursovalidacion->update($request->all());
                    }

                    return response()->json($signatariocursovalidacion);
                
                } else //eliminar

                {
                    $signatariocursovalidacion = CursosDocumentosValidacionModel::findOrFail($request['ID_DOCUMENTO_CURSO']);
                    $signatariocursovalidacion->ELIMINADO = 1;
                    $signatariocursovalidacion->save();
                    return response()->json($signatariocursovalidacion);
                }
        

            }
        
        } catch(Exception $e){
            return response()->json('Error al guardar');
        }
    }
}

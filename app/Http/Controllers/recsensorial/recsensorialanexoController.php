<?php

namespace App\Http\Controllers\recsensorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\recsensorial\recsensorialanexoModel;
use App\modelos\clientes\contratoAnexosModel;
use Illuminate\Support\Facades\Storage;


use DB;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');


class recsensorialanexoController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $proveedor_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialanexolista($proveedor_id)
    {
        try
        {
            $sql = DB::select('SELECT
                                    acreditacion.proveedor_id, 
                                    acreditacion.id, 
                                    acreditacion.acreditacion_Tipo, 
                                    acreditacion.acreditacion_Entidad, 
                                    acreditacion.acreditacion_Numero, 
                                    acreditacion.acreditacion_Vigencia,
                                    acreditacion.acreditacion_SoportePDF 
                                FROM
                                    acreditacion
                                WHERE
                                    acreditacion.proveedor_id = '.$proveedor_id.' 
                                    AND acreditacion.acreditacion_Eliminado = 0 
                                    AND acreditacion.acreditacion_SoportePDF != "" 
                                ORDER BY
                                    acreditacion.acreditacion_Tipo ASC,
                                    acreditacion.acreditacion_Numero ASC');

            $opciones = '<option value=""></option>';

            foreach ($sql as $key => $value) 
            {
                $opciones .= '<option value="'.$value->id.'">'.$value->acreditacion_Entidad.' ('.$value->acreditacion_Numero.')</option>';
            }

            // respuesta
            $dato['opciones'] = $opciones;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['opciones'] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialanexotabla($recsensorial_id)
    {
        try
        {
            // Reconocimiento
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);

            $sql = DB::select('SELECT
                                recsensorialanexo.recsensorial_id,
                                recsensorialanexo.recsensorialanexo_tipo,
                                recsensorialanexo.id,
                                recsensorialanexo.proveedor_id,
                                ifnull(proveedor.proveedor_RazonSocial, ca.NOMBRE_ANEXO) proveedor_RazonSocial,
                                recsensorialanexo.acreditacion_id,
                                acreditacion.acreditacion_Tipo,
                                recsensorialanexo.contrato_anexo_id,
                                ca.TIPO,
                                IFNULL(acreditacion.acreditacion_Entidad, "NA") acreditacion_Entidad,
                                IFNULL(acreditacion.acreditacion_Numero, "NA") acreditacion_Numero,
                                IFNULL(acreditacion.acreditacion_Vigencia, "NA") acreditacion_Vigencia,
                                IFNULL(acreditacion.acreditacion_SoportePDF, recsensorialanexo.ruta_anexo) acreditacion_SoportePDF 
                            FROM
                                recsensorialanexo
                                LEFT JOIN proveedor ON recsensorialanexo.proveedor_id = proveedor.id
                                LEFT JOIN acreditacion ON recsensorialanexo.acreditacion_id = acreditacion.id
                                LEFT JOIN contratros_anexos ca ON ca.ID_CONTRATO_ANEXO = recsensorialanexo.contrato_anexo_id
                            WHERE
                                recsensorialanexo.recsensorial_id = ' . $recsensorial_id . '
                                ORDER BY
                                recsensorialanexo.recsensorialanexo_tipo ASC,
                                proveedor.proveedor_RazonSocial ASC,
                                acreditacion.acreditacion_Tipo ASC,
                                acreditacion.acreditacion_Entidad ASC,
                                acreditacion.acreditacion_Numero ASC');

            // FORMATEAR FILAS
            $numero_registro = 0;
            foreach ($sql as $key => $value) 
            {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;


                if (($value->recsensorialanexo_tipo+0) == 1)
                {
                    $value->anexo_tipo = 'Físicos';
                }
                else
                {
                    $value->anexo_tipo = 'Químicos';
                }

                if ($value->TIPO == 'IMAGEN'){
                    $value->boton_pdf = '<button type="button" class="btn btn-info btn-circle anexo_pdf"><i class="fa fa-file-image-o"></i></button>';

                }else{

                    $value->boton_pdf = '<button type="button" class="btn btn-info btn-circle anexo_pdf"><i class="fa fa-file-pdf-o"></i></button>';
                }
                

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI'])  && ($recsensorial->recsensorial_bloqueado + 0) == 0)
                {
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle elimina_anexo"><i class="fa fa-trash"></i></button>';
                }
                else
                {
                    $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                }
            }

            // respuesta
            $dato['data'] = $sql;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            $dato['data'] = 0;
            return response()->json($dato);
        }
    }



    public function mostrarFotoAnexo($contrato_anexo_id)
    {
        $foto = recsensorialanexoModel::where('contrato_anexo_id', $contrato_anexo_id)->first();
        return Storage::response($foto->ruta_anexo);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            if ($request->opcion == 1) // Nuevo
            {
                // AUTO_INCREMENT
                DB::statement('ALTER TABLE recsensorialanexo AUTO_INCREMENT=1');

                $anexo = recsensorialanexoModel::create($request->all());

                if ($request->file('anexo_archivo')) {
                    $extension = $request->file('anexo_archivo')->getClientOriginalExtension();
                    $request['ruta_anexo'] = $request->file('anexo_archivo')->storeAs('recsensorial/' . $request->recsensorial_id . '/anexos', $anexo->id . '.' . $extension);

                    $anexo->update($request->all());
                }

                if ($request->file('anexo_imagen')) {
                    $extension = $request->file('anexo_imagen')->getClientOriginalExtension();
                    $request['ruta_anexo'] = $request->file('anexo_imagen')->storeAs('recsensorial/' . $request->recsensorial_id . '/anexos', $anexo->id . '.' . $extension);

                    $anexo->update($request->all());
                }

                //INDICAMOS QUE EL ANEXO DEL CONTARTO HA SIDO USADO (YA NO SE USA)
                // $contratp_anexo = contratoAnexosModel::findOrFail($request->contrato_anexo_id);
                // $contratp_anexo->USADO = 1;
                // $contratp_anexo->save();

                // mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }

            // respuesta
            $dato['recsensorial_id'] = $request->recsensorial_id;
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $recsensorialanexo_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialanexoeliminar($recsensorialanexo_id, $contrato_anexo_id)
    {
        try
        {
            $anexo = recsensorialanexoModel::where('id', $recsensorialanexo_id)->delete();

            //INDICAMOS QUE EL ANEXO DEL CONTRATO AUN NO SE HA USADAO (YA NO SE USA)
            // $contratp_anexo = contratoAnexosModel::findOrFail($contrato_anexo_id);
            // $contratp_anexo->USADO = 0;
            // $contratp_anexo->save();

            // respuesta
            $dato["msj"] = 'Anexo eliminado correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }
}

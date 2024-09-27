<?php

namespace App\Http\Controllers\PSICO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\reconocimientopsico\reconocimientopsicoModel;
use App\modelos\reconocimientopsico\recopsiconormativaModel;
use DB;

class recopsiconormativaController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  

    public function store(Request $request)
     {
         try {
             if ($request['ID_RECOPSICONORMATIVA'] == 0) //nuevo
             {
                 // AUTO_INCREMENT
                 DB::statement('ALTER TABLE recopsiconormativa AUTO_INCREMENT=1');
 
                 // guardar
                 $normativapsico = recopsiconormativaModel::create($request->all());
 
                 // mensaje
                 $dato["msj"] = 'Informacion guardada correctamente';
             } else //editar
             {
                 // modificar
                  $normativapsico = recopsiconormativaModel::findOrFail($request['ID_RECOPSICONORMATIVA']);
 
                 $normativapsico->update($request->all());
 
                 // mensaje
                 $dato["msj"] = 'Informacion modificada correctamente';
             }
 
             // respuesta
             $dato['normativapsico'] = $normativapsico;
             return response()->json($dato);
         } catch (Exception $e) {
             $dato["msj"] = 'Error ' . $e->getMessage();
             return response()->json($dato);
         }
     }
}

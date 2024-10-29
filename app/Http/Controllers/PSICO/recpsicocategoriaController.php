<?php

namespace App\Http\Controllers\PSICO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\reconocimientopsico\reconocimientopsicoModel;
use App\modelos\reconocimientopsico\recpsicocategoriaModel;
use DB;

class recpsicocategoriaController extends Controller
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
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function recpsicocategoriatabla($recsensorial_id)
    {
        try {
            // Reconocimiento
            $recsensorial = reconocimientopsicoModel::findOrFail($recsensorial_id);

            $tabla = recpsicocategoriaModel::with(['catdepartamento', 'catmovilfijo'])
                ->where('RECPSICO_ID', $recsensorial_id)
                ->orderBy('catdepartamento_id', 'ASC')
                ->orderBy('RECPSICO_NOMBRECATEGORIA', 'ASC')
                ->get();

            // FORMATEAR FILAS
            $numero_registro = 0;
            foreach ($tabla  as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;
            }

            // respuesta
            $dato['data'] = $tabla;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  


     public function store(Request $request)
     {
         try {
             if ($request['RECPSICOCATEGORIA_ID'] == 0) //nuevo
             {
                 // AUTO_INCREMENT
                 DB::statement('ALTER TABLE recpsicocategoria AUTO_INCREMENT=1');
 
                 // guardar
                 $categoria = recpsicocategoriaModel::create($request->all());
 
                 // mensaje
                 $dato["msj"] = 'Informacion guardada correctamente';
             } else //editar
             {
                 // modificar
                  //$categoria = recpsicocategoriaModel::findOrFail($request['RECPSICOCATEGORIA_ID']);
                 $categoria = recpsicocategoriaModel::where('RECPSICOCATEGORIA_ID', $request['RECPSICOCATEGORIA_ID'])->firstOrFail();
 
                 $categoria->update($request->all());
 
                 // mensaje
                 $dato["msj"] = 'Informacion modificada correctamente';
             }
 
             // respuesta
             $dato['categoria'] = $categoria;
             return response()->json($dato);
         } catch (Exception $e) {
             $dato["msj"] = 'Error ' . $e->getMessage();
             return response()->json($dato);
         }
     }
  

}

<?php

namespace App\Http\Controllers\proyecto;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTime;
use DB;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class pruebaController extends Controller
{


    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(Auth()->user());
        return view('catalogos.proyecto.prueba');
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
            dd($request);
            if ($request->file('imagen'))
            {
                $extension = $request->file('imagen')->getClientOriginalExtension();
                $archivo_foto = $request->file('imagen')->storeAs('proyecto/'.$request->proyecto_id.'/evidencias_campo/'.$request->agente_nombre.'/fotos', 'foto_'.$archivo->id.'.'.$extension);

                //====================================

                // // Ruta destino archivo
                // $destinoPath = 'proyecto/'.$request->proyecto_id.'/evidencias_campo/'.$request->agente_nombre.'/fotos';

                // // Crear directorio Ruta
                // Storage::makeDirectory($destinoPath);

                // // Obtener extension
                // $extension = $request->file('inputevidenciafotofisicos')->getClientOriginalExtension();

                // // Redimensionar imagen a [1200 x 900] y GUARDAR
                // Image::make($request->file('inputevidenciafotofisicos')->path())->resize(1200, null, function ($constraint) {$constraint->aspectRatio();})->save(storage_path('app/'.$destinoPath.'/foto_'.$archivo->id.'.'.$extension));

                // // Actualizar ruta foto
                // $archivo->update([
                //     'proyectoevidenciafoto_archivo' => $destinoPath.'/foto_'.$archivo->id.'.'.$extension
                // ]);
            }

            // respuesta
            $dato["msj"] = 'Información guardada correctamente';
            return response()->json($dato);
        }
        catch(\Exception $e)
        {
            $dato["msj"] = 'Error en el controlador: '.$e->getMessage();
            return response()->json($dato);
        }
    }





}

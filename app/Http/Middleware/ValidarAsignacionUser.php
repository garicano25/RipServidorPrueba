<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use App\modelos\reportes\reporterevisionesModel;

class ValidarAsignacionUser {



    public function SQL($user, $proyecto){

        $permiso = DB::select("SELECT COUNT(u.ID_PROYECTO_USUARIO) AS PERMISO
                            FROM proyectoUsuarios u
                            WHERE u.SERVICIO_HI = 1
                            AND u.ACTIVO = 1 
                            AND u.PROYECTO_ID = ?
                            AND u.USUARIO_ID = ?", [$proyecto, $user]);
    
        return $permiso[0]->PERMISO;
    }


    public function handle($request, Closure $next, $MODULO){
        //Obtenemos nuestra variable global para obtener el usuario en session
        $ID = auth()->user()->id;


        //Epezamos con la validaciones de los permisos en este caso si el usuario que manda la Request es un Superusuario o Administrador le damos paso a realizar la acción en el store
        if (auth()->user()->hasRoles(['Administrador', 'Superusuario'])){ 


            return $next($request);
        
        }else{

            //Si el usuario no es Superusuario o Administrador validamos desde que modulo nos envian la informacion
            switch ($MODULO) {
                case 'POE':

                    //Obtenemos la validaciones de los permisos para su validacion
                    $PROYECTO_ID = isset($request['proyecto_id']) ?  $request['proyecto_id'] : intval($request->route('proyecto_id'));
                    $permiso = $this->SQL($ID, $PROYECTO_ID);

                    if($permiso != 0){ 
                        return $next($request);
                     }

                    break;
                case 'INFORMES':
                    //Obtenemos la validaciones de los permisos para su validacion
                    $PROYECTO_ID = isset($request['proyecto_id']) ? $request['proyecto_id'] : 0;
                    $permiso = $this->SQL($ID, $PROYECTO_ID);

                    if ($permiso != 0) {
                        return $next($request);
                    }

                    break;
                case 'REVISION':

                    //Obtenemos la validaciones de los permisos para su validacion
                    $revision_id = $request->route('reporte_id');

                    $revision  = reporterevisionesModel::findOrFail($revision_id);

                    $permiso = $this->SQL($ID, $revision->proyecto_id);

                    if ($permiso != 0) {
                        return $next($request);
                    }

                    break;
                default:

                    return response()->json('No tienes permisos para realizar esta acción.', 403);

                    break;
            }
            
            return response()->json('No tienes permisos para realizar esta acción.', 403);


        }

    }
}

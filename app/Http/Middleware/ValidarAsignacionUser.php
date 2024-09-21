<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class ValidarAsignacionUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){


        if (auth()->user()->hasRoles(['Superusuario', 'Administrador'])) {
        
            return $next($request);
        
        }else{

            $ID = auth()->user()->id;
            // $PROYECTO_ID = $request->PROYE

            
            

        }


    }
}

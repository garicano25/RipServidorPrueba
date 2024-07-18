<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'usuario';
    protected $fillable = [
        'usuario_tipo',
        'empleado_id',
        'name', 
        'email',
        'password',
        'clave',
        'usuario_foto',
        'usuario_activo'
    ];



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    //=============== SINCRONIZACION CON TABLAS ===================


    public function asignarroles()
    {
        return $this->belongsToMany(\App\modelos\usuario\asignarrolesModel::class, 'asignar_roles', 'usuario_id', 'rol_id');
    }


    //==============================================00



    public function empleado()
    {
        return $this->belongsTo(\App\modelos\usuario\empleadoModel::class, 'empleado_id');
    }


    public function roles()
    {
        return $this->belongsToMany(\App\modelos\usuario\rolModel::class,'asignar_roles','usuario_id','rol_id');
    }
    

    public function hasRoles(array $roles)
    {
        // dd($this->roles);
        foreach ($roles as $rol) {
            foreach ($this->roles as $RolUsuario) {
               if ($RolUsuario->rol_Modulo === $rol) {
                   return true;
               }
            }
        }
        return false;
    }

}

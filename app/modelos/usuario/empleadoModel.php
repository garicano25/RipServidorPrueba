<?php

namespace App\modelos\usuario;

use Illuminate\Database\Eloquent\Model;

class empleadoModel extends Model
{
    protected $table = 'empleado';
    protected $fillable = [
        'empleado_nombre',
		'empleado_apellidopaterno',
		'empleado_apellidomaterno',
		'empleado_cargo',
		'empleado_direccion',
		'empleado_telefono',
        'empleado_fechanacimiento',
        'empleado_correo',
        'empleado_foto'
    ];

    public function usuario()
    {
        return $this->hasOne(\App\User::class);
    }
}

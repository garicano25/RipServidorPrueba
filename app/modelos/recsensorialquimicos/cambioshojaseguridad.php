<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class cambioshojaseguridad extends Model
{
    protected $primaryKey = 'ID_CAMBIOS_CATHOJA';
    protected $table = 'cambios_cathojaseguridad';
    protected $fillable = [
        'HOJA_ID',
        'USUARIO_ID',
        'ACTIVO'
    ];

}

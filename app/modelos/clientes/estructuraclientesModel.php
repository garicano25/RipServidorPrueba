<?php

namespace App\modelos\clientes;

use Illuminate\Database\Eloquent\Model;

class estructuraclientesModel extends Model
{
    protected $primaryKey = 'ID_ESTRUCTURA_CLIENTE';
    protected $table = 'estructuraclientes';
    protected $fillable = [
        'CLIENTES_ID',
        'ETIQUETA_ID',
        'OPCIONES_ID',
        'NIVEL'

    ];
}

<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class recpsicofotostrabajadoresModel extends Model
{
    protected $primaryKey = 'ID_RECOPSICOFOTOTRABAJADOR';
    protected $table = 'recopsicoFotosTrabajadores';
    protected $fillable = [
        'RECPSICO_ID',
        'RECPSICO_TRABAJADOR',
        'RECPSICO_FOTOPREGUIA',
        'RECPSICO_FOTOPOSTGUIA',
        'RECPSICO_FOTOPRESENCIAL'
    ];
}

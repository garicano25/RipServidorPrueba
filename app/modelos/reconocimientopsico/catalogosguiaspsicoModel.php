<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class catalogosguiaspsicoModel extends Model
{
    protected $primaryKey = 'ID_GUIAPREGUNTA';
    protected $table = 'catalogoguiaspsico';
    protected $fillable = [
        'TIPOGUIA',
        'PREGUNTA_ID',
        'PREGUNTA_NOMBRE',
        'PREGUNTA_EXPLICACION',
        'ACTIVO'
    ];
}

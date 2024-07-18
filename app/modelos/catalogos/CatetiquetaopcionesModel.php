<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class CatetiquetaopcionesModel extends Model
{


    protected $primaryKey = 'ID_OPCIONES_ETIQUETAS';
    protected $table = 'catetiquetas_opciones';
    protected $fillable = [
        'ETIQUETA_ID',
        'NOMBRE_OPCIONES',
        'ACTIVO'
    ];


}

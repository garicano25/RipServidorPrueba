<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class recsensorialRecursosInformesModel extends Model
{
    protected $primaryKey = 'ID_RECURSO_INFORME';
    protected $table = 'recsensorial_recursos_informes';
    protected $fillable = [
        'RECSENSORIAL_ID',
        'INTRODUCCION',
        'METODOLOGIA',
        'CONCLUSION',
        'IMAGEN_PORTADA',
        'OPCION_PORTADA1',
        'OPCION_PORTADA2',
        'OPCION_PORTADA3',
        'OPCION_PORTADA4',
        'OPCION_PORTADA5',
        'OPCION_PORTADA6',
        'NIVEL1',
        'NIVEL2',
        'NIVEL3',
        'NIVEL4',
        'NIVEL5',
        'PETICION_CLIENTE',
        'REQUIERE_CONCLUSION',
        'ID_CATCONCLUSION'


    ];
}

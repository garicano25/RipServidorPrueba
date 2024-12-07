<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class catconclusiones_psicoModel extends Model
{
    protected $primaryKey = 'ID_CONCLUSION_INFORME';
    protected $table = 'psicocat_conclusiones';
    protected $fillable = [
        'DOMINIO',
        'NIVEL',
        'CONCLUSION',
        'ACTIVO'
    ];
}

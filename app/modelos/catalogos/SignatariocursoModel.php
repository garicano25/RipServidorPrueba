<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class SignatariocursoModel extends Model
{
    //
    protected $table = 'signatariocurso';
    protected $fillable = [
		'signatario_id',
		'signatarioCurso_NombreCurso',
		'signatarioCurso_FechaExpedicion',
        'signatarioCurso_FechaVigencia',
		'signatarioCurso_FolioCurso',
		'signatarioCurso_SoportePDF',
		'signatarioCurso_Eliminado'
    ];

    public function signatario()
    {
        return $this->belongsTo(\App\modelos\catalogos\SignatarioModel::class);
    }
}

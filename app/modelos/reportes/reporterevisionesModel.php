<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporterevisionesModel extends Model
{
    protected $table = 'reporterevisiones';
	protected $fillable = [
		  'proyecto_id'
		, 'agente_id'
		, 'agente_nombre'
		, 'reporterevisiones_revision'
		, 'reporterevisiones_concluido'
		, 'reporterevisiones_concluidonombre'
		, 'reporterevisiones_concluidofecha'
		, 'reporterevisiones_cancelado'
		, 'reporterevisiones_canceladonombre'
		, 'reporterevisiones_canceladofecha'
		, 'reporterevisiones_canceladoobservacion'
	];
}

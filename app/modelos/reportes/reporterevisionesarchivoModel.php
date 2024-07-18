<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporterevisionesarchivoModel extends Model
{
    protected $table = 'reporterevisionesarchivo';
	protected $fillable = [
		  'reporterevisiones_id'
		, 'reporterevisionesarchivo_tipo'
		, 'reporterevisionesarchivo_archivo'
	];
}

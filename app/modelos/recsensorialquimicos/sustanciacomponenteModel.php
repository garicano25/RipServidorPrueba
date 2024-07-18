<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class sustanciacomponenteModel extends Model
{
    protected $table = 'sustanciacomponente';
	protected $fillable = [
		'recsensorialquimicosinventario_id',
		'sustanciacomponente_nombre',
		'sustanciacomponente_exposicionppt',
		'sustanciacomponente_exposicionctop',
		'sustanciacomponente_unidadmedida'
	];
}

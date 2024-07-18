<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class catsustanciacomponenteModel extends Model
{
	protected $table = 'catsustanciacomponente';
	protected $fillable = [
		'catsustancia_id',
		'catsustanciacomponente_nombre',
		'catsustanciacomponente_cas',
		'catsustanciacomponente_ebullicion',
		'catsustanciacomponente_porcentaje',
		'catsustanciacomponente_pesomolecular',
		'catsustanciacomponente_estadofisico',
		'catsustanciacomponente_volatilidad',
		'catsustanciacomponente_exposicionppt',
		'catsustanciacomponente_exposicionctop',
		'catsustanciacomponente_connotacion'
	];
}

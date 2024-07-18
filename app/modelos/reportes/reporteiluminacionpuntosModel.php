<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteiluminacionpuntosModel extends Model
{
    protected $table = 'reporteiluminacionpuntos';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'reporteiluminacionpuntos_nopunto'
		, 'reporteiluminacionpuntos_area_id'
		, 'reporteiluminacionpuntos_categoria_id'
		, 'reporteiluminacionpuntos_nopoe'
		, 'reporteiluminacionpuntos_nombre'
		, 'reporteiluminacionpuntos_ficha'
		, 'reporteiluminacionpuntos_concepto'
		, 'reporteiluminacionpuntos_fechaeval'
		, 'reporteiluminacionpuntos_horario1'
		, 'reporteiluminacionpuntos_horario2'
		, 'reporteiluminacionpuntos_horario3'
		, 'reporteiluminacionpuntos_lux'
		, 'reporteiluminacionpuntos_luxmed1'
		, 'reporteiluminacionpuntos_luxmed2'
		, 'reporteiluminacionpuntos_luxmed3'
		, 'reporteiluminacionpuntos_luxmed1menor'
		, 'reporteiluminacionpuntos_luxmed2menor'
		, 'reporteiluminacionpuntos_luxmed3menor'
		, 'reporteiluminacionpuntos_luxmed1mayor'
		, 'reporteiluminacionpuntos_luxmed2mayor'
		, 'reporteiluminacionpuntos_luxmed3mayor'
		, 'reporteiluminacionpuntos_frp'
		, 'reporteiluminacionpuntos_frpmed1'
		, 'reporteiluminacionpuntos_frpmed2'
		, 'reporteiluminacionpuntos_frpmed3'
		, 'reporteiluminacionpuntos_frpt'
		, 'reporteiluminacionpuntos_frptmed1'
		, 'reporteiluminacionpuntos_frptmed2'
		, 'reporteiluminacionpuntos_frptmed3'
	];
}

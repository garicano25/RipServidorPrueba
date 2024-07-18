<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportequimicosconclusionModel extends Model
{
    protected $table = 'reportequimicosconclusion';
    protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'catreportequimicospartidas_id'
		, 'reportequimicosconclusion_conclusion'
    ];
}
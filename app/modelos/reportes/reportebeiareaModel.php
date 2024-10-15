<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportebeiareaModel extends Model
{
    protected $table = 'reportebeiarea';
    protected $fillable = [
        "proyecto_id",
        "registro_id" ,
        "recsensorialarea_id" ,
        "reportebeiarea_numorden" ,
        "reportebeiarea_nombre" ,
        "reportebeiarea_instalacion" ,
        "reportebeiarea_porcientooperacion" ,
        "reportebeiarea_descripcion" 
    ];
}

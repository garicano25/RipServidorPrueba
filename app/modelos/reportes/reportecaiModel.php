<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportecaiModel extends Model
{
    protected $primaryKey = 'ID_CAI_INFORMES';  
    protected $table = 'cai_informes';
    protected $fillable = [
        'proyecto_id',                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              
        'JSON_CARACTERISTICA'
    ];
}

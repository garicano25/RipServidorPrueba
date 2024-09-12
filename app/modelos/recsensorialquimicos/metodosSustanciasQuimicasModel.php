<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class metodosSustanciasQuimicasModel extends Model
{
    protected $primaryKey = 'ID_METODO';
    protected $table = 'metodosSustanciasQuimicas';
    protected $fillable = [
        'SUSTANCIAS_QUIMICA_ID',
        'DESCRIPCION',
        'ACTIVO'
    ];
}

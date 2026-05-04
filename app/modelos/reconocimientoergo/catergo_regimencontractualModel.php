<?php

namespace App\modelos\reconocimientoergo;

use Illuminate\Database\Eloquent\Model;

class catergo_regimencontractualModel extends Model
{
    protected $primaryKey = 'ID_REGIMEN_CONTRACTUAL';
    protected $table = 'catergo_regimencontractual';
    protected $fillable = [
        'NOMBRE_REGIMEN_CONTRACTUAL',
        'ACTIVO'

    ];
}

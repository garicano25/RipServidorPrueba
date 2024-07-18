<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class cattipoinstalacionporpuntoModel extends Model
{
    protected $table = 'cattipoinstalacionporpunto';
    protected $primaryKey = 'id';
    protected $fillable = [
		'cattipoinstalacionporpunto_puntoinicial',
		'cattipoinstalacionporpunto_puntofinal',
		'cattipoinstalacionporpunto_tipoinstalacion',
		'cattipoinstalacionporpunto_color'
    ];
}

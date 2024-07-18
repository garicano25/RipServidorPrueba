<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class recsensorialagentesclienteModel extends Model
{
    protected $table = 'recsensorialagentescliente';
    protected $fillable = [        
        'recsensorial_id',
		'agentescliente_agenteid',
		'agentescliente_nombre',
		'agentescliente_tipoanalisis',
		'agentescliente_puntos',
		'agentescliente_analisis',
		'agentescliente_observacion'
    ];
}

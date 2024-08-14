<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class recsensorialanexoModel extends Model
{
  protected $table = 'recsensorialanexo';
  protected $fillable = [
    'recsensorial_id',
    'proveedor_id',
    'acreditacion_id',
    'recsensorialanexo_tipo',
    'recsensorialanexo_orden',
    'ruta_anexo',
    'contrato_anexo_id'
  ];
}

<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class SignatariodocumentoModel extends Model
{
  //
  protected $table = 'signatariodocumento';
  protected $fillable = [
    'signatario_id',
    'signatarioDocumento_Nombre',
    'signatarioDocumento_SoportePDF',
    'signatarioDocumento_Tipo',
    'signatarioDocumento_FechaVigencia',
    'signatarioDocumento_Eliminado',
    'signatarioDocumentoEleccion'
  ];

  public function signatario()
  {
    return $this->belongsTo(\App\modelos\catalogos\SignatarioModel::class);
  }
}

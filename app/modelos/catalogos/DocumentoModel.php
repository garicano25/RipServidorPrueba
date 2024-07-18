<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class DocumentoModel extends Model
{
    //
    //
    protected $table = 'proveedordocumento';
    protected $fillable = [
		'proveedor_id',
		'proveedorDocumento_Nombre',
		'proveedorDocumento_SoportePDF',
		'proveedorDocumento_FechaAlta',
		'proveedorDocumento_FechaModificacion',
		'proveedorDocumento_Eliminado',
    'TIPO_DOCUMENTO'
    ];

    public function proveedor()
    {
        return $this->belongsTo(\App\modelos\catalogos\ProveedorModel::class);
    }
}

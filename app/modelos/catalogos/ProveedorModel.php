<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class ProveedorModel extends Model
{
    protected $table = 'proveedor';
    protected $fillable = [
        'cat_tipoproveedor_id',
        'proveedor_RazonSocial',
        'proveedor_NombreComercial',
        'proveedor_DomicilioFiscal',
        'proveedor_LineaNegocios',
        'proveedor_GiroComercial',
        'proveedor_Rfc',
        'proveedor_CiudadPais',
        'proveedor_RepresentanteLegal',
        'proveedor_PaginaWeb',
        // 'proveedor_NombreContacto',
        // 'proveedor_CargoContacto',
        // 'proveedor_CorreoContacto',
        // 'proveedor_TelefonoContacto',
        // 'proveedor_CelularContacto',
        'CONTACTOS_JSON', //ESTA ES PARA ALMACENAR LA LISTA DE TODOS LOS CONTACTOS QUE SE AGREGEN
        'proveedor_Bloqueado',
        'proveedor_Eliminado'
    ];

    // =================================

    public function domicilio()
    {
        return $this->hasMany(\App\modelos\catalogos\DomicilioModel::class);
    }

    public function documento()
    {
        return $this->hasMany(\App\modelos\catalogos\DocumentoModel::class);
    }

    public function acreditacion()
    {
        return $this->hasMany(\App\modelos\catalogos\AcreditacionModel::class);
    }

    public function equipo()
    {
        return $this->hasMany(\App\modelos\catalogos\EquipoModel::class);
    }

    public function signatario()
    {
        return $this->hasMany(\App\modelos\catalogos\SignatarioModel::class);
    }

    public function servicio()
    {
        return $this->hasMany(\App\modelos\catalogos\ServicioModel::class);
    }

    // =================================

    public function cat_tipoproveedor()
    {
        return $this->belongsTo(\App\modelos\catalogos\Cat_tipoproveedorModel::class);
    }
}

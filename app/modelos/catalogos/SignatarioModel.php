<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class SignatarioModel extends Model
{
    //
    protected $table = 'signatario';
    protected $fillable = [
        'proveedor_id',
        'signatario_Nombre',
        'signatario_Cargo',
        'signatario_Telefono',
        'signatario_Correo',
        'signatario_Rfc',
        'signatario_Curp',
        'signatario_Nss',
        'signatario_TipoSangre',
        'signatario_Foto',
        'signatario_EstadoActivo',
        'signatario_Eliminado',
        //Nuevos datos agregados
        'signatario_apoyo',
        'signatario_Alergias',
        'signatario_telEmergencia',
        'signatario_NombreContacto',
        'signatario_parentesco'

    ];

    public function proveedor()
    {
        return $this->belongsTo(\App\modelos\catalogos\ProveedorModel::class);
    }

    public function cat_signatariodisponibilidad()
    {
        return $this->belongsTo(\App\modelos\catalogos\Cat_signatariodisponibilidadModel::class, 'signatario_EstadoActivo');
    }

    public function signatariodocumento()
    {
        return $this->hasMany(\App\modelos\catalogos\SignatariodocumentoModel::class);
    }

    public function signatariocurso()
    {
        return $this->hasMany(\App\modelos\catalogos\SignatariocursoModel::class);
    }

    public function signatariprueba()
    {
        return $this->hasMany(\App\modelos\catalogos\SignatariopruebaModel::class);
    }
}

<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoModel extends Model
{
    protected $table = 'proyecto';
    protected $fillable = [
        'contrato_id'
         , 'recsensorial_id'
        , 'proyecto_folio'
        , 'proyecto_ordenservicio'
        , 'proyecto_cotizacion'
        , 'proyecto_razonsocial'
        , 'proyecto_rfc'
        , 'proyecto_direccion'
        , 'proyecto_contacto'
        , 'proyecto_contactocorreo'
        , 'proyecto_contactotelefono'
        , 'proyecto_contactocelular'
        , 'proyecto_ciudadpais'
        , 'proyecto_personaelabora'
        , 'proyecto_personaaprueba'
        , 'proyecto_fechaelaboracion'
        , 'proyecto_clienterazonsocial'
        , 'proyecto_clientenombrecomercial'
        , 'proyecto_clienterfc'
        , 'proyecto_clientegiroempresa'
        , 'catregion_id'
        , 'catsubdireccion_id'
        , 'catgerencia_id'
        , 'catactivo_id'
        , 'proyecto_clienteinstalacion'
        , 'proyecto_clientedireccionservicio'
        , 'proyecto_clientepersonadirigido'
        , 'proyecto_clientepersonacontacto'
        , 'proyecto_clientetelefonocontacto'
        , 'proyecto_clientecelularcontacto'
        , 'proyecto_clientecorreocontacto'
        , 'proyecto_clienteobjetivoservicio'
        , 'proyecto_clienteobservacion'
        , 'proyecto_fechainicio'
        , 'proyecto_fechafin'
        , 'proyecto_totaldias'
        , 'proyecto_fechaentrega'
        , 'proyecto_puntosrealesactivo'
        , 'proyecto_bitacoraactivo'
        , 'proyecto_concluido'
        , 'proyecto_eliminado',
        'solicitudOS',
        'proyectoInterno',
        'requiereContrato',
        'cliente_id',
        'tipoServicioCliente'
    ];

    //=============== RELACION A TABLAS ===================

    public function recsensorial()
    {
    	return $this->belongsTo(\App\modelos\recsensorial\recsensorialModel::class);
    }

    //=============== RELACION A CATALOGOS ===================

    public function catregion()
    {
        return $this->belongsTo(\App\modelos\recsensorial\catregionModel::class);
    }

    public function catsubdireccion()
    {
        return $this->belongsTo(\App\modelos\recsensorial\catsubdireccionModel::class);
    }

    public function catgerencia()
    {
        return $this->belongsTo(\App\modelos\recsensorial\catgerenciaModel::class);
    }

    public function catactivo()
    {
        return $this->belongsTo(\App\modelos\recsensorial\catactivoModel::class);
    }
    public function proyectoordentrabajo()
    {
        return $this->hasMany(\App\modelos\proyecto\proyectoordentrabajoModel::class,'proyecto_id');
    }
    public function proyectosignatarios()
    {
        return $this->hasMany(\App\modelos\proyecto\proyectosignatarioModel::class,'proyecto_id');
    }
     public function proyectoproveedores()
    {
        return $this->hasMany(\App\modelos\proyecto\proyectoproveedoresModel::class,'proyecto_id');
    }

    public function prorrogas()
    {
        return $this->hasMany(\App\modelos\proyecto\proyectoprorrogasModel::class,'proyecto_id');
    }

    public function serviciosProyectos()
    {
        return $this->hasMany(\App\modelos\proyecto\serviciosProyectoModel::class, 'PROYECTO_ID');
    }

 
}

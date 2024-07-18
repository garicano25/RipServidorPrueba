<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class Cat_tipoproveedorModel extends Model
{
    //
    protected $table = 'cat_tipoproveedor';
    protected $fillable = [
		'catTipoproveedor_Nombre',
        'catTipoproveedor_Activo'
    ];

    public function alcance()
    {
     return $this->hasMany(\App\modelos\catalogos\Cat_tipoproveedoralcanceModel::class, 'cat_tipoproveedor_id');
    }

    // public function proveedor()
    // {
    // 	return $this->hasMany(\App\modelos\catalogos\ProveedorModel::class);
    // }
}

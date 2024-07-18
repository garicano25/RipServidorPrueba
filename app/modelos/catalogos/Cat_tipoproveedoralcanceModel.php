<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class Cat_tipoproveedoralcanceModel extends Model
{
    protected $table = 'cat_tipoproveedoralcance';
    protected $fillable = [
		'cat_tipoproveedor_id',
		'cat_tipoproveedoralcance_alcance'
    ];
}

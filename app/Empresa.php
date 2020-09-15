<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Empresa extends Model
{
    protected $table = 'empresa'; //con qué tabla se trabaja
    protected $primaryKey = 'idEmpresa';//nombre de la Columna, por defecto es id pero si le cambias de nombre en los parentesis debe de ir el nombre que le pusiste
    protected $filleable = [
        'idCategoria',
        'razonSocial',
        'ruc',
        'urlFacebook',
        'urlInstagram',
        'urlWhatsApp',
        'correo',
        'logo',
        'vigencia'
    ];
}

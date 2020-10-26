<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class medioPago extends Model
{
    protected $table = "mediopago";
    protected $primaryKey = 'Codigo';
    public $timestamps = false;
    protected $fillable = [
        'Codigo',
        'Nombre',
        'CodigoSUNAT',
        'Vigencia'
    ];
}


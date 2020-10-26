<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;
    protected $table = "empresa";
    protected $primaryKey = 'Codigo';
    public $timestamps = false;
    protected $fillable = [
        'Codigo',
        'RazonSocial',
        'RUC',
        'Facebook',
        'Instagram',
        'Youtube',
        'Whatsapp',
        'Correo',
        'Logo',
        'Vigencia'
    ];
}

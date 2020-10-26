<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoPersonal extends Model
{
    use HasFactory;
    protected $table = "documentopersonal";
    protected $primaryKey = 'Codigo';
    public $timestamps = false;
    protected $fillable = [
        'Codigo',
        'Nombre',
        'CodigoSUNAT',
        'Tipo',
        'CantidadMinima',
        'CantidadMaxima',
        'Vigencia'
    ];
}

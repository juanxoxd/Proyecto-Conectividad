<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SistemaPension extends Model
{
    use HasFactory;
    protected $table = "sistemapensiones";
    protected $primaryKey = 'Codigo';
    public $timestamps = false;
    protected $fillable = [
        'Codigo',
        'Nombre',
        'Siglas',
        'Vigencia'
    ];
}

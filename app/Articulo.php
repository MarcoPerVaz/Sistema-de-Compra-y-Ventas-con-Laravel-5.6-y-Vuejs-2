<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    // Protección contra asignación masiva
    protected $fillable = [
        'idcategoria',
        'codigo',
        'nombre',
        'precio_venta',
        'stock',
        'descripcion',
        'condicion',
    ];

    // Relación Pertenece a - belongsTo
    public function categoria()
    {
        return $this->belongsTo('App\Categoria');
    }
}

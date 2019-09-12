<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $fillable = [

        'idproveedor',
        'idusuario',
        'tipo_comprobante',
        'serie_comprobante',
        'num_comprobante',
        'fecha_hora',
        'impuesto',
        'total',
        'estado',

    ];

    /**
     * Relación pertenece a - belongsTo - Un Ingreso pertenece a un Usuario
    */
    public function usuario()
    {

        return $this->belongsTo( 'App\User' );

    }

    /**
     * Relación pertenece a - belongsTo - Un Ingreso pertenece a un Proveedor
    */
    public function proveedor()
    {

        return $this->belongsTo( 'App\Proveedor' );

    }

}

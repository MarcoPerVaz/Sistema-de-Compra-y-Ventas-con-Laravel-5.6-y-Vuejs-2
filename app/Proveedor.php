<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores'; /* Se le indica a laravel que la tabla será proveedores */

    
    protected $fillable = [ /* Para definir que campos pueden ser asignados y proteger al proyecto de asignación masiva */
        'id',
        'contacto',
        'telefono_contacto',
    ];

    protected $timestamps = false; /* Se le indica a laravel que la tabla proveedores no incluirá los campos created_at y updated_at */

    // Relación pertenece a - belongsTo - Un proveedor pertenece a una persona
    public function persona()
    {
        return $this->belongsTo('App\Persona');
    }
}

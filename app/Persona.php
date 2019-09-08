<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = [
        'nombre',
        'tipo_documento',
        'num_documento',
        'direccion',
        'telefono',
        'email',
    ];

    // Relación tiene un - hasOne - Una persona tiene un proveedor
    public function proveedor()
    {
        return $this->hasOne('App\Proveedor');
    }

    // Relación tiene un - hasOne - Una persona tiene un usuario
    public function user()
    {

        return $this->hasOne('App\User');
        
    }
}

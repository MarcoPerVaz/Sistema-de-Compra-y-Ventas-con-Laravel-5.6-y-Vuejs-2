<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles'; /* Se le indica a laravel que la tabla será roles */

    protected $fillable = [
        'nombre',
        'descripcion',
        'condicion',
    ];

    public $timestamps = false; /* Se le indica a laravel que la tabla roles no incluirá los campos created_at y updated_at */
}

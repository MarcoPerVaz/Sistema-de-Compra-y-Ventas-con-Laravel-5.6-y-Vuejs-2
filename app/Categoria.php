<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    /* No es necesario porque laravel define el nombre de la tabla basandose en el nombre del modelo */
        /* protected $tables = 'categorias'; */ 

    /* Por si se requiere asignar una llave primaria que no es el campo id */
        /* protected $primaryKey = 'id' */

    /* Para definir que campos pueden ser asignados y proteger al proyecto de asignación masiva */
    protected $fillable = [
        'nombre',
        'descripcion',
        'cndicion',
    ];
}

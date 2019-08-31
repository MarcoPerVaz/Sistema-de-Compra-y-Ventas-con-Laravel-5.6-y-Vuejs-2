<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Importado
use App\Categoria;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $categorias = Categoria::all();

        return $categorias;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $categoria = new Categoria();

        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = "1";

        $categoria->save();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
        $categoria = Categoria::findOrfail($request->id);

        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = "1";

        $categoria->save();

    }

    /**
     * Función para determinar si la categoría está inactiva - Cambia el campo condición de la tambla categorias
    */
    public function desactivar(Request $request)
    {

        $categoria = Categoria::findOrfail($request->id);

        $categoria->condicion = "0";

        $categoria->save();

    }

    /**
     * Función para determinar si la categoría está activa - Cambia el campo condición de la tambla categorias
    */
    public function activar(Request $request)
    {

        $categoria = Categoria::findOrfail($request->id);

        $categoria->condicion = "1";

        $categoria->save();

    }


}

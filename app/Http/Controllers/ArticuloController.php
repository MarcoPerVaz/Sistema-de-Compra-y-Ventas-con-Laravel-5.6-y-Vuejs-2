<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Importado
use App\Articulo;

class ArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ( !$request->ajax() )  return redirect('/'); /* Condicional para solo aceptar peticiones ajax */

        $buscar     = $request->buscar;
        $criterio   = $request->criterio;

        if ( $buscar == '' ) {

            // Consulta para obtener campos de las tablas categorias y articulos usando query builder
            $articulos = Articulo::join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
                ->select('articulos.id', 'articulos.idcategoria', 'articulos.codigo', 'articulos.nombre',
                    'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion',
                    'articulos.condicion')
                ->orderBy( 'articulos.id', 'DESC' )->paginate( 3 ); /* Eloquent para paginar de 3 en 3 */
        
        }
        else {

            // Consulta para obtener campos de las tablas categorias y articulos usando query builder
            $articulos = Articulo::join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
                ->select('articulos.id', 'articulos.idcategoria', 'articulos.codigo', 'articulos.nombre',
                    'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion',
                    'articulos.condicion')
                ->where( 'articulos.' . $criterio, 'LIKE', '%' . $buscar . '%' ) /* Filtrar las artículos */
                ->orderBy( 'articulos.id', 'DESC' )->paginate( 3 ); /* Eloquent para paginar de 3 en 3 */

        }


        return [

            'pagination' => [

                'total'         => $articulos->total(),
                'current_page'  => $articulos->currentPage(),
                'per_page'      => $articulos->perPage(),
                'last_page'     => $articulos->lastPage(),
                'from'          => $articulos->firstItem(),
                'to'            => $articulos->lastItem(),

            ],

            'articulos' => $articulos

        ];

    }

    /**
     * Función para buscar un artículo por código de barras
     */
    public function buscarArticulo( Request $request )
    {

        if ( !$request->ajax() )  return redirect('/'); /* Condicional para solo aceptar peticiones ajax */
        
        $filtro = $request->filtro;

        $articulos = Articulo::where( 'codigo', '=', $filtro )
                    ->select( 'id', 'nombre' )->orderBy( 'nombre', 'asc' )->take(1)->get();

        return [

            'articulos' => $articulos

        ];

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ( !$request->ajax() )  return redirect('/'); /* Condicional para solo aceptar peticiones ajax */

        $articulo = new Articulo();

        $articulo->idcategoria = $request->idcategoria;
        $articulo->codigo = $request->codigo;
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock;
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = "1";

        $articulo->save();

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
        if ( !$request->ajax() )  return redirect('/'); /* Condicional para solo aceptar peticiones ajax */

        $articulo = Articulo::findOrfail($request->id);

        $articulo->idcategoria = $request->idcategoria;
        $articulo->codigo = $request->codigo;
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock;
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = "1";

        $articulo->save();

    }

    /**
     * Función para determinar si la categoría está inactiva - Cambia el campo condición de la tabla articulos
    */
    public function desactivar(Request $request)
    {

        if ( !$request->ajax() )  return redirect('/'); /* Condicional para solo aceptar peticiones ajax */

        $articulo = Articulo::findOrfail( $request->id );

        $articulo->condicion = "0";

        $articulo->save();

    }

    /**
     * Función para determinar si la categoría está activa - Cambia el campo condición de la tabla articulos
    */
    public function activar(Request $request)
    {

        if ( !$request->ajax() )  return redirect('/'); /* Condicional para solo aceptar peticiones ajax */

        $articulo = Articulo::findOrfail( $request->id );

        $articulo->condicion = "1";

        $articulo->save();

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Importado
use App\Persona;

class ClienteController extends Controller
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

            $personas = Persona::orderBy( 'id', 'DESC' )->paginate( 3 ); /* Eloquent para paginar de 3 en 3 */
        
        }
        else {
            /* Filtrar las categorías */
            $personas = Persona::where( $criterio, 'LIKE', '%' . $buscar . '%' )
                ->orderBy('id', 'DESC')->paginate( 3 ); 
        }


        return [

            'pagination' => [

                'total'         => $personas->total(),
                'current_page'  => $personas->currentPage(),
                'per_page'      => $personas->perPage(),
                'last_page'     => $personas->lastPage(),
                'from'          => $personas->firstItem(),
                'to'            => $personas->lastItem(),

            ],

            'personas' => $personas

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

        $persona = new Persona();

        $persona->nombre = $request->nombre;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->num_documento = $request->num_documento;
        $persona->direccion = $request->direccion;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;

        $persona->save();

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

        $persona = Persona::findOrfail($request->id);

        $persona->nombre = $request->nombre;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->num_documento = $request->num_documento;
        $persona->direccion = $request->direccion;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;

        $persona->save();

    }

}

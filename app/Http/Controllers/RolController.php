<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Importado
use App\Rol;

class RolController extends Controller
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

            $roles = Rol::orderBy( 'id', 'DESC' )->paginate( 3 ); /* Eloquent para paginar de 3 en 3 */
        
        }
        else {
            /* Filtrar los roles */
            $roles = Rol::where( $criterio, 'LIKE', '%' . $buscar . '%' )
                ->orderBy('id', 'DESC')->paginate( 3 ); 
        }


        return [

            'pagination' => [

                'total'         => $roles->total(),
                'current_page'  => $roles->currentPage(),
                'per_page'      => $roles->perPage(),
                'last_page'     => $roles->lastPage(),
                'from'          => $roles->firstItem(),
                'to'            => $roles->lastItem(),

            ],

            'roles' => $roles

        ];

    }

    /**
     * Función para seleccionar Rol
    */
    public function selectRol()
    {
        $roles = Rol::where( 'condicion', '=', '1' )
                    ->select( 'id', 'nombre' )
                    ->orderBy( 'nombre', 'asc' )->get();
        
        return [ 'roles' => $roles ];
    }

}

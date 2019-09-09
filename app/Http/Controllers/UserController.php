<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Importado
use App\User;
use App\Persona;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
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

            $personas = User::join( 'personas', 'users.id', '=', 'personas.id' )
            ->join('roles', 'users.idrol', '=', 'roles.id')
            ->select( 'personas.id', 'personas.nombre', 'personas.tipo_documento', 'personas.num_documento', 'personas.direccion',
                      'personas.telefono', 'personas.email', 'users.usuario', 'users.password', 'users.condicion', 'users.idrol',
                      'roles.nombre as rol' )
            ->orderBy( 'personas.id', 'DESC' )->paginate( 3 ); /* Eloquent para paginar de 3 en 3 */
        
        }
        else {
            /* Filtrar las personas */
            $personas = User::join( 'personas', 'users.id', '=', 'personas.id' )
                ->join('roles', 'users.idrol', '=', 'roles.id')
                ->select( 'personas.id', 'personas.nombre', 'personas.tipo_documento', 'personas.num_documento', 'personas.direccion',
                          'personas.telefono', 'personas.email', 'users.usuario', 'users.password', 'users.condicion', 'users.idrol',
                          'roles.nombre as rol' )
                ->where(  'personas.' . $criterio, 'LIKE', '%' . $buscar . '%' )
                ->orderBy('personas.id', 'DESC')->paginate( 3 ); 
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

        try {

            /* Se usan transacciones cuando se van a hacer operaciones en diferentes tablas al mismo tiempo,
               si falla una, fallan todas
            */
            DB::beginTransaction();

            /* Modelo Persona */

                $persona = new Persona();

                $persona->nombre = $request->nombre;
                $persona->tipo_documento = $request->tipo_documento;
                $persona->num_documento = $request->num_documento;
                $persona->num_documento = $request->num_documento;
                $persona->direccion = $request->direccion;
                $persona->telefono = $request->telefono;
                $persona->email = $request->email;

                $persona->save();

            /*  */

            /* Modelo User */

                $user = new User();

                $user->usuario = $request->usuario;
                $user->password = bcrypt($request->password);
                $user->condicion = '1';
                $user->idrol = $request->idrol;
                $user->id = $persona->id;

                $user->save();

            /*  */

            DB::commit(); /* Se finaliza la transacción */
            
        } catch (Exception $e) {
            
            DB::rollBack(); /* Si falla la transacción se regresa todo */

        }

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

        try {

            /* Se usan transacciones cuando se van a hacer operaciones en diferentes tablas al mismo tiempo,
               si falla una, fallan todas
            */
            DB::beginTransaction();

            $user = User::findOrfail($request->id);

            $persona = Persona::findOrfail($user->id);

            /* Modelo Persona */

                $persona->nombre = $request->nombre;
                $persona->tipo_documento = $request->tipo_documento;
                $persona->num_documento = $request->num_documento;
                $persona->num_documento = $request->num_documento;
                $persona->direccion = $request->direccion;
                $persona->telefono = $request->telefono;
                $persona->email = $request->email;

                $persona->save();

            /*  */

            /* Modelo Proveedor */

                $user->usuario = $request->usuario;
                $user->password = bcrypt($request->password);
                $user->condicion = '1';
                $user->idrol = $request->idrol;
                $user->save();

            /*  */

            DB::commit(); /* Se finaliza la transacción */
            
        } catch (Exception $e) {
            
            DB::rollBack(); /* Si falla la transacción se regresa todo */

        }

    }

    /**
     * Función para determinar si la categoría está inactiva - Cambia el campo condición de la tabla categorias
    */
    public function desactivar(Request $request)
    {

        if ( !$request->ajax() )  return redirect('/'); /* Condicional para solo aceptar peticiones ajax */

        $user = User::findOrfail($request->id);

        $user->condicion = "0";

        $user->save();

    }

    /**
     * Función para determinar si la categoría está activa - Cambia el campo condición de la tabla categorias
    */
    public function activar(Request $request)
    {

        if ( !$request->ajax() )  return redirect('/'); /* Condicional para solo aceptar peticiones ajax */

        $user = User::findOrfail($request->id);

        $user->condicion = "1";

        $user->save();

    }
}

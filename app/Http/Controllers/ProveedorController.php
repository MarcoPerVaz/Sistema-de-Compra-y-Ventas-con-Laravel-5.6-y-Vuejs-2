<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Import
use Illuminate\Support\Facades\DB;
use App\Proveedor;
use App\Persona;

class ProveedorController extends Controller
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

            $personas = Proveedor::join( 'personas', 'proveedores.id', '=', 'personas.id' )
            ->select( 'personas.id', 'personas.nombre', 'personas.tipo_documento', 'personas.num_documento', 'personas.direccion',
                      'personas.telefono', 'personas.email', 'proveedores.contacto', 'proveedores.telefono_contacto' )
            ->orderBy( 'personas.id', 'DESC' )->paginate( 3 ); /* Eloquent para paginar de 3 en 3 */
        
        }
        else {
            /* Filtrar las personas */
            $personas = Proveedor::join( 'personas', 'proveedores.id', '=', 'personas.id' )
                ->select( 'personas.id', 'personas.nombre', 'personas.tipo_documento', 'personas.num_documento', 'personas.direccion',
                          'personas.telefono', 'personas.email', 'proveedores.contacto', 'proveedores.telefono_contacto' )
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

            /* Modelo Proveedor */

                $proveedor = new Proveedor();

                $proveedor->contacto = $request->contacto;
                $proveedor->telefono_contacto = $request->telefono_contacto;
                $proveedor->id = $persona->id;

                $proveedor->save();

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

            $proveedor = Proveedor::findOrfail($request->id);

            $persona = Persona::findOrfail($proveedor->id);

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

                $proveedor->contacto = $request->contacto;
                $proveedor->telefono_contacto = $request->telefono_contacto;

                $proveedor->save();

            /*  */

            DB::commit(); /* Se finaliza la transacción */
            
        } catch (Exception $e) {
            
            DB::rollBack(); /* Si falla la transacción se regresa todo */

        }

    }

    /**
        *Función para cargar proveedores
    */
    public function selectProveedor(Request $request)
    {

        if ( !$request->ajax() )  return redirect('/'); /* Condicional para solo aceptar peticiones ajax */

        $filtro = $request->filtro;
        $proveedores= Proveedor::join('personas', 'proveedores.id', '=', 'personas.id')
                        ->where('personas.nombre', 'like', '%' . $filtro . '%')
                        ->orWhere('personas.num_documento', 'like', '%' . $filtro . '%')
                        ->select('personas.id', 'personas.nombre', 'personas.num_documento')
                        ->orderBy('personas.nombre', 'asc')->get();
        
        return [

            'proveedores' => $proveedores

        ];
    }
}

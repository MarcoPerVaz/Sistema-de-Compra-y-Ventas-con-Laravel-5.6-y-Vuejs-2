<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Importado
use App\Ingreso;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\DetalleIngreso;
use App\User;
use App\Notifications\NotifyAdmin;

class IngresoController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ( !$request->ajax() )  return redirect('/'); /* Condicional para solo aceptar peticiones ajax */

        $buscar     = $request->buscar;
        $criterio   = $request->criterio;

        if ( $buscar == '' ) {

            $ingresos = Ingreso::join( 'personas', 'ingresos.idproveedor', '=', 'personas.id' )
            ->join('users', 'ingresos.idusuario', '=', 'users.id')
            ->select( 'ingresos.id', 'ingresos.tipo_comprobante', 'ingresos.serie_comprobante', 'ingresos.num_comprobante',
                      'ingresos.fecha_hora', 'ingresos.impuesto', 'ingresos.total', 'ingresos.estado',
                      'personas.nombre', 'users.usuario' )
            ->orderBy( 'ingresos.id', 'DESC' )->paginate( 3 ); /* Eloquent para paginar de 3 en 3 */
        
        }
        else {
            /* Filtrar los ingresos */
            $ingresos = Ingreso::join( 'personas', 'ingresos.idproveedor', '=', 'personas.id' )
                ->join('users', 'ingresos.idusuario', '=', 'users.id')
                ->select( 'ingresos.id', 'ingresos.tipo_comprobante', 'ingresos.serie_comprobante', 'ingresos.num_comprobante',
                          'ingresos.fecha_hora', 'ingresos.impuesto', 'ingresos.total', 'ingresos.estado',
                          'personas.nombre', 'users.usuario' )
                ->where(  'ingresos.' . $criterio, 'LIKE', '%' . $buscar . '%' )
                ->orderBy('ingresos.id', 'DESC')->paginate( 3 ); 
        }


        return [

            'pagination' => [

                'total'         => $ingresos->total(),
                'current_page'  => $ingresos->currentPage(),
                'per_page'      => $ingresos->perPage(),
                'last_page'     => $ingresos->lastPage(),
                'from'          => $ingresos->firstItem(),
                'to'            => $ingresos->lastItem(),

            ],

            'ingresos' => $ingresos

        ];

    }

    /**
     * 
     */
    public function obtenerCabecera ( Request $request ) 
    {
        if ( !$request->ajax() )  return redirect('/'); /* Condicional para solo aceptar peticiones ajax */

        $id = $request->id;

        $ingreso = Ingreso::join( 'personas', 'ingresos.idproveedor', '=', 'personas.id' )
            ->join('users', 'ingresos.idusuario', '=', 'users.id')
            ->select( 'ingresos.id', 'ingresos.tipo_comprobante', 'ingresos.serie_comprobante', 'ingresos.num_comprobante',
                    'ingresos.fecha_hora', 'ingresos.impuesto', 'ingresos.total', 'ingresos.estado',
                    'personas.nombre', 'users.usuario' )
            ->where( 'ingresos.id', '=', $id )
            ->orderBy( 'ingresos.id', 'DESC' )->take( 1 )->get(); /* Eloquent para tomar solo un registro */

        return [

            'ingreso' => $ingreso

        ];
    }

    /**
     * 
     */
    public function obtenerDetalles ( Request $request ) 
    {

        if ( !$request->ajax() )  return redirect('/'); /* Condicional para solo aceptar peticiones ajax */

        $id = $request->id;

        $detalles = DetalleIngreso::join( 'articulos', 'detalle_ingresos.idarticulo', '=', 'articulos.id' )
            ->select( 'detalle_ingresos.cantidad', 'detalle_ingresos.precio', 'articulos.nombre as articulo' )
            ->where( 'detalle_ingresos.idingreso', '=', $id )
            ->orderBy( 'detalle_ingresos.id', 'DESC' )->get(); /* Eloquent para obtener todos los registros */

        return [

            'detalles' => $detalles

        ];

    }

     /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if ( !$request->ajax() )  return redirect('/'); /* Condicional para solo aceptar peticiones ajax */

        try {

            /* Se usan transacciones cuando se van a hacer operaciones en diferentes tablas al mismo tiempo,
               si falla una, fallan todas
            */
            DB::beginTransaction();

            /* Modelo Ingreso */

                $mytime = Carbon::now('America/Mexico_city'); /* Se obtiene la hora actual */

                $ingreso = new Ingreso();

                $ingreso->idproveedor = $request->idproveedor;
                $ingreso->idusuario = \Auth::user()->id;
                $ingreso->tipo_comprobante = $request->tipo_comprobante;
                $ingreso->serie_comprobante = $request->serie_comprobante;
                $ingreso->num_comprobante = $request->num_comprobante;
                $ingreso->fecha_hora = $mytime->toDateString(); /* Convierte la variable mytime en formato de fecha y hora aceptable */
                $ingreso->impuesto = $request->impuesto;
                $ingreso->total = $request->total;
                $ingreso->estado = 'Registrado';

                $ingreso->save();

            /*  */

            /* Modelo DetalleIngreso */

                $detalles = $request->data; /* Array con todos los detalles de ingresos */

                /* Foreach para recorrer la información de la variable $detalles */
                foreach ($detalles as $ep => $det) {
                    
                    $detalle = new DetalleIngreso();

                    $detalle->idingreso = $ingreso->id;
                    $detalle->idarticulo = $det['idarticulo'];
                    $detalle->cantidad = $det['cantidad'];
                    $detalle->precio = $det['precio'];

                    $detalle->save();
                }

                /*  */
                    $fechaActual = date( 'Y-m-d' ); /* Obtener la fecha formateada */
                    $numVentas = DB::table( 'ventas' )->whereDate( 'created_at', $fechaActual )->count(); /* Conteo de ventas */
                    $numIngresos = DB::table( 'ingresos' )->whereDate( 'created_at', $fechaActual )->count(); /* Conteo de ingresos */

                    $arregloDatos = [

                        'ventas' => [

                            'numero' => $numVentas,
                            'msj' => 'Ventas'

                        ],

                        'ingresos' => [

                            'numero' => $numIngresos,
                            'msj' => 'Ingresos'

                        ]
                    ];

                    $allUsers = User::all();/* Obtener todos los usuarios */

                    /* Recorrer a todos los usuarios  */
                    foreach ( $allUsers as $notificar ) {
                        
                        /* Consulta para saber a que usuario se notifica */
                        User::findOrFail( $notificar->id )->notify( new NotifyAdmin( $arregloDatos ) );
                        
                    }


                /*  */
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

        $ingreso = Ingreso::findOrfail($request->id);

        $ingreso->estado = 'Anulado';

        $ingreso->save();

    }

}

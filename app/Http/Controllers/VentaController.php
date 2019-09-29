<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Importado
use App\Venta;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\DetalleVenta;

class VentaController extends Controller
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

            $ventas = Venta::join( 'personas', 'ventas.idcliente', '=', 'personas.id' )
                ->join('users', 'ventas.idusuario', '=', 'users.id')
                ->select( 'ventas.id', 'ventas.tipo_comprobante', 'ventas.serie_comprobante', 'ventas.num_comprobante',
                        'ventas.fecha_hora', 'ventas.impuesto', 'ventas.total', 'ventas.estado',
                        'personas.nombre', 'users.usuario' )
                ->orderBy( 'ventas.id', 'DESC' )->paginate( 3 ); /* Eloquent para paginar de 3 en 3 */
        
        }
        else {
            /* Filtrar los ingresos */
            $ventas = Venta::join( 'personas', 'ventas.idcliente', '=', 'personas.id' )
                ->join('users', 'ventas.idusuario', '=', 'users.id')
                ->select( 'ventas.id', 'ventas.tipo_comprobante', 'ventas.serie_comprobante', 'ventas.num_comprobante',
                      'ventas.fecha_hora', 'ventas.impuesto', 'ventas.total', 'ventas.estado',
                      'personas.nombre', 'users.usuario' )
                ->where(  'ventas.' . $criterio, 'LIKE', '%' . $buscar . '%' )
                ->orderBy('ventas.id', 'DESC')->paginate( 3 ); 
        }


        return [

            'pagination' => [

                'total'         => $ventas->total(),
                'current_page'  => $ventas->currentPage(),
                'per_page'      => $ventas->perPage(),
                'last_page'     => $ventas->lastPage(),
                'from'          => $ventas->firstItem(),
                'to'            => $ventas->lastItem(),

            ],

            'ventas' => $ventas

        ];

    }

    /**
     * 
     */
    public function obtenerCabecera ( Request $request ) 
    {
        if ( !$request->ajax() )  return redirect('/'); /* Condicional para solo aceptar peticiones ajax */

        $id = $request->id;

        $venta = Venta::join( 'personas', 'ventas.idcliente', '=', 'personas.id' )
            ->join('users', 'ventas.idusuario', '=', 'users.id')
            ->select( 'ventas.id', 'ventas.tipo_comprobante', 'ventas.serie_comprobante', 'ventas.num_comprobante',
                    'ventas.fecha_hora', 'ventas.impuesto', 'ventas.total', 'ventas.estado',
                    'personas.nombre', 'users.usuario' )
            ->where( 'ventas.id', '=', $id )
            ->orderBy( 'ventas.id', 'DESC' )->take( 1 )->get(); /* Eloquent para tomar solo un registro */

        return [

            'venta' => $venta

        ];
    }

    /**
     * 
     */
    public function obtenerDetalles ( Request $request ) 
    {

        if ( !$request->ajax() )  return redirect('/'); /* Condicional para solo aceptar peticiones ajax */

        $id = $request->id;

        $detalles = DetalleVenta::join( 'articulos', 'detalle_ventas.idarticulo', '=', 'articulos.id' )
            ->select( 'detalle_ventas.cantidad', 'detalle_ventas.precio', 'detalle_ventas.descuento', 'articulos.nombre as articulo' )
            ->where( 'detalle_ventas.idventa', '=', $id )
            ->orderBy( 'detalle_ventas.id', 'DESC' )->get(); /* Eloquent para obtener todos los registros */

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

            /* Modelo Venta */

                $mytime = Carbon::now('America/Mexico_city'); /* Se obtiene la hora actual */

                $venta = new Venta();

                $venta->idcliente = $request->idcliente;
                $venta->idusuario = \Auth::user()->id;
                $venta->tipo_comprobante = $request->tipo_comprobante;
                $venta->serie_comprobante = $request->serie_comprobante;
                $venta->num_comprobante = $request->num_comprobante;
                $venta->fecha_hora = $mytime->toDateString(); /* Convierte la variable mytime en formato de fecha y hora aceptable */
                $venta->impuesto = $request->impuesto;
                $venta->total = $request->total;
                $venta->estado = 'Registrado';

                $venta->save();

            /*  */

            /* Modelo Detalleventa */

                $detalles = $request->data; /* Array con todos los detalles de ventas */

                /* Foreach para recorrer la información de la variable $detalles */
                foreach ($detalles as $ep => $det) {
                    
                    $detalle = new DetalleVenta();

                    $detalle->idventa = $venta->id;
                    $detalle->idarticulo = $det['idarticulo'];
                    $detalle->cantidad = $det['cantidad'];
                    $detalle->precio = $det['precio'];
                    $detalle->descuento = $det['descuento'];

                    $detalle->save();
                }

            /*  */

            DB::commit(); /* Se finaliza la transacción */

            return [

                'id' => $venta->id

            ];
            
        } catch (Exception $e) {
            
            DB::rollBack(); /* Si falla la transacción se regresa todo */

        }

    }

    /**
     * Función para determinar si la venta está inactiva - Cambia el campo condición de la tabla ventas
    */
    public function desactivar(Request $request)
    {

        if ( !$request->ajax() )  return redirect('/'); /* Condicional para solo aceptar peticiones ajax */

        $venta = Venta::findOrfail($request->id);

        $venta->estado = 'Anulado';

        $venta->save();

    }

    public function pdf ( Request $request, $id)
    {

        $venta = Venta::join( 'personas', 'ventas.idcliente', '=', 'personas.id' )
            ->join( 'users', 'ventas.idusuario', '=', 'users.id' )
            ->select( 'ventas.id', 'ventas.tipo_comprobante', 'ventas.serie_comprobante', 'ventas.num_comprobante',
                    'ventas.created_at', 'ventas.impuesto', 'ventas.total', 'ventas.estado', 'personas.nombre',
                    'personas.tipo_documento', 'personas.num_documento', 'personas.direccion', 'personas.email',
                    'personas.telefono', 'users.usuario' )
            ->where( 'ventas.id', '=', $id )
            ->orderBy( 'ventas.id', 'desc' )->take( 1 )->get();

        $detalles = DetalleVenta::join( 'articulos', 'detalle_ventas.idarticulo', '=', 'articulos.id' )
            ->select( 'detalle_ventas.cantidad', 'detalle_ventas.precio', 'detalle_ventas.descuento', 'articulos.nombre as articulo' )
            ->where( 'detalle_ventas.idventa', '=', $id )
            ->orderBy( 'detalle_ventas.id', 'desc' )->get();

        $numVenta = Venta::select( 'num_comprobante' )->where( 'id', $id )->get();

        $pdf = \PDF::loadView( 'pdf.venta', [

            'venta' => $venta,
            'detalles' => $detalles,

        ] );

        return $pdf->download( 'venta-' . $numVenta[ 0 ]->num_comprobante . '.pdf' );

    }

}

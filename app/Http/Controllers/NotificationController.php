<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Importado
use App\Notification;
use Auth;

class NotificationController extends Controller
{
    /**
     * Función para obtener las notificaciones
     */
    public function get ()
    {

        // return Notification::all(); /* Retorna todas las notificaciones */
        
        // return Auth::user()->notifications; /* Retorna las notificaciones del usuario logueado */

        $unreadNotifications = Auth::user()->unreadNotifications;

        $fechaActual = date( 'Y-m-d' );

        foreach ( $unreadNotifications as $notification ) {

            if ( $fechaActual != $notification->created_at->toDateString() ) {
                
                $notification->markAsRead();

            }

        }

        return Auth::user()->unreadNotifications;
    }
}

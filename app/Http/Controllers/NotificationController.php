<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Importado
use App\Notification;

class NotificationController extends Controller
{
    /**
     * Función para obtener las notificaciones
     */
    public function get ()
    {
        return Notification::all(); /* Retorna todas las notificaciones */
    }
}

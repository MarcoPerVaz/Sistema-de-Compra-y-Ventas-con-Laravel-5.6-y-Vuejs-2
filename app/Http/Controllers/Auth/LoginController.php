<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

// Importado
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /** 
    * Función para mostrar el Login 
    */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Validación de campos,
     * Condicional para comparar datos para el login
    */
    public function login(Request $request)
    {

       $this->validateLogin( $request ); /* Instancia de la función para validar los campos */

        if ( Auth::attempt( [ 'usuario' => $request->usuario, 'password' => $request->password, 'condicion' => 1 ] ) ) 
        {

            return redirect()->route( 'main' );

        }

        return back()->withErrors( [ 'usuario' => trans( 'auth.failed' ) ] )
        ->withInput( request ( [ 'usuario' ] ) ); 

    }
    /**
        *Función para validar los campos 
    */
    protected function validateLogin( Request $request)
    {
        
        $this->validate( $request, [

            'usuario' => 'required|string',
            'password' => 'required|string',

        ] );
    }

    /**
     *Función para cerrar sesión 
    */
    public function logout(Request $request)
    {

        Auth::logout();

        $request->session()->invalidate();

        return redirect( '/' );
    }

}

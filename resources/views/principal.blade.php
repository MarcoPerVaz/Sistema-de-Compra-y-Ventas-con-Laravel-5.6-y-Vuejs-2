<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema Ventas Laravel Vue Js">
    <meta name="keyword" content="Sistema ventas Laravel Vue Js, Sistema compras Laravel Vue Js">
    {{-- Csrf-Token --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="img/favicon.png">
    {{-- ID para obtener el canal de la notificación --}}
        <meta name="userId" content="{{ Auth::check() ? Auth::user()->id : '' }}">
    <title>Sistema Compra y Ventas</title>
    <!-- Estilos Compilados -->
    <link href="css/plantilla.css" rel="stylesheet">
    {{-- Chart.js --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js">
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <div id="app">
        <header class="app-header navbar">
            <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
              <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="#">Sistema</a>
            <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
              <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="nav navbar-nav d-md-down-none">
                <li class="nav-item px-3">
                    <a class="nav-link" href="#">Escritorio</a>
                </li>
                <li class="nav-item px-3">
                    <a class="nav-link" href="#">Configuraciones</a>
                </li>
            </ul>
            <ul class="nav navbar-nav ml-auto">
                
                {{-- Componente Notification.vue --}}
                    {{-- Usar dos puntos es como poner v-bind que es enlazar a la variable notificatios usando props en Notification.vue--}}
                    <notification :notifications="notifications"></notification> 

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="img/avatars/6.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                        <span class="d-md-down-none">{{ Auth::user()->usuario }} </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header text-center">
                            <strong>Cuenta</strong>
                        </div>
                        <a class="dropdown-item" href="{{ route( 'logout' ) }}" 
                            onclick="event.preventDefault(); document.getElementById( 'logout-form' ).submit();">
                            <i class="fa fa-lock"></i> Cerrar sesión
                        </a>
                        <form action="{{ route('logout') }}" method="post" id="logout-form" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
        </header>
    
        <div class="app-body">
            
            @if ( Auth::check() ) {{-- Si el usuario está registrado --}}

                @if ( Auth::user()-> idrol == 1 ) {{-- Rol Administrador --}}

                    @include('plantilla.sidebaradministrador')

                @elseif ( Auth::user()-> idrol == 2 ) {{-- Rol Vendedor --}}

                    @include('plantilla.sidebarvendedor')

                @elseif ( Auth::user()-> idrol == 3 ) {{-- Rol Almacenero --}}

                    @include('plantilla.sidebaralmacenero')
                
                @else {{-- Ningún sidebar --}}

                @endif

            @endif
    
            <!-- Contenido Principal -->
                @yield('contenido')
            <!-- /Fin del contenido principal -->
        </div>
    
    </div>

        <footer class="app-footer">
            <span> &copy; Sistema de Compra y Ventas 2017</span>
        </footer>

    <!-- Plugins Compilados -->
    <script src="js/app.js"></script>
    <script src="js/plantilla.js"></script>
</body>

</html>
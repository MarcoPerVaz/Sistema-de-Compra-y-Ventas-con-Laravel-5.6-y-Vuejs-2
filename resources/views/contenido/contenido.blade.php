
@extends('principal')

@section('contenido')

    @if ( Auth::check() ) {{-- Si el usuario está registrado --}}

        @if ( Auth::user()-> idrol == 1 ) {{-- Rol Administrador --}}

           <template v-if = "menu == 0">

                {{-- Componente Dashboard.vue --}}
                <dashboard></dashboard>

            </template>
            <template v-if = "menu == 1">

                {{-- Componente Categoria.vue --}}
                <categoria></categoria>

            </template>
            <template v-if = "menu == 2">
                
                {{-- Componente Articulo.vue --}}
                <articulo></articulo>

            </template>
            <template v-if = "menu == 3">

                {{-- Componente Ingreso.vue --}}
                <ingreso></ingreso>
                
            </template>
            <template v-if = "menu == 4">

                {{-- Componente Proveedor.vue --}}
                <proveedor></proveedor>

            </template>
            <template v-if = "menu == 5">

                {{-- Componente Venta.vue --}}
                <venta></venta>
                
            </template>
            <template v-if = "menu == 6">

                {{-- Componente Cliente.vue --}}
                <cliente></cliente>
                
            </template>
            <template v-if = "menu == 7">

                {{-- Componente User.vue --}}
                <user></user>

            </template>
            <template v-if = "menu == 8">

                {{-- {{ Componente Rol.vue }} --}}
                <rol></rol>

            </template>
            <template v-if = "menu == 9">
                
                {{-- Componente ConsultaIngreso.vue --}}
                <consultaingreso></consultaingreso>
                
            </template>
            <template v-if = "menu == 10">
                
                {{-- Componente ConsultaVenta.vue --}}
                <consultaventa></consultaventa>

            </template>
            <template v-if = "menu == 11">
                <h1>Ayuda</h1>
            </template>
            <template v-if = "menu == 12">
                <h1>Acerca de</h1>
            </template>

        @elseif ( Auth::user()-> idrol == 2 ) {{-- Rol Vendedor --}}

            <template v-if = "menu == 0">

                {{-- Componente Dashboard.vue --}}
                <dashboard></dashboard>
                
            </template>
            <template v-if = "menu == 5">
                
                {{-- Componente Venta.vue --}}
                <venta></venta>
                
            </template>
            <template v-if = "menu == 6">

                {{-- Componente Cliente.vue --}}
                <cliente></cliente>
                
            </template>
            <template v-if = "menu == 10">
                
                {{-- Componente ConsultaVenta.vue --}}
                <consultaventa></consultaventa>
                
            </template>
            <template v-if = "menu == 11">
                <h1>Ayuda</h1>
            </template>
            <template v-if = "menu == 12">
                <h1>Acerca de</h1>
            </template>

        @elseif ( Auth::user()-> idrol == 3 ) {{-- Rol Almacenero --}}

            <template v-if = "menu == 0">

                {{-- Componente Dashboard.vue --}}
                <dashboard></dashboard>
                
            </template>

            <template v-if = "menu == 1">

                {{-- Componente Categoria.vue --}}
                <categoria></categoria>

            </template>
            <template v-if = "menu == 2">
                
                {{-- Componente Articulo.vue --}}
                <articulo></articulo>

            </template>
            <template v-if = "menu == 3">

                {{-- Componente Ingreso.vue --}}
                <ingreso></ingreso>
                
            </template>
            <template v-if = "menu == 4">

                {{-- Componente Proveedor.vue --}}
                <proveedor></proveedor>

            </template>
            <template v-if = "menu == 9">

                {{-- Componente ConsultaIngreso.vue --}}
                <consultaingreso></consultaingreso>
                
            </template>
            <template v-if = "menu == 11">
                <h1>Ayuda</h1>
            </template>
            <template v-if = "menu == 12">
                <h1>Acerca de</h1>
            </template>
        
        @else {{-- Ningún sidebar --}}

        @endif

    @endif

@endsection
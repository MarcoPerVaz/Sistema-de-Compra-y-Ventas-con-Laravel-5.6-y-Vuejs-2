<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!--  Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Ventas
                    <button type="button" class="btn btn-secondary" @click="mostrarDetalle()">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <!-- Listado -->
                    <template v-if="listado == 1">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                            <option value="tipo_comprobante">Tipo comprobante</option>
                                            <option value="num_comprobante">Número comprobante</option>
                                            <option value="fecha_hora">Fecha - hora</option>
                                        </select>
                                        <input type="text" class="form-control" placeholder="Texto a buscar" 
                                            v-model="buscar" @keyup.enter="listarVenta( 1, buscar, criterio )">
                                        <button type="submit" class="btn btn-primary" @click="listarVenta( 1, buscar, criterio )">
                                            <i class="fa fa-search"></i> 
                                            Buscar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Usuario</th>
                                            <th>ProvClienteeedor</th>
                                            <th>Tipo de comprobante</th>
                                            <th>Serie de comprobante</th>
                                            <th>Número de comprobante</th>
                                            <th>Fecha - hora</th>
                                            <th>Total</th>
                                            <th>Impuesto</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
            
                                        <!-- Renderizando el array arrayPersona -->
                                        <tr v-for="venta in arrayVenta" :key="venta.id">
            
                                            <td>
                                                <button type="button" 
                                                        class="btn btn-success btn-sm" 
                                                        @click="verVenta( venta.id )">
                                                <i class="icon-eye"></i>
                                                </button> &nbsp;
                                                <button type="button" 
                                                        class="btn btn-info btn-sm" 
                                                        @click="pdfVenta( venta.id )">
                                                <i class="icon-doc"></i>
                                                </button> &nbsp;
            
                                                <!-- Borrado lógico - activado/desactivado -->
                                                    <template v-if="venta.estado == 'Registrado'">
                                                        <button type="button" class="btn btn-danger btn-sm" 
                                                            @click="desactivarVenta(venta.id)">
                                                                <i class="icon-trash"></i>
                                                        </button>
                                                    </template>
                                                <!-- Borrado lógico - activado/desactivado -->
            
                                            </td>
            
                                            <!-- Mostrando información de la BD -->
                                                <td v-text="venta.usuario"></td>
                                                <td v-text="venta.nombre"></td>
                                                <td v-text="venta.tipo_comprobante"></td>
                                                <td v-text="venta.serie_comprobante"></td>
                                                <td v-text="venta.num_comprobante"></td>
                                                <td v-text="venta.fecha_hora"></td>
                                                <td v-text="venta.total"></td>
                                                <td v-text="venta.impuesto"></td>
                                                <td v-text="venta.estado"></td>
                                            <!-- Fin Mostrando información de la BD -->
            
                                        </tr>
                                        <!-- Fin Renderizando el array arrayCategoria -->
                                        
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <ul class="pagination">

                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina( pagination.current_page - 1, buscar, criterio )">
                                            Ant
                                        </a>
                                    </li>
                                    <!-- li que usa las propiedades computadas -->
                                        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                            <a class="page-link" href="#" @click.prevent="cambiarPagina( page, buscar, criterio )" v-text="page"></a>
                                        </li>

                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page ">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina( pagination.current_page + 1, buscar, criterio )">
                                            Sig
                                        </a>
                                    </li>

                                </ul>
                            </nav>
                        </div>
                    </template>
                <!-- Fin Listado -->
                <!-- Detalle de Venta -->
                    <template v-else-if="listado == 0">
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="">Cliente(*)</label>

                                        <!-- Componente vue-select -->
                                            <v-select :on-search="selectCliente" 
                                                    label="nombre" 
                                                    :options="arrayCliente"
                                                    placeholder="Buscar Clientes..."
                                                    :onChange="getDatosCliente"></v-select>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Impuesto(*)</label>
                                    <input type="text" class="form-control" v-model="impuesto">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Tipo Comprobante (*)</label>
                                        <select name="" id="" class="form-control" v-model="tipo_comprobante">
                                            <option value="0">Seleccione</option>
                                            <option value="BOLETA">Boleta</option>
                                            <option value="FACTURA">Factura</option>
                                            <option value="TICKET">Ticket</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Serie Comprobante</label>
                                        <input type="text" class="form-control" placeholder="000x" v-model="serie_comprobante">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Número Comprobante(*)</label>
                                        <input type="text" class="form-control" placeholder="000xx" v-model="num_comprobante">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <!-- Mostrar errores en el modal --> <!-- Visualizar los errores si errorVenta tiene el valor 1 -->
                                        <div class="form-group row div-error" v-show="errorVenta">
                                            <div class="text-center text-error">
                                                <div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error">

                                                </div>
                                            </div>
                                        </div>
                                    <!-- Fin Mostrar errores en el modal -->
                                </div>
                            </div>
                            <div class="form-group row border">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Artículo <span style="color:red;" v-show="idarticulo == 0">(*Seleccione)</span></label>
                                        <div class="form-inline">
                                            <input type="text" 
                                                   class="form-control" 
                                                   placeholder="Ingrese artículo" 
                                                   v-model="codigo"
                                                   @keyup.enter="buscarArticulo()">
                                            <button class="btn btn-primary" @click="abrirModal()">...</button>
                                            <input type="text" readonly class="form-control" v-model="articulo">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Precio <span style="color:red;" v-show="precio == 0">(*Ingrese)</span></label>
                                        <input type="number" step="any" class="form-control" value="0" v-model="precio">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Cantidad <span style="color:red;" v-show="cantidad == 0">(*Ingrese)</span></label>
                                        <input type="number" class="form-control" value="0" v-model="cantidad">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Descuento</label>
                                        <input type="number" class="form-control" value="0" v-model="descuento">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button class="btn btn-success form-control btnagregar" @click="agregarDetalle()">
                                            <i class="icon-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row border">
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Opciones</th>
                                                <th>Artículo</th>
                                                <th>Precio</th>
                                                <th>Cantidad</th>
                                                <th>Descuento</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="( detalle, index ) in arrayDetalle" :key="detalle.id">
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm" @click="eliminarDetalle( index )">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>

                                                <!-- Nombre artículo -->
                                                <td v-text="detalle.articulo">  

                                                </td>

                                                <!-- Precio artículo -->
                                                <td>
                                                    <input type="number" class="form-control" v-model="detalle.precio">
                                                </td>

                                                <!-- cantidad de artículos -->
                                                <td>
                                                    <span style="color: red;" 
                                                          v-show="detalle.cantidad > detalle.stock">Stock: {{ detalle.stock }}</span>
                                                    <input type="number" class="form-control" v-model="detalle.cantidad">
                                                </td>

                                                <!-- descuento de artículos -->
                                                <td>
                                                    <span style="color: red;" 
                                                          v-show="detalle.descuento > (detalle.precio * detalle.cantidad)">
                                                            Descuento superior
                                                    </span>
                                                    <input type="number" class="form-control" v-model="detalle.descuento">
                                                </td>

                                                <!-- Precio total del artículo -->
                                                <td>
                                                    {{ detalle.precio * detalle.cantidad - detalle.descuento }}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="5" align="right"><strong>Total Parcial:</strong> </td>
                                                <td>$ {{ totalParcial = (  total - totalImpuesto ).toFixed( 2 ) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="5" align="right"><strong>Total Impuesto:</strong> </td>
                                                <td>$ {{ totalImpuesto =  ( total * impuesto ).toFixed( 2 ) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="5" align="right"><strong>Total Neto:</strong> </td>
                                                <td>$ {{ total = calcularTotal }}</td> <!-- Dónde calcularTotal es la propiedad computada -->
                                            </tr>
                                        </tbody>

                                        <tbody v-else>
                                            <tr>
                                                <td colspan="6">
                                                    NO hay artículos agregados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary" @click="ocultarDetalle()">Cerrar</button>
                                    <button type="button" class="btn btn-primary" @click="registrarVenta()">Registrar Venta</button>
                                </div>
                            </div>
                        </div>
                    </template>
                <!-- Fin Detalle de Venta -->

                <!-- Ver Venta -->
                    <template v-else-if="listado == 2">
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="">Cliente</label>
                                        <p v-text="cliente"></p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Impuesto</label>
                                    <p v-text="impuesto"></p>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Tipo Comprobante</label>
                                        <p v-text="tipo_comprobante"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Serie Comprobante</label>
                                        <p v-text="serie_comprobante"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Número Comprobante</label>
                                        <p v-text="num_comprobante"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row border">
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Artículo</th>
                                                <th>Precio</th>
                                                <th>Cantidad</th>
                                                <th>Descuento</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="detalle in arrayDetalle" :key="detalle.id">

                                                <!-- Nombre artículo -->
                                                <td v-text="detalle.articulo">  

                                                </td>

                                                <!-- Precio artículo -->
                                                <td v-text="detalle.precio">

                                                </td>

                                                <!-- Cantidad de artículos -->
                                                <td v-text="detalle.cantidad">
                                                    
                                                </td>

                                                <!-- Descuento de artículos -->
                                                <td v-text="detalle.descuento">
                                                    
                                                </td>

                                                <!-- Precio total del artículo -->
                                                <td>
                                                    {{ detalle.precio * detalle.cantidad - descuento }}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total Parcial:</strong> </td>
                                                <td>$ {{ totalParcial = (  total - totalImpuesto ).toFixed( 2 ) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total Impuesto:</strong> </td>
                                                <td>$ {{ totalImpuesto =  ( total * impuesto ).toFixed( 2 ) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total Neto:</strong> </td>
                                                <td>$ {{ total }}</td> <!-- Dónde calcularTotal es la propiedad computada -->
                                            </tr>
                                        </tbody>

                                        <tbody v-else>
                                            <tr>
                                                <td colspan="5">
                                                    NO hay artículos agregados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary" @click="ocultarDetalle()">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </template>
                <!-- Fin Ver Venta -->
            </div>
            <!-- Fin Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" 
             style="display: none;" aria-hidden="true" :class="{ 'mostrar' : modal }"> <!-- Por defecto la variable modal es 0 -->
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" aria-label="Close" @click="cerrarModal()">
                        <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioA">
                                        <option value="nombre">Nombre</option>
                                        <option value="descripcion">Descripción</option>
                                        <option value="codigo">Código</option>
                                    </select>
                                    <input type="text" class="form-control" placeholder="Texto a buscar" 
                                        v-model="buscarA" @keyup.enter="listarArticulo( buscarA, criterioA )">
                                    <button type="submit" class="btn btn-primary" @click="listarArticulo( buscarA, criterioA )">
                                        <i class="fa fa-search"></i> 
                                        Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Código de barras</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Precio Venta</th>
                                        <th>Stock</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <!-- Renderizando el array arrayArticulo -->
                                    <tr v-for="articulo in arrayArticulo" :key="articulo.id">

                                        <td>
                                            <button type="button" 
                                                    class="btn btn-success btn-sm" 
                                                    @click="agregarDetalleModal( articulo )">
                                            <i class="icon-check"></i>
                                            </button>
                                        </td>

                                        <!-- Mostrando información de la BD -->
                                            <td v-text="articulo.codigo"></td>
                                            <td v-text="articulo.nombre"></td>
                                            <td v-text="articulo.nombre_categoria"></td>
                                            <td v-text="articulo.precio_venta"></td>
                                            <td v-text="articulo.stock"></td>
                                            <td>
                                            <!-- Condicional para mostrar si la categoría está activa o inactiva -->
                                                <div v-if="articulo.condicion == 1">

                                                <span class="badge badge-success">Activo</span>

                                                </div>

                                                <div v-else>

                                                <span class="badge badge-danger">Desactivado</span>

                                                </div>

                                            <!-- Fin Condicional para mostrar si la categoría está activa o inactiva -->
                                            </td>
                                        <!-- Fin Mostrando información de la BD -->

                                    </tr>
                                    <!-- Fin Renderizando el array arrayCategoria -->
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" v-if="tipoAccion == 1" @click="registrarPersona()">Guardar</button>
                        <button type="button" class="btn btn-primary" v-if="tipoAccion == 2" @click="actualizarPersona()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>

    import vSelect from 'vue-select';

    export default {
        data() {

          return {

            venta_id: 0,
            idcliente: 0,
            cliente: '',
            tipo_comprobante: 'BOLETA',
            serie_comprobante: '',
            num_comprobante: '',
            impuesto: 0.16,
            total: 0.0,
            totalImpuesto: 0.0,
            totalParcial: 0.0,
            arrayVenta: [],
            arrayCliente: [],
            arrayDetalle: [],
            listado: 1,
            modal: 0, /* Variable para mostrar u ocultar el modal */
            tituloModal: '', /* Variable para definir si se crea o se actualiza la categoría */
            tipoAccion: 0, /* Variable para definir la cadena de texto del botón guardar del modal, dónde 1 es Guardar y 2 Actualizar */
            errorVenta: 0,
            errorMostrarMsjVenta: [],
            pagination: {

                'total': 0, 
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,

            },
            offset: 3,
            criterio: 'num_comprobante',
            buscar: '',
            criterioA: 'nombre',
            buscarA: '',
            arrayArticulo: [],
            idarticulo: 0,
            codigo: '',
            articulo: '',
            precio: 0,
            cantidad: 0,
            descuento: 0,
            stock: 0,

          }

        },

        components: {

            vSelect

        },

        computed: {

            isActived: function() {

                return this.pagination.current_page;

            },

            /* Calcula los elementos de la paginación */
            pagesNumber: function() {

                if ( !this.pagination.to ) {

                    return [];

                }
                
                // 
                var from = this.pagination.current_page - this.offset; /* página actual - 3 */ /* offset es inicializada en 3 en data */

                if ( from < 1 ) { /* from = pagina actual - 3 */

                    from = 1;

                }

                // 
                var to = from + ( this.offset * 2 ); /* to = from + ( 3 * 2 ) */ /* offset es inicializada en 3 en data */

                if ( to >= this.pagination.last_page ) { /* to >= última página */
                    
                    to = this.pagination.last_page;
                }

                // 
                var pagesArray = [];

                while ( from <= to ) {
                    
                    pagesArray.push( from );

                    from ++;
                }

                return pagesArray;

            },

            /**
             * Función para calcular totales
             */
            calcularTotal: function () {

                var resultado = 0;

                for ( var i = 0; i < this.arrayDetalle.length; i++ ) {
                    
                    resultado = resultado + ( this.arrayDetalle[ i ].precio * this.arrayDetalle[ i ].cantidad 
                        - this.arrayDetalle[ i ].descuento )
                    
                }

                return resultado;
            },

        },

        methods: {

          listarVenta(page, buscar, criterio) {

            let me = this; 

            var url = '/venta?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;

            axios.get( url ).then(function (response){

                var respuesta = response.data;
                
                me.arrayVenta = respuesta.ventas.data; /* Esto es igual a poner this.arrayVenta = respuesta.ventas.data */

                me.pagination = respuesta.pagination; /* Esto es igual a poner this.pagination = respuesta.pagination */

            })
            .catch(function(error) {

              console.log(error);

            });

          },

          selectCliente( search, loading ) {

              let me = this; 
              loading( true )

            var url = '/cliente/selectCliente?filtro=' + search ;

            axios.get( url ).then(function (response){

                var respuesta = response.data;

                q:search
                
                me.arrayCliente = respuesta.clientes /* Esto es igual a poner this.arrayCliente = respuesta.clientes */

                loading( false )

            })
            .catch(function(error) {

              console.log(error);

            });

          },

          getDatosCliente( val1 ){

              let me= this;
              me.loading = true;
              me.idcliente = val1.id;

          },

          buscarArticulo() {

              let me = this;
              var url = '/articulo/buscarArticuloVenta?filtro=' + me.codigo;

              axios.get( url ).then( function ( response ) {

                var respuesta = response.data;
                me.arrayArticulo = respuesta.articulos;

                if ( me.arrayArticulo.length ) {
                    
                    me.articulo = me.arrayArticulo[ 0 ][ 'nombre' ];
                    me.idarticulo = me.arrayArticulo[ 0 ][ 'id' ];
                    me.precio = me.arrayArticulo[ 0 ][ 'precio_venta' ];
                    me.stock = me.arrayArticulo[ 0 ][ 'stock' ];

                }
                else {

                    me.articulo = 'No existe artículo';
                    me.idarticulo = 0;

                }

              } )
              .catch( function ( error ) {

                  console.log(error);
                  
              });
          },

          cambiarPagina( page, buscar, criterio ) {

            let me = this;
              
            // Actualiza la página actual
            me.pagination.current_page = page;
            
            // Envia la petición para visualizar la data de esa página
            me.listarVenta( page, buscar, criterio );

          },
  
        /**
         * Función para comprobar si ya existe el artículo en la lista de detalle
         */
          encuentra ( id ){

              var sw = 0;

              for ( var i = 0; i < this.arrayDetalle.length; i++ ) {

                  if ( this.arrayDetalle[ i ].idarticulo == id ) {
                      
                      sw = true;

                  }
                  
              }

              return sw;
          },

          agregarDetalle () {

              let me = this;

              if ( me.idarticulo == 0 || me.cantidad == 0 || me.precio == 0 ) {
                  
              }
              else {
                  
                  if ( me.encuentra( me.idarticulo ) ) {
                    swal( {
                        type: 'error',
                        title: 'Error...',
                        text: 'Ese artículo ya se encuentra agregado',
                    })    
                  }
                  else {

                      if ( me.cantidad > me.stock ) {
                          
                        swal( {
                            type: 'error',
                            title: 'Error...',
                            text: 'NO hay stock disponible',
                        } )    

                      }
                      else {

                        me.arrayDetalle.push( {
        
                            idarticulo: me.idarticulo,
                            articulo: me.articulo,
                            cantidad: me.cantidad,
                            precio: me.precio,
                            descuento: me.descuento,
                            stock: me.stock,
    
                        } );
            
                        me.codigo = '';
                        me.idarticulo = 0;
                        me.articulo = '';
                        me.cantidad = 0;
                        me.precio = 0;
                        me.descuento = 0;
                        me.stock = 0;

                      }
                    
                  }

              }

          },

          /**
           * Funci+on para agregar un artículo al detalle desde el modal
           */
          agregarDetalleModal ( data = [ ] ) {

            let me = this;

            if ( me.encuentra( data[ 'id' ] ) ) {
                    swal( {
                        type: 'error',
                        title: 'Error...',
                        text: 'Ese artículo ya se encuentra agregado',
                    })    
                  }
            else {

                me.arrayDetalle.push( {

                    idarticulo: data[ 'id' ],
                    articulo: data[ 'nombre' ],
                    cantidad: 1,
                    precio: data[ 'precio_venta' ],
                    descuento: 0,
                    stock: data[ 'stock' ],

                } );

            }

          },

          /**
           * Función para listar los artículos en el modal
           */
          listarArticulo( buscar, criterio ) {

            let me = this; 

            var url = '/articulo/listarArticuloVenta?buscar=' + buscar + '&criterio=' + criterio;

            axios.get( url ).then(function (response){

                var respuesta = response.data;
                
                me.arrayArticulo = respuesta.articulos.data; /* Esto es igual a poner this.arrayCategoria = respuesta.categorias.data */

            })
            .catch(function(error) {

              console.log(error);

            });

          },

          /**
           * Función para eliminar artículo de la lista de detalles
           */
          eliminarDetalle ( index ) {

              let me = this;

              me.arrayDetalle.splice( index, 1 );
          },

          registrarVenta () {

              if ( this.validarVenta() ) {
                  return;
              }

              let me = this;

              axios.post('/venta/registrar', {

                  'idcliente': this.idcliente,
                  'tipo_comprobante': this.tipo_comprobante,
                  'serie_comprobante': this.serie_comprobante,
                  'num_comprobante': this.num_comprobante,
                  'impuesto': this.impuesto,
                  'total': this.total,
                  'data': this.arrayDetalle,

              }).then(function(response) {

                  me.listado = 1;
                  me.listarVenta( 1, '', 'num_comprobante' ); /* Esto es igual a poner this.listarVenta( 1, '', 'num_comprobante' ); */
                  me.idcliente = 0;
                  me.tipo_comprobante = 'BOLETA';
                  me.serie_comprobante = '';
                  me.num_comprobante = '';
                  me.impuesto = 0.16;
                  me.total = 0.0;
                  me.idarticulo = 0;
                  me.articulo = '';
                  me.cantidad = 0;
                  me.precio = 0;
                  me.arrayDetalle = [];
                  me.stock = 0;
                  me.codigo = 0;
                  me.descuento = 0;
                  window.open( 'http://127.0.0.1:8000/venta/pdf/' + response.data.id + ',' + '_blank' );/* Abrir el pdf de la venta */

              }).catch(function (error) {

                  console.log(error);
                  
              });

          },

          validarVenta () {

              let me= this;

              me.errorVenta = 0;
              me.errorMostrarMsjVenta = [];

              var art;

              /* Código para validar y mostrar que artículo supera el stock al momento de registrar */
              me.arrayDetalle.map( function ( x ) {

                  if ( x.cantidad > x.stock ) {
                      
                      art = x.articulo + " con stock insuficiente";
                      me.errorMostrarMsjVenta.push( art );

                  }

              } );

              if ( me.idcliente == 0 ) me.errorMostrarMsjVenta.push( "Seleccione un Cliente" );
              if ( me.tipo_comprobante == 0 ) me.errorMostrarMsjVenta.push( "Seleccione el comprobante" );
              if ( !me.num_comprobante ) me.errorMostrarMsjVenta.push( "Seleccione el número del comprobante" );
              if ( !me.impuesto ) me.errorMostrarMsjVenta.push( "Seleccione el impuesto de compra" );
              if ( me.arrayDetalle.length <= 0 ) me.errorMostrarMsjVenta.push( "Ingrese detalles" );

              if ( me.errorMostrarMsjVenta.length ) me.errorVenta = 1; 

              return me.errorVenta;

          },

          abrirModal ()  {

            this.arrayArticulo = [];
            this.modal = 1;
            this.tituloModal = 'Seleccione uno o varios artículos'  

          },

          cerrarModal () {

            this.modal = 0;
            this.tituloModal = '';

          },

          desactivarVenta ( id ) { /* Borrado lógico de ventas */
            
            swal({
                title: '¿Estás seguro de anular esta venta?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
                }).then((result) => {

                    if (result.value) {

                        let me = this;

                        axios.put('/venta/desactivar', {

                            'id': id,

                        }).then(function(response) {

                            me.listarVenta( 1, '', 'num_comprobante' ); /*Esto es igual a poner this.listarVenta( 1,'','num_comprobante'); */

                            swal(
                                'Anulado!',
                                'La venta ha sido anulada con éxito.',
                                'success'
                            )

                        }).catch(function (error) {

                            console.log(error);
                            
                        });
                        
                    } else if (result.dismiss === swal.DismissReason.cancel) {
                        /* No se muestra nada si se da en cancelar */
                    }
                })

          },

          mostrarDetalle() {

            let me = this;

            this.listado = 0;

            me.idproveedor = 0;
            me.tipo_comprobante = 'BOLETA';
            me.serie_comprobante = '';
            me.num_comprobante = '';
            me.impuesto = 0.16;
            me.total = 0.0;
            me.idarticulo = 0.0;
            me.articulo = '';
            me.cantidad = 0;
            me.precio = 0;
            me.arrayDetalle = [];

          },

          ocultarDetalle() {

              this.listado = 1;

          },
          
          /**
           * Función para ver los detalles del ingreso registrado
           */
          verVenta ( id ) {

            let me = this;
            me.listado = 2;

            var arrayVentaT = [];

            // Obtener Los datos del ingreso
            var url = '/venta/obtenerCabecera?id=' + id;

            axios.get( url ).then(function (response){

                var respuesta = response.data;
                
                arrayVentaT = respuesta.venta; 

                me.cliente = arrayVentaT[ 0 ][ 'nombre' ];
                me.tipo_comprobante = arrayVentaT[ 0 ][ 'tipo_comprobante' ];
                me.serie_comprobante = arrayVentaT[ 0 ][ 'serie_comprobante' ];
                me.num_comprobante = arrayVentaT[ 0 ][ 'num_comprobante' ];
                me.impuesto = arrayVentaT[ 0 ][ 'impuesto' ];
                me.total = arrayVentaT[ 0 ][ 'total' ];

            })
            .catch(function(error) {

                console.log(error);

            });

            // Obtener los datos de los detalles
            var urld = '/venta/obtenerDetalles?id=' + id;

            axios.get( urld ).then(function (response){

                var respuesta = response.data;
                
                me.arrayDetalle = respuesta.detalles; 

            })
            .catch(function(error) {

                console.log(error);

            });

          },
          
          /**
           *
           */
          pdfVenta ( id ) {

              window.open( 'http://127.0.0.1:8000/venta/pdf/' + id + ',' + '_blank' );

          },

        },
        
        mounted() {

            this.listarVenta(1, this.buscar, this.criterio);

        }
    }
</script>

 <style>

    .modal-content {

        width: 100% !important;
        position: absolute !important;
        
    }

    .mostrar {

        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;

    }

    .div-error {

        display: flex;
        justify-content: center;

    }

    .text-error {

        color: red !important;
        font-weight: bold;
    }

    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }

</style>
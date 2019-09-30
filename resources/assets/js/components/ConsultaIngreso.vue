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
                    <i class="fa fa-align-justify"></i> Ingresos
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
                                            v-model="buscar" @keyup.enter="listarIngreso( 1, buscar, criterio )">
                                        <button type="submit" class="btn btn-primary" @click="listarIngreso( 1, buscar, criterio )">
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
                                            <th>Proveedor</th>
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
                                        <tr v-for="ingreso in arrayIngreso" :key="ingreso.id">
            
                                            <td>
                                                <button type="button" 
                                                        class="btn btn-success btn-sm" 
                                                        @click="verIngreso( ingreso.id )">
                                                <i class="icon-eye"></i>
                                                </button>
            
                                            </td>
            
                                            <!-- Mostrando información de la BD -->
                                                <td v-text="ingreso.usuario"></td>
                                                <td v-text="ingreso.nombre"></td>
                                                <td v-text="ingreso.tipo_comprobante"></td>
                                                <td v-text="ingreso.serie_comprobante"></td>
                                                <td v-text="ingreso.num_comprobante"></td>
                                                <td v-text="ingreso.fecha_hora"></td>
                                                <td v-text="ingreso.total"></td>
                                                <td v-text="ingreso.impuesto"></td>
                                                <td v-text="ingreso.estado"></td>
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

                <!-- Ver Ingreso -->
                    <template v-else-if="listado == 2">
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="">Proveedor</label>
                                        <p v-text="proveedor"></p>
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

                                                <!-- cantidad de artículos -->
                                                <td v-text="detalle.cantidad">
                                                    
                                                </td>

                                                <!-- Precio total del artículo -->
                                                <td>
                                                    {{ detalle.precio * detalle.cantidad }}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="3" align="right"><strong>Total Parcial:</strong> </td>
                                                <td>$ {{ totalParcial = (  total - totalImpuesto ).toFixed( 2 ) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="3" align="right"><strong>Total Impuesto:</strong> </td>
                                                <td>$ {{ totalImpuesto =  ( total * impuesto ).toFixed( 2 ) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="3" align="right"><strong>Total Neto:</strong> </td>
                                                <td>$ {{ total }}</td> <!-- Dónde calcularTotal es la propiedad computada -->
                                            </tr>
                                        </tbody>

                                        <tbody v-else>
                                            <tr>
                                                <td colspan="4">
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
                <!-- Fin Ver Ingreso -->
            </div>
            <!-- Fin Listado -->
        </div>
    </main>
</template>

<script>

    import vSelect from 'vue-select';

    export default {
        data() {

          return {

            ingreso_id: 0,
            idproveedor: 0,
            proveedor: '',
            nombre: '',
            tipo_comprobante: 'BOLETA',
            serie_comprobante: '',
            num_comprobante: '',
            impuesto: 0.16,
            total: 0.0,
            totalImpuesto: 0.0,
            totalParcial: 0.0,
            arrayIngreso: [],
            arrayProveedor: [],
            arrayDetalle: [],
            listado: 1,
            modal: 0, /* Variable para mostrar u ocultar el modal */
            tituloModal: '', /* Variable para definir si se crea o se actualiza la categoría */
            tipoAccion: 0, /* Variable para definir la cadena de texto del botón guardar del modal, dónde 1 es Guardar y 2 Actualizar */
            errorIngreso: 0,
            errorMostrarMsjIngreso: [],
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
                    
                    resultado = resultado + ( this.arrayDetalle[ i ].precio * this.arrayDetalle[ i ].cantidad )
                    
                }

                return resultado;
            },

        },

        methods: {

          listarIngreso(page, buscar, criterio) {

            let me = this; 

            var url = '/ingreso?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;

            axios.get( url ).then(function (response){

                var respuesta = response.data;
                
                me.arrayIngreso = respuesta.ingresos.data; /* Esto es igual a poner this.arrayIngreso = respuesta.ingresos.data */

                me.pagination = respuesta.pagination; /* Esto es igual a poner this.pagination = respuesta.pagination */

            })
            .catch(function(error) {

              console.log(error);

            });

          },

          cambiarPagina( page, buscar, criterio ) {

            let me = this;
              
            // Actualiza la página actual
            me.pagination.current_page = page;
            
            // Envia la petición para visualizar la data de esa página
            me.listarIngreso( page, buscar, criterio );

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
          verIngreso ( id ) {

            let me = this;
            me.listado = 2;

            var arrayIngresoT = [];

            // Obtener Los datos del ingreso
            var url = '/ingreso/obtenerCabecera?id=' + id;

            axios.get( url ).then(function (response){

                var respuesta = response.data;
                
                arrayIngresoT = respuesta.ingreso; 

                me.proveedor = arrayIngresoT[ 0 ][ 'nombre' ];
                me.tipo_comprobante = arrayIngresoT[ 0 ][ 'tipo_comprobante' ];
                me.serie_comprobante = arrayIngresoT[ 0 ][ 'serie_comprobante' ];
                me.num_comprobante = arrayIngresoT[ 0 ][ 'num_comprobante' ];
                me.impuesto = arrayIngresoT[ 0 ][ 'impuesto' ];
                me.total = arrayIngresoT[ 0 ][ 'total' ];

            })
            .catch(function(error) {

                console.log(error);

            });

            // Obtener los datos de los detalles
            var urld = '/ingreso/obtenerDetalles?id=' + id;

            axios.get( urld ).then(function (response){

                var respuesta = response.data;
                
                me.arrayDetalle = respuesta.detalles; 

            })
            .catch(function(error) {

                console.log(error);

            });

          },

        },
        
        mounted() {

            this.listarIngreso(1, this.buscar, this.criterio);

        }
    }
</script>

<style>
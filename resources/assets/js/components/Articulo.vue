<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Artículos
                    <button type="button" class="btn btn-secondary" @click="abrirModal('articulo', 'registrar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <button type="button" class="btn btn-info" @click="cargarPdf()">
                        <i class="icon-doc"></i>&nbsp;Reporte
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombre">Nombre</option>
                                    <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" class="form-control" placeholder="Texto a buscar" 
                                       v-model="buscar" @keyup.enter="listarArticulo( 1, buscar, criterio )">
                                <button type="submit" class="btn btn-primary" @click="listarArticulo( 1, buscar, criterio )">
                                    <i class="fa fa-search"></i> 
                                    Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Código de barras</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Precio Venta</th>
                                <th>Stock</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- Renderizando el array arrayArticulo -->
                              <tr v-for="articulo in arrayArticulo" :key="articulo.id">

                                  <td>
                                      <button type="button" 
                                             class="btn btn-warning btn-sm" 
                                             @click="abrirModal('articulo', 'actualizar', articulo)">
                                      <i class="icon-pencil"></i>
                                      </button> &nbsp;

                                     <!-- Borrado lógico - activado/desactivado -->
                                        <template v-if="articulo.condicion">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarArticulo(articulo.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>

                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarArticulo(articulo.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                     <!-- Borrado lógico - activado/desactivado -->

                                  </td>

                                  <!-- Mostrando información de la BD -->
                                    <td v-text="articulo.codigo"></td>
                                    <td v-text="articulo.nombre"></td>
                                    <td v-text="articulo.nombre_categoria"></td>
                                    <td v-text="articulo.precio_venta"></td>
                                    <td v-text="articulo.stock"></td>
                                    <td v-text="articulo.descripcion"></td>
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
            </div>
            <!-- Fin ejemplo de tabla Listado -->
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
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                          <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                                <div class="col-md-9">
                                  <select name="" id="" class="form-control" v-model="idcategoria">
                                    <option value="0" disabled>Seleccione</option>
                                    <option v-for="categoria in arrayCategoria" 
                                            :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                                  </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Código de barras</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Código de barras" v-model="codigo">
                                    <barcode :value="codigo" :options="{ format: 'EAN-13' }">
                                      Generando código de barras.
                                    </barcode>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Nombre de artículo" v-model="nombre">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Precio venta</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" placeholder="" v-model="precio_venta">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Stock</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" placeholder="" v-model="stock">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control" placeholder="Ingrese descripción" v-model="descripcion">
                                </div>
                            </div>

                            <!-- Mostrar errores en el modal --> <!-- Visualizar los errores si errorArticulo tiene el valor 1 -->
                                <div class="form-group row div-error" v-show="errorArticulo">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjArticulo" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            <!-- Fin Mostrar errores en el modal -->

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" v-if="tipoAccion == 1" @click="registrarArticulo()">Guardar</button>
                        <button type="button" class="btn btn-primary" v-if="tipoAccion == 2" @click="actualizarArticulo()">Actualizar</button>
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
  
    import VueBarcode from 'vue-barcode'; /* Código de barras Vue-Barcode */

    export default {

        props: [ 'ruta' ],

        data() {

          return {

            articulo_id: 0,
            idcategoria: 0,
            nombre_categoria: '',
            codigo: '',
            nombre: '',
            precio_venta: 0,
            stock: 0,
            descripcion: '',
            arrayArticulo: [],
            modal: 0, /* Variable para mostrar u ocultar el modal */
            tituloModal: '', /* Variable para definir si se crea o se actualiza la categoría */
            tipoAccion: 0, /* Variable para definir la cadena de texto del botón guardar del modal, dónde 1 es Guardar y 2 Actualizar */
            errorArticulo: 0,
            errorMostrarMsjArticulo: [],
            pagination: {

                'total': 0, 
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,

            },
            offset: 3,
            criterio: 'nombre',
            buscar: '', 
            arrayCategoria: [],

          }

        },

        components: {
          'barcode': VueBarcode
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

        },

        methods: {

          listarArticulo( page, buscar, criterio ) {

            let me = this; 

            var url = this.ruta + '/articulo?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;

            axios.get( url ).then(function (response){

                var respuesta = response.data;
                
                me.arrayArticulo = respuesta.articulos.data; /* Esto es igual a poner this.arrayCategoria = respuesta.categorias.data */

                me.pagination = respuesta.pagination; /* Esto es igual a poner this.pagination = respuesta.pagination */

            })
            .catch(function(error) {

              console.log(error);

            });

          },

          selectCategoria (  ) {

             let me = this; 

            var url = this.ruta + '/categoria/selectCategoria';

            axios.get( url ).then(function (response){
              
              // console.log(response);

                var respuesta = response.data;
                
                me.arrayCategoria = respuesta.categorias; /* Esto es igual a poner this.arrayCategoria = respuesta.categorias */

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
            me.listarArticulo( page, buscar, criterio );

          },

          registrarArticulo () {

              if ( this.validarArticulo() ) {
                  return;
              }

              let me = this;

              axios.post( this.ruta + '/articulo/registrar', {

                  'idcategoria': this.idcategoria,
                  'codigo': this.codigo,
                  'nombre': this.nombre,
                  'stock': this.stock,
                  'precio_venta': this.precio_venta,
                  'descripcion': this.descripcion,

              }).then(function(response) {

                  me.cerrarModal(); /* Esto es igual a poner this.cerrarModal(); */
                  me.listarArticulo( 1, '', 'nombre' ); /* Esto es igual a poner this.listarCategoria( 1, '', 'nombre' ); */

              }).catch(function (error) {

                  console.log(error);
                  
              });

          },

          actualizarArticulo () {

              if ( this.validarArticulo() ) {
                  return;
              }

              let me = this;

              axios.put( this.ruta + '/articulo/actualizar', {

                  'idcategoria': this.idcategoria,
                  'codigo': this.codigo,
                  'nombre': this.nombre,
                  'stock': this.stock,
                  'precio_venta': this.precio_venta,
                  'descripcion': this.descripcion,
                  'id': this.articulo_id,

              }).then(function(response) {

                  me.cerrarModal(); /* Esto es igual a poner this.cerrarModal(); */
                  me.listarArticulo( 1, '', 'nombre' ); /* Esto es igual a poner this.listarCategoria( 1, '', 'nombre' ); */

              }).catch(function (error) {

                  console.log(error);
                  
              });

          },

          desactivarArticulo ( id ) { /* Borrado lógico de la categoría */
            
            swal({
                title: '¿Estás seguro de desactivar este artículo?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
                }).then((result) => {

                    if (result.value) {

                        let me = this;

                        axios.put( this.ruta + '/articulo/desactivar', {

                            'id': id,

                        }).then(function(response) {

                            me.listarArticulo( 1, '', 'nombre' ); /* Esto es igual a poner this.listarArticulo( 1, '', 'nombre' ); */

                            swal(
                                'Desactivado!',
                                'El artículo ha sido desactivado con éxito.',
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

          activarArticulo (id) { /* Borrado lógico de la categoría */
            
            swal({
                title: '¿Estás seguro de activar este artículo?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
                }).then((result) => {

                    if (result.value) {

                        let me = this;

                        axios.put( this.ruta + '/articulo/activar', {

                            'id': id,

                        }).then(function(response) {

                            me.listarArticulo( 1, '', 'nombre' ); /* Esto es igual a poner this.listarArticulo( 1, '', 'nombre' ); */

                            swal(
                                'Activado!',
                                'El artículo ha sido activado con éxito.',
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

          validarArticulo () {

              this.errorArticulo = 0;
              this.errorMostrarMsjArticulo = [];

              if ( this.idcategoria == 0 ) this.errorMostrarMsjArticulo.push("Seleccione una categoría.");
              if ( !this.nombre ) this.errorMostrarMsjArticulo.push("El nombre del artículo no puede estar vacío.");
              if ( !this.stock ) this.errorMostrarMsjArticulo.push("El stock del artículo debe ser un número y no puede estar vacío.");
              if ( !this.precio_venta ) this.errorMostrarMsjArticulo.push("El precio venta del artículo debe ser un número y no puede estar vacío.");

              if ( this.errorMostrarMsjArticulo.length ) this.errorArticulo = 1; 

              return this.errorArticulo;

          },

          abrirModal ( modelo, accion, data = [] )  {

              switch (modelo) {
                  case "articulo":
                  {
                      switch (accion) {
                          case "registrar":
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar artículo'
                                this.idecategoria = 0;
                                this.nombre_categoria = '';
                                this.codigo = '';
                                this.nombre = '';
                                this.precio_venta = 0;
                                this.stock = 0;
                                this.descripcion = '';
                                this.tipoAccion = 1; 
                                break;
                            }
                          case "actualizar":
                            {
                                // console.log(data);
                                this.modal = 1; /* Para abrir el modal */
                                this.tituloModal = "Actualizar artículo"
                                this.tipoAccion = 2; /* Para que el modal sepa que es actualizar */
                                this.articulo_id = data['id']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                this.idcategoria = data['idcategoria']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                this.codigo = data['codigo']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                this.nombre = data['nombre']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                this.stock = data['stock']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                this.precio_venta = data['precio_venta']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                this.descripcion = data['descripcion']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                break;
                                
                            }  
                      }
                  }    
                    
              }

              this.selectCategoria();

          },

          cerrarModal () {

            this.modal = 0;
            this.tituloModal = '';
            this.idcategoria = 0;
            this.codigo = '';
            this.nombre = '';
            this.precio_venta = 0;
            this.stock = 0;
            this.descripcion = '';
            this.errorArticulo = 0;

          },

          cargarPdf () {

              window.open( this.ruta + '/articulo/listarPdf', '_blank' ); /* Abrir el pdf en una pestaña nueva */

          },

        },
        
        mounted() {

            this.listarArticulo( 1, this.buscar, this.criterio );

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
</style>
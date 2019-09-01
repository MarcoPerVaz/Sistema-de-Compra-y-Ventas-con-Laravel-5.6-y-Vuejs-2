<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Categorías
                    <button type="button" class="btn btn-secondary" @click="abrirModal('categoria', 'registrar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" id="opcion" name="opcion">
                                <option value="nombre">Nombre</option>
                                <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- Renderizando el array arrayCategoria -->
                              <tr v-for="categoria in arrayCategoria" :key="categoria.id">

                                  <td>
                                      <button type="button" 
                                             class="btn btn-warning btn-sm" 
                                             @click="abrirModal('categoria', 'actualizar', categoria)">
                                      <i class="icon-pencil"></i>
                                      </button> &nbsp;

                                     <!-- Borrado lógico - activado/desactivado -->
                                        <template v-if="categoria.condicion">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarCategoria(categoria.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>

                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarCategoria(categoria.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                     <!-- Borrado lógico - activado/desactivado -->

                                  </td>

                                  <!-- Mostrando información de la BD -->
                                    <td v-text="categoria.nombre"></td>
                                    <td v-text="categoria.descripcion"></td>
                                    <td>
                                      <!-- Condicional para mostrar si la categoría está activa o inactiva -->
                                        <div v-if="categoria.condicion == 1">

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
                            <li class="page-item">
                                <a class="page-link" href="#">Ant</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">4</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Sig</a>
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
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Nombre de categoría" v-model="nombre">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control" placeholder="Ingrese descripción" v-model="descripcion">
                                </div>
                            </div>
                            <!-- Mostrar errores en el modal --> <!-- Visualizar los errores si errorCategoria tiene el valor 1 -->
                                <div class="form-group row div-error" v-show="errorCategoria">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCategoria" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            <!-- Fin Mostrar errores en el modal -->

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" v-if="tipoAccion == 1" @click="registrarCategoria()">Guardar</button>
                        <button type="button" class="btn btn-primary" v-if="tipoAccion == 2" @click="actualizarCategoria()">Actualizar</button>
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
    export default {
        data() {

          return {

            nombre: '',
            descripcion: '',
            arrayCategoria: [],
            modal: 0, /* Variable para mostrar u ocultar el modal */
            tituloModal: '', /* Variable para definir si se crea o se actualiza la categoría */
            tipoAccion: 0, /* Variable para definir la cadena de texto del botón guardar del modal, dónde 1 es Guardar y 2 Actualizar */
            errorCategoria: 0,
            errorMostrarMsjCategoria: [],
            categoria_id: 0,

          }

        },

        methods: {

          listarCategoria() {

            let me = this; 

            axios.get('/categoria').then(function (response){
                
              me.arrayCategoria = response.data; /* Esto es igual a poner this.arrayCategoria = response.data */

            })
            .catch(function(error) {

              console.log(error);

            });

          },

          registrarCategoria () {

              if ( this.validarCategoria() ) {
                  return;
              }

              let me = this;

              axios.post('/categoria/registrar', {

                  'nombre': this.nombre,
                  'descripcion': this.descripcion,

              }).then(function(response) {

                  me.cerrarModal(); /* Esto es igual a poner this.cerrarModal(); */
                  me.listarCategoria(); /* Esto es igual a poner this.listarCategoria(); */

              }).catch(function (error) {

                  console.log(error);
                  
              });

          },

          actualizarCategoria () {

              if ( this.validarCategoria() ) {
                  return;
              }

              let me = this;

              axios.put('/categoria/actualizar', {

                  'nombre': this.nombre,
                  'descripcion': this.descripcion,
                  'id': this.categoria_id,

              }).then(function(response) {

                  me.cerrarModal(); /* Esto es igual a poner this.cerrarModal(); */
                  me.listarCategoria(); /* Esto es igual a poner this.listarCategoria(); */

              }).catch(function (error) {

                  console.log(error);
                  
              });

          },

          desactivarCategoria (id) { /* Borrado lógico de la categoría */
            
            swal({
                title: '¿Estás seguro de desactivar esta categoría?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
                }).then((result) => {

                    if (result.value) {

                        let me = this;

                        axios.put('/categoria/desactivar', {

                            'id': id,

                        }).then(function(response) {

                            me.listarCategoria(); /* Esto es igual a poner this.listarCategoria(); */

                            swal(
                                'Desactivado!',
                                'La categoría ha sido desactivada con éxito.',
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

          activarCategoria (id) { /* Borrado lógico de la categoría */
            
            swal({
                title: '¿Estás seguro de activar esta categoría?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
                }).then((result) => {

                    if (result.value) {

                        let me = this;

                        axios.put('/categoria/activar', {

                            'id': id,

                        }).then(function(response) {

                            me.listarCategoria(); /* Esto es igual a poner this.listarCategoria(); */

                            swal(
                                'Activado!',
                                'La categoría ha sido activada con éxito.',
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

          validarCategoria () {

              this.errorCategoria = 0;
              this.errorMostrarMsjCategoria = [];

              if ( !this.nombre ) this.errorMostrarMsjCategoria.push("El nombre de la categoría no puede estar vacío.");

              if ( this.errorMostrarMsjCategoria.length ) this.errorCategoria = 1; 

              return this.errorCategoria;

          },

          abrirModal ( modelo, accion, data = [] )  {

              switch (modelo) {
                  case "categoria":
                  {
                      switch (accion) {
                          case "registrar":
                            {
                                this.modal = 1;
                                this.nombre = '';
                                this.descripcion = '';
                                this.tituloModal = 'Registrar categoría'
                                this.tipoAccion = 1; 
                                break;
                            }
                          case "actualizar":
                            {
                                // console.log(data);
                                this.modal = 1; /* Para abrir el modal */
                                this.tituloModal = "Actualizar categoría"
                                this.tipoAccion = 2; /* Para que el modal sepa que es actualizar */
                                this.categoria_id = data['id']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                this.nombre = data['nombre']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                this.descripcion = data['descripcion']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                break;
                                
                            }  
                      }
                  }    
                    
              }

          },

          cerrarModal () {

            this.modal = 0;
            this.tituloModal = '';
            this.nombre = '';
            this.descripcion = '';

          },

        },
        
        mounted() {

            this.listarCategoria();

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
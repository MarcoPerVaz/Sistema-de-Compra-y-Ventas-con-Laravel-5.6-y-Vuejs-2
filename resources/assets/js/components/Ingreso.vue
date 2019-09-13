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
                    <i class="fa fa-align-justify"></i> Ingresos
                    <button type="button" class="btn btn-secondary" @click="abrirModal('ingreso', 'registrar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
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
                                                 @click="abrirModal('ingreso', 'actualizar', ingreso)">
                                          <i class="icon-eye"></i>
                                          </button> &nbsp;
    
                                          <!-- Borrado lógico - activado/desactivado -->
                                            <template v-if="ingreso.estado == 'Registrado'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarIngreso(ingreso.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                         <!-- Borrado lógico - activado/desactivado -->
    
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
                                    <input type="text" class="form-control" placeholder="Nombre de la persona" v-model="nombre">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Tipo de documento</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="tipo_documento">
                                        <option value="DNI">DNI</option>
                                        <option value="RUC">RUC</option>
                                        <option value="PASS">PASS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Número de documento</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Número de documento" v-model="num_documento">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Dirección" v-model="direccion">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Teléfono</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Teléfono" v-model="telefono">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control" placeholder="Email" v-model="email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Rol (*)</label>
                                <div class="col-md-9">
                                    <select name="" id="" class="form-control" v-model="idrol">
                                        <option value="0">Seleccione un rol</option>
                                        <option v-for="rol in arrayRol" :key="rol.id" :value="rol.id" v-text="rol.nombre">
                                            
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Usuario (*)</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Nombre de usuario" v-model="usuario">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Password (*)</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" placeholder="Password de acceso" v-model="password">
                                </div>
                            </div>
                            <!-- Mostrar errores en el modal --> <!-- Visualizar los errores si errorPersona tiene el valor 1 -->
                                <div class="form-group row div-error" v-show="errorPersona">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjPersona" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            <!-- Fin Mostrar errores en el modal -->

                        </form>
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
    export default {
        data() {

          return {

            ingreso_id: 0,
            idproveedor: 0,
            nombre: '',
            tipo_comprobante: 'Boleta',
            serie_comprobante: '',
            num_comprobante: '',
            impuesto: 0.16,
            total: 0.0,
            arrayIngreso: [],
            arrayDetalle: [],
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

          }

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

          selectRol(  ) {

              let me = this; 

            var url = '/rol/selectRol';

            axios.get( url ).then(function (response){

                var respuesta = response.data;
                
                me.arrayRol = respuesta.roles; /* Esto es igual a poner this.arrayRol = respuesta.roles */

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

          registrarPersona () {

              if ( this.validarPersona() ) {
                  return;
              }

              let me = this;

              axios.post('/user/registrar', {

                  'nombre': this.nombre,
                  'tipo_documento': this.tipo_documento,
                  'num_documento': this.num_documento,
                  'direccion': this.direccion,
                  'telefono': this.telefono,
                  'email': this.email,
                  'usuario': this.usuario,
                  'password': this.password,
                  'idrol': this.idrol,

              }).then(function(response) {

                  me.cerrarModal(); /* Esto es igual a poner this.cerrarModal(); */
                  me.listarPersona( 1, '', 'nombre' ); /* Esto es igual a poner this.listarCategoria( 1, '', 'nombre' ); */

              }).catch(function (error) {

                  console.log(error);
                  
              });

          },

          actualizarPersona () {

              if ( this.validarPersona() ) {
                  return;
              }

              let me = this;

              axios.put('/user/actualizar', {

                  'nombre': this.nombre,
                  'tipo_documento': this.tipo_documento,
                  'num_documento': this.num_documento,
                  'direccion': this.direccion,
                  'telefono': this.telefono,
                  'email': this.email,
                  'usuario': this.usuario,
                  'password': this.password,
                  'idrol': this.idrol,
                  'id': this.persona_id,

              }).then(function(response) {

                  me.cerrarModal(); /* Esto es igual a poner this.cerrarModal(); */
                  me.listarPersona( 1, '', 'nombre' ); /* Esto es igual a poner this.listarCategoria( 1, '', 'nombre' ); */

              }).catch(function (error) {

                  console.log(error);
                  
              });
              

          },

          validarPersona () {

              this.errorPersona = 0;
              this.errorMostrarMsjPersona = [];

              if ( !this.nombre ) this.errorMostrarMsjPersona.push("El nombre de la persona no puede estar vacío.");
              if ( !this.usuario ) this.errorMostrarMsjPersona.push("El nombre de usuario no puede estar vacío.");
              if ( !this.password ) this.errorMostrarMsjPersona.push("El password no puede estar vacío.");
              if ( this.idrol == 0 ) this.errorMostrarMsjPersona.push("Debe seleccionar un rol para el usuario");

              if ( this.errorMostrarMsjPersona.length ) this.errorPersona = 1; 

              return this.errorPersona;

          },

          abrirModal ( modelo, accion, data = [] )  {

              this.selectRol();

              switch (modelo) {
                  case "persona":
                  {
                      switch (accion) {
                          case "registrar":
                            {
                                this.modal = 1;
                                this.nombre = '';
                                this.tipo_documento = 'DNI';
                                this.num_documento = '';
                                this.direccion = '';
                                this.telefono = '';
                                this.email = '';
                                this.usuario = '';
                                this.password = '';
                                this.idrol = 0;
                                this.tituloModal = 'Registrar usuario'
                                this.tipoAccion = 1; 
                                break;
                            }
                          case "actualizar":
                            {
                                // console.log(data);
                                this.modal = 1; /* Para abrir el modal */
                                this.tituloModal = "Actualizar usuario"
                                this.tipoAccion = 2; /* Para que el modal sepa que es actualizar */
                                this.persona_id = data['id']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                this.nombre = data['nombre']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                this.tipo_documento = data['tipo_documento']; /*data[] son los datos que vienen de la vista pasados por parámetro*/
                                this.num_documento = data['num_documento']; /*data[] son los datos que vienen de la vista pasados por parámetro*/
                                this.direccion = data['direccion']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                this.telefono = data['telefono']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                this.email = data['email']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                this.usuario = data['usuario']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                this.password = data['password']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                this.idrol = data['idrol']; /* data[] son los datos que vienen de la vista pasados por parámetro */
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
            this.tipo_documento = 'DNI';
            this.num_documento = '',
            this.direccion = '',
            this.telefono = '',
            this.email = '',
            this.usuario = '',
            this.password = '',
            this.idrol = 0,
            this.errorPersona = 0;

          },

          desactivarUsuario ( id ) { /* Borrado lógico de usuarios */
            
            swal({
                title: '¿Estás seguro de desactivar este usuario?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
                }).then((result) => {

                    if (result.value) {

                        let me = this;

                        axios.put('/user/desactivar', {

                            'id': id,

                        }).then(function(response) {

                            me.listarPersona( 1, '', 'nombre' ); /* Esto es igual a poner this.listarPersona( 1, '', 'nombre' ); */

                            swal(
                                'Desactivado!',
                                'El usuario ha sido desactivado con éxito.',
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

          activarUsuario (id) { /* Borrado lógico de usuarios */
            
            swal({
                title: '¿Estás seguro de activar este usuario?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
                }).then((result) => {

                    if (result.value) {

                        let me = this;

                        axios.put('/user/activar', {

                            'id': id,

                        }).then(function(response) {

                            me.listarPersona( 1, '', 'nombre' ); /* Esto es igual a poner this.listarPersona( 1, '', 'nombre' ); */

                            swal(
                                'Activado!',
                                'El usuario ha sido activado con éxito.',
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

        },
        
        mounted() {

            this.listarIngreso(1, this.buscar, this.criterio);

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
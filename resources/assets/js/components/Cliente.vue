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
                    <i class="fa fa-align-justify"></i> Clientes
                    <button type="button" class="btn btn-secondary" @click="abrirModal('persona', 'registrar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombre">Nombre</option>
                                    <option value="num_documento">Número de documento</option>
                                    <option value="email">Email</option>
                                    <option value="telefono">Teléfono</option>
                                </select>
                                <input type="text" class="form-control" placeholder="Texto a buscar" 
                                       v-model="buscar" @keyup.enter="listarPersona( 1, buscar, criterio )">
                                <button type="submit" class="btn btn-primary" @click="listarPersona( 1, buscar, criterio )">
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
                                <th>Nombre</th>
                                <th>Tipo documento</th>
                                <th>Número documento</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- Renderizando el array arrayPersona -->
                              <tr v-for="persona in arrayPersona" :key="persona.id">

                                  <td>
                                      <button type="button" 
                                             class="btn btn-warning btn-sm" 
                                             @click="abrirModal('persona', 'actualizar', persona)">
                                      <i class="icon-pencil"></i>
                                      </button> &nbsp;
                                  </td>

                                  <!-- Mostrando información de la BD -->
                                    <td v-text="persona.nombre"></td>
                                    <td v-text="persona.tipo_documento"></td>
                                    <td v-text="persona.num_documento"></td>
                                    <td v-text="persona.direccion"></td>
                                    <td v-text="persona.telefono"></td>
                                    <td v-text="persona.email"></td>
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

            persona_id: 0,
            nombre: '',
            tipo_documento: 'DNI',
            num_documento: '',
            direccion: '',
            telefono: '',
            email: '',
            arrayPersona: [],
            modal: 0, /* Variable para mostrar u ocultar el modal */
            tituloModal: '', /* Variable para definir si se crea o se actualiza la categoría */
            tipoAccion: 0, /* Variable para definir la cadena de texto del botón guardar del modal, dónde 1 es Guardar y 2 Actualizar */
            errorPersona: 0,
            errorMostrarMsjPersona: [],
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

          listarPersona(page, buscar, criterio) {

            let me = this; 

            var url = '/cliente?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;

            axios.get( url ).then(function (response){

                var respuesta = response.data;
                
                me.arrayPersona = respuesta.personas.data; /* Esto es igual a poner this.arrayPersona = respuesta.personas.data */

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
            me.listarPersona( page, buscar, criterio );

          },

          registrarPersona () {

              if ( this.validarPersona() ) {
                  return;
              }

              let me = this;

              axios.post('/cliente/registrar', {

                  'nombre': this.nombre,
                  'tipo_documento': this.tipo_documento,
                  'num_documento': this.num_documento,
                  'direccion': this.direccion,
                  'telefono': this.telefono,
                  'email': this.email,

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

              axios.put('/cliente/actualizar', {

                  'nombre': this.nombre,
                  'tipo_documento': this.tipo_documento,
                  'num_documento': this.num_documento,
                  'direccion': this.direccion,
                  'telefono': this.telefono,
                  'email': this.email,
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

              if ( this.errorMostrarMsjPersona.length ) this.errorPersona = 1; 

              return this.errorPersona;

          },

          abrirModal ( modelo, accion, data = [] )  {

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
                                this.tituloModal = 'Registrar cliente'
                                this.tipoAccion = 1; 
                                break;
                            }
                          case "actualizar":
                            {
                                // console.log(data);
                                this.modal = 1; /* Para abrir el modal */
                                this.tituloModal = "Actualizar ciente"
                                this.tipoAccion = 2; /* Para que el modal sepa que es actualizar */
                                this.persona_id = data['id']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                this.nombre = data['nombre']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                this.tipo_documento = data['tipo_documento']; /*data[] son los datos que vienen de la vista pasados por parámetro*/
                                this.num_documento = data['num_documento']; /*data[] son los datos que vienen de la vista pasados por parámetro*/
                                this.direccion = data['direccion']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                this.telefono = data['telefono']; /* data[] son los datos que vienen de la vista pasados por parámetro */
                                this.email = data['email']; /* data[] son los datos que vienen de la vista pasados por parámetro */
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
            this.errorPersona = 0;

          },

        },
        
        mounted() {

            this.listarPersona(1, this.buscar, this.criterio);

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
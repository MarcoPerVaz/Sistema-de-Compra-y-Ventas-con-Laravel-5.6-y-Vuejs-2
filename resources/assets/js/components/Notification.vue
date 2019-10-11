<template>
  <li class="nav-item d-md-down-none">
      <a class="nav-link" href="#" data-toggle="dropdown">
          <i class="icon-bell"></i>
          <span class="badge badge-pill badge-danger">{{ notifications.length }}</span>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
          <div class="dropdown-header text-center">
              <strong>Notificaciones</strong>
          </div>

          <div v-if="notifications.length">
              <!-- Dónde listar es la propiedad computada -->
              <li v-for="item in listar" :key="item.id">
                  <a class="dropdown-item" href="#">
                      <!-- <i class="fa fa-envelope-o"></i> {{ item.data }} --> <!-- Regresa un array -->
                      <!-- <i class="fa fa-envelope-o"></i> {{ JSON.parse( item.data ) }} --> <!-- Regresa un array --> 
                      <!-- <i class="fa fa-envelope-o"></i> {{ item.data.datos.ingresos.msj }} -->
                      <i class="fa fa-envelope-o"></i> {{ item.ingresos.msj }}
                      <!-- <span class="badge badge-success">{{ item.data.datos.ingresos.numero }}</span> -->
                      <span class="badge badge-success">{{ item.ingresos.numero }}</span>
                  </a>
                  <a class="dropdown-item" href="#">
                      <!-- <i class="fa fa-tasks"></i> {{ item.data.datos.ventas.msj }} -->
                      <i class="fa fa-tasks"></i> {{ item.ventas.msj }}
                      <!-- <span class="badge badge-danger">{{ item.data.datos.ventas.numero }}</span> -->
                      <span class="badge badge-danger">{{ item.ventas.numero }}</span>
                  </a>
              </li>
          </div>
          <div v-else>
              <a><span>No tiene notificaciones</span></a>
          </div>
      </div>
  </li>
</template>

<script>
export default {
    
    props: [ 'notifications' ],

    data () {

        return {

            arrayNotifications: [],

        }

    },

    computed: {

        listar: function() {

            /* Retorna el arreglo de la variable notifications usada por props */
                // return this.notifications[ 0 ];  

            /* Retorna el contenido de la variable notifications de props y lo guarda en la variable
               arrayNotifications 
            */
                this.arrayNotifications = Object.values( this.notifications[ 0 ] );

            if ( this.notifications == '' ) {

                return this.arrayNotifications = [];

            } else {

                // Capturo la última notificación agregada
                this.arrayNotifications = Object.values( this.notifications[ 0 ] );

                // Validación por índice fuera de rango
                if ( this.arrayNotifications.length > 3 ) {

                    // Si el tamaño es > 3 es cuando las notificaciones son obtenidas desde el
                    return Object.values( this.arrayNotifications[ 4 ] );
                    
                } else {

                    // Si el tamaño es < 3 Es cuando las notificaciones son obtenidas desde el 
                    return Object.values( this.arrayNotifications[ 0 ] );

                }

            }
        }

    } 
}
</script>
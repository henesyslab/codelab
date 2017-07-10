<template>
  <div class="panel panel-primary">
    <div class="panel-heading">
      Clientes
    </div>

    <div class="panel-body">
      <div class="text-right margin-20">
        <div class="btn-group">
          <router-link :to="{ name: 'client.new' }" class="btn btn-primary">
            <i class="fa fa-plus"></i> Novo Cliente
          </router-link>
        </div>
      </div>

      <p v-if="clients.length == 0" class="lead text-center text-warning">
        <i class="fa fa-warning"></i> Nada encontrado
      </p>

      <ul v-if="clients.length > 0" class="list-unstyled list-padding list-striped list-hover">
        <li v-for="client in clients" :id="'client_' + client.id">
          <div class="pull-left">
            <strong>{{ client.name }}</strong> <small class="text-muted">{{ client.path }}</small><br>
            <span class="text-muted">{{ client.description }}</span>
          </div>

          <div class="pull-right">
            <!-- View -->
            <a :href="'https://gitlab.com/' + client.gitlab_api + '/' + client.path" target="_blank" class="btn btn-success btn-xs">
              <i class="fa fa-eye"></i>
            </a>
            <!-- Edit -->
            <router-link :to="{ name: 'client.edit', params: { id: client.id } }" class="btn btn-primary btn-xs">
              <i class="fa fa-edit"></i>
            </router-link>
            <!-- Delete -->
            <button class="btn btn-danger btn-xs" v-on:click.prevent="deleteClient(client.id)">
              <i class="fa fa-times"></i>
            </button>
          </div>
        </li>
      </ul>
    </div>

    <!-- Carrega a janela modal -->
    <router-view></router-view>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        clients: []
      }
    },
    mounted() {
      // Busca pela listagem de clientes
      this.fetchClients()
    },
    methods: {
      /**
       * Executa uma requisição AJAX em busca dos clientes cadastrados no sistema.
       */
      fetchClients() {
        axios.get('/ajax/clientes').then(response => {
          this.clients = response.data
        }).catch(error => {
          console.log(error)
        })
      },

      /**
       * Exibe uma janela de confirmação para a exclusão do cliente.
       */
      deleteClient(client_id) {
        var that = this
        BootstrapDialog.show({
          type: BootstrapDialog.TYPE_DANGER,
          title: 'Remover cliente',
          message: 'Tem certeza que deseja remover este cliente? Esta operação não poderá ser desfeita.',
          buttons: [{
            label: 'Cancelar',
            cssClass: 'btn btn-default',
            hotkey: 27,
            action(dialog) {
              dialog.close()
            }
          }, {
            label: 'OK',
            cssClass: 'btn btn-danger',
            hotkey: 13,
            action(dialog) {
              this.disable().spin()
              dialog.setClosable(false)

              axios.delete('/ajax/clientes/' + client_id).then(response => {
                // Atualiza a lista com os dados dos clientes
                that.fetchClients()
                // Exibe uma mensagem de sucesso
                toastr.success(response.data.message, 'Sucesso')
              }).catch(error => {
                // Exibe a mensagem de erro
                toastr.error(error.response.data.message, 'Erro')
              })

              dialog.close()
            }
          }]
        })
      },
    },
    metaInfo() {
      return {
        title: 'Clientes',
        meta: [
          { name: 'description', content: 'Lista de clientes cadastrados no sistema.' }
        ],
      }
    },
  }
</script>

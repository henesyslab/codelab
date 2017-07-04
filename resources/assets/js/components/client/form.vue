<template>
  <div class="hide">
    <form id="formModal" method="post" v-on:submit.prevent>
      <div class="row">
        <div :class="{ 'col-sm-6': true, 'form-group': true, 'has-error': errors.name }">
          <label class="control-label" for="name">Nome</label>
          <input type="text" name="name" class="form-control" placeholder="Nome" v-model="client.name" v-on:keyup="fillPathField()">
          <div class="help-block" v-for="error in errors.name">
            {{ error }}
          </div>
        </div>

        <div :class="{ 'col-sm-6': true, 'form-group': true, 'has-error': errors.path }">
          <label class="control-label" for="path">Namespace</label>
          <input type="text" name="path" class="form-control" placeholder="Namespace" v-model="client.path" :disabled="$route.name === 'client.edit'">
          <div class="help-block" v-for="error in errors.path">
            {{ error }}
          </div>
        </div>
      </div>

      <div :class="{ 'form-group': true, 'has-error': errors.description }">
        <label class="control-label" for="description">Descrição</label>
        <textarea name="description" rows="2" class="form-control" placeholder="Descrição" v-model="client.description"></textarea>
        <div class="help-block" v-for="error in errors.description">
          {{ error }}
        </div>
      </div>

      <div :class="{ 'form-group': true, 'has-error': errors.notes }">
        <label class="control-label" for="notes">Notas</label>
        <textarea name="notes" rows="5" class="form-control" placeholder="Notas" v-model="client.notes"></textarea>
        <div class="help-block" v-for="error in errors.notes">
          {{ error }}
        </div>
      </div>
    </form>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        title: '',
        errors: {},
        client: {
          name: '',
          path: '',
          description: '',
          notes: '',
        },
      }
    },
    mounted() {
      // Exibe o formulário de cadastro ou edição
      if (this.$route.name === 'client.new') {
        this.title = 'Novo Cliente'
        this.openCreateForm()
      } else {
        this.title = 'Editando o Cliente'
        this.openEditForm()
      }
    },
    methods: {
      /**
       * Gera uma namespace automaticamente a medida que o nome do cliente é informado.
       */
      fillPathField() {
        // Somente durante o cadastro
        if (this.$route.name === 'client.new') {
          this.client.path = helper.generateNamespace(this.client.name)
        }
      },

      /**
       * Reseta os dados do formulário para seu estado inicial.
       */
      clearForm() {
        this.client = {
          name: '',
          path: '',
          description: '',
          notes: '',
        }
      },

      /**
       * Abre a janela modal com o formulário
       * @param  title  Título da janela modal
       * @param  callback
       */
      openDialog(title, callback) {
        BootstrapDialog.show({
          type: BootstrapDialog.TYPE_PRIMARY,
          title: title,
          message: $('#formModal'),
          buttons: [{
            label: 'Cancelar',
            cssClass: 'btn btn-default',
            hotkey: 27,
            action: dialog => {
              dialog.close()
            }
          }, {
            icon: 'fa fa-floppy-o',
            label: ' Salvar informações',
            cssClass: 'btn btn-primary',
            // hotkey: 13,
            action(dialog) {
              var button = this
              button.disable().spin()
              dialog.setClosable(false)

              callback({dialog, button})
            }
          }],
          onhide: dialog => {
            // Limpa o formulário
            this.clearForm()
            // Redireciona o usuário para a lista
            this.$router.push('/clientes')
          }
        })
      },

      /**
       * Exibe o formulário de cadastro
       */
      openCreateForm() {
        this.openDialog(this.title, modal => {
          this.storeClient(modal)
        })
      },

      /**
       * Exibe o formulário de edição
       */
      openEditForm() {
        axios.get('/ajax/clientes/' + this.$route.params.id + '/edit').then(response => {
          this.client = response.data
          this.title = 'Editando o Cliente: ' + this.client.name
          this.openDialog(this.title, modal => {
            this.updateClient(modal)
          })
        })
      },

      /**
       * Envia os novos dados para serem processados e armazenados pelo servidor.
       * @param  modal  Instância da janela modal
       */
      storeClient(modal) {
        axios.post('/ajax/clientes', this.client).then(response => {
          // Exibe uma mensagem de sucesso
          toastr.success(response.data.message, 'Sucesso')
          // Adiciona o novo cliente à lista
          this.$parent.clients.push(response.data.client)
          // Fecha a janela modal
          modal.dialog.close()
        }).catch(error => {
          this.errors = error.response.data
          modal.button.enable().stopSpin()
          modal.dialog.setClosable(true)
        })
      },

      /**
       * Envia os dados alterados para serem processados e armazenados pelo servidor.
       * @param  modal  Instância da janela modal
       */
      updateClient(modal) {
        axios.patch('/ajax/clientes/' + this.$route.params.id, this.client).then(response => {
          // Exibe uma mensagem de sucesso
          toastr.success(response.data.message, 'Sucesso')

          // Atualiza a lista com os dados dos clientes
          this.$parent.fetchClients()

          // Fecha a janela modal
          modal.dialog.close()
        }).catch(error => {
          this.errors = error.response.data
          modal.button.enable().stopSpin()
          modal.dialog.setClosable(true)
        })
      },
    },
    metaInfo() {
      return {
        title: this.title,
        meta: [
          { name: 'description', content: 'Formulário de cadastro e edição de clientes.' }
        ],
      }
    },
  }
</script>

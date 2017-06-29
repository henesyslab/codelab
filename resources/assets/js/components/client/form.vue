<template>
<div class="hide">
  <form id="formModal" method="post" v-on:submit.prevent>
    <div class="row">
      <div v-bind:class="{ 'col-sm-6': true, 'form-group': true, 'has-error': errors.name }">
        <label class="control-label" for="name">Nome</label>
        <input type="text" name="name" class="form-control" placeholder="Nome" v-model="client.name" v-on:keyup="fillPathField()">
        <div class="help-block" v-for="error in errors.name">
          {{ error }}
        </div>
      </div>

      <div v-bind:class="{ 'col-sm-6': true, 'form-group': true, 'has-error': errors.path }">
        <label class="control-label" for="path">Namespace</label>
        <input type="text" name="path" class="form-control" placeholder="Namespace" v-model="client.path" v-bind:disabled="$route.name === 'client.edit'">
        <div class="help-block" v-for="error in errors.path">
          {{ error }}
        </div>
      </div>
    </div>

    <div v-bind:class="{ 'form-group': true, 'has-error': errors.description }">
      <label class="control-label" for="description">Descrição</label>
      <textarea name="description" rows="2" class="form-control" placeholder="Descrição" v-model="client.description"></textarea>
      <div class="help-block" v-for="error in errors.description">
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
      client: {
        name: '',
        path: '',
        description: '',
      },
      errors: {},
    }
  },
  mounted() {
    // Exibe o formulário de cadastro ou edição
    if (this.$route.name === 'client.new') {
      this.openCreateForm()
    } else {
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
            this.closeDialog(dialog)
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
        }]
      })
    },

    /**
     * Fecha a janela modal com o formulário
     * @param  dialog  Instância da janela modal
     */
    closeDialog(dialog) {
      // Limpa o formulário
      this.clearForm()
      // Redireciona o usuário para a lista
      this.$router.push('/clientes')
      // Fecha a janela modal
      dialog.close()
    },

    /**
     * Exibe o formulário de cadastro
     */
    openCreateForm() {
      this.openDialog('Novo Cliente', modal => {
        this.storeClient(modal)
      })
    },

    /**
     * Exibe o formulário de edição
     */
    openEditForm() {
      axios.get('/ajax/clientes/' + this.$route.params.id + '/edit').then(response => {
        this.client = response.data
        this.openDialog('Editando o Cliente: ' + this.client.name, modal => {
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
        this.closeDialog(modal.dialog)
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
        this.closeDialog(modal.dialog)
      }).catch(error => {
        this.errors = error.response.data
        modal.button.enable().stopSpin()
        modal.dialog.setClosable(true)
      })
    },
  }
}
</script>

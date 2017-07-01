<template>
<div class="hide">
  <form id="formModal" method="post" v-on:submit.prevent>
    <div v-bind:class="{ 'form-group': true, 'has-error': errors.name }">
      <label class="control-label" for="client_id">Cliente</label>
      <select name="client_id" class="form-control" v-model="project.client_id">
        <option value="">Selecione...</option>
        <option v-bind:value="client.id" v-for="client in clients">{{ client.name }}</option>
      </select>
      <div class="help-block" v-for="error in errors.client_id">
        {{ error }}
      </div>
    </div>

    <div class="row">
      <div v-bind:class="{ 'col-sm-6': true, 'form-group': true, 'has-error': errors.name }">
        <label class="control-label" for="name">Nome</label>
        <input type="text" name="name" class="form-control" placeholder="Nome" v-model="project.name" v-on:keyup="fillPathField()">
        <div class="help-block" v-for="error in errors.name">
          {{ error }}
        </div>
      </div>

      <div v-bind:class="{ 'col-sm-6': true, 'form-group': true, 'has-error': errors.path }">
        <label class="control-label" for="path">Namespace</label>
        <input type="text" name="path" class="form-control" placeholder="Namespace" v-model="project.path" v-bind:disabled="$route.name === 'project.edit'">
        <div class="help-block" v-for="error in errors.path">
          {{ error }}
        </div>
      </div>
    </div>

    <div v-bind:class="{ 'form-group': true, 'has-error': errors.description }">
      <label class="control-label" for="description">Descrição</label>
      <textarea name="description" rows="2" class="form-control" placeholder="Descrição" v-model="project.description"></textarea>
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
      project: {
        client_id: '',
        name: '',
        path: '',
        description: '',
      },
      clients: {},
      errors: {},
    }
  },
  mounted() {
    // Preenche o select de clientes
    this.fetchClients()

    // Exibe o formulário de cadastro ou edição
    if (this.$route.name === 'project.new') {
      this.openCreateForm()
    } else {
      this.openEditForm()
    }
  },
  methods: {
    /**
     * Busca pela lista de clientes cadastrados no sistema.
     */
    fetchClients() {
      axios.get('/ajax/clientes').then(response => {
        this.clients = response.data
      }).catch(error => {
        console.log(error)
      })
    },

    /**
     * Gera uma namespace automaticamente a medida que o nome do projeto é informado.
     */
    fillPathField() {
      // Somente durante o cadastro
      if (this.$route.name === 'project.new') {
        this.project.path = helper.generateNamespace(this.project.name)
      }
    },

    /**
     * Reseta os dados do formulário para seu estado inicial.
     */
    clearForm() {
      this.project = {
        client_id: '',
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
      this.$router.push('/projetos')
      // Fecha a janela modal
      dialog.close()
    },

    /**
     * Exibe o formulário de cadastro
     */
    openCreateForm() {
      this.openDialog('Novo Projeto', modal => {
        this.storeClient(modal)
      })
    },

    /**
     * Exibe o formulário de edição
     */
    openEditForm() {
      axios.get('/ajax/projetos/' + this.$route.params.id + '/edit').then(response => {
        this.project = response.data
        this.openDialog('Editando o Projeto: ' + this.project.name, modal => {
          this.updateClient(modal)
        })
      })
    },

    /**
     * Envia os novos dados para serem processados e armazenados pelo servidor.
     * @param  modal  Instância da janela modal
     */
    storeClient(modal) {
      axios.post('/ajax/projetos', this.project).then(response => {
        // Exibe uma mensagem de sucesso
        toastr.success(response.data.message, 'Sucesso')
        // Adiciona o novo projeto à lista
        this.$parent.projects.push(response.data.project)
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
      axios.patch('/ajax/projetos/' + this.$route.params.id, this.project).then(response => {
        // Exibe uma mensagem de sucesso
        toastr.success(response.data.message, 'Sucesso')

        // Atualiza a lista com os dados dos projetos
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

<template>
  <div class="hide">
    <form id="formModal" method="post" v-on:submit.prevent>
      <!-- Cliente -->
      <div :class="{ 'form-group': true, 'has-error': errors.client_id }">
        <label class="control-label" for="client_id">Cliente</label>
        <select name="client_id" class="form-control" v-model="project.client_id">
          <option value="">Selecione...</option>
          <option :value="client.id" v-for="client in clients">{{ client.name }}</option>
        </select>
        <div class="help-block" v-for="error in errors.client_id">
          {{ error }}
        </div>
      </div>

      <div class="row">
        <!-- Nome -->
        <div :class="{ 'col-sm-6': true, 'form-group': true, 'has-error': errors.name }">
          <label class="control-label" for="name">Nome</label>
          <input type="text" name="name" class="form-control" placeholder="Nome" v-model="project.name" v-on:keyup="fillPathField()">
          <div class="help-block" v-for="error in errors.name">
            {{ error }}
          </div>
        </div>

        <!-- Namespace -->
        <div :class="{ 'col-sm-6': true, 'form-group': true, 'has-error': errors.path }">
          <label class="control-label" for="path">Namespace</label>
          <input type="text" name="path" class="form-control" placeholder="Namespace" v-model="project.path" :disabled="$route.name === 'project.edit'">
          <div class="help-block" v-for="error in errors.path">
            {{ error }}
          </div>
        </div>
      </div>

      <!-- Descrição -->
      <div :class="{ 'form-group': true, 'has-error': errors.description }">
        <label class="control-label" for="description">Descrição</label>
        <textarea name="description" rows="2" maxlength="255" class="form-control" placeholder="Descrição" v-model="project.description"></textarea>
        <div class="help-block" v-for="error in errors.description">
          {{ error }}
        </div>
      </div>

      <!-- Tags -->
      <div :class="{ 'form-group': true, 'has-error': errors.tag_list }">
        <label class="control-label" for="tag_list">Tags</label>
        <input type="text" name="tag_list" class="form-control" placeholder="Tags" v-model="project.tag_list">
        <div class="help-block" v-for="error in errors.tag_list">
          {{ error }}
        </div>
      </div>

      <!-- Notas -->
      <div :class="{ 'form-group': true, 'has-error': errors.notes }">
        <label class="control-label" for="notes">Notas</label>
        <textarea name="notes" rows="5" class="form-control" placeholder="Notas" v-model="project.notes"></textarea>
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
        project: {
          client_id: '',
          name: '',
          path: '',
          description: '',
        },
        clients: {},
        errors: {},
        title: '',
      }
    },
    mounted() {
      // Preenche o select de projetos
      this.fetchProjects()

      // Exibe o formulário de cadastro ou edição
      if (this.$route.name === 'project.new') {
        this.title = 'Novo Projeto'
        this.openCreateForm()
      } else {
        this.title = 'Editando o Projeto'
        this.openEditForm()
      }
    },
    methods: {
      /**
       * Busca pela lista de projetos cadastrados no sistema.
       */
      fetchProjects() {
        axios.get('/ajax/projetos').then(response => {
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
            this.$router.push('/projetos')
          },
        })
      },

      /**
       * Exibe o formulário de cadastro
       */
      openCreateForm() {
        this.openDialog(this.title, modal => {
          this.storeProject(modal)
        })
      },

      /**
       * Exibe o formulário de edição
       */
      openEditForm() {
        axios.get('/ajax/projetos/' + this.$route.params.id + '/edit').then(response => {
          this.project = response.data
          this.title = 'Editando o Projeto: ' + this.project.name
          this.openDialog(this.title, modal => {
            this.updateProject(modal)
          })
        })
      },

      /**
       * Envia os novos dados para serem processados e armazenados pelo servidor.
       * @param  modal  Instância da janela modal
       */
      storeProject(modal) {
        axios.post('/ajax/projetos', this.project).then(response => {
          // Exibe uma mensagem de sucesso
          toastr.success(response.data.message, 'Sucesso')
          // Adiciona o novo projeto à lista
          this.$parent.projects.push(response.data.project)
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
      updateProject(modal) {
        axios.patch('/ajax/projetos/' + this.$route.params.id, this.project).then(response => {
          // Exibe uma mensagem de sucesso
          toastr.success(response.data.message, 'Sucesso')

          // Atualiza a lista com os dados dos projetos
          this.$parent.fetchProjects()

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
          { name: 'description', content: 'Formulário de cadastro e edição de projetos.' }
        ],
      }
    },
  }
</script>

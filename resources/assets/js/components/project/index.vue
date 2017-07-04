<style lang="less">
  @import "~bootstrap/less/mixins/clearfix";

  .list-padding > li {
    .clearfix;
    padding: 10px;
  }
  .list-striped > li:nth-child(even) {
    background-color: #f9f9f9;
  }
  .list-hover > li:hover {
    background-color: #f5f5f5;
  }
</style>

<template>
<div class="panel panel-primary">
  <div class="panel-heading">
    Projetos
  </div>

  <div class="panel-body">
    <div class="text-right margin-20">
      <div class="btn-group">
        <router-link v-bind:to="{ name: 'project.new' }" class="btn btn-primary">
          <i class="fa fa-plus"></i> Novo Projeto
        </router-link>
      </div>
    </div>

    <p v-if="projects.length == 0" class="lead text-center text-warning">
      <i class="fa fa-warning"></i> Nada encontrado
    </p>

    <ul v-if="projects.length > 0" class="list-unstyled list-padding list-striped list-hover">
      <li v-for="project in projects" v-bind:id="'project_' + project.id">
        <div class="pull-left">
          <strong>{{ project.path }} / {{ project.name }}</strong><br>
          <span class="text-muted">{{ project.description }}</span>
        </div>

        <div class="pull-right">
          <!-- View -->
          <a v-bind:href="'https://gitlab.com/groups/' + project.path" target="_blank" class="btn btn-success btn-xs">
            <i class="fa fa-eye"></i>
          </a>
          <!-- Edit -->
          <router-link v-bind:to="{ name: 'project.edit', params: { id: project.id } }" class="btn btn-primary btn-xs">
            <i class="fa fa-edit"></i>
          </router-link>
          <!-- Delete -->
          <button class="btn btn-danger btn-xs" v-on:click.prevent="deleteProject(project.id)">
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
      projects: []
    }
  },
  mounted() {
    // Busca pela listagem de projetos
    this.fetchProjects()
  },
  methods: {
    /**
     * Executa uma requisição AJAX em busca dos projetos cadastrados no sistema.
     */
    fetchProjects() {
      axios.get('/ajax/projetos').then(response => {
        this.projects = response.data
      }).catch(error => {
        console.log(error)
      })
    },

    /**
     * Exibe uma janela de confirmação para a exclusão do projeto.
     */
    deleteProject(project_id) {
      var that = this
      BootstrapDialog.show({
        type: BootstrapDialog.TYPE_DANGER,
        title: 'Remover projeto',
        message: 'Tem certeza que deseja remover este projeto? Esta operação não poderá ser desfeita.',
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

            axios.delete('/ajax/projetos/' + project_id).then(response => {
              // Atualiza a lista com os dados dos projetos
              that.fetchProjects()
              // Exibe uma mensagem de sucesso
              toastr.success(response.data.message, 'Sucesso')
              // Fecha a janela modal
              dialog.close()
            }).catch(error => {
              console.log(error)
            })
          }
        }]
      })
    },
  }
}
</script>

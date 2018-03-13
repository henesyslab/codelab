<template>
  <div>
    <h1>Tarefas - FastWay</h1>

    <div class="panel panel-primary">
      <div class="panel-heading">
        <strong>Cadastrar tarefa</strong>
      </div>

      <form class="panel-body">
        <div :class="{ 'form-group': true, 'has-error': errors.name }">
          <label for="name">Título</label>
          <input type="text" id="name" class="form-control"
            v-model="task.name" placeholder="Título da tarefa">
          <small class="help-block">
            <span v-for="error in errors.name">
              {{ error }}
            </span>
          </small>
        </div>

        <div :class="{ 'form-group': true, 'has-error': errors.description }">
          <label for="description">Descrição</label>
          <textarea id="description" class="form-control" rows="5"
            v-model="task.description" placeholder="Descrição da tarefa"></textarea>
          <small class="help-block">
            <span v-for="error in errors.description">
              {{ error }}
            </span>
          </small>
        </div>

        <button type="submit" class="btn btn-primary"
          v-on:click.prevent="sendForm($event)">
          <i class="fa fa-plus"></i>
          Cadastrar
        </button>
      </form>
    </div>
  </div>
</template>

<script>
  import _ from 'lodash'
  import axios from 'axios'
  import toastr from 'toastr'

  export default {
    name: 'fastway-task',

    data() {
      return {
        task: {
          name: '',
          description: ''
        },
        errors: {}
      }
    },

    methods: {

      sendForm(e) {
        this.disableForm(e.target)

        axios.post('/ajax/fastway-tarefas', this.task)
          .then(res => {
            // Limpa o formulário
            this.errors = {}
            _(this.task).forEach((value, key) => {
              return this.task[key] = ''
            })

            // Re-habilita o formulário
            this.enableForm(e.target)

            toastr.success('Tarefa cadastrada com sucesso!')
          })
          .catch(err => {
            if (err.response.status === 422) {
              this.errors = err.response.data
              this.enableForm(e.target)
            } else {
              toastr.error('Ocorreu um erro!')
            }
          })
      },

      enableForm(btn) {
        $(btn)
          .prop('disabled', false)
          .find('.fa')
          .removeClass('fa-spin fa-spinner')
          .addClass('fa-plus')
      },

      disableForm(btn) {
        $(btn)
          .prop('disabled', true)
          .find('.fa')
          .removeClass('fa-plus')
          .addClass('fa-spin fa-spinner')
      }

    }
  }
</script>

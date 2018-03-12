import VueRouter from 'vue-router'

// Define route components
const Dashboard = require('./components/dashboard')
const Client = {
  index: require('./components/client/index'),
  form:  require('./components/client/form'),
}
const Project = {
  index: require('./components/project/index'),
  form:  require('./components/project/form'),
}

import FastWayTask from './components/FastWay/Task'

export default new VueRouter({
    mode: 'history',
    base: '/app',
    linkExactActiveClass: 'active',
    routes: [
      { path: '/', name: 'dashboard', component: Dashboard },
      { path: '/clientes', name: 'client.index', component: Client.index, children: [
        { path: 'adicionar', name: 'client.new', component: Client.form },
        { path: 'editar/:id', name: 'client.edit', component: Client.form },
      ] },
      { path: '/projetos', name: 'project.index', component: Project.index, children: [
        { path: 'adicionar', name: 'project.new', component: Project.form },
        { path: 'editar/:id', name: 'project.edit', component: Project.form },
      ] },
      { path: '/fastway-task', name: 'fastway.task', component: FastWayTask },
    ]
})

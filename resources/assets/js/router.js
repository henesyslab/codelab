import VueRouter from 'vue-router'

// Define route components
const Dashboard = require('./components/dashboard.vue')
const Client = {
  index: require('./components/client/index.vue'),
  form:  require('./components/client/form.vue'),
}

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
    ]
})

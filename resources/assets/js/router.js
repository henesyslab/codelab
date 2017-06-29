import VueRouter from 'vue-router'

// Define route components
const Dashboard    = require('./components/Dashboard.vue')

export default new VueRouter({
    mode: 'history',
    base: '/app',
    linkExactActiveClass: 'active',
    routes: [
      // Módulo: Dashboard
      { path: '/', component: Dashboard },
    ]
})

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vue from 'vue'
import VueRouter from 'vue-router'
import router from './router'
import Meta from 'vue-meta'

Vue.use(VueRouter)
Vue.use(Meta)

const app = new Vue({
  el: '#app',
  router,
  metaInfo: {
    title: 'Homepage',
    titleTemplate: '%s | ' + document.getElementsByTagName('title')[0].innerText,
  },
})

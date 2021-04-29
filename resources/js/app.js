/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

//window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
//test edit
import Vue from 'vue';
window.Vue = Vue;
import VueRouter from 'vue-router'

Vue.use(VueRouter)
import App from './components/App'
import Home from './components/Home'
import Login from './components/Login'
import Register from './components/Register'
import Profile from './components/Profile'
const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/login',
      name: 'login',
      component: Login
    },
    {
      path: '/register',
      name: 'register',
      component: Register
    },
    {
      path: '/:userId',
      name: 'user',
      component: Profile,
      props: true,
      meta: { requiresAuth: true }
    },
  ],
})
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('jwt') == null;
  if (to.matched.some(record => record.meta.guest)) {
    if (!token) next({ name: 'home' })
    else next()
  }
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (token) {
      next({
        path: '/login',
        query: { redirect: to.fullPath }
      })
    } else {
      next()
    }
  } else {
    next()
  }
})
const app = new Vue({
  el: '#app',
  components: { App },
  router,
});
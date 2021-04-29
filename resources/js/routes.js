import VueRouter from 'vue-router'

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
export default router
import VueRouter from 'vue-router';

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
    ],
  })
/*router.beforeResolve((to, from, next) => {
    // If this isn't an initial page load.
    if (to.name) {
        $('.fade').removeClass('show').addClass('out');
        // Start the route progress bar.
        NProgress.start()
    }
    next()
})

router.afterEach((to, from) => {
    // Complete the animation of the route progress bar.
    //NProgress.done()
    //$('.fade').removeClass('out').addClass('show');
    setTimeout(function() {
        NProgress.done();
        $('.fade').removeClass('out').addClass('show');
    }, 1000);
})*/
export default router
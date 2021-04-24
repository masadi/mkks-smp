import VueRouter from 'vue-router';


let routes = [{
        path: '/login',
        name: 'login',
        component: require('./views/auth/login').default
    },
    {
        path: '/',
        name: 'beranda',
        component: require('./views/beranda').default
    },
];

const router = new VueRouter({
    path: '/app',
    component: require('./views/beranda').default,
    base: '/',
    mode: 'history',
    routes,
    linkActiveClass: 'active',
    user: user,
});
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
import Vue from 'vue';
import VueRouter from 'vue-router';
import {getUser} from "@/api/auth";
import Login from "@/views/Login.vue";
import Home from "@/views/Home.vue";
import Register from "@/views/Register.vue";
    
export const checkLogin = async (to, from, next) => {
    let auth = true;
    await getUser().catch(({response}) => {
        if (response.status === 401 || response.status === 403) {
            auth = false;
        }
    })
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (auth === false) {
            next({name: ''})
        } else {
            next()
        }

    } else if (to.matched.some(record => record.meta.requiresVisitor)) {
        if (auth) {
            next({name: 'home'})
        } else {
            next()
        }

    } else {
        next()
    }

}
Vue.use(VueRouter);

const routes = [
    {
        path: '',
        component: Login,
        meta: {requiresAuth: false},
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {requiresAuth: false},
    },

    {
        path: '/home',
        name: 'home',
        component: Home,
        beforeEnter: checkLogin,
        meta: {requiresAuth: true},
    },

];

const router = new VueRouter({
    mode: 'history',
    base: import.meta.env.Base,
    routes,
});

export default router;

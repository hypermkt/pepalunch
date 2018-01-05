import Vue from 'vue';
import VueRouter from 'vue-router';
import Index from './components/Index.vue';

require('./bootstrap');

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/', component: Index }
    ]
})

const app = new Vue({
    router,
    el: '#app'
})


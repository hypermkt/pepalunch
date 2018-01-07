import Vue from 'vue';
import VueRouter from 'vue-router';
import Index from './components/Index.vue';
import Callback from './components/Callback.vue';
import ListLunch from './components/ListLunch.vue';

require('./bootstrap');

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/', component: Index },
        { path: '/auth/slack/callback', component: Callback },
        { path: '/list', component: ListLunch }
    ]
})

const app = new Vue({
    router,
    el: '#app'
})


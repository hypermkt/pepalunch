import Vue from 'vue';
import VueRouter from 'vue-router';
import Element from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css'

import Index from './components/Index.vue';
import Callback from './components/Callback.vue';
import ListLunch from './components/ListLunch.vue';

require('./bootstrap');

Vue.use(VueRouter);
Vue.use(Element);

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


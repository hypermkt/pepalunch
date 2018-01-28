import Vue from 'vue';
import Vuex from 'vuex';

import lunch from './lunch';
import user from './user';

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {
        lunch,
        user,
    }
});

export default store;

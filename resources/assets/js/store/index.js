import Vue from 'vue';
import Vuex from 'vuex';

import lunch from './lunch';

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {
        lunch
    }
});

export default store;

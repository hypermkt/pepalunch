import Vue from 'vue';
import Vuex from 'vuex';

import lunch from './lunch';

const store = new Vuex.store({
    modules: {
        lunch
    }
});

export default store;
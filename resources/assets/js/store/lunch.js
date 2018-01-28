import api from '../api';

export default {
    state: {
        lunches: []
    },
    getters: {
    },
    actions: {
        fetchMyLunches({state, commit}) {
            api.lunch.list().then((response) => {
                let lunches = response.data;
                commit('storeLunches', {lunches});
            });
        },
        createShuffleLunch({state, commit}) {
            api.lunch.create().then((response) => {
                api.lunch.list().then((response) => {
                    let lunches = response.data;
                    commit('storeLunches', { lunches });
                });
            });
        }
    },
    mutations: {
        storeLunches(state, payload) {
            state.lunches = payload.lunches;
        }
    }
}

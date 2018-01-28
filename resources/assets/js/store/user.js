import api from '../api';

export default {
    state: {
        token: ''
    },
    getters: {
    },
    actions: {
        async login({commit}, {code, state}) {
            let response = await api.user.login(code, state);
            localStorage.token = response.data.token;
            return response.status;
        }
    },
    mutations: {
        storeToken(state, payload) {
            state.token = payload.token
        }
    }
}

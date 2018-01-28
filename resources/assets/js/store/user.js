import api from '../api';
import jwtDecode from 'jwt-decode';

export default {
    state: {
        userId: 0
    },
    getters: {
    },
    actions: {
        async login({commit}, {code, state}) {
            let response = await api.user.login(code, state);
            localStorage.token = response.data.token;

            let payload = jwtDecode(response.data.token);
            commit('storeUserId', payload);

            return response.status;
        },
        updateActive({commit}, {userId, active}) {
            api.user.update(userId, {active});
        },

    },
    mutations: {
        storeToken(state, payload) {
            state.token = payload.token
        },
        storeUserId(state, payload) {
            state.userId = payload.sub;
        }
    }
}

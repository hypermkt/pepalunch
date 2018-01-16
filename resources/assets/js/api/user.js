import client from './client';

export default {
    login: (code, state) => {
        return client.post('/login', { code, state })
    },
    update: (id, params) => {
        return client.put('/users/' + id, params);
    },
    show: (id) => {
        return client.get('/users/' + id);
    }
}
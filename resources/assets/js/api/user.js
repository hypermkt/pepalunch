import client from './client';

export default {
    login: (code, state) => {
        return client.post('/login', { code, state })
    },
}
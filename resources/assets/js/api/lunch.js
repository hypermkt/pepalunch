import client from './client';

export default {
    list: () => {
        return client.get('/lunches');
    }
}
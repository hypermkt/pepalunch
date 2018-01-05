import axios from 'axios';

// TODO: 環境別でbaseURLの切り替え
let client = axios.create({
    baseURL: 'http://localhost:8000/api/'
});

export default client;

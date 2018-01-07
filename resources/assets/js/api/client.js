import axios from 'axios';

// TODO: 環境別でbaseURLの切り替え
let client = axios.create({
    baseURL: 'http://localhost:8000/api/'
});

var config = {};

client.interceptors.request.use(function (config) {
    config.headers['Authorization']    = `Bearer ${localStorage.getItem('token')}`
    return config;
  }, function (error) {
    // Do something with request error
    return Promise.reject(error);
  });

export default client;

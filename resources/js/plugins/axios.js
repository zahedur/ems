import axios from "axios";
import store from '../store';

//axios.defaults.baseURL = 'https://bms.zahedur.com/api/';
axios.defaults.baseURL = 'http://127.0.0.1:8000/api/';

const tokenInfo = store.getters['auth/tokenInfo'];
axios.defaults.headers.common['Authorization'] = `Bearer ${ tokenInfo.token }`;

axios.interceptors.response.use(function (response) {
    // Any status code that lie within the range of 2xx cause this function to trigger


    return response;
}, function (error) {
    // Any status codes that falls outside the range of 2xx cause this function to trigger

    if (error.response.status === 401 || error.response.message === "Unauthenticated.") {
        store.dispatch('auth/logout');
    }

    return Promise.reject(error);
});


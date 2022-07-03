import axios from 'axios';
import {getToken, setToken, removeToken} from './auth';

// Create axios instance
const http = axios.create({
    baseURL: 'http://localhost:8000',
    timeout: 50000, // Request timeout,
    withCredentials: true,
});

// Request interceptor
http.interceptors.request.use(
    config => {
        const token = getToken();

        if (token) {
            config.headers['Authorization'] = 'Bearer ' + getToken(); // Set JWT token
        }
        config.headers['Current-Language'] = 'en';

        return config;
    },
    error => {
        // Do something with request error
        Promise.reject(error);
    },
);

// response pre-processing
http.interceptors.response.use(
    response => {
        if (response.headers.authorization) {
            setToken(response.headers.authorization);
            response.data.token = response.headers.authorization;
        }

        return response.data;
    },
    error => {
        // const message = error.message;
        if (error.response) {
            if (error.response.status === 401) {
                removeToken();
                // TODO
            }
        }
        return Promise.reject(error);
    },
);

export default http;

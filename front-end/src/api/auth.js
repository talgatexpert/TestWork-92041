import request from '../utils/request';

export function login(data) {
    return request({
        url: 'api/auth/login',
        method: 'post',
        data: data
    });
}

export function register(data) {
    return request({
        url: 'api/auth/register',
        method: 'post',
        data: data
    });
}

export function resetPassword(data) {
    return request({
        url: 'api/auth/password-reset',
        method: 'post',
        data: data
    });
}

export function newPassword(data) {
    return request({
        url: `api/auth/password-reset/new`,
        method: 'post',
        data: data
    });
}


export function logout() {
    return request({
        url: 'api/auth/logout',
        method: 'post'
    })
}

export function getUser() {
    return request({
        url: 'api/auth/user',
        method: 'get'
    });
}

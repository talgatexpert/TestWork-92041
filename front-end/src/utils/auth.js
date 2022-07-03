import Cookies from 'js-cookie';

const TokenKey = 'session-token';

export function getToken() {
    return Cookies.get(TokenKey);
}

export function setToken(token, remember) {
    const options = {};
    if (remember) {
        options.expires = 365;
    }
    return Cookies.set(TokenKey, token, options);
}

export function removeToken() {
    Cookies.remove('laravel_session');
    Cookies.remove('XSRF-TOKEN');
    return Cookies.remove(TokenKey);
}

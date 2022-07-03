import router from "@/router";

export default {
    namespaced: true,
    state: {
        authenticated: false,
        user: {}
    },
    getters: {
        authenticated(state) {
            return state.authenticated
        },
        user(state) {
            return state.user
        }
    },
    mutations: {
        SET_AUTHENTICATED(state, value) {
            state.authenticated = value
        },
        SET_USER(state, value) {
            state.user = value
        }
    },
    actions: {
        login({commit}, payload) {
            commit('SET_USER', payload)
            commit('SET_AUTHENTICATED', true)
        },
        async logout({commit}) {
            commit('SET_USER', {})
            commit('SET_AUTHENTICATED', false)
            await router.push('/')
        }
    }
}

export default {
    namespaced: true,
    state: {
        desserts: [],
        pagination: {
            total: 0,
            last_page: null,
            next_page_url: null,
            prev_page_url: null,
        }
    },
    getters: {
        pagination(state) {
            return state.pagination
        },
        desserts(state) {
            return state.desserts
        }
    },
    mutations: {
        SET_DESSERTS(state, value) {
            state.desserts = value
        },
        SET_PAGINATION(state, value) {
            state.pagination = value
        }
    },
    actions: {
        get({commit}, payload) {
            commit('SET_DESSERTS', payload.data)
            commit('SET_PAGINATION', {total: payload.total, last_page: payload.last_page, next_page_url: payload.next_page_url, prev_page_url: payload.prev_page_url,})
        },
    }
}
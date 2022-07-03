import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate'
import auth from "@/store/auth";
import dessert from "@/store/dessert";


Vue.use(Vuex);

export default new Vuex.Store({
  namespaces: false,
  state: {
  },
  plugins:[
    createPersistedState()
  ],

  mutations: {
  },

  actions: {
  },

  modules: {
      auth,
    dessert,
  },
});

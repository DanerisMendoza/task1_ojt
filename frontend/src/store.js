import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

/*
  issue encounter when i reload tab all the value become null
  The Vuex store is usually not persisted. It is handled like regular JavaScript: After the page is reloaded, the data is released. If you want to persist the Vuex Store in a browser, 
  there are however possibilities for that. One example is vuex-persistedstate.
*/

const store = new Vuex.Store({
  state: {
    user_id: null,
    username: null,
    role: null
  },
  mutations: {
    setUserId(state, user_id) {
      state.user_id = user_id;
    },
    setUsername(state, username) {
      state.username = username;
    },
    setRole(state, role) {
      state.role = role;
    }
  },
  actions: {
    setUser({ commit }, user) {
      commit('setUserId', user.user_id);
      commit('setUsername', user.username);
      commit('setRole', user.role);
    }
  },
  getters: {
    getUserId(state) {
      return state.user_id;
    },
    getUsername(state) {
      return state.username;
    },
    getRole(state) {
      return state.role;
    }
  }
});

export default store;

import Vue from 'vue';
import Vuex from 'vuex';
import menu from '../menu';
Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        pageTitle: 'Home',
        menu: menu,
        message: {
            type: null,
            body: null
        }
    },
    mutations: {
        setMenu (state, data) {
            state.menu = data
        },
        setPageTitle (state, data) {
            state.pageTitle = data
        },
        showMessage (state, type, body) {
            state.message = { type, body }
        }
    },
    actions: {
        checkPageTitle ({commit, state}, path) {
            for (let k in state.menu) {
                if (state.menu[k].href === path) {
                    commit('setPageTitle', state.menu[k].title)
                    break
                }
            }
        }
    }
});

export default store;

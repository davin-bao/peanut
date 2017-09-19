import Vue from 'vue';
import Vuex from 'vuex';
import menu from '../menu';
Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        pageTitle: 'Home',
        menu: menu,
        nodes: [],
        message: {
            show: false,
            body: null,
            success: null,
            error: null,
            info: null,
        }
    },
    mutations: {
        getNodes (state) {

        },
        setMenu (state, data) {
            state.menu = data
        },
        setPageTitle (state, data) {
            state.pageTitle = data
        },
        showMessage (state, message) {
            state.message = {body: message.body, show: true};
            switch (message.type){
                case 'success':
                    state.message.success = true;
                    break;
                case 'error':
                    state.message.error = true;
                    break;
                case 'info':
                    state.message.info = true;
                    break;
            }
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

import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';
import menu from '../menu';

Vue.use(Vuex);

const NODES_PATH = '/nodes';
const CONTAINERS_PATH = '/containers';
const NETWORKS_PATH = '/networks';

const store = new Vuex.Store({
    state: {
        pageTitle: 'Home',
        menu: menu,
        breadcrumbs: [],
        endpoint: 'http://localhost:8000',
        nodes: {loading: null, data: []},
        containers: {loading: null, data: []},
        networks: {loading: null, data: []},
        message: {
            show: false,
            body: null,
            type: null
        },
        deleteConfirm: {
            show: false,
            action: null
        }
    },
    mutations: {
        async getNodes (state) {
            state.nodes.loading = true;
            state.nodes.data = [];
            try{
                var response = await axios({
                    method: 'get',
                    url: state.endpoint + NODES_PATH
                });
                if(response.status !== 200){
                    state.message = {body: 'get endpoint error [' + response.statusText + ']', show: true, type: 'error'};
                }

                state.nodes.data = response.data;
                await this.commit('getContainers');
                state.nodes.loading = false;
            }catch(e){
                state.message = {body: 'get nodes error [' + e.message + ']', show: true, type: 'error'};
            }
        },
        async getContainers (state){
            state.containers.loading = true;
            state.containers.data = [];

            for(var i=0; i<state.nodes.data.length; i++){
                var nodeId = state.nodes.data[i].ID;
                var address = state.nodes.data[i].ManagerStatus.Addr;
                var endpoint = address.substring(0, address.indexOf(':')).split('.').join('-');
                var response = await axios({
                    method: 'get',
                    url: state.endpoint + CONTAINERS_PATH + '/' + endpoint
                });
                if(response.status !== 200){
                    state.message = {body: 'get containers error [' + response.statusText + ']', show: true, type: 'error'};
                }
                state.containers.data[nodeId] = response.data;
                state.nodes.data[i].containers = response.data;
                console.log(nodeId, state.nodes.data[i].containers);
            }
            state.containers.loading = false;
        },
        async getNetworks (state){
            state.networks.loading = true;
            state.networks.data = [];

            var response = await axios({
                method: 'get',
                url: state.endpoint + NETWORKS_PATH
            });
            if(response.status !== 200){
                state.message = {body: 'get containers error [' + response.statusText + ']', show: true, type: 'error'};
            }
            state.networks.data = response.data;

            state.networks.loading = false;
        },
        setBreadcrumbs (state, breadcrumbs) {
            state.breadcrumbs = breadcrumbs;
        },
        setMenu (state, data) {
            state.menu = data
        },
        setPageTitle (state, data) {
            state.pageTitle = data
        },
        showMessage (state, message) {
            state.message = {body: message.body, show: true, type: message.type};
        },
        showDeleteConfirm (state, action) {
            state.deleteConfirm = { show: true, action: action};
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

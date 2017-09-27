import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';
import menu from '../menu';

Vue.use(Vuex);

const NODES_PATH = '/nodes';
const NODES_INSPECT_PATH = '/nodes/';
const CONTAINERS_PATH = '/containers';
const NETWORKS_PATH = '/networks';
const NETWORKS_CREATE_PATH = '/networks/create';
const NETWORKS_INSPECT_PATH = '/networks/';
const NETWORKS_REMOVE_PATH = '/networks/';
const COMPOSES_PATH = '/composes';
const COMPOSES_CREATE_PATH = '/composes/create';
const COMPOSES_INSPECT_PATH = '/composes/';
const COMPOSES_REMOVE_PATH = '/composes/';

const store = new Vuex.Store({
    state: {
        pageTitle: 'Home',
        menu: menu,
        breadcrumbs: [],
        endpoint: 'http://localhost:8000',
        nodes: {loading: null, data: []},
        node: {loading: null, data: {}},
        containers: {loading: null, data: []},
        networks: {loading: null, data: []},
        composes: {loading: null, data: []},
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
        },
        //Node
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
                    return;
                }

                state.nodes.data = response.data;
                await this.commit('getContainers');
                state.nodes.loading = false;
            }catch(e){
                state.message = {body: 'get nodes error [' + e.message + ']', show: true, type: 'error'};
            }
        },
        async getNode(state, ID) {
            state.node.loading = true;
            try{
                var response = await axios({
                    method: 'get',
                    url: state.endpoint + NODES_INSPECT_PATH + ID
                });
                if(response.status !== 200){
                    state.message = {body: 'get node error [' + response.statusText + ']', show: true, type: 'error'};
                    return;
                }

                state.node.data = response.data;

                state.node.loading = false;
            }catch(e){
                state.message = {body: 'get node error [' + e.message + ']', show: true, type: 'error'};
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
        //Network
        async getNetworks (state){
            state.networks.loading = true;
            state.networks.data = [];

            var response = await axios({
                method: 'get',
                url: state.endpoint + NETWORKS_PATH
            });
            if(response.status !== 200){
                state.message = {body: 'get containers error [' + response.statusText + ']', show: true, type: 'error'};
                return;
            }
            state.networks.data = response.data;

            state.networks.loading = false;
        },
        async createNetwork(state, newItem){
            var response = await axios({
                method: 'post',
                url: state.endpoint + NETWORKS_CREATE_PATH,
                data: newItem
            });
            if(response.status !== 200){
                state.message = {body: 'create network error [' + response.statusText + ']', show: true, type: 'error'};
                return;
            }
            state.message = {body: 'create network success', show: true, type: 'success'};
            await this.commit('getNetworks');
        },
        async removeNetwork(state, items){
            for(let i=0; i< items.length; i++){
                var response = await axios({
                    method: 'delete',
                    url: state.endpoint + NETWORKS_REMOVE_PATH + items[i].Id
                });
                if(response.status !== 200){
                    state.message = {body: 'create network error [' + response.statusText + ']', show: true, type: 'error'};
                }
            }

            await this.commit('getNetworks');
        },
        //Compose
        async getComposes (state){
            state.composes.loading = true;
            state.composes.data = [];

            var response = await axios({
                method: 'get',
                url: state.endpoint + COMPOSES_PATH
            });
            if(response.status !== 200){
                state.message = {body: 'get containers error [' + response.statusText + ']', show: true, type: 'error'};
                return;
            }
            state.composes.data = response.data;

            state.composes.loading = false;
        },
        async createCompose(state, newItem){
            var response = await axios({
                method: 'post',
                url: state.endpoint + COMPOSES_CREATE_PATH,
                data: newItem
            });
            if(response.status !== 200){
                state.message = {body: 'create compose error [' + response.statusText + ']', show: true, type: 'error'};
                return;
            }
            state.message = {body: 'create compose success', show: true, type: 'success'};
            await this.commit('getComposes');
        },
        async removeCompose(state, items){
            for(let i=0; i< items.length; i++){
                var response = await axios({
                    method: 'delete',
                    url: state.endpoint + COMPOSES_REMOVE_PATH + items[i].Name
                });
                if(response.status !== 200){
                    state.message = {body: 'create compose error [' + response.statusText + ']', show: true, type: 'error'};
                }
            }

            await this.commit('getComposes');
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

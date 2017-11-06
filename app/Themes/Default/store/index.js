import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';
import menu from '../menu';
import Util from '../libs/util';

Vue.use(Vuex);

const NODES_PATH = '/nodes';
const NODES_CREATE_PATH = '/nodes/create';
const NODES_UPDATE_PATH = '/nodes/';
const NODES_REMOVE_PATH = '/nodes/';
const NODES_INSPECT_PATH = '/nodes/';
const NODES_STATUS_PATH = '/nodes/status/';
const CONTAINERS_PATH = '/containers';
const STACKS_PATH = '/stacks';
const STACKS_CREATE_PATH = '/stacks/create';
const STACKS_INSPECT_PATH = '/stacks/';
const STACKS_REMOVE_PATH = '/stacks/';
const SERVICES_PATH = '/services';
const SERVICES_CREATE_PATH = '/services/create';
const SERVICES_INSPECT_PATH = '/services/';
const SERVICES_REMOVE_PATH = '/services/';
const NETWORKS_PATH = '/networks';
const NETWORKS_CREATE_PATH = '/networks/create';
const NETWORKS_INSPECT_PATH = '/networks/';
const NETWORKS_REMOVE_PATH = '/networks/';
const COMPOSES_PATH = '/composes';
const COMPOSES_CREATE_PATH = '/composes/create';
const COMPOSES_UPDATE_PATH = '/composes/';
const COMPOSES_INSPECT_PATH = '/composes/';
const COMPOSES_REMOVE_PATH = '/composes/';

const store = new Vuex.Store({
    state: {
        pageTitle: 'Home',
        menu: menu,
        breadcrumbs: [],
        endpoint: 'http://peanut.local',
        nodes: {loading: null, statusLoading: null, data: []},
        node: {loading: null, data: {}},
        nodeCreateCommand: {show: false, loading: null, data: { workCommand: '', managerCommand: ''}},
        nodeStatus: [],
        stacks: {loading: null, data: []},
        services: {loading: null, data: []},
        containers: {loading: null, data: []},
        networks: {loading: null, data: []},
        composes: {loading: null, data: [], selectData:[]},
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
                if(response.status == 299){
                    state.message = {body: 'get nodes error [' + response.data.msg + ']', show: true, type: 'error'};
                    return;
                }

                state.nodes.data = response.data;
                await this.commit('getContainers');
                await this.commit('getNodesStatus');
                state.nodes.loading = false;
            }catch(e){
                state.message = {body: 'get nodes error [' + e.message + ']', show: true, type: 'error'};
            }
        },
        async getNodesStatus (state){
            state.nodes.statusLoading = true;
            //获取当前节点的CPU及内存使用情况
            for(var i=0; i<state.nodes.data.length; i++){
                var nodeId = state.nodes.data[i].ID;
                var address = state.nodes.data[i].ManagerStatus.Addr;
                var endpoint = address.substring(0, address.indexOf(':')).split('.').join('-');
                try{
                    var response = await axios({
                        method: 'get',
                        url: state.endpoint + NODES_STATUS_PATH + endpoint
                    });

                    if(response.status == 299){
                        state.message = {body: 'get containers error [' + response.data.msg + ']', show: true, type: 'error'};
                        return;
                    }

                    state.nodes.data[i].status = {};
                    state.nodes.data[i].status.cpu = parseFloat(Util.numToCurrency(response.data.cpu, 2));
                    state.nodes.data[i].status.memory = parseFloat(Util.numToCurrency(response.data.memFree*100/response.data.memTotal, 2));
                }catch(e){
                    state.message = {body: 'get containers error [' + e.message + ']', show: true, type: 'error'};
                }
            }
            state.nodes.statusLoading = false;
        },
        async getNodeCreateCommand(state) {
            state.nodeCreateCommand.loading = true;
            state.nodeCreateCommand.data = [];
            try{
                var response = await axios({
                    method: 'get',
                    url: state.endpoint + NODES_CREATE_PATH
                });
                if(response.status == 299){
                    state.message = {body: 'get create node command error [' + response.data.msg + ']', show: true, type: 'error'};
                    return;
                }

                state.nodeCreateCommand.data = response.data;
                await this.commit('getContainers');
                state.nodeCreateCommand.loading = false;
            }catch(e){
                state.message = {body: 'get create node command error [' + e.message + ']', show: true, type: 'error'};
            }
        },
        async updateNode(state, editItem){
            try{
                var response = await axios({
                    headers: {'Content-Type': 'application/json; charset=UTF-8', 'Accept': 'application/json'},
                    method: 'post',
                    url: state.endpoint + NODES_UPDATE_PATH + editItem.ID,
                    data: editItem
                });
                if(response.status == 299){
                    state.message = {body: 'update node error [' + response.data.msg + ']', show: true, type: 'error'};
                    return;
                }
                state.message = {body: 'update node success', show: true, type: 'success'};
                await this.commit('getNode', editItem.ID);
            }catch(e){
                state.message = {body: 'update node error [' + e.message + ']', show: true, type: 'error'};
            }
        },
        async getNode(state, ID) {
            state.node.loading = true;
            try{
                var response = await axios({
                    method: 'get',
                    url: state.endpoint + NODES_INSPECT_PATH + ID
                });
                if(response.status == 299){
                    state.message = {body: 'get node detail error [' + response.data.msg + ']', show: true, type: 'error'};
                    return;
                }

                state.node.data = response.data;
                await this.commit('getNodeStatus', state.node.data.ManagerStatus.Addr);

                state.node.loading = false;
            }catch(e){
                state.message = {body: 'get node error [' + e.message + ']', show: true, type: 'error'};
            }
        },
        async removeNode(state, items){
            for(let i=0; i< items.length; i++){
                try{
                    var response = await axios({
                        method: 'delete',
                        url: state.endpoint + NODES_REMOVE_PATH + items[i].ID
                    });
                    if(response.status == 299){
                        state.message = {body: 'remove node error [' + response.data.msg + ']', show: true, type: 'error'};
                        return;
                    }

                }catch(e){
                    state.message = {body: 'remove node error [' + e.message + ']', show: true, type: 'error'};
                }
            }

            await this.commit('getNodes');
        },
        async getNodeStatus(state, address){
            var endpoint = address.substring(0, address.indexOf(':')).split('.').join('-');
            var response = await axios({
                method: 'get',
                url: state.endpoint + NODES_STATUS_PATH + endpoint
            });
            state.nodeStatus.push({key: (new Date()).format('hh:mm:ss'), value: response.data});
        },
        async getContainers (state){
            state.containers.loading = true;
            state.containers.data = [];
            if(state.nodes.data.length <= 0){
                await this.commit('getNodes');
            }

            for(var i=0; i<state.nodes.data.length; i++){
                var nodeId = state.nodes.data[i].ID;
                var address = state.nodes.data[i].ManagerStatus.Addr;
                var endpoint = address.substring(0, address.indexOf(':')).split('.').join('-');
                try{
                    var response = await axios({
                        method: 'get',
                        url: state.endpoint + CONTAINERS_PATH + '/' + endpoint
                    });
                    if(response.status == 299){
                        state.message = {body: 'get containers error [' + response.data.msg + ']', show: true, type: 'error'};
                        return;
                    }
                    state.nodes.data[i].containers = [];
                    for(var j=0; Util.isArray(response.data)&&j<response.data.length; j++){
                        let entity = response.data[j];
                        entity.NodeAddress = address.substring(0, address.indexOf(':'));

                        state.containers.data.push(entity);
                        state.nodes.data[i].containers.push(entity);
                    }

                }catch(e){
                    state.message = {body: 'get containers error [' + e.message + ']', show: true, type: 'error'};
                }
            }
            state.containers.loading = false;
        },
        //Stack
        async getStacks (state){
            state.stacks.loading = true;
            state.stacks.data = [];

            try{
                var response = await axios({
                    method: 'get',
                    url: state.endpoint + STACKS_PATH
                });
                if(response.status == 299){
                    state.message = {body: 'get stacks error [' + response.data.msg + ']', show: true, type: 'error'};
                    return;
                }

                state.stacks.data = response.data;
                state.stacks.loading = false;
            }catch(e){
                state.message = {body: 'get stacks error [' + e.message + ']', show: true, type: 'error'};
            }
        },
        async createStack(state, editItem){
            try{
                var response = await axios({
                    method: 'post',
                    url: state.endpoint + STACKS_CREATE_PATH,
                    data: editItem
                });
                if(response.status == 299){
                    state.message = {body: 'create stack error [' + response.data.msg + ']', show: true, type: 'error'};
                    return;
                }

                state.message = {body: 'create stack success', show: true, type: 'success'};
                await this.commit('getStacks');
            }catch(e){
                state.message = {body: 'create stack error [' + e.message + ']', show: true, type: 'error'};
            }
        },
        async removeStack(state, items){
            for(let i=0; i< items.length; i++){
                try{
                    var response = await axios({
                        method: 'delete',
                        url: state.endpoint + STACKS_REMOVE_PATH + items[i].Id
                    });
                    if(response.status == 299){
                        state.message = {body: 'remove stack error [' + response.data.msg + ']', show: true, type: 'error'};
                        return;
                    }

                }catch(e){
                    state.message = {body: 'remove stack error [' + e.message + ']', show: true, type: 'error'};
                }
            }

            await this.commit('getStacks');
        },
        //Service
        async getServices (state, Stack){
            state.services.loading = true;
            state.services.data = [];

            try{
                var response = await axios({
                    method: 'get',
                    url: state.endpoint + SERVICES_PATH
                });
                if(response.status == 299){
                    state.message = {body: 'get services error [' + response.data.msg + ']', show: true, type: 'error'};
                    return;
                }
                if(typeof(Stack) == 'undefined'){
                    state.services.data = response.data;
                }else{
                    for(var i=0; i<response.data.length; i++){
                        if(response.data[i].Stack === Stack){
                            state.services.data.push(response.data[i]);
                        }
                    }
                }
                state.services.loading = false;
            }catch(e){
                state.message = {body: 'get services error [' + e.message + ']', show: true, type: 'error'};
            }
        },
        async createService(state, editItem){
            try{
                var response = await axios({
                    method: 'post',
                    url: state.endpoint + SERVICES_CREATE_PATH,
                    data: editItem
                });
                if(response.status == 299){
                    state.message = {body: 'create service error [' + response.data.msg + ']', show: true, type: 'error'};
                    return;
                }

                state.message = {body: 'create service success', show: true, type: 'success'};
                await this.commit('getServices');
            }catch(e){
                state.message = {body: 'create service error [' + e.message + ']', show: true, type: 'error'};
            }
        },
        async removeService(state, items){
            for(let i=0; i< items.length; i++){
                try{
                    var response = await axios({
                        method: 'delete',
                        url: state.endpoint + SERVICES_REMOVE_PATH + items[i].Id
                    });
                    if(response.status == 299){
                        state.message = {body: 'remove service error [' + response.data.msg + ']', show: true, type: 'error'};
                        return;
                    }

                }catch(e){
                    state.message = {body: 'remove service error [' + e.message + ']', show: true, type: 'error'};
                }
            }

            await this.commit('getServices');
        },
        //Network
        async getNetworks (state){
            state.networks.loading = true;
            state.networks.data = [];

            try{
                var response = await axios({
                    method: 'get',
                    url: state.endpoint + NETWORKS_PATH
                });
                if(response.status == 299){
                    state.message = {body: 'get networks error [' + response.data.msg + ']', show: true, type: 'error'};
                    return;
                }

                state.networks.data = response.data;
                state.networks.loading = false;
            }catch(e){
                state.message = {body: 'get networks error [' + e.message + ']', show: true, type: 'error'};
            }
        },
        async createNetwork(state, editItem){
            try{
                var response = await axios({
                    method: 'post',
                    url: state.endpoint + NETWORKS_CREATE_PATH,
                    data: editItem
                });
                if(response.status == 299){
                    state.message = {body: 'create network error [' + response.data.msg + ']', show: true, type: 'error'};
                    return;
                }

                state.message = {body: 'create network success', show: true, type: 'success'};
                await this.commit('getNetworks');
            }catch(e){
                state.message = {body: 'create network error [' + e.message + ']', show: true, type: 'error'};
            }
        },
        async removeNetwork(state, items){
            for(let i=0; i< items.length; i++){
                try{
                    var response = await axios({
                        method: 'delete',
                        url: state.endpoint + NETWORKS_REMOVE_PATH + items[i].Id
                    });
                    if(response.status == 299){
                        state.message = {body: 'remove network error [' + response.data.msg + ']', show: true, type: 'error'};
                        return;
                    }

                }catch(e){
                    state.message = {body: 'remove network error [' + e.message + ']', show: true, type: 'error'};
                }
            }

            await this.commit('getNetworks');
        },
        //Compose
        async getComposes (state){
            state.composes.loading = true;
            state.composes.data = [];
            try{
                var response = await axios({
                    method: 'get',
                    url: state.endpoint + COMPOSES_PATH
                });

                if(response.status == 299){
                    state.message = {body: 'get composes error [' + response.data.msg + ']', show: true, type: 'error'};
                    return;
                }
                state.composes.data = response.data;
                for(var i=0; i<state.composes.data.length; i++){
                    state.composes.selectData.push(state.composes.data[i].Name);
                }

                state.composes.loading = false;
            }catch(e){
                state.message = {body: 'get composes error [' + e.message + ']', show: true, type: 'error'};
            }
        },
        async createCompose(state, editItem){
            try{
                var response = await axios({
                    headers: {'Content-Type': 'application/json; charset=UTF-8', 'Accept': 'application/json'},
                    method: 'post',
                    url: state.endpoint + COMPOSES_CREATE_PATH,
                    data: editItem
                });
                if(response.status == 299){
                    state.message = {body: 'create compose error [' + response.data.msg + ']', show: true, type: 'error'};
                    return;
                }
                state.message = {body: 'create compose success', show: true, type: 'success'};
                await this.commit('getComposes');
            }catch(e){
                state.message = {body: 'create compose error [' + e.message + ']', show: true, type: 'error'};
            }
        },
        async updateCompose(state, editItem){
            try{
                var response = await axios({
                    headers: {'Content-Type': 'application/json; charset=UTF-8', 'Accept': 'application/json'},
                    method: 'post',
                    url: state.endpoint + COMPOSES_UPDATE_PATH + editItem.Name,
                    data: editItem
                });
                if(response.status == 299){
                    state.message = {body: 'update compose error [' + response.data.msg + ']', show: true, type: 'error'};
                    return;
                }
                state.message = {body: 'update compose success', show: true, type: 'success'};
                await this.commit('getComposes');
            }catch(e){
                state.message = {body: 'update compose error [' + e.message + ']', show: true, type: 'error'};
            }
        },
        async removeCompose(state, items){
            for (let i = 0; i < items.length; i++) {
                try {
                    var response = await axios({
                        method: 'delete',
                        url: state.endpoint + COMPOSES_REMOVE_PATH + items[i].Name
                    });
                    if(response.status == 299){
                        state.message = {body: 'remove composes error [' + response.data.msg + ']', show: true, type: 'error'};
                        return;
                    }
                }catch(e){
                    state.message = {body: 'remove composes error [' + e.message + ']', show: true, type: 'error'};
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

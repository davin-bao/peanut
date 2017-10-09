<style lang="less">
    .create-dialog-save-btn {
        bottom: 14px!important;
    }
</style>
<template>
    <div>
        <v-container fluid grid-list-md>
            <v-layout row>
                <v-flex xs12 md6 lg4 xl3 v-if="nodes.loading!=true" v-for="item in nodes.data" :key="nodes.data.ID">
                    <v-card>
                        <v-card-title primary-title>
                            <h6 class=" mb-0">{{ item.Spec.Name !== '' ? item.Spec.Name : item.Description.Hostname }}</h6>
                            <div>Docker Version: {{ item.Description.Engine.EngineVersion }}</div>
                        </v-card-title>
                        <v-card-text>
                            <div class="text-xs-left">
                                <v-chip label class="primary white--text" v-if="item.ManagerStatus.Leader">Leader</v-chip>
                                <v-chip label class="success white--text">{{ item.Status.State }}</v-chip>
                            </div>
                            <div>
                                <v-divider></v-divider>
                                <v-layout row  v-if="nodes.statusLoading!=true">
                                    <v-flex xs12 md6 lg6 xl6 class="text-xs-center">
                                        <v-progress-circular
                                                v-bind:size="100"
                                                v-bind:width="15"
                                                v-bind:rotate="90"
                                                v-bind:value="item.status.cpu"
                                        >
                                            CPU<br/> {{ item.status.cpu }} %
                                        </v-progress-circular>
                                    </v-flex>
                                    <v-flex xs12 md6 lg6 xl6 class="text-xs-center">
                                        <v-progress-circular
                                                v-bind:size="100"
                                                v-bind:width="15"
                                                v-bind:rotate="90"
                                                v-bind:value="item.status.memory"
                                        >
                                            Memory<br/> {{ item.status.memory }} %
                                        </v-progress-circular>
                                    </v-flex>
                                </v-layout>
                            </div>
                            <v-divider></v-divider>
                            <div class="text-xs-left">
                                <v-chip label class="pink white--text" v-for="(value, index) in item.Spec.Labels" :key="value"> {{ index + '=' + value }} </v-chip>
                            </div>
                            <v-divider></v-divider>
                            <p></p>
                            <p class="caption">CPU Core: {{ item.Description.Resources.NanoCPUs / 1000000000 }}</p>
                            <p class="caption">Memory(G): {{ Math.round(item.Description.Resources.MemoryBytes / (1024*1024*1024)) }}</p>
                            <p class="caption">Platform: {{ item.Description.Platform.OS }} {{ item.Description.Platform.Architecture }}</p>
                            <v-divider></v-divider>
                            <p></p>
                            <p class="caption">Address: {{ item.Status.Addr }}</p>
                            <p class="caption">Manager address: {{ item.ManagerStatus.Addr }}</p>
                            <p class="caption">Role: {{ item.Spec.Role }}</p>
                            <p class="caption">Availability: {{ item.Spec.Availability }}</p>
                            <p class="caption">Reachability: {{ item.ManagerStatus.Reachability }}</p>
                            <v-divider></v-divider>
                            <v-data-table
                                    v-bind:loading="containers.loading===true"
                                    v-bind:headers="containerHeaders"
                                    v-bind:items="item.containers"
                                    hide-actions
                            >
                                <template slot="items" scope="props">
                                    <td class="text-xs-left"><v-substr :value="props.item.Names[0]"></v-substr></td>
                                    <td class="text-xs-right">
                                        <v-icon v-if="props.item.State=='running'" class="green--text">access_time</v-icon>
                                        <v-icon v-else-if="props.item.State=='stop'" class="red--text">stop</v-icon>
                                        <v-progress-circular v-else indeterminate class="amber--text" size="24"></v-progress-circular>
                                    </td>
                                </template>
                            </v-data-table>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn flat class="orange--text" :to="'/node/'+item.ID"><v-icon>visibility</v-icon>DETAIL</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-flex>
                <v-flex xs12 md6 lg4 xl3>
                    <v-card>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-progress-circular  v-if="nodes.loading===true" indeterminate v-bind:size="70" v-bind:width="7"></v-progress-circular>
                            <v-btn v-else flat class="orange--text" @click.native="showNodeCreateDialog"><v-icon>add</v-icon>Add Node</v-btn>
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
        <v-layout row justify-center>
            <v-dialog v-model="nodeCreateCommand.show" v-if="nodeCreateCommand.show" width="50%" :fullscreen="this.$parent.$data.clientWidth<770" transition="dialog-bottom-transition" :overlay=false>
                <v-btn primary dark slot="activator">{{ $t('nouns.node_create') }}</v-btn>
                <v-card>
                    <v-toolbar dark class="primary">
                        <v-btn icon @click.native="nodeCreateCommand.show = false">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>{{ $t('nouns.node_create') }}</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                        </v-toolbar-items>
                    </v-toolbar>
                    <v-container fluid grid-list-md>
                        <v-layout row wrap>
                            <v-flex d-flex xs12 sm12 md12>
                                <v-subheader>Add As Worker Node</v-subheader>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md12>
                                <v-card>
                                    <v-card-text>
                                        <v-progress-circular  v-if="nodeCreateCommand.loading===true" indeterminate v-bind:size="25" v-bind:width="3"></v-progress-circular>
                                        <p v-else>{{ nodeCreateCommand.data.workCommand }}</p>
                                    </v-card-text>
                                </v-card>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md12>
                                <v-subheader>Add As Manager Node</v-subheader>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md12>
                                <v-card>
                                    <v-card-text>
                                        <v-progress-circular  v-if="nodeCreateCommand.loading===true" indeterminate v-bind:size="25" v-bind:width="3"></v-progress-circular>
                                        <p v-else>{{ nodeCreateCommand.data.managerCommand }}</p>
                                    </v-card-text>
                                </v-card>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md12>
                                <p class="text-xs-right">Run the command in target machine</p>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card>
            </v-dialog>
        </v-layout>
    </div>
</template>
<script>
    import { mapState } from 'vuex';
    export default {
        data () {
            return {
                containerHeaders: [
                    {
                        text: 'Name',
                        align: 'left',
                        sortable: false,
                        value: 'Names'
                    },
                    {
                        text: 'State',
                        align: 'right',
                        sortable: false,
                        value: 'State'
                    }
                ],
                items: [
                    {
                        status: 'stop',
                        name: 'Frozen Yogurt'
                    },
                    {
                        status: 'running',
                        name: 'Ice cream sandwich'
                    },
                    {
                        status: 'created',
                        name: 'Created sandwich'
                    }
                ],
                newItem: {
                    show: false
                }
            }
        },
        methods: {
            showNodeCreateDialog () {
                this.nodeCreateCommand.show = true;
                this.$store.commit('getNodeCreateCommand');
            }
        },
        mounted () {
            this.$store.commit('setBreadcrumbs', [{ text: 'menu.home'}, { text: 'menu.dashboard'}]);
            this.$store.commit('getNodes');
        },
        computed: {
            ...mapState(['message', 'nodes', 'containers', 'nodeCreateCommand'])
        }
    }
</script>

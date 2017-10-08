<template>
    <v-card>
        <v-container fluid grid-list-md>
            <v-layout row>
                <template>
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
                        <v-card v-if="nodes.loading===true">
                            <v-card-text>
                                <v-progress-circular indeterminate v-bind:size="70" v-bind:width="7"></v-progress-circular>
                            </v-card-text>
                        </v-card>
                        <v-card v-else>
                            <v-card-title primary-title>
                                <h6 class=" mb-0">New Node</h6>
                                <div></div>
                            </v-card-title>
                            <v-card-text>

                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn flat class="orange--text"><v-icon>visibility</v-icon>DETAIL</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </template>
            </v-layout>
        </v-container>
        <v-layout row justify-center>
            <v-dialog v-model="newItem.show" v-if="newItem.show" width="50%" :fullscreen="this.$parent.$data.clientWidth<770" transition="dialog-bottom-transition" :overlay=false>
                <v-btn primary dark slot="activator">{{ $t('nouns.node_create') }}</v-btn>

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
            showNodeDetail (id) {
                console.log(id);
            }
        },
        mounted () {
            this.$store.commit('setBreadcrumbs', [{ text: 'menu.home'}, { text: 'menu.dashboard'}]);
            this.$store.commit('getNodes');
        },
        computed: {
            ...mapState(['message', 'nodes', 'containers'])
        }
    }
</script>

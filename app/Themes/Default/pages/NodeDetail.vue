<style scoped>
    .content-toolbar {
        background-color: rgba(0,0,0,0)!important;
    }
</style>
<template>
    <div>
        <v-container fluid grid-list-md>
        <v-layout row wrap>
            <v-flex d-flex xs12 sm12 md12 v-bind='node' v-if="node.loading===true">
                <div><v-progress-circular indeterminate v-bind:size="70" v-bind:width="7"></v-progress-circular></div>
            </v-flex>
            <template v-if="node.loading===false">
                <v-flex d-flex xs12 sm12 md12>
                    <v-divider></v-divider>
                </v-flex>
                <v-flex d-flex xs12 sm12 md12>
                    <v-toolbar flat small class="content-toolbar">
                        <v-spacer></v-spacer>
                        <v-btn icon @click.active="showEditDialog(node.data)">
                            <v-icon>edit</v-icon>
                        </v-btn>
                        <v-btn icon @click.active="refreshAll">
                            <v-icon>refresh</v-icon>
                        </v-btn>
                    </v-toolbar>
                </v-flex>
                <v-flex d-flex xs12 sm12 md12>
                    <v-card flat>
                        <v-card-text>
                            <v-subheader>Node specification</v-subheader>
                            <v-layout row wrap>
                                <v-flex d-flex xs12 sm6 md4>
                                    <p class="caption">Name: {{ node.data.Spec.Name }}</p>
                                </v-flex>
                                <v-flex d-flex xs12 sm6 md4>
                                    <p class="caption">Host Name: {{ node.data.Description.Hostname }}</p>
                                </v-flex>
                                <v-flex d-flex xs12 sm6 md4>
                                    <p class="caption">Role: {{ node.data.Spec.Role }}</p>
                                </v-flex>
                                <v-flex d-flex xs12 sm6 md4>
                                    <p class="caption">Availability: {{ node.data.Spec.Availability }}</p>
                                </v-flex>
                                <v-flex d-flex xs12 sm6 md4>
                                    <p class="caption">Status: <v-chip label class="success white--text">{{ node.data.Status.State }}</v-chip></p>
                                </v-flex>
                            </v-layout>
                            <v-subheader>Node description</v-subheader>
                            <v-layout row wrap>
                                <v-flex d-flex xs12 sm6 md4>
                                    <p class="caption">CPU Core: {{ node.data.Description.Resources.NanoCPUs / 1000000000 }}</p>
                                </v-flex>
                                <v-flex d-flex xs12 sm6 md4>
                                    <p class="caption">Memory(G): {{ Math.round(node.data.Description.Resources.MemoryBytes / (1024*1024*1024)) }}</p>
                                </v-flex>
                                <v-flex d-flex xs12 sm6 md4>
                                    <p class="caption">Platform: {{ node.data.Description.Platform.OS }} {{ node.data.Description.Platform.Architecture }}</p>
                                </v-flex>
                                <v-flex d-flex xs12 sm6 md4>
                                    <p class="caption">Docker Version: {{ node.data.Description.Engine.EngineVersion }}</p>
                                </v-flex>
                            </v-layout>
                            <v-subheader>Manager status</v-subheader>
                            <v-layout row wrap>
                                <v-flex d-flex xs12 sm6 md4>
                                    <p class="caption">Leader: <v-chip label class="primary white--text" v-if="node.data.ManagerStatus.Leader">Leader</v-chip></p>
                                </v-flex>
                                <v-flex d-flex xs12 sm6 md4>
                                    <p class="caption">Reachability: {{ node.data.ManagerStatus.Reachability }}</p>
                                </v-flex>
                                <v-flex d-flex xs12 sm6 md4>
                                    <p class="caption">Manager address: {{ node.data.ManagerStatus.Addr }}</p>
                                </v-flex>
                                <v-flex d-flex xs12 sm6 md4>
                                    <p class="caption">Labels:
                                        <template v-for="(value, index) in node.data.Spec.Labels">
                                            <v-chip label class="white--text">{{ index }}: {{ value }}</v-chip>
                                        </template>
                                    </p>
                                </v-flex>
                            </v-layout>
                            <v-subheader>Monitoring</v-subheader>
                            <v-layout row wrap>
                                <v-flex d-flex xs12 sm6 md6>
                                    <vue-chart type="line" :data="cpu.chartData"></vue-chart>
                                </v-flex>
                                <v-flex d-flex xs12 sm6 md6>
                                    <vue-chart type="line" :data="memory.chartData"></vue-chart>
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </template>
        </v-layout>
    </v-container>
        <v-layout row justify-center>
            <v-dialog v-model="editItem.show" v-if="editItem.show" width="50%" :fullscreen="this.$parent.$data.clientWidth<770" transition="dialog-bottom-transition" :overlay=false>
                <v-btn primary slot="activator">{{ $t('nouns.node_update') }}</v-btn>
                <v-card>
                    <v-toolbar class="primary">
                        <v-btn icon @click.native="editItem.show = false">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>{{ $t('nouns.node_update') }}</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                            <v-btn flat @click.native="createAction()">{{ $t('nouns.save') }}</v-btn>
                        </v-toolbar-items>
                    </v-toolbar>
                    <v-container fluid grid-list-md>
                        <v-layout row wrap>
                            <v-flex d-flex xs12 sm3 md2>
                                <v-subheader>Name</v-subheader>
                            </v-flex>
                            <v-flex d-flex xs12 sm9 md10>
                                <v-text-field
                                        label="Name"
                                        v-model="editItem.Name"
                                        :rules="[() => editItem.Name.length > 0 || $t('validate.required')]"
                                        required
                                ></v-text-field>
                            </v-flex>
                            <v-flex d-flex xs12 sm3 md2>
                                <v-subheader>Role</v-subheader>
                            </v-flex>
                            <v-flex d-flex xs12 sm9 md10>
                                <v-select
                                        v-bind:items="editItem.RoleSelectItems"
                                        v-model="editItem.Role"
                                        label="Select"
                                        single-line
                                        bottom
                                ></v-select>
                            </v-flex>
                            <v-flex d-flex xs12 sm3 md2>
                                <v-subheader>Availability</v-subheader>
                            </v-flex>
                            <v-flex d-flex xs12 sm9 md10>
                                <v-select
                                        v-bind:items="editItem.AvailabilitySelectItems"
                                        v-model="editItem.Availability"
                                        label="Select"
                                        single-line
                                        bottom
                                ></v-select>
                            </v-flex>
                            <v-flex d-flex xs12 sm3 md2>
                                <v-layout row wrap>
                                    <v-flex xs6>
                                        <v-subheader>Labels</v-subheader>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-btn @click="editItem.Labels.push({name: '', value: ''})" icon>
                                            <v-icon>add</v-icon>
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md12>
                                <v-layout row wrap>
                                    <template  v-for="(item, index) in editItem.Labels">
                                        <v-flex d-flex xs3>
                                            <v-text-field
                                                    label="Name"
                                                    v-model="editItem.Labels[index].name"
                                            ></v-text-field>
                                        </v-flex>
                                        <v-flex d-flex xs3>
                                            <v-text-field
                                                    label="Value"
                                                    v-model="editItem.Labels[index].value"
                                            ></v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-btn @click="editItem.Labels.length>1 && editItem.Labels.splice(index,1)" icon>
                                                <v-icon>remove</v-icon>
                                            </v-btn>
                                        </v-flex>
                                    </template>
                                </v-layout>
                            </v-flex>
                            <v-flex xs12 offset-xs0 offset-sm2>
                                &nbsp;
                                <v-btn @click="createAction()" primary
                                       absolute
                                       bottom
                                       right
                                       class="create-dialog-save-btn"
                                >SAVE</v-btn>
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
    import VueChart from 'vue-chart-js';
    import Util from '../libs/util';

    export default {
        data () {
            return {
                intervalId: null,
                chartOptions: {},
                cpu: {
                    dataBackgroundColor: null,
                    dataBorderColor: null,
                    chartData: {},
                    labels: [],
                    data: [],
                    count: 50
                },
                memory: {
                    dataBackgroundColor: null,
                    dataBorderColor: null,
                    chartData: {},
                    chartData: {},
                    labels: [],
                    data: [],
                    count: 50
                },
                editItem: {
                    show: false,
                    ID: '',
                    Name: '',
                    Role: 'manager',
                    RoleSelectItems: ['manager', 'worker'],
                    Availability: 'active',
                    AvailabilitySelectItems: ['active', 'pause', 'drain'],
                    Labels: []
                }
            }
        },
        watch: {
        },
        mounted () {
            this.$store.commit('setBreadcrumbs', [{ text: 'menu.home'}, { text: 'nouns.node_detail'}]);
            this.refreshAll();
            this.monitor();
            this.intervalId = setInterval(this.change, 5000);
        },
        methods: {
            refreshAll() {
                this.$store.commit('getNode', this.$route.params.id);
            },
            change() {
                if(!this.node.data.ManagerStatus){
                    return;
                }
                this.$store.commit('getNodeStatus', this.node.data.ManagerStatus.Addr);
                if(this.nodeStatus.length <= 0){
                    return;
                }

                var chartCpuLabels = this.cpu.chartData.labels;
                var chartMemoryLabels = this.memory.chartData.labels;
                var chartCpuData = this.cpu.chartData.datasets[0].data;
                var chartMemoryData = this.memory.chartData.datasets[0].data;
                for(var i=0; i<this.nodeStatus.length; i++){
                    chartCpuLabels.push(this.nodeStatus[i].key);
                    chartCpuData.push(this.nodeStatus[i].value.cpu);
                    chartMemoryLabels.push(this.nodeStatus[i].key);
                    chartMemoryData.push(this.nodeStatus[i].value.memFree * 100/this.nodeStatus[i].value.memTotal);
                }

                this.cpu.chartData.labels = chartCpuLabels.length > this.cpu.count ? chartCpuLabels.splice(chartCpuLabels.length - this.cpu.count, chartCpuLabels.length) : chartCpuLabels;
                this.memory.chartData.labels = chartMemoryLabels.length > this.cpu.count ? chartMemoryLabels.splice(chartMemoryLabels.length - this.cpu.count, chartMemoryLabels.length) : chartMemoryLabels;
                this.cpu.chartData.datasets[0].data = chartCpuData.length > this.cpu.count ? chartCpuData.splice(chartCpuData.length - this.cpu.count, chartCpuData.length) : chartCpuData;
                this.memory.chartData.datasets[0].data = chartMemoryData.length > this.memory.count ? chartMemoryData.splice(chartMemoryData.length - this.memory.count, chartMemoryData.length) : chartMemoryData;
            },
            monitor() {
                this.cpu.backgroundColor = Util.randomColorRgba(0.4);
                this.cpu.borderColor = this.cpu.backgroundColor.substr(0, this.cpu.backgroundColor - 4) + '1)';
                this.memory.backgroundColor = Util.randomColorRgba(0.4);
                this.memory.borderColor = this.memory.backgroundColor.substr(0, this.memory.backgroundColor - 4) + '1)';

                for(var i=0; i<this.cpu.count-1; i++){
                    this.cpu.labels.push(0);
                    this.cpu.data.push(0);
                    this.memory.labels.push(0);
                    this.memory.data.push(0);
                }
                this.chartOptions = {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                };

                this.cpu.chartData = {
                    labels: this.cpu.labels,
                    datasets: [
                        {
                            label: 'CPU(%)',
                            data: this.cpu.data,
                            backgroundColor: this.cpu.backgroundColor,
                            borderColor: this.cpu.borderColor,
                            borderWidth: 1
                        }
                    ],
                    options: this.chartOptions
                };

                this.memory.chartData = {
                    labels: this.memory.labels,
                    datasets: [
                        {
                            label: 'Memory(%)',
                            data: this.memory.data,
                            backgroundColor: this.memory.backgroundColor,
                            borderColor: this.memory.borderColor,
                            borderWidth: 1
                        }
                    ],
                    options: this.chartOptions
                };

            },
            showEditDialog(item) {
                this.editItem.show = true;
                this.editItem.ID = item.ID;
                this.editItem.Name = typeof(item.Spec.Name) === 'undefined' ? '' : item.Spec.Name;
                this.editItem.Role = item.Spec.Role;
                this.editItem.Availability = item.Spec.Availability;
                this.editItem.Labels = item.Spec.Labels;
            },
            createAction() {
                this.$store.commit('updateNode', this.editItem);
                this.editItem.show = false;
            }
        },
        computed: {
            ...mapState(['node', 'nodeStatus'])
        },
        beforeDestroy:function(){
            if(this.intervalId !== null){
                clearInterval(this.intervalId);
            }
        }
    }
</script>


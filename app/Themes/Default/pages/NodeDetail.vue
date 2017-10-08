<style scoped>
    .content-toolbar {
        background-color: rgba(0,0,0,0)!important;
    }
</style>
<template>
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
                        <v-btn icon>
                            <v-icon>edit</v-icon>
                        </v-btn>
                        <v-btn icon @click="removeAction(node)">
                            <v-icon>delete</v-icon>
                        </v-btn>
                        <v-btn icon>
                            <v-icon>refresh</v-icon>
                        </v-btn>
                    </v-toolbar>
                </v-flex>
                <v-flex d-flex xs12 sm6 md4>
                    <v-card flat>
                        <v-card-title class="title">Node specification</v-card-title>
                        <v-card-text>
                            <p class="caption">Name: {{ node.data.Spec.Name }}</p>
                            <p class="caption">Host Name: {{ node.data.Description.Hostname }}</p>
                            <p class="caption">Role: {{ node.data.Spec.Role }}</p>
                            <p class="caption">Availability: {{ node.data.Spec.Availability }}</p>
                            <p class="caption">Status: <v-chip label class="success white--text">{{ node.data.Status.State }}</v-chip></p>
                        </v-card-text>
                    </v-card>
                </v-flex>
                <v-flex d-flex xs12 sm6 md4>
                    <v-card flat>
                        <v-card-title class="title">Manager status</v-card-title>
                        <v-card-text>
                            <p class="caption">Leader: <v-chip label class="primary white--text" v-if="node.data.ManagerStatus.Leader">Leader</v-chip></p>
                            <p class="caption">Reachability: {{ node.data.ManagerStatus.Reachability }}</p>
                            <p class="caption">Manager address: {{ node.data.ManagerStatus.Addr }}</p>
                        </v-card-text>
                    </v-card>
                </v-flex>
                <v-flex d-flex xs12 sm6 md4>
                    <v-card flat>
                        <v-card-title class="title">Node description</v-card-title>
                        <v-card-text>
                            <p class="caption">CPU Core: {{ node.data.Description.Resources.NanoCPUs / 1000000000 }}</p>
                            <p class="caption">Memory(G): {{ Math.round(node.data.Description.Resources.MemoryBytes / (1024*1024*1024)) }}</p>
                            <p class="caption">Platform: {{ node.data.Description.Platform.OS }} {{ node.data.Description.Platform.Architecture }}</p>
                            <p class="caption">Docker Version: {{ node.data.Description.Engine.EngineVersion }}</p>
                        </v-card-text>
                    </v-card>
                </v-flex>
                <v-flex d-flex xs12 sm6 md4>
                    <v-card flat>
                        <v-card-title class="title">Labels</v-card-title>
                        <v-card-text v-for="(value, index) in node.data.Spec.Labels" :key="value">
                            <p class="caption">{{ index }}: {{ value }}</p>
                        </v-card-text>
                    </v-card>
                </v-flex>
                <v-flex d-flex xs12 sm6 md4>
                    <v-card flat>
                        <v-card-title class="title">CPU</v-card-title>
                        <v-card-text>
                            <vue-chart type="line" :data="cpu.chartData"></vue-chart>
                        </v-card-text>
                    </v-card>
                </v-flex>
                <v-flex d-flex xs12 sm6 md4>
                    <v-card flat>
                        <v-card-title class="title">Memory</v-card-title>
                        <v-card-text>
                            <vue-chart type="line" :data="memory.chartData"></vue-chart>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </template>
        </v-layout>
    </v-container>
</template>
<script>
    import { mapState } from 'vuex';
    import VueChart from 'vue-chart-js';
    import Util from '../libs/util';

    export default {
        data () {
            return {
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
                }
            }
        },
        watch: {
        },
        mounted () {
            this.$store.commit('setBreadcrumbs', [{ text: 'menu.home'}, { text: 'nouns.node_detail'}]);
            this.$store.commit('getNode', this.$route.params.id);
            this.monitor();
            setInterval(this.change, 5000);
        },
        methods: {
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
                for(var i=0; i<this.cpu.count; i++){
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
                            label: '%',
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
                            label: '%',
                            data: this.memory.data,
                            backgroundColor: this.memory.backgroundColor,
                            borderColor: this.memory.borderColor,
                            borderWidth: 1
                        }
                    ],
                    options: this.chartOptions
                };

            },
            removeAction(item) {
                var self = this;
                this.$store.commit('showDeleteConfirm', function(){
                    self.$store.commit('removeNode', [item]);
                });
            }
        },
        computed: {
            ...mapState(['node', 'nodeStatus'])
        },
        destoryed (){
            console.log('=======beforeDestory');
        },
    }
</script>


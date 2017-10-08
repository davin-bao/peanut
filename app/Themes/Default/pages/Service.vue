<style lang="less">
    .content-toolbar {
        background-color: rgba(0,0,0,0)!important;
        .toolbar__content {
            height: auto!important;
        }
    }
    .create-dialog-save-btn {
        bottom: 14px!important;
    }
</style>
<template>
    <div>
        <v-card>
            <v-card-title>
                <v-spacer></v-spacer>
                <v-toolbar flat class="content-toolbar">
                    <v-spacer></v-spacer>
                    <v-btn @click="newItem.show=true" icon>
                        <v-icon>add</v-icon>
                    </v-btn>
                    <v-btn @click="removeAllAction()" icon>
                        <v-icon>delete</v-icon>
                    </v-btn>
                    <v-btn @click="refreshAll()" icon>
                        <v-icon>refresh</v-icon>
                    </v-btn>
                </v-toolbar>
            </v-card-title>
            <v-data-table
                    v-bind:headers="headers"
                    v-bind:items="services.data"
                    v-bind:search="search"
                    v-bind:pagination.sync="pagination"
                    :total-items="totalItems"
                    :loading="services.loading"
                    :rows-per-page-items="pageCount"
                    v-model="selected"
                    select-all
                    selected-key="Id"
            >
                <template slot="headerCell" scope="props">
                <span v-tooltip:bottom="{ 'html': props.header.text }">
                  {{ props.header.text }}
                </span>
                </template>
                <template slot="items" scope="props">
                    <v-checkbox
                            primary
                            hide-details
                            v-model="props.selected"
                    ></v-checkbox>
                    <td>{{ props.item.Spec.Name }}</td>
                    <td>{{ props.item.Image }}</td>
                    <td>{{ props.item.Spec.Mode.Replicated.Replicas }}</td>
                    <td>{{ props.item.PublishedPorts }}</td>
                    <td>{{ props.item.Stack }}</td>
                    <td>{{ props.item.UpdatedAt }}</td>
                    <td  class="text-xs-right">
                        <v-btn-toggle small>
                            <v-btn flat primary>
                                <v-icon>visibility</v-icon>
                            </v-btn>
                            <v-btn flat error @click="removeAction(props.item)">
                                <v-icon>delete</v-icon>
                            </v-btn>
                        </v-btn-toggle>
                    </td>
                </template>
            </v-data-table>
        </v-card>
        <v-layout row justify-center>
            <v-dialog v-model="newItem.show" v-if="newItem.show" width="50%" :fullscreen="this.$parent.$data.clientWidth<770" transition="dialog-bottom-transition" :overlay=false>
                <v-btn primary dark slot="activator">{{ $t('nouns.service_create') }}</v-btn>
                <v-card>
                    <v-toolbar dark class="primary">
                        <v-btn icon @click.native="newItem.show = false" dark>
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>{{ $t('nouns.service_create') }}</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                            <v-btn dark flat @click.native="createAction()">{{ $t('nouns.save') }}</v-btn>
                        </v-toolbar-items>
                    </v-toolbar>
                    <v-container fluid grid-list-md>
                        <v-layout row wrap>
                            <v-flex d-flex xs12 sm12 md12>
                                <v-subheader>Basic configuration</v-subheader>
                            </v-flex>
                            <v-flex d-flex xs12 sm3 md2>
                                <v-subheader>Name</v-subheader>
                            </v-flex>
                            <v-flex d-flex xs12 sm9 md10>
                                <v-text-field
                                        label="Name"
                                        v-model="newItem.Name"
                                        :rules="[() => newItem.Name.length > 0 || $t('validate.required')]"
                                        required
                                ></v-text-field>
                            </v-flex>
                            <v-flex d-flex xs12 sm3 md2>
                                <v-subheader>Driver</v-subheader>
                            </v-flex>
                            <v-flex d-flex xs12 sm9 md10>
                                <v-select
                                        v-bind:items="newItem.DirverSelectItems"
                                        v-model="newItem.Driver"
                                        label="Select"
                                        single-line
                                        bottom
                                ></v-select>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md6 lg4>
                                <v-switch primary label="CheckDuplicate" v-model="newItem.CheckDuplicate"></v-switch>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md6 lg4>
                                <v-switch primary label="Internal" v-model="newItem.Internal"></v-switch>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md6 lg4>
                                <v-switch primary label="Attachable" v-model="newItem.Attachable"></v-switch>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md6 lg4>
                                <v-switch primary label="Ingress" v-model="newItem.Ingress"></v-switch>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md6 lg4>
                                <v-switch primary label="EnableIPv6" v-model="newItem.EnableIPv6"></v-switch>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md12>
                                <v-subheader>Service configuration</v-subheader>
                            </v-flex>
                            <v-flex d-flex xs12 sm3 md2>
                                <v-subheader>Subnet</v-subheader>
                            </v-flex>
                            <v-flex d-flex xs12 sm9 md10>
                                <v-text-field
                                        label="Subnet"
                                        v-model="newItem.Subnet"
                                        :rules="['e.g. 172.20.0.0/16']"
                                ></v-text-field>
                            </v-flex>
                            <v-flex d-flex xs12 sm3 md2>
                                <v-subheader>Gateway</v-subheader>
                            </v-flex>
                            <v-flex d-flex xs12 sm9 md10>
                                <v-text-field
                                        label="Gateway"
                                        v-model="newItem.Gateway"
                                        :rules="['e.g. 172.20.10.11']"
                                ></v-text-field>
                            </v-flex>
                            <v-flex d-flex xs12 sm3 md2>
                                <v-layout row wrap>
                                    <v-flex xs6>
                                        <v-subheader>Options</v-subheader>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-btn @click="newItem.Options.push({name: '', value: ''})" icon>
                                            <v-icon>add</v-icon>
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex xs12 sm9 md10>
                                <v-layout row wrap>
                                    <template  v-for="(item, index) in newItem.Options">
                                        <v-flex d-flex xs3>
                                            <v-text-field
                                                    label="Name"
                                                    v-model="newItem.Options[index].name"
                                            ></v-text-field>
                                        </v-flex>
                                        <v-flex d-flex xs3>
                                            <v-text-field
                                                    label="Value"
                                                    v-model="newItem.Options[index].value"
                                            ></v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-btn @click="newItem.Options.length>1 && newItem.Options.splice(index,1)" icon>
                                                <v-icon>remove</v-icon>
                                            </v-btn>
                                        </v-flex>
                                    </template>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex xs12 sm3 md2>
                                <v-layout row wrap>
                                    <v-flex xs6>
                                        <v-subheader>Labels</v-subheader>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-btn @click="newItem.Labels.push({name: '', value: ''})" icon>
                                            <v-icon>add</v-icon>
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md12>
                                <v-layout row wrap>
                                    <template  v-for="(item, index) in newItem.Labels">
                                        <v-flex d-flex xs3>
                                            <v-text-field
                                                    label="Name"
                                                    v-model="newItem.Labels[index].name"
                                            ></v-text-field>
                                        </v-flex>
                                        <v-flex d-flex xs3>
                                            <v-text-field
                                                    label="Value"
                                                    v-model="newItem.Labels[index].value"
                                            ></v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-btn @click="newItem.Labels.length>1 && newItem.Labels.splice(index,1)" icon>
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

    export default {
        data () {
            return {
                search: '',
                selected: [],
                totalItems: 0,
                pagination: {
                },
                pageCount: [20, 50, 100, { text: 'All', value: -1 }],
                headers: [
                    {
                        text: 'Name',
                        align: 'left',
                        sortable: true,
                        value: 'Name'
                    },
                    {
                        text: 'Image',
                        align: 'left',
                        sortable: true,
                        value: 'Image'
                    },
                    {
                        text: 'Scheduling mode',
                        align: 'left',
                        sortable: false,
                        value: 'SchedulingMode'
                    },
                    {
                        text: 'Published Ports',
                        align: 'left',
                        sortable: false,
                        value: 'PublishedPorts'
                    },
                    {
                        text: 'Stack',
                        align: 'left',
                        sortable: true,
                        value: 'Stack'
                    },
                    {
                        text: 'UpdatedAt',
                        align: 'left',
                        sortable: true,
                        value: 'UpdatedAt'
                    }
                ],
                items: [],
                newItem: {
                    show: false,
                    Name: '',
                    CheckDuplicate: false,
                    Driver: 'overlay',
                    DirverSelectItems: ['overlay', 'bridge'],
                    Internal: false,
                    Attachable: false,
                    Ingress: false,
                    EnableIPv6: false,
                    Subnet: '',
                    Gateway: '',
                    Options: [],
                    Labels: []
                }
            }
        },
        watch: {

        },
        mounted () {
            this.$store.commit('setBreadcrumbs', [{ text: 'menu.home'}, { text: 'menu.service'}]);
            this.refreshAll();
        },
        methods: {
            removeAllAction() {
                var self = this;
                if(self.selected.length == 0) return;
                this.$store.commit('showDeleteConfirm', function(){
                    self.$store.commit('removeService', self.selected);
                });
            },
            refreshAll() {
                this.$store.commit('getServices', this.$route.params.Stack);
            },
            showAddDialog() {
                this.newItem.show = true;
            },
            createAction() {
                this.$store.commit('createService', this.newItem);
                this.newItem.show = false;
            },
            removeAction(item) {
                var self = this;
                this.$store.commit('showDeleteConfirm', function(){
                    self.$store.commit('removeService', [item]);
                });
            }
        },
        computed: {
            ...mapState(['services'])
        }
    }
</script>


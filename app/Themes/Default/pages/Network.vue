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
                    <v-btn @click="showAddDialog" icon>
                        <v-icon>add</v-icon>
                    </v-btn>
                    <v-btn @click="removeAllAction" icon>
                        <v-icon>delete</v-icon>
                    </v-btn>
                    <v-btn @click="refreshAll" icon>
                        <v-icon>refresh</v-icon>
                    </v-btn>
                </v-toolbar>
            </v-card-title>
            <v-data-table
                    v-bind:headers="headers"
                    v-bind:items="networks.data"
                    v-bind:search="search"
                    v-bind:pagination.sync="pagination"
                    :total-items="totalItems"
                    :loading="networks.loading"
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
                    <td class="text-xs-left">
                        <v-checkbox
                            primary
                            hide-details
                            v-model="props.selected"
                        ></v-checkbox>
                    </td>
                    <td>{{ props.item.Id }}</td>
                    <td  class="text-xs-right">{{ props.item.Name }}</td>
                    <td  class="text-xs-right">{{ props.item.Scope }}</td>
                    <td  class="text-xs-right">{{ props.item.Driver }}</td>
                    <td  class="text-xs-right">{{ props.item.Internal }}</td>
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
            <v-dialog v-model="editItem.show" v-if="editItem.show" width="50%" :fullscreen="this.$parent.$data.clientWidth<770" transition="dialog-bottom-transition" :overlay=false>
                <v-btn primary slot="activator">{{ $t('nouns.network_create') }}</v-btn>
                <v-card>
                    <v-toolbar class="primary">
                        <v-btn icon @click.native="editItem.show = false">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>{{ $t('nouns.network_create') }}</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                            <v-btn flat @click.native="createAction()">{{ $t('nouns.save') }}</v-btn>
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
                                        v-model="editItem.Name"
                                        :rules="[() => editItem.Name.length > 0 || $t('validate.required')]"
                                        required
                                ></v-text-field>
                            </v-flex>
                            <v-flex d-flex xs12 sm3 md2>
                                <v-subheader>Driver</v-subheader>
                            </v-flex>
                            <v-flex d-flex xs12 sm9 md10>
                                <v-select
                                        v-bind:items="editItem.DirverSelectItems"
                                        v-model="editItem.Driver"
                                        label="Select"
                                        single-line
                                        bottom
                                ></v-select>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md6 lg4>
                                <v-switch primary label="CheckDuplicate" v-model="editItem.CheckDuplicate"></v-switch>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md6 lg4>
                                <v-switch primary label="Internal" v-model="editItem.Internal"></v-switch>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md6 lg4>
                                <v-switch primary label="Attachable" v-model="editItem.Attachable"></v-switch>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md6 lg4>
                                <v-switch primary label="Ingress" v-model="editItem.Ingress"></v-switch>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md6 lg4>
                                <v-switch primary label="EnableIPv6" v-model="editItem.EnableIPv6"></v-switch>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md12>
                                <v-subheader>Network configuration</v-subheader>
                            </v-flex>
                            <v-flex d-flex xs12 sm3 md2>
                                <v-subheader>Subnet</v-subheader>
                            </v-flex>
                            <v-flex d-flex xs12 sm9 md10>
                                <v-text-field
                                        label="Subnet"
                                        v-model="editItem.Subnet"
                                        :rules="['e.g. 172.20.0.0/16']"
                                ></v-text-field>
                            </v-flex>
                            <v-flex d-flex xs12 sm3 md2>
                                <v-subheader>Gateway</v-subheader>
                            </v-flex>
                            <v-flex d-flex xs12 sm9 md10>
                                <v-text-field
                                        label="Gateway"
                                        v-model="editItem.Gateway"
                                        :rules="['e.g. 172.20.10.11']"
                                ></v-text-field>
                            </v-flex>
                            <v-flex d-flex xs12 sm3 md2>
                                <v-layout row wrap>
                                    <v-flex xs6>
                                        <v-subheader>Options</v-subheader>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-btn @click="editItem.Options.push({name: '', value: ''})" icon>
                                            <v-icon>add</v-icon>
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex xs12 sm9 md10>
                                <v-layout row wrap>
                                    <template  v-for="(item, index) in editItem.Options">
                                        <v-flex d-flex xs3>
                                            <v-text-field
                                                    label="Name"
                                                    v-model="editItem.Options[index].name"
                                            ></v-text-field>
                                        </v-flex>
                                        <v-flex d-flex xs3>
                                            <v-text-field
                                                    label="Value"
                                                    v-model="editItem.Options[index].value"
                                            ></v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-btn @click="editItem.Options.length>1 && editItem.Options.splice(index,1)" icon>
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
                        text: 'Id',
                        align: 'left',
                        sortable: false,
                        value: 'Id'
                    },
                    { text: 'Name', value: 'Name' },
                    { text: 'Scope', value: 'Scope' },
                    { text: 'Driver', value: 'Driver' },
                    { text: 'Internal', value: 'Internal' }
                ],
                items: [],
                editItem: {
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
            this.$store.commit('setBreadcrumbs', [{ text: 'menu.home'}, { text: 'menu.network'}]);
            this.refreshAll();
        },
        methods: {
            removeAllAction() {
                var self = this;
                if(self.selected.length == 0) return;
                this.$store.commit('showDeleteConfirm', function(){
                    self.$store.commit('removeNetwork', self.selected);
                });
            },
            refreshAll() {
                this.$store.commit('getNetworks');
            },
            showAddDialog() {
                this.editItem.show = true;
            },
            createAction() {
                this.$store.commit('createNetwork', this.editItem);
                this.editItem.show = false;
            },
            removeAction(item) {
                var self = this;
                this.$store.commit('showDeleteConfirm', function(){
                    self.$store.commit('removeNetwork', [item]);
                });
            }
        },
        computed: {
            ...mapState(['networks'])
        }
    }
</script>


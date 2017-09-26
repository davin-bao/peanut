<style lang="less">
    .content-toolbar {
        background-color: rgba(0,0,0,0)!important;
        .toolbar__content {
            height: auto!important;
        }
    }
</style>
<template>
    <div>
        <v-card>
            <v-card-title>
                <v-spacer></v-spacer>
                <v-toolbar flat class="content-toolbar">
                    <v-spacer></v-spacer>
                    <v-btn @click="add.show=true" icon>
                        <v-icon>add</v-icon>
                    </v-btn>
                    <v-btn @click="deleteList()" icon>
                        <v-icon>delete</v-icon>
                    </v-btn>
                    <v-btn @click="refresh()" icon>
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
                    <v-checkbox
                            primary
                            hide-details
                            v-model="props.selected"
                    ></v-checkbox>
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
                            <v-btn flat error>
                                <v-icon>delete</v-icon>
                            </v-btn>
                        </v-btn-toggle>
                    </td>
                </template>
            </v-data-table>
        </v-card>
        <v-layout row justify-center>
            <v-dialog v-model="add.show" v-if="add.show" fullscreen transition="dialog-bottom-transition" :overlay=false>
                <v-btn primary dark slot="activator">{{ $t('nouns.network_create') }}</v-btn>
                <v-card>
                    <v-toolbar dark class="primary">
                        <v-btn icon @click.native="add.show = false" dark>
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>{{ $t('nouns.network_create') }}</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                            <v-btn dark flat @click.native="addAction()">{{ $t('nouns.save') }}</v-btn>
                        </v-toolbar-items>
                    </v-toolbar>
                    <v-container fluid grid-list-md>
                        <v-layout row wrap>
                            <v-flex d-flex xs12 sm12 md12>
                                <v-subheader>User Controls</v-subheader>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md6 lg4>
                                <v-text-field
                                        label="Name"
                                        v-model="add.Name"
                                        :rules="[() => add.Name.length > 0 || $t('validate.required')]"
                                        required
                                ></v-text-field>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md6 lg4>
                                <v-select
                                        v-bind:items="add.DirverSelectItems"
                                        v-model="add.Driver"
                                        label="Select"
                                        single-line
                                        bottom
                                ></v-select>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md6 lg4>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md6 lg4>
                                <v-switch primary label="CheckDuplicate" v-model="add.CheckDuplicate"></v-switch>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md6 lg4>
                                <v-switch primary label="Internal" v-model="add.Internal"></v-switch>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md6 lg4>
                                <v-switch primary label="Attachable" v-model="add.Attachable"></v-switch>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md6 lg4>
                                <v-switch primary label="Ingress" v-model="add.Ingress"></v-switch>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md6 lg4>
                                <v-switch primary label="EnableIPv6" v-model="add.EnableIPv6"></v-switch>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md12 lg12>
                                <v-subheader>IPAM</v-subheader>
                            </v-flex>
                            <v-flex d-flex xs12 sm12 md6 lg4>
                                <v-select
                                        v-bind:items="add.IPAM.DirverSelectItems"
                                        v-model="add.IPAM.Driver"
                                        label="Select"
                                        single-line
                                        bottom
                                ></v-select>
                            </v-flex>
                            <v-flex xs12 sm12 md6 lg4>
                                <v-text-field
                                        label="IPAM.Options"
                                        v-model="add.IPAM.Options"
                                        :rules="[() => add.IPAM.Options.length > 0 || $t('validate.required')]"
                                        required
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md12 lg12>
                                <v-btn @click="add.IPAM.Config.push('')" icon>
                                    <v-icon>add</v-icon>
                                </v-btn>
                            </v-flex>
                            <template  v-for="(item, index) in add.IPAM.Config">
                                <v-flex d-flex xs11 sm11 md11 lg11>
                                        <v-text-field
                                                label="IPAM.Config"
                                                :rules="[() => item.length > 0 || $t('validate.required')]"
                                                required
                                                v-model="add.IPAM.Config[index]"
                                        ></v-text-field>
                                </v-flex>
                                <v-flex xs1 sm1 md1 lg1>
                                    <v-btn @click="add.IPAM.Config.splice(index, 1)" icon>
                                        <v-icon>remove</v-icon>
                                    </v-btn>
                                </v-flex>
                            </template>
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
                add: {
                    show: false,
                    Name: '',
                    CheckDuplicate: false,
                    Driver: 'default',
                    DirverSelectItems: ['default', 'bridge'],
                    Internal: true,
                    Attachable: true,
                    Ingress: true,
                    EnableIPv6: false,
                    IPAM: {
                        Driver: 'default',
                        DirverSelectItems: ['default', 'bridge'],
                        Config: [''],
                        Options: ''
                    }
                }
            }
        },
        watch: {

        },
        mounted () {
            this.$store.commit('setBreadcrumbs', [{ text: 'menu.home'}, { text: 'menu.network'}]);
            this.refresh();
        },
        methods: {
            deleteList() {
                var self = this;
                this.$store.commit('showDeleteConfirm', function(){
                    console.log(self.selected);
                });
            },
            refresh() {
                this.$store.commit('getNetworks');
            },
            addAction() {
                console.log(this.add.IPAM.Config);
            }
        },
        computed: {
            ...mapState(['networks'])
        }
    }
</script>


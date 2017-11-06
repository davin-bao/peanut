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
                    <v-btn @click="editItem.show=true" icon>
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
                    v-bind:items="stacks.data"
                    v-bind:search="search"
                    v-bind:pagination.sync="pagination"
                    :total-items="totalItems"
                    :loading="stacks.loading"
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
                    <td>{{ props.item.Name }}</td>
                    <td>{{ props.item.Image }}</td>
                    <td>{{ props.item.Count }}</td>
                    <td>{{ props.item.UpdatedAt }}</td>
                    <td  class="text-xs-right">
                        <v-btn-toggle small>
                            <v-btn flat :to="'/service/'+props.item.Name">
                                <v-icon>list</v-icon>
                            </v-btn>
                            <v-btn flat primary @click="updateAction(props.item)">
                                <v-icon>edit</v-icon>
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
                <v-btn primary slot="activator">{{ $t(editItem.title) }}</v-btn>
                <v-card>
                    <v-toolbar class="primary">
                        <v-btn icon @click.native="editItem.show = false">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>{{ $t(editItem.title) }}</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                            <v-btn flat @click.native="createAction">{{ $t('nouns.save') }}</v-btn>
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
                                <v-subheader>Compose File</v-subheader>
                            </v-flex>
                            <v-flex d-flex xs12 sm9 md10>
                                <v-select
                                        label="Select"
                                        autocomplete
                                        :loading="editItem.ComposeFileContentLoading"
                                        cache-items
                                        required
                                        :items="composes.selectData"
                                        v-model="editItem.ComposeFileName"
                                        @blur="showComposeFileContent"
                                ></v-select>
                            </v-flex>
                            <v-flex d-flex xs12 sm3 md2>
                                <v-subheader>Compose File</v-subheader>
                            </v-flex>
                            <v-flex d-flex xs12 sm9 md10>
                                <v-text-field
                                        box
                                        textarea
                                        rows="20"
                                        label="Compose File Content"
                                        v-model="editItem.ComposeFileContent"
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs12 offset-xs0 offset-sm2>
                                &nbsp;
                                <v-btn @click="createAction" primary
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
                        text: 'Service Count',
                        align: 'left',
                        sortable: false,
                        value: 'Count'
                    },
                    {
                        text: 'UpdatedAt',
                        align: 'left',
                        sortable: true,
                        value: 'UpdatedAt'
                    }
                ],
                items: [],
                editItem: {
                    show: false,
                    title: 'nouns.stack_create',
                    Name: '',
                    ComposeFileName: '',
                    ComposeFileContentLoading: false,
                    ComposeFileContent: ''
                },
            }
        },
        watch: {
        },
        mounted () {
            this.$store.commit('setBreadcrumbs', [{ text: 'menu.home'}, { text: 'menu.stack'}]);
            this.refreshAll();
        },
        methods: {
            removeAllAction() {
                var self = this;
                if(self.selected.length == 0) return;
                this.$store.commit('showDeleteConfirm', function(){
                    self.$store.commit('removeStack', self.selected);
                });
            },
            refreshAll() {
                this.$store.commit('getStacks');
                this.$store.commit('getComposes');
            },
            showAddDialog() {
                this.editItem.show = true;
                this.editItem.title = 'nouns.stack_create';
            },
            createAction() {
                this.$store.commit('createStack', this.editItem);
                this.editItem.show = false;
            },
            updateAction(item) {
                this.editItem.show = true;
                this.editItem.title = 'nouns.stack_update';
                this.editItem.Name = item.Name;
                this.editItem.ComposeFileName = '';
            },
            removeAction(item) {
                var self = this;
                this.$store.commit('showDeleteConfirm', function(){
                    self.$store.commit('removeStack', [item]);
                });
            },
            showComposeFileContent(val) {
                this.editItem.ComposeFileContentLoading = true;
                let matched = false, value = typeof(val.target)=='undefined' || typeof(val.target.value)=='undefined' ? value : val.target.value;

                for(var i=0; i<this.composes.data.length; i++){
                    if(this.composes.data[i].Name == value){
                        matched = true;
                        this.editItem.ComposeFileContent = this.composes.data[i].Content;
                    }
                }
                if(value == '' || !matched){
                    this.editItem.ComposeFileName = '';
                    this.editItem.ComposeFileContent = '';
                }
                this.editItem.ComposeFileContentLoading = false;
            }
        },
        computed: {
                ...mapState(['stacks', 'composes'])
        }
    }
</script>
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
                    v-bind:items="composes.data"
                    v-bind:search="search"
                    v-bind:pagination.sync="pagination"
                    :total-items="totalItems"
                    :loading="composes.loading"
                    :rows-per-page-items="pageCount"
                    v-model="selected"
                    select-all
                    selected-key="Name"
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
                    <td  class="text-xs-left">{{ props.item.Name }}</td>
                    <td  class="text-xs-right">
                        <v-btn-toggle small>
                            <v-btn flat primary>
                                <v-icon>visibility</v-icon>
                            </v-btn>
                            <v-btn flat  @click="updateAction(props.item)">
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
                    <v-form v-model="editItem.valid" ref="createForm">
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
                        <v-text-field
                                label="Name"
                                v-model="editItem.Name"
                                :rules="[(v) => !!v || $t('validate.required'), () => editItem.Name.indexOf(' ') == -1 || $t('validate.space_forbid')]"
                                required
                                :disabled="editItem.title!='nouns.compose_create'"
                        ></v-text-field>
                        <v-text-field
                                label="Content"
                                v-model="editItem.Content"
                                :rules="[(v) => !!v || $t('validate.required')]"
                                required
                                multi-line
                                rows="20"
                        ></v-text-field>
                        <v-btn @click="createAction" :class="{ green: editItem.valid, red: !editItem.valid }">Save</v-btn>
                        <v-btn @click="clearCreateFrom">clear</v-btn>
                    </v-form>
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
                    }
                ],
                items: [],
                editItem: {
                    show: false,
                    title: 'nouns.compose_create',
                    Name: '',
                    Content: '',
                    valid: false
                }
            }
        },
        watch: {

        },
        mounted () {
            this.$store.commit('setBreadcrumbs', [{ text: 'menu.home'}, { text: 'menu.compose'}]);
            this.refreshAll();
        },
        methods: {
            removeAllAction() {
                let self = this;
                if(self.selected.length == 0) return;
                this.$store.commit('showDeleteConfirm', function(){
                    self.$store.commit('removeCompose', self.selected);
                });
            },
            refreshAll() {
                this.$store.commit('getComposes');
            },
            showAddDialog() {
                this.editItem.show = true;
                this.editItem.title = 'nouns.compose_create';
            },
            clearCreateFrom () {
                this.$refs.createForm.reset()
            },
            createAction () {
                if (this.$refs.createForm.validate()) {
                    let commitAction = 'createCompose';
                    if(this.editItem.title !== 'nouns.compose_create') commitAction = 'updateCompose';

                    this.$store.commit(commitAction, this.editItem);
                    this.editItem.show = false;
                    this.refreshAll();
                }
            },
            updateAction(item) {
                this.editItem.show = true;
                this.editItem.title = 'nouns.compose_update';
                this.editItem.Name = item.Name;
                this.editItem.Content = item.Content;
            },
            removeAction(item) {
                var self = this;
                this.$store.commit('showDeleteConfirm', function(){
                    self.$store.commit('removeCompose', [item]);
                });
            }
        },
        computed: {
            ...mapState(['composes'])
        }
    }
</script>


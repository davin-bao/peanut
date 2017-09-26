<style lang="less">
    .content-toolbar {
        background-color: rgba(0,0,0,0)!important;
        .toolbar__content {
            height: auto!important;
        }
    }
</style>
<template>
    <v-card>
        <v-card-title>
            <v-spacer></v-spacer>
            <v-toolbar flat class="content-toolbar">
                <v-spacer></v-spacer>
                <v-btn icon>
                    <v-icon>add</v-icon>
                </v-btn>
                <v-btn icon :to="delete">
                    <v-icon>delete</v-icon>
                </v-btn>
                <v-btn icon>
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
                        <v-btn flat success>
                            <v-icon>edit</v-icon>
                        </v-btn>
                        <v-btn flat error>
                            <v-icon>delete</v-icon>
                        </v-btn>
                    </v-btn-toggle>
                </td>
            </template>
        </v-data-table>
    </v-card>
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
                items: []
            }
        },
        watch: {

        },
        mounted () {
            this.$store.commit('setBreadcrumbs', [{ text: 'menu.home'}, { text: 'menu.network'}]);
            this.$store.commit('getNetworks');
        },
        methods: {
            delete: function(){
                console.log(this.dialog);
            }
        },
        computed: {
            ...mapState(['message', 'breadcrumbs', 'dialog', 'networks'])
        }
    }
</script>


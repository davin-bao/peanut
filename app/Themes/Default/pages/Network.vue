<style scoped>
    .system-bar {
        background-color: rgba(0,0,0,0)!important;
    }
</style>
<template>
    <div>
        <v-system-bar>
            <v-btn success fab small>
                <v-icon>add</v-icon>
            </v-btn>
        </v-system-bar>
        <v-data-table
                v-bind:headers="headers"
                v-bind:items="networks.data"
                v-bind:search="search"
                v-bind:pagination.sync="pagination"
                :total-items="totalItems"
                :loading="networks.loading"
                class="elevation-1"
                :rows-per-page-items="pageCount"
        >
            <template slot="headerCell" scope="props">
                <span v-tooltip:bottom="{ 'html': props.header.text }">
                  {{ props.header.text }}
                </span>
            </template>
            <template slot="items" scope="props">
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
    </div>
</template>
<script>
    import { mapState } from 'vuex';

    export default {
        data () {
            return {
                search: '',
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
            this.$store.commit('setBreadcrumbs', [{ text: 'menu.home'}, { text: 'menu.stack'}]);
            this.$store.commit('getNetworks');
        },
        methods: {
        },
        computed: {
            ...mapState(['message', 'networks'])
        }
    }
</script>


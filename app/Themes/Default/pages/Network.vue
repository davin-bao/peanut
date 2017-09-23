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
                v-bind:items="items"
                v-bind:search="search"
                v-bind:pagination.sync="pagination"
                :total-items="totalItems"
                :loading="loading"
                class="elevation-1"
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
                items: [],
                loading: true,
                pagination: {},
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
                ]
            }
        },
        watch: {
            pagination: {
                handler () {
                    this.getData()
                            .then(data => {
                        this.items = data.items
                        this.totalItems = data.total
                    })
                },
                deep: true
            }
        },
        mounted () {
            this.$store.commit('setBreadcrumbs', [{ text: 'menu.home'}, { text: 'menu.stack'}]);
            this.$store.commit('getNetworks');
            this.getData()
                    .then(data => {
                this.items = data.items
                this.totalItems = data.total
            });
        },
        methods: {
            getData () {
                return new Promise((resolve, reject) => {
                    this.loading = true;
                    const { sortBy, descending, page, rowsPerPage } = this.pagination;

                    let items = this.networks.data;
                    const total = items.length;

                    if (rowsPerPage > 0) {
                        items = items.slice((page - 1) * rowsPerPage, page * rowsPerPage)
                    }

                    setTimeout(() => {
                        this.loading = false;
                        resolve({
                            items,
                            total
                        })
                    }, 1000)
                })
            }
        },
        computed: {
            ...mapState(['message', 'networks'])
        }
    }
</script>


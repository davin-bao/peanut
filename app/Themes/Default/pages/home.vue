<template>
    <v-container fluid grid-list-md>
        <v-layout row>
            <v-flex xs6 md4 lg3 xl3>
                <v-card>
                    <v-card-title primary-title>
                        <h6 class=" mb-0">host-192-168-188-210-1</h6>
                        <div>Docker Version: 17.09.0-ce-rc1</div>
                        <div class="text-xs-right">
                            <v-chip label class="primary white--text">Leader</v-chip>
                            <v-chip label class="success white--text">ready</v-chip>
                        </div>
                    </v-card-title>
                    <v-card-text>
                        <v-divider></v-divider>
                        <div class="text-xs-left">
                            <v-chip label class="pink white--text">Label</v-chip>
                        </div>
                        <v-divider></v-divider>
                        <p></p>
                        <p class="caption">CPU Core: 16</p>
                        <p class="caption">Memory(G): 16</p>
                        <p class="caption">Platform: linux x86_64</p>
                        <v-divider></v-divider>
                        <p></p>
                        <p class="caption">Address: 192.168.188.210:2377</p>
                        <p class="caption">Role: Manager</p>
                        <p class="caption">Availability: Active</p>
                        <p class="caption">Reachability: reachable</p>
                        <v-divider></v-divider>
                        <v-data-table
                                v-bind:headers="headers"
                                v-bind:items="items"
                                hide-actions
                        >
                            <template slot="items" scope="props">
                                <td class="text-xs-left">{{ props.item.name }}</td>
                                <td class="text-xs-right">
                                    <v-icon v-if="props.item.status=='running'" class="green--text">access_time</v-icon>
                                    <v-icon v-else-if="props.item.status=='stop'" class="red--text">stop</v-icon>
                                    <v-progress-circular v-else indeterminate class="amber--text" size="24"></v-progress-circular>
                                </td>
                            </template>
                        </v-data-table>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn flat class="orange--text">EDIT</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
            <v-flex xs6 md4 lg3 xl3>
                <v-card>
                    <v-card-title primary-title>
                        <div>
                            <h3 class="headline mb-0">Node 1</h3>
                            <div>Node</div>
                        </div>
                    </v-card-title>
                    <v-card-text>#2</v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
    export default {
        data () {
            return {
                headers: [
                    {
                        text: 'Name',
                        align: 'left',
                        sortable: false,
                        value: 'name'
                    },
                    {
                        text: 'Status',
                        align: 'right',
                        sortable: false,
                        value: 'status'
                    }
                ],
                items: [
                    {
                        status: 'stop',
                        name: 'Frozen Yogurt'
                    },
                    {
                        status: 'running',
                        name: 'Ice cream sandwich'
                    },
                    {
                        status: 'created',
                        name: 'Created sandwich'
                    }
                ]
            }
        },
        methods: {
            changeLimit () {
                function getArrayItems(arr, num) {
                    const temp_array = [];
                    for (let index in arr) {
                        temp_array.push(arr[index]);
                    }
                    const return_array = [];
                    for (let i = 0; i<num; i++) {
                        if (temp_array.length>0) {
                            const arrIndex = Math.floor(Math.random()*temp_array.length);
                            return_array[i] = temp_array[arrIndex];
                            temp_array.splice(arrIndex, 1);
                        } else {
                            break;
                        }
                    }
                    return return_array;
                }
                this.randomMovieList = getArrayItems(this.movieList, 5);
                this.randomMovieList1 = getArrayItems(this.movieList, 5);
            }
        },
        mounted () {
            this.changeLimit();
        }
    }
</script>

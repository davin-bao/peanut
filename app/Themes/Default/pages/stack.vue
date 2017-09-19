<template>
    <v-expansion-panel expand>
        <v-expansion-panel-content v-for="(item,i) in 5" :key="i" v-bind:value="item === 2">
            <div slot="header">
                <v-icon>stop</v-icon> dfsdfsjlfk
            </div>
            <v-divider></v-divider>
            <v-list  class="">
                <v-subheader inset>Info</v-subheader>
                <v-list-tile avatar>
                    <v-list-tile-action>
                    </v-list-tile-action>
                    <v-list-tile-content>
                    </v-list-tile-content>
                </v-list-tile>
                <v-divider inset></v-divider>
                <v-subheader inset>Services</v-subheader>
                <template v-for="item in items">
                    <v-list-tile avatar v-bind:key="item.title" @click="">
                        <v-list-tile-action>
                            <v-icon v-if="item.icon" class="pink--text">star</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title v-text="item.title"></v-list-tile-title>
                        </v-list-tile-content>
                        <v-btn-toggle small v-model="toggle_exclusive">
                            <v-btn flat>
                                <v-icon>visibility</v-icon>
                            </v-btn>
                            <v-btn flat>
                                <v-icon>edit</v-icon>
                            </v-btn>
                            <v-btn flat>
                                <v-icon>delete</v-icon>
                            </v-btn>
                        </v-btn-toggle>
                    </v-list-tile>
                    <v-divider inset></v-divider>
                </template>
            </v-list>
        </v-expansion-panel-content>
    </v-expansion-panel>
</template>
<script>
    export default {
        data () {
            return {
                items: [
                    { icon: true, title: 'Jason Oner', avatar: '/static/doc-images/lists/1.jpg' },
                    { title: 'Travis Howard', avatar: '/static/doc-images/lists/2.jpg' },
                    { title: 'Ali Connors', avatar: '/static/doc-images/lists/3.jpg' },
                    { title: 'Cindy Baker', avatar: '/static/doc-images/lists/4.jpg' },
                ]
            }
        },
        methods: {
        },
        mounted () {
        }
    }
</script>

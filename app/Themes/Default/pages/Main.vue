<style scoped>
    @import '../styles/fonts.css';
    @import '../styles/app.css';

    .system-bar {
        background-color: rgba(0,0,0,0)!important;
    }
</style>
<template>
    <v-app id="example-1" toolbar footer :dark="dark">
        <v-navigation-drawer
                persistent
                height="100%"
                clipped
                persistent
                v-model="drawer"
                enable-resize-watcher
                absolute
                fill-height="false"
                :dark="dark"
                :mini-variant="mini"
                overflow
        >
            <v-list>
                <v-list-group v-for="item in menu"  :key="item.href">
                    <v-list-tile :to="item.href" slot="item" router ripple :title="$t(item.title)" v-model="item.active">
                        <v-list-tile-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ $t(item.title) }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-divider></v-divider>
                </v-list-group>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar fixed>
            <v-toolbar-side-icon @click.stop="mini = !mini"></v-toolbar-side-icon>
            <v-toolbar-title>{{ $t("title") }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-menu offset-y>
                <v-btn icon slot="activator">
                    <v-icon>language</v-icon>
                </v-btn>
                <v-list>
                    <v-list-tile v-for="lang in locales" :key="lang" @click="changeLocale(lang)">
                        <v-list-tile-title>{{lang}}</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
            <v-btn icon @click.stop="dark = !dark">
                    <v-icon>style</v-icon>
            </v-btn>
            <v-btn icon>
                <v-icon>account_circle</v-icon>
            </v-btn>
        </v-toolbar>
        <main>
            <v-container fluid>
                <v-system-bar status>
                    <v-breadcrumbs divider="/">
                        <v-breadcrumbs-item v-for="item in breadcrumbs" :key="item.text">
                            {{ $t(item.text) }}
                        </v-breadcrumbs-item>
                    </v-breadcrumbs>
                    <v-spacer></v-spacer>
                </v-system-bar>
                <div>
                    <v-alert v-bind='message' v-model='message.show' v-if="message.type=='success'" success dismissible transition="slide-y-transition">
                        {{message.body}}
                    </v-alert>
                    <v-alert v-bind='message' v-model='message.show' v-if="message.type=='error'" error dismissible transition="slide-y-transition">
                        {{message.body}}
                    </v-alert>
                    <v-alert v-bind='message' v-model='message.show' v-if="message.type=='info'" info dismissible transition="slide-y-transition">
                        {{message.body}}
                    </v-alert>
                    <v-dialog v-bind='deleteConfirm' v-model='deleteConfirm.show'  v-if="deleteConfirm.show" lazy absolute>
                        <v-card>
                            <v-card-title>
                                <div class="headline">Confirm</div>
                            </v-card-title>
                            <v-card-text>Do you sure delete this ?</v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn class="green--text darken-1" flat="flat" @click.native="deleteConfirmOk()">OK</v-btn>
                                <v-btn class="green--text darken-1" flat="flat" @click.native="deleteConfirm.show = false">Cancel</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </div>
                <router-view></router-view>
            </v-container>
        </main>
        <v-footer>
            <span class="light-blue--text">© 2017</span>
        </v-footer>
    </v-app>
</template>
<script>
    import { mapState } from 'vuex';
    export default {
        data () {
            return {
                drawer: true,
                dark: false,
                mini: false,
                locales: ['en-US', 'zh-CN']
            }
        },
        methods: {
            changeLocale (to) {
                if(typeof(to) == 'undefined'){
                    to = 'zh-CN';
                }
                this.$i18n.locale = to;
                this.$store.commit('showMessage', {type: 'success', body: to});
            },
            deleteConfirmOk () {
                this.deleteConfirm.show = false;
                this.deleteConfirm.action();
            }
        },
        mounted () {
        },
        watch: {
//            '$route' (to, from) {
//                var self = this;
//                self.menus.forEach(function(menu) {
//                    if(to.path == menu.href || (to.path == '' && menu.href == '/')){
//                        menu.active = true;
//                        self.breadcrumbs.push({ text: menu.title});
//                    }else{
//                        menu.active = false;
//                    }
//                });
//            }
        },
        computed: {
                ...mapState(['message', 'deleteConfirm', 'menu', 'pageTitle', 'breadcrumbs'])
        }
    }
</script>
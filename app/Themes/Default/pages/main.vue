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
                <v-list-group v-for="item in menu">
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
        <v-toolbar fixed :dark="dark">
            <v-toolbar-side-icon @click.stop="mini = !mini"></v-toolbar-side-icon>
            <v-toolbar-title>{{ $t("title") }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-menu offset-y>
                <v-btn fab dark slot="activator">
                    <v-icon>language</v-icon>
                </v-btn>
                <v-list>
                    <v-list-tile v-for="lang in locales" :key="lang" @click="changeLocale(lang)">
                        <v-list-tile-title>{{lang}}</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
            <v-btn icon>
                <v-icon>account_circle</v-icon>
            </v-btn>
        </v-toolbar>
        <main>
            <v-container fluid>
                <v-system-bar class="primary">
                    <v-spacer></v-spacer>
                    <v-breadcrumbs divider="/">
                        <v-breadcrumbs-item v-for="item in breadcrumbs" :key="item.text">
                            {{ $t(item.text) }}
                        </v-breadcrumbs-item>
                    </v-breadcrumbs>
                </v-system-bar>
                <div>
                    <v-alert v-bind='message' v-model='message.show' error='message.error' info='message.info' success='message.success' dismissible transition="slide-y-transition">
                        {{message.body}}
                    </v-alert>
                </div>
                <router-view></router-view>
            </v-container>
        </main>
        <v-footer :dark="dark">
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
                dark: true,
                mini: false,
                locales: ['en-US', 'zh-CN'],
                breadcrumbs: [{ text: 'menu.home'}, { text: 'menu.dashboard'}]
            }
        },
        methods: {
            changeLocale (to) {
                if(typeof(to) == 'undefined'){
                    to = 'zh-CN';
                }
                this.$i18n.locale = to;
                this.$store.commit('showMessage', {type: 'success', body: to});
            }
        },
        mounted () {
            //this.changeLocale();
        },
        watch: {
            '$route' (to, from) {
                var self = this;
                self.breadcrumbs = [{ text: "menu.home"}];
                self.menus.forEach(function(menu) {
                    if(to.path == menu.href || (to.path == '' && menu.href == '/')){
                        menu.active = true;
                        self.breadcrumbs.push({ text: menu.title});
                    }else{
                        menu.active = false;
                    }
                });

            }
        },
        computed: {
                ...mapState(['message', 'menu', 'pageTitle'])
        }
    }
</script>
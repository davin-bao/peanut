<style scoped>
    @import '../styles/fonts.css';
    @import '../styles/app.css';
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
                <v-list-group v-for="item in menus" :value="item.active" v-bind:key="item.title">
                    <v-list-tile slot="item" v-model="item.active" :title="$t(item.title)" :key='item.href' :href='item.href' v-bind:router='true'>
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
                <v-breadcrumbs divider="/" offset-y>
                    <v-breadcrumbs-item
                            v-for="item in breadcrumbs" :key="item.text"
                            :disabled="item.disabled"
                    >
                        {{ item.text }}
                    </v-breadcrumbs-item>
                </v-breadcrumbs>
                <div>
                    <v-alert v-bind='message' v-model='message.show' :error='message.error' :info='message.info' :success='message.success' dismissible transition="slide-y-transition">
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
    export default {
        data () {
            return {
                message: {},
                drawer: true,
                dark: true,
                mini: false,
                locales: ['en-US', 'zh-CN'],
                breadcrumbs: [],
                menus: [
                    {title: 'menu.dashboard', icon: 'home', href: '/'},
                    {title: 'menu.stack', icon: 'domain', href: '#/stack'}
                ]
            }
        },
        methods: {
            changeLocale (to) {
                if(typeof(to) == 'undefined'){
                    to = 'zh-CN';
                }
                this.$i18n.locale = to;
                this.message = { show: true, success: true, body: to};
            }
        },
        mounted () {
            //this.changeLocale();
        },
        watch: {
            '$route' (to, from) {
                this.breadcrumbs.push({ text: "首页"});
                this.menus.forEach(function(menu) {
                    if(to.hash == menu.href || (to.hash == '' && menu.href == '/')){
                        menu.active = true;
                    }
                });

            }
        }
    }
</script>
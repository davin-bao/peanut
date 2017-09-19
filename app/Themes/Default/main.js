import Vue from 'vue';
import VueRouter from 'vue-router';
import Routers from './router';
import Util from './libs/util';
import App from './app.vue';
//import iView from 'iview';
//import 'iview/dist/styles/iview.css';
import Vuetify from 'vuetify';
import '../../../node_modules/vuetify/dist/vuetify.min.css';
import VueI18n from 'vue-i18n';
import messages from './lang.js';

Vue.use(VueI18n);

const i18n = new VueI18n({
    locale: 'en-us', // set locale
    messages, // set locale messages
});

Vue.use(VueRouter);
//Vue.use(iView);
Vue.use(Vuetify);

// 路由配置
const RouterConfig = {
    mode: 'history',
    routes: Routers
};
const router = new VueRouter(RouterConfig);

router.beforeEach((to, from, next) => {
    //iView.LoadingBar.start();
    Util.title(to.meta.title);
    next();
});

router.afterEach((to, from, next) => {
    //iView.LoadingBar.finish();
    window.scrollTo(0, 0);
});

new Vue({
    el: '#app',
    i18n,
    router: router,
    render: h => h(App)
});

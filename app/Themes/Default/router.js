const routers = [
    {
        path: '/',
        meta: {
            title: '',
        },
        component: (resolve) => require(['./vues/index.vue'], resolve)
    },
    {
        path: '/stack',
        meta: {
            title: ''
        },
        component: (resolve) => require(['./vues/stack.vue'], resolve)
    }
];
export default routers;
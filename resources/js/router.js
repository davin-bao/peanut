const routers = [
    {
        path: '/',
        meta: {
            title: ''
        },
        component: (resolve) => require(['../vues/index.vue'], resolve)
    }
];
export default routers;
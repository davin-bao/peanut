const routers = [
    {
        path: '/',
        meta: {
            title: '',
            breadcrumbName: "控制面板"
        },
        component: (resolve) => require(['./vues/index.vue'], resolve)
    }
];
export default routers;
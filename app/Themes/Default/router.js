function route (path, file, name, children) {
    return {
        exact: true,
        path,
        name,
        children,
        component: require(`./pages/${file}.vue`)
    }
}

const routers = [
    route('/', 'main', null, [
        route('/', 'home', 'home'),
        route('/stack', 'stack', 'stack'),
        route('/network', 'Network', 'network')
    ])
];
export default routers;
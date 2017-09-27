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
    route('/', 'Main', null, [
        route('/', 'Home', 'home'),
        route('/node/:id', 'NodeDetail', 'node_detail'),
        route('/stack', 'Stack', 'stack'),
        route('/network', 'Network', 'network'),
        route('/compose', 'Compose', 'compose')
    ])
];
export default routers;
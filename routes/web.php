<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->get('nodes/create', ['as' => 'node_create', 'uses' => 'NodeController@getCreateCommand']);
$router->get('nodes/{Id}', ['as' => 'node_detail', 'uses' => 'NodeController@getDetail']);
$router->post('nodes/{id}', ['as' => 'node_update', 'uses' => 'NodeController@postUpdate']);
$router->get('nodes/status/{endpoint}', ['as' => 'nodes_status', 'uses' => 'NodeController@getStatus']);
$router->delete('nodes/{Id}', ['as' => 'node_remove', 'uses' => 'NodeController@postRemove']);
$router->get('nodes', ['as' => 'node_list', 'uses' => 'NodeController@getList']);
$router->get('containers/{endpoint}/{id}/logs', ['as' => 'container_log', 'uses' => 'ContainerController@getLog']);
$router->get('containers/{endpoint}/{id}', ['as' => 'container_detail', 'uses' => 'ContainerController@getDetail']);
$router->get('containers/{endpoint}', ['as' => 'container_list', 'uses' => 'ContainerController@getList']);

$router->post('stacks/create', ['as' => 'stack_create', 'uses' => 'StackController@postCreate']);
$router->get('stacks/{Name}', ['as' => 'stack_detail', 'uses' => 'StackController@getDetail']);
$router->delete('stacks/{Name}', ['as' => 'stack_remove', 'uses' => 'StackController@postRemove']);
$router->get('stacks', ['as' => 'stack_list', 'uses' => 'StackController@getList']);

$router->post('services/create', ['as' => 'service_create', 'uses' => 'ServiceController@postCreate']);
$router->get('services/{id}/logs', ['as' => 'service_log', 'uses' => 'ServiceController@getLog']);
$router->delete('services/{Id}', ['as' => 'service_remove', 'uses' => 'ServiceController@postRemove']);
$router->get('services', ['as' => 'service_list', 'uses' => 'ServiceController@getList']);

$router->delete('tasks/{Id}', ['as' => 'task_remove', 'uses' => 'TaskController@postRemove']);
$router->get('tasks', ['as' => 'task_list', 'uses' => 'TaskController@getList']);

$router->post('networks/create', ['as' => 'network_create', 'uses' => 'NetworkController@postCreate']);
$router->delete('networks/{Id}', ['as' => 'network_remove', 'uses' => 'NetworkController@postRemove']);
$router->get('networks', ['as' => 'network_list', 'uses' => 'NetworkController@getList']);

$router->post('composes/create', ['as' => 'compose_create', 'uses' => 'ComposeController@postCreate']);
$router->post('composes/{Name}', ['as' => 'compose_update', 'uses' => 'ComposeController@postUpdate']);
$router->delete('composes/{Name}', ['as' => 'compose_remove', 'uses' => 'ComposeController@postRemove']);
$router->get('composes', ['as' => 'compose_list', 'uses' => 'ComposeController@getList']);

$router->get('/', ['as' => 'index', 'uses' => 'IndexController@getIndex']);

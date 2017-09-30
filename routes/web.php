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

$router->get('nodes/{Id}', ['as' => 'node_inspect', 'uses' => 'NodeController@getDetail']);
$router->post('nodes/{id}', ['as' => 'node_update', 'uses' => 'NodeController@postUpdate']);
$router->get('nodes/status/{endpoint}', ['as' => 'nodes_status', 'uses' => 'NodeController@getStatus']);
$router->get('nodes', ['as' => 'node_list', 'uses' => 'NodeController@getList']);
$router->get('containers/{endpoint}', ['as' => 'container_list', 'uses' => 'ContainerController@getList']);

$router->post('stacks/create', ['as' => 'stack_create', 'uses' => 'StackController@postCreate']);
$router->get('stacks/{Name}', ['as' => 'stack_inspect', 'uses' => 'StackController@getDetail']);
$router->delete('stacks/{Name}', ['as' => 'stack_remove', 'uses' => 'StackController@postRemove']);
$router->get('stacks', ['as' => 'stack_list', 'uses' => 'StackController@getList']);

$router->post('services/create', ['as' => 'service_create', 'uses' => 'ServiceController@postCreate']);
$router->delete('services/{Id}', ['as' => 'service_remove', 'uses' => 'ServiceController@postRemove']);
$router->get('services', ['as' => 'service_list', 'uses' => 'ServiceController@getList']);

$router->post('networks/create', ['as' => 'network_create', 'uses' => 'NetworkController@postCreate']);
$router->delete('networks/{Id}', ['as' => 'network_remove', 'uses' => 'NetworkController@postRemove']);
$router->get('networks', ['as' => 'network_list', 'uses' => 'NetworkController@getList']);

$router->post('composes/create', ['as' => 'compose_create', 'uses' => 'ComposeController@postCreate']);
$router->delete('composes/{Name}', ['as' => 'compose_remove', 'uses' => 'ComposeController@postRemove']);
$router->get('composes', ['as' => 'compose_list', 'uses' => 'ComposeController@getList']);

$router->post('system/status', ['as' => 'system_status', 'uses' => 'SystemController@getStatus']);
$router->get('system/command', ['as' => 'system_command', 'uses' => 'SystemController@getCommand']);
$router->post('system/command-result', ['as' => 'system_command_result_create', 'uses' => 'SystemController@postCommandResult']);
$router->get('system/command-result', ['as' => 'system_command_result', 'uses' => 'SystemController@getCommandResult']);

$router->get('/', ['as' => 'index', 'uses' => 'IndexController@getIndex']);

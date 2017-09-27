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
$router->get('nodes', ['as' => 'node_list', 'uses' => 'NodeController@getList']);
$router->post('nodes/{id}', ['as' => 'node_update', 'uses' => 'NodeController@postUpdate']);
$router->get('containers/{endpoint}', ['as' => 'container_list', 'uses' => 'ContainerController@getList']);
$router->get('networks', ['as' => 'network_list', 'uses' => 'NetworkController@getList']);
$router->post('networks/create', ['as' => 'network_create', 'uses' => 'NetworkController@postCreate']);
$router->delete('networks/{Id}', ['as' => 'network_remove', 'uses' => 'NetworkController@postRemove']);

$router->get('/', ['as' => 'index', 'uses' => 'IndexController@getIndex']);

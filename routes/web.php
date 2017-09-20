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
$router->get('nodes/endpoint', ['as' => 'node_list', 'uses' => 'NodeController@getEndpoint']);
$router->get('containers/{endpoint}', ['as' => 'container_list', 'uses' => 'ContainerController@getList']);

$router->get('/', ['as' => 'index', 'uses' => 'IndexController@getIndex']);

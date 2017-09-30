<?php
namespace App\Model;

class Container extends DockerApiModel {
    const LIST_PATH = 'containers/json';

    public static function find($endpoint){
        $result = [];
        $uri = 'http://' . $endpoint . ':' . env('DOCKER_PORT', '2376') . '/' . env('DOCKER_VERSION', 'v1.30') . '/';
        $list = Container::HttpGet( static::LIST_PATH, $uri);

        foreach($list as $item){
            array_push($result, new Container([
                'ID' => array_get($item, 'Id'),
                'Node' => $endpoint,
                'Names' => array_get($item, 'Names'),
                'Image' => array_get($item, 'Image'),
                'Networks' => array_get($item, 'NetworkSettings.Networks'),
                'State' => array_get($item, 'State'),
                'Ports' => array_get($item, 'Ports'),
            ]));
        }
        return $result;
    }
}
<?php
namespace App\Model;

class Container extends DockerApiModel {
    const LIST_PATH = 'containers/json';

    public static function find($endpoint){
        $result = [];
        $list = Container::HttpGet( static::LIST_PATH, $endpoint);

        foreach($list as $item){
            array_push($result, new Container([
                'ID' => $item->Id,
                'Node' => $endpoint,
                'Names' => $item->Names,
                'Image' => $item->Image,
                'Networks' => $item->NetworkSettings->Networks,
                'State' => $item->State,
                'Ports' => $item->Ports
            ]));
        }
        return $result;
    }
}
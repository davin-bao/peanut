<?php
namespace App\Model;

class Container extends DockerApiModel {
    const LIST_PATH = 'containers/json';
    const GET_PATH = 'containers/{id}/json';
    const LOG_PATH = 'containers/{id}/logs';

    public static function get($endpoint, $Id){
        $uri = 'http://' . $endpoint . ':' . env('DOCKER_PORT', '2376') . '/' . env('DOCKER_VERSION', 'v1.30') . '/';
        $item = parent::HttpGet(str_replace('{id}', $Id, static::GET_PATH), $uri);
//dd($item);
        return new Container([
            'Id' => array_get($item, 'Id'),
            'Name' => array_get($item, 'Name'),
            'Image' => array_get($item, 'Image'),
            'Hostname' => array_get($item, 'Config.Hostname'),
            'Domainname' => array_get($item, 'Config.Domainname'),
            'User' => array_get($item, 'Config.User'),
            'AttachStdin' => array_get($item, 'Config.AttachStdin'),
            'AttachStdout' => array_get($item, 'Config.AttachStdout'),
            'AttachStderr' => array_get($item, 'Config.AttachStderr'),
            'Networks' => array_get($item, 'NetworkSettings.Networks'),
            'Tty' => array_get($item, 'Config.Tty'),
            'OpenStdin' => array_get($item, 'Config.OpenStdin'),
            'StdinOnce' => array_get($item, 'Config.StdinOnce'),
            'Env' => array_get($item, 'Config.Env'),
            'Cmd' => array_get($item, 'Config.Cmd'),
//            'Healthcheck' => array_get($item, 'Config.Healthcheck'),
            'Mounts' => array_get($item, 'Mounts'),
            'WorkingDir' => array_get($item, 'WorkingDir.WorkingDir'),
            'Entrypoint' => array_get($item, 'Entrypoint'),
            'NetworkDisabled' => array_get($item, 'NetworkDisabled'),
            'MacAddress' => array_get($item, 'MacAddress'),
            'Labels' => array_get($item, 'Labels'),
            'StopSignal' => array_get($item, 'Config.StopSignal'),
            'StopTimeout' => array_get($item, 'Config.StopTimeout'),
            'HostConfig' => array_get($item, 'HostConfig'),
            'Created' => array_get($item, 'Created'),
            'RestartCount' => array_get($item, 'RestartCount'),
            'Args' => array_get($item, 'Args'),
            'State' => array_get($item, 'State'),
            'Ports' => array_get($item, 'Ports'),
        ]);
    }

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

    public static function getLogs($endpoint, $Id, $stdout=1, $stderr=0, $since=0, $timestamps=false, $tail='all'){
        $uri = 'http://' . $endpoint . ':' . env('DOCKER_PORT', '2376') . '/' . env('DOCKER_VERSION', 'v1.30') . '/';
        $params = "?follow=false&stdout=$stdout&stderr=$stderr&since=$since&timestamps=$timestamps&tail=$tail";
        $item = parent::HttpGet(str_replace('{id}', $Id, static::LOG_PATH) . $params, $uri);
        dd($item);
    }
}
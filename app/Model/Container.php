<?php
namespace App\Model;

class Container extends DockerApiModel {

    protected static $getPath = 'containers/{id}/json';
    protected static $findPath = 'containers/json';

    const LIST_PATH = 'containers/json';
    const GET_PATH = 'containers/{id}/json';
    const LOG_PATH = 'containers/{id}/logs';

    public static function get($endpoint, $Id){
        $uri = 'http://' . $endpoint . ':' . env('DOCKER_PORT', '2376') . '/' . env('DOCKER_VERSION', 'v1.30') . '/';
        $item = static::HttpGet(str_replace('{id}', $Id, self::GET_PATH), $uri);
        return static::getInstance($item);
    }

    public static function find($endpoint){
        $uri = 'http://' . $endpoint . ':' . env('DOCKER_PORT', '2376') . '/' . env('DOCKER_VERSION', 'v1.30') . '/';
        $result = [];
        $list = static::HttpGet(self::LIST_PATH, $uri);

        foreach($list as $item){
            array_push($result, static::getInstance($item));
        }
        return $result;
    }

    private static function getInstance($item){
        $entity = static::getInstanceByJson($item);
        $entity->Name = is_array($entity->Names) && count($entity->Names)>0 ? substr($entity->Names[0], 1, strlen($entity->Names[0])) : '';
        $entity->Image = substr($entity->Image, 0, strpos($entity->Image, '@'));
        $entity->Stack = is_array($entity->Labels) && isset($entity->Labels['com.docker.stack.namespace']) ? $entity->Labels['com.docker.stack.namespace'] : '';
        $entity->NodeID = is_array($entity->Labels) && isset($entity->Labels['com.docker.swarm.node.id']) ? $entity->Labels['com.docker.swarm.node.id'] : '';
        $entity->ServiceID = is_array($entity->Labels) && isset($entity->Labels['com.docker.swarm.service.id']) ? $entity->Labels['com.docker.swarm.service.id'] : '';
        $entity->ServiceName = is_array($entity->Labels) && isset($entity->Labels['com.docker.swarm.service.name']) ? $entity->Labels['com.docker.swarm.service.name'] : '';
        $entity->TaskID = is_array($entity->Labels) && isset($entity->Labels['com.docker.swarm.task.id']) ? $entity->Labels['com.docker.swarm.task.id'] : '';
        $entity->TaskName = is_array($entity->Labels) && isset($entity->Labels['com.docker.swarm.task.name']) ? $entity->Labels['com.docker.swarm.task.name'] : '';
        $entity->IPAddress = '';
        if(is_array($entity->NetworkSettings) && is_array($entity->NetworkSettings['Networks']) && count($entity->NetworkSettings['Networks'])>0){
            $network = current($entity->NetworkSettings['Networks']);
            $entity->IPAddress = array_get($network, 'IPAddress', '');
        }
        $publishedPorts = [];
        $ports = array_get($item, 'Ports', []);
        foreach($ports as $port){
            if(array_get($port, 'PublicPort', false)){
                array_push($publishedPorts, array_get($port, 'PublicPort') . ':' . array_get($port, 'PrivatePort'));
            }
        }
        $entity->PublishedPorts = implode(' ', $publishedPorts);

        return $entity;
    }

    public static function getLogs($endpoint, $Id, $stdout=1, $stderr=0, $since=0, $timestamps=false, $tail='all'){
        $uri = 'http://' . $endpoint . ':' . env('DOCKER_PORT', '2376') . '/' . env('DOCKER_VERSION', 'v1.30') . '/';
        $params = "?follow=false&stdout=$stdout&stderr=$stderr&since=$since&timestamps=$timestamps&tail=$tail";

        $item = parent::HttpGet(str_replace('{id}', $Id, static::LOG_PATH) . $params, $uri);
        return $item;
    }
}
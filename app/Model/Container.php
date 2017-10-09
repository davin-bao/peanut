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
        return static::getInstanceByJson($item);
    }

    public static function find($endpoint){
        $result = [];
        $uri = 'http://' . $endpoint . ':' . env('DOCKER_PORT', '2376') . '/' . env('DOCKER_VERSION', 'v1.30') . '/';

        $result = [];
        $list = static::HttpGet(self::LIST_PATH, $uri);

        foreach($list as $item){
            array_push($result, static::getInstanceByJson($item));
        }
        return $result;
    }

    public static function getLogs($endpoint, $Id, $stdout=1, $stderr=0, $since=0, $timestamps=false, $tail='all'){
        $uri = 'http://' . $endpoint . ':' . env('DOCKER_PORT', '2376') . '/' . env('DOCKER_VERSION', 'v1.30') . '/';
        $params = "?follow=false&stdout=$stdout&stderr=$stderr&since=$since&timestamps=$timestamps&tail=$tail";

        $item = parent::HttpGet(str_replace('{id}', $Id, static::LOG_PATH) . $params, $uri);
        return $item;
    }
}
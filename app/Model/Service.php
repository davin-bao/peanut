<?php
namespace App\Model;

use App\Exceptions\NoticeMessageException;

class Service extends DockerApiModel {
    const GET_PATH = 'services/{id}';
    const LIST_PATH = 'services';
    const CREATE_PATH = 'services/create';
    const REMOVE_PATH = 'services/';
    const UPDATE_PATH = 'services/{id}/update';
    const LOG_PATH = 'services/{id}/logs';

    public static function get($Id){
        $item = static::HttpGet(str_replace('{id}', $Id, self::GET_PATH));
        return static::getInstanceByJson($item);
    }

    public static function find(){
        $result = [];
        $list = parent::HttpGet( static::LIST_PATH);

        foreach($list as $item){
            $entity = static::getInstanceByJson($item);
            $publishedPorts = [];
            $ports = array_get($item, 'Endpoint.Ports', []);
            foreach($ports as $port){
                if(array_get($port, 'PublishedPort', false)){
                    array_push($publishedPorts, array_get($port, 'PublishedPort') . ':' . array_get($port, 'TargetPort'));
                }
            }
            $entity->PublishedPorts = implode(' ', $publishedPorts);
            $entity->Image = array_get(array_get($item, "Spec.Labels"), 'com.docker.stack.image', '');
            $entity->Stack = array_get(array_get($item, "Spec.Labels"), 'com.docker.stack.namespace', '');
            array_push($result, $entity);
        }
        return $result;
    }

    public static function create($request){
        if(!$request->get('Name', null)){
            throw new NoticeMessageException('Name field is required');
        }
        $attributes = [
            'Name' => $request->get('Name'),
            'CheckDuplicate' => $request->get('CheckDuplicate', false),
            'Driver' => $request->get('Driver'),
            'Internal' => $request->get('Internal'),
            'Attachable' => $request->get('Attachable'),
            'Ingress' => $request->get('Ingress'),
            'EnableIPv6' => $request->get('EnableIPv6')
        ];

        $configItem = [];
        if($subnet = $request->get('Subnet', null)){
            $configItem['Subnet']=$subnet;
        }
        if($gateway = $request->get('Gateway', null)){
            $configItem['Gateway']=$gateway;
        }
        if(count($configItem)>0){
            $attributes['IPAM'] = [];
            $attributes['IPAM']['Config'] = [$configItem];
        }

        $entities = $request->get('Options', []);
        if(is_array($entities) && count($entities) > 0){
            $attributes['Options'] = [];
            foreach($entities as $entity){
                if(!empty($entity['name']) && !empty($entity['value'])) $attributes['Options'][$entity['name']] = $entity['value'];
            }
        }
        $entities = $request->get('Labels', []);
        if(is_array($entities) && count($entities) > 0){
            $attributes['Labels'] = [];
            foreach($entities as $entity){
                if(!empty($entity['name']) && !empty($entity['value'])) $attributes['Labels'][$entity['name']] = $entity['value'];
            }
        }

        return parent::HttpPost(static::CREATE_PATH, $attributes);
    }

    public function remove(){
        return parent::HttpDelete(static::REMOVE_PATH . $this->Id);
    }

    public static function getLogs($Id, $stdout=1, $stderr=0, $since=0, $timestamps=false, $tail='all'){
        $params = "?follow=false&stdout=$stdout&stderr=$stderr&since=$since&timestamps=$timestamps&tail=$tail";
        $item = parent::HttpGet(str_replace('{id}', $Id, static::LOG_PATH) . $params);
        return $item;
    }
}
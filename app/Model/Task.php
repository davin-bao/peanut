<?php
namespace App\Model;

use App\Exceptions\NoticeMessageException;

class Task extends DockerApiModel {
    const GET_PATH = 'tasks/';
    const LIST_PATH = 'tasks';
    const CREATE_PATH = 'tasks/create';
    const REMOVE_PATH = 'tasks/';
    const UPDATE_PATH = 'tasks/{$Id}/update';
    const LOGS_PATH = 'tasks/{$Id}/logs';

    public static function get($Id){
        $item = parent::HttpGet(static::GET_PATH . $Id);

        $publishedPorts = [];
        $ports = array_get($item, 'Endpoint.Ports', []);
        foreach($ports as $port){
            if(array_get($port, 'PublishedPort', false)){
                array_push($publishedPorts, array_get($port, 'PublishedPort') . ':' . array_get($port, 'TargetPort'));
            }
        }

        return new Task([
            'ID' => array_get($item, 'ID'),
            'Version' => array_get($item, 'Version.Index'),
            'CreatedAt' => array_get($item, 'CreatedAt'),
            'UpdatedAt' => array_get($item, 'UpdatedAt'),
            'Name' => array_get($item, 'Spec.Name'),
            'Labels' => array_get($item, 'Spec.Labels'),
            'TaskTemplate' => array_get($item, 'Spec.TaskTemplate'),
            'EndpointSpec' => array_get($item, 'Spec.EndpointSpec'),
            'Mode' => array_get($item, 'Spec.Mode'),
            'Image' => array_get(array_get($item, "Spec.Labels"), 'com.docker.stack.image', ''),
            'Stack' => array_get(array_get($item, "Spec.Labels"), 'com.docker.stack.namespace', ''),
            'SchedulingMode' => array_get($item, "Spec.Mode.Replicated.Replicas"),
            'PublishedPorts' => implode(' ', $publishedPorts),
            'Endpoint' => array_get($item, 'Endpoint')
        ]);
    }

    public static function find($serviceId = null){
        $result = [];
        $list = parent::HttpGet( static::LIST_PATH);

        foreach($list as $item){
            if(is_null($serviceId) || $item->ServiceID === $serviceId) {
                array_push($result, new Task([
                    'ID' => array_get($item, 'ID'),
                    'Version' => array_get($item, 'Version.Index'),
                    'CreatedAt' => array_get($item, 'CreatedAt'),
                    'UpdatedAt' => array_get($item, 'UpdatedAt'),
                    'Labels' => array_get($item, 'Labels'),
                    'Spec' => array_get($item, 'Spec'),
                    'ServiceID' => array_get($item, 'ServiceID'),
                    'Slot' => array_get($item, 'Slot'),
                    'NodeID' => array_get($item, 'NodeID'),
                    'Status' => array_get($item, 'Status'),
                    'DesiredState' => array_get($item, 'DesiredState'),
                    'NetworksAttachments' => array_get($item, "NetworksAttachments")
                ]));
            }
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
}
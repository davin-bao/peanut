<?php
namespace App\Model;

use App\Exceptions\NoticeMessageException;

class Network extends DockerApiModel {
    const GET_PATH = 'networks/';
    const LIST_PATH = 'networks';
    const CREATE_PATH = 'networks/create';
    const REMOVE_PATH = 'networks/';

    public static function get($Id){
        $item = parent::HttpGet(static::GET_PATH . $Id);

        return new Network([
            'Id' => array_get($item, 'Id'),
            'Name' => array_get($item, 'Name'),
            'Created' => array_get($item, 'Created'),
            'Scope' => array_get($item, 'Scope'),
            'Driver' => array_get($item, 'Driver'),
            'EnableIPv6' => array_get($item, 'EnableIPv6'),
            'IPAM' => array_get($item, 'IPAM'),
            'Internal' => array_get($item, 'Internal'),
            'Attachable' => array_get($item, 'Attachable'),
            'Ingress' => array_get($item, 'Ingress'),
            'ConfigFrom' => array_get($item, 'ConfigFrom'),
            'ConfigOnly' => array_get($item, 'ConfigOnly'),
            'Containers' => array_get($item, 'Containers'),
            'Options' => array_get($item, 'Options'),
            'Labels' => array_get($item, 'Labels'),
            'Containers' => array_get($item, 'Containers')
        ]);
    }

    public static function find(){
        $result = [];
        $list = parent::HttpGet( static::LIST_PATH);

        foreach($list as $item){
            array_push($result, new Network([
                'Id' => array_get($item, 'Id'),
                'Name' => array_get($item, 'Name'),
                'Created' => array_get($item, 'Created'),
                'Scope' => array_get($item, 'Scope'),
                'Driver' => array_get($item, 'Driver'),
                'EnableIPv6' => array_get($item, 'EnableIPv6'),
                'IPAM' => array_get($item, 'IPAM'),
                'Internal' => array_get($item, 'Internal'),
                'Attachable' => array_get($item, 'Attachable'),
                'Ingress' => array_get($item, 'Ingress'),
                'ConfigFrom' => array_get($item, 'ConfigFrom'),
                'ConfigOnly' => array_get($item, 'ConfigOnly'),
                'Containers' => array_get($item, 'Containers'),
                'Options' => array_get($item, 'Options'),
                'Labels' => array_get($item, 'Labels'),
                'Containers' => array_get($item, 'Containers')
            ]));
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
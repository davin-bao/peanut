<?php
namespace App\Model;

use App\Exceptions\NoticeMessageException;

class Network extends DockerApiModel {
    const GET_PATH = 'networks/{id}';
    const LIST_PATH = 'networks';
    const CREATE_PATH = 'networks/create';
    const REMOVE_PATH = 'networks/';

    public static function get($Id){
        $item = static::HttpGet(str_replace('{id}', $Id, self::GET_PATH));
        return static::getInstanceByJson($item);
    }

    public static function find(){
        $result = [];
        $list = static::HttpGet(self::LIST_PATH);

        foreach($list as $item){
            array_push($result, static::getInstanceByJson($item));
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
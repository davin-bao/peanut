<?php
namespace App\Model;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Network extends DockerApiModel {
    const GET_PATH = 'networks/';
    const LIST_PATH = 'networks';
    const CREATE_PATH = 'networks/create';
    const REMOVE_PATH = 'networks/';

    public static function get($Id){
        $item = parent::HttpGet(static::GET_PATH . $Id);

        return new Network([
            'Id' => $item->Id,
            'Name' => $item->Name,
            'Created' => $item->Created,
            'Scope' => $item->Scope,
            'Driver' => $item->Driver,
            'EnableIPv6' => $item->EnableIPv6,
            'IPAM' => $item->IPAM,
            'Internal' => $item->Internal,
            'Attachable' => $item->Attachable,
            'Ingress' => $item->Ingress,
            'ConfigFrom' => $item->ConfigFrom,
            'ConfigOnly' => $item->ConfigOnly,
            'Containers' => $item->Containers,
            'Options' => $item->Options,
            'Labels' => $item->Labels,
            'Containers' => $item->Containers
        ]);
    }

    public static function find(){
        $result = [];
        $list = parent::HttpGet( static::LIST_PATH);

        foreach($list as $item){
            array_push($result, new Network([
                'Id' => $item->Id,
                'Name' => $item->Name,
                'Created' => $item->Created,
                'Scope' => $item->Scope,
                'Driver' => $item->Driver,
                'EnableIPv6' => $item->EnableIPv6,
                'IPAM' => $item->IPAM,
                'Internal' => $item->Internal,
                'Attachable' => $item->Attachable,
                'Ingress' => $item->Ingress,
                'ConfigFrom' => $item->ConfigFrom,
                'ConfigOnly' => $item->ConfigOnly,
                'Containers' => $item->Containers,
                'Options' => $item->Options,
                'Labels' => $item->Labels
            ]));
        }
        return $result;
    }

    public static function create($request){
        if(!$request->get('Name', null)){
            throw new NotFoundHttpException('Name field is required');
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
<?php
namespace App\Model;

class Node extends DockerApiModel {

    const LIST_PATH = 'nodes';
    const GET_PATH = 'nodes/{id}';
    const REMOVE_PATH = 'nodes/{id}';

    public static function get($Id, $uri=null){
        $item = static::HttpGet(str_replace('{id}', $Id, self::GET_PATH), $uri);
        return static::getInstanceByJson($item);
    }

    public static function find($uri=null){
        $result = [];
        $list = static::HttpGet(self::LIST_PATH, $uri);

        foreach($list as $item){
            $entity = static::getInstanceByJson($item);
            array_push($result, static::getInstanceByJson($item));
        }
        return $result;
    }

    public function update($availability, $name, $role, $labels){

        $this->Spec = [
            'Availability' => $availability ? $availability : array_get($this->Spec, 'Availability', 'active'),
            'Name' => $name ? $name : array_get($this->Spec, 'Name', ''),
            'Role' => $role ? $role : array_get($this->Spec, 'Role', 'manager'),
            'Labels' => $labels ? $labels : array_get($this->Spec, 'Labels', [])
        ];

        $labels = [];
        foreach(array_get($this->Spec, 'Labels', []) as $label){
            if(!empty(array_get($label,'name')) && !empty(array_get($label,'value'))){
                $labels[array_get($label,'name')] = array_get($label,'value');
            }
        }
        $attributes = [
            'Availability' => $availability ? $availability : array_get($this->Spec, 'Availability', 'active'),
            'Name' => $name ? $name : array_get($this->Spec, 'Name', ''),
            'Role' => $role ? $role : array_get($this->Spec, 'Role', 'manager'),
            'Labels' => count($labels) == 0 ? null : $labels
        ];

        return parent::HttpPost('nodes/' . $this->ID . '/update?version=' . $this->Version['Index'], $attributes);
    }

    public function remove(){
        return str_replace('{id}', $this->Id, self::REMOVE_PATH);
//        return parent::HttpDelete(str_replace('{id}', $this->Id, self::REMOVE_PATH));
    }
}
<?php
namespace App\Model;

class Node extends DockerApiModel {

    const LIST_PATH = 'nodes';
    const GET_PATH = 'nodes/{id}';

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
        $this->Availability = $availability ? $availability : $this->Availability;
        $this->Name = $name ? $name : $this->Name;
        $this->Role = $role ? $role : $this->Role;
        $this->Labels = $labels ? $labels : $this->Labels;
        $attributes = [
            'Availability' => $this->Availability,
            'Name' => $this->Name,
            'Role' => $this->Role,
            'Labels' => (object)$this->Labels,
        ];

        return parent::HttpPost('nodes/' . $this->ID . '/update?version=' . $this->Version->Index, $attributes);
    }
}
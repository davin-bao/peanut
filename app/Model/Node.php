<?php
namespace App\Model;

class Node extends DockerApiModel {

    public static function get($id){
        $item = parent::HttpGet('nodes/' . $id);

        return new Node([
            'ID' => array_get($item, 'ID'),
            'Version' => array_get($item, 'Version.Index'),
            'CreatedAt' => array_get($item, 'CreatedAt'),
            'UpdatedAt' => array_get($item, 'UpdatedAt'),
            'Name' => array_get($item, 'Spec.Name', ''),
            'Availability' => array_get($item, 'Spec.Availability'),
            'Labels' => array_get($item, 'Spec.Labels'),
            'Role' => array_get($item, 'Spec.Role'),
            'Description'=> array_get($item, 'Description'),
            'Status' => array_get($item, 'Status'),
            'ManagerStatus' => array_get($item, 'ManagerStatus'),
        ]);
    }

    public static function find(){
        $result = [];
        $list = parent::HttpGet('nodes');

        foreach($list as $item){
            array_push($result, new Node([
                'ID' => array_get($item, 'ID'),
                'Version' => array_get($item, 'Version.Index'),
                'CreatedAt' => array_get($item, 'CreatedAt'),
                'UpdatedAt' => array_get($item, 'UpdatedAt'),
                'Name' => array_get($item, 'Spec.Name', ''),
                'Availability' => array_get($item, 'Spec.Availability'),
                'Labels' => array_get($item, 'Spec.Labels'),
                'Role' => array_get($item, 'Spec.Role'),
                'Description'=> array_get($item, 'Description'),
                'Status' => array_get($item, 'Status'),
                'ManagerStatus' => array_get($item, 'ManagerStatus'),
            ]));
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

        return parent::HttpPost('nodes/' . $this->ID . '/update?version=' . $this->Version, $attributes);
    }
}
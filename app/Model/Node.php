<?php
namespace App\Model;

class Node extends DockerApiModel {

    public function get($id){
        $nodeInfo = $this->HttpGet('nodes/' + $id);
    }

    public static function find(){
        $result = [];
        $list = Node::HttpGet('nodes');

        foreach($list as $item){
            array_push($result, new Node([
                'ID' => $item->ID,
                'Version' => $item->Version->Index,
                'CreatedAt' => $item->CreatedAt,
                'UpdatedAt' => $item->UpdatedAt,
                'Name' => isset($item->Spec->Name) ? $item->Spec->Name : '',
                'Availability' => $item->Spec->Availability,
                'Labels' => $item->Spec->Labels,
                'Role' => $item->Spec->Role,
                'Description'=> $item->Description,
                'Status' => $item->Status,
                'ManagerStatus' => isset($item->ManagerStatus) ? $item->ManagerStatus : '',
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

        return Node::HttpPost('nodes/' . $this->ID . '/update?version=' . $this->Version, $attributes);
    }
}
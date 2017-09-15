<?php
namespace App\Model;

class Node extends DockerApiModel {

    public function get($id){
        $nodeInfo = $this->HttpGet('nodes/' + $id);
    }

    public static function find(){
        $nodeList = [];
        $nodeDataList = Node::HttpGet('nodes');

        foreach($nodeDataList as $nodeData){
            array_push($nodeList, new Node([
                'ID' => $nodeData->ID,
                'Version' => $nodeData->Version->Index,
                'CreatedAt' => $nodeData->CreatedAt,
                'UpdatedAt' => $nodeData->UpdatedAt,
                'Name' => isset($nodeData->Spec->Name) ? $nodeData->Spec->Name : '',
                'Availability' => $nodeData->Spec->Availability,
                'Labels' => $nodeData->Spec->Labels,
                'Role' => $nodeData->Spec->Role,
                'Description'=> $nodeData->Description,
                'Status' => $nodeData->Status,
                'ManagerStatus' => $nodeData->ManagerStatus
            ]));
        }
        return $nodeList;
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
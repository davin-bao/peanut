<?php
namespace App\Model;

class Network extends DockerApiModel {
    const LIST_PATH = 'networks';

    public static function find(){
        $result = [];
        $list = Network::HttpGet( static::LIST_PATH);

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
}
<?php
namespace App\Model;

use App\Exceptions\NoticeMessageException;

class Stack extends DockerApiModel {

    public static function get($Name){
        $result = new Stack();
        $serviceList = [];
        $services = Service::find();
        foreach($services as $service){
            if($service->Stack == $Name){
                $result->Name = $Name;
                $result->Image = $service->Image;
                $result->UpdatedAt = $service->UpdatedAt;
                array_push($serviceList, $service->ID);
            }
        }
        $result->Services = $serviceList;
        $result->Count = count($serviceList);

        return $result;
    }

    public static function find(){

        $result = [];
        $serviceList = [];
        $services = Service::find();
        foreach($services as $service){
            if(!isset($result[$service->Stack])){
                $result[$service->Stack] = new Stack([
                    'Name' => $service->Stack,
                    'Image' => $service->Image,
                    'UpdatedAt' => $service->UpdatedAt
                ]);

                $serviceList[$service->Stack] = [];
            }
            array_push($serviceList[$service->Stack], $service->ID);
        }

        foreach($result as $item){
            $item->Service = $serviceList[$item->Name];
            $item->Count = count($serviceList[$item->Name]);
        }

        return $result;
    }

    public static function create($request){
        if(!$request->get('Name', null)){
            throw new NoticeMessageException('Name field is required');
        }
        if(!$composeName = $request->get('ComposeFileName', null)){
            throw new NoticeMessageException('Compose File Name field is required');
        }

        $compose = Compose::get($composeName);



    }

    public function remove(){
        $services = Service::find();
        foreach($services as $service) {
            if ($service->Stack == $this->Name) {
                $service->remove();
            }
        }
    }
}
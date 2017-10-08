<?php
namespace App\Model;

use App\Exceptions\NoticeMessageException;

class Task extends DockerApiModel {
    const GET_PATH = 'tasks/{$id}';
    const LIST_PATH = 'tasks';
    const CREATE_PATH = 'tasks/create';
    const REMOVE_PATH = 'tasks/';
    const UPDATE_PATH = 'tasks/{$Id}/update';
    const LOGS_PATH = 'tasks/{$Id}/logs';

    public static function get($Id){
        $item = static::HttpGet(str_replace('{id}', $Id, self::GET_PATH));
        return static::getInstanceByJson($item);
    }

    public static function find($serviceId = null){
        $result = [];
        $list = parent::HttpGet( static::LIST_PATH);

        foreach($list as $item){
            if(is_null($serviceId) || $item->ServiceID === $serviceId) {
                array_push($result, static::getInstanceByJson($item));
            }
        }
        return $result;
    }

    public function remove(){
        return parent::HttpDelete(static::REMOVE_PATH . $this->Id);
    }
}
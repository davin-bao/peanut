<?php
namespace App\Model;

use App\Exceptions\NoticeMessageException;

class Compose extends DockerApiModel {
    const SAVE_PATH = 'compose';

    public static function get($Name){
        $root = storage_path(static::SAVE_PATH);
        $fileName = $root . DIRECTORY_SEPARATOR . $Name . '.yml';
        if(!file_exists($fileName)){
            throw new NoticeMessageException('Can not find the compose data file');
        }
        return new Compose([
            'Name' => $Name,
            'Content' => file_get_contents($fileName)
        ]);
    }

    public static function find(){
        $root = storage_path(static::SAVE_PATH);

        $result = [];
        $files = scandir($root);
        foreach($files as $file){
            if(pathinfo($file, PATHINFO_EXTENSION) == 'yml'){
                array_push($result,
                    new Compose([
                        'Name' => str_replace('.yml', '', $file),
                        'Content' => ''
                    ]));
            }
        }

        return $result;
    }

    public static function create($request){
        if(!$Name = $request->get('Name', null)){
            throw new NoticeMessageException('Name field is required');
        }
        if(!$Content = $request->get('Content', null)){
            throw new NoticeMessageException('Content field is required');
        }
        $root = storage_path(static::SAVE_PATH);
        $fileName = $root . DIRECTORY_SEPARATOR . $Name . '.yml';
        if(file_exists($fileName)){
            throw new NoticeMessageException('Compose data file is exist');
        }

        file_put_contents($fileName, $Content);

        return [];
    }

    public function remove(){
        $root = storage_path(static::SAVE_PATH);
        $fileName = $root . DIRECTORY_SEPARATOR . $this->Name . '.yml';
        if(file_exists($fileName)){
            unlink($fileName);
        }
        return [];
    }
}
<?php
namespace App\Model;

use Illuminate\Support\Facades\Cache;

class System extends DockerApiModel {

    public static function getCommand(){
//        Cache::flush();
        $commandList = Cache::get('command');
        $commandList = is_array($commandList) ? $commandList : [];
        $command = array_pop($commandList);
        Cache::put('command', $commandList, 10);

        return $command;
    }

    public static function putCommand($Id, $Command){
        $commandList = Cache::get('command');
        $commandList = is_array($commandList) ? $commandList : [];
        array_push($commandList, ['Id' => $Id, 'Command' => $Command]);

        Cache::put('command', $commandList, 10);
    }

    public static function saveCommandResult($Id, $Result){
        Cache::put('command-result:' . $Id, $Result, 10);
    }

    public static function getCommandResult($Id){
        return Cache::get('command-result:' . $Id);
    }
}
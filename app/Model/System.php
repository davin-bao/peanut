<?php
namespace App\Model;

use Illuminate\Support\Facades\Cache;

class System extends DockerApiModel {

    public static function getAddWorkNodeCommand(){
        $host = env('DOCKER_URL', 'http://127.0.0.1');
        $uri = $host . ':8888/';
        $result = static::HttpGet('?command=docker%20swarm%20join-token%20worker', $uri);
        $result = array_get($result, 'Result', '');
        $splitStr = 'following command:';
        if(strpos($result, $splitStr) < 0) {
            throw new NoticeMessageException('Get swarm join command failed');
        }

        list($title, $command) = explode($splitStr, $result);
        return trim($command);
    }

    public static function getAddManagerNodeCommand(){
        $host = env('DOCKER_URL', 'http://127.0.0.1');
        $uri = $host . ':8888';
        $result = static::HttpGet('?command=docker%20swarm%20join-token%20manager', $uri);
        $result = array_get($result, 'Result', '');
        $splitStr = 'following command:';
        if(strpos($result, $splitStr) < 0) {
            throw new NoticeMessageException('Get swarm join command failed');
        }

        list($title, $command) = explode($splitStr, $result);
        return trim($command);
    }

    public static function getStatus($uri){
        return [
            'cpu' => random_int(0, 100),
            'memTotal' => 1000,
            'memFree' => random_int(0, 1000)
        ];
        $uri = 'http://' . $uri . ':8888';
        //%Cpu(s): 0.9 us, 0.3 sy, 0.0 ni, 98.7 id, 0.1 wa, 0.0 hi, 0.0 si, 0.1 st@KiB Mem : 16266460 total, 7605332 free, 4267496 used, 4393632 buff/cache
        $statusStr = static::HttpGet('?command=status', $uri);
        if(!is_array($statusStr)){
            throw new NoticeMessageException('Get node status failed');
        }
        list($cpuStr, $memStr) = explode('@', array_get($statusStr, 'Result'));

        $cpuPercent = 0;
        $cpuList= explode(',', $cpuStr);
        if(count($cpuList) >= 4) {
            $cpuPercentStr = $cpuList[3];
            $cpuPercent = trim(str_replace('id', '', $cpuPercentStr));
        }
        $memTotal = 0;
        $memFree = 0;
        $memList = explode(',', $memStr);
        if(count($memList) >= 1){
            $memTotal = trim(str_replace('st@KiB Mem :', '', str_replace('total', '', $memList[0])));
            $memFree = trim(str_replace('free', '', $memList[1]));
        }

        return [
            'cpu' => $cpuPercent,
            'memTotal' => $memTotal,
            'memFree' => $memFree
        ];
    }

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
<?php

namespace App\Http\Controllers;

use App\Model\Container;
use Illuminate\Http\Request;

class ContainerController extends Controller
{

    public function getDetail($endpoint, $Id){
        $endpoint = str_replace('-', '.', $endpoint);
        $item = Container::get($endpoint, $Id);
        return $this->response($item);
    }

    public function getLog(Request $request, $endpoint, $Id){
        $endpoint = str_replace('-', '.', $endpoint);
        $stdout=$request->get('stdout', 1);
        $stderr=$request->get('stderr', 0);
        $since=$request->get('since', 0);
        $timestamps=$request->get('timestamps', 1);
        $tail=$request->get('tail', '2000');
        $item = Container::getLogs($endpoint, $Id, $stdout, $stderr, $since, $timestamps, $tail);

        echo $item;
    }

    public function getList($endpoint){
        $result = [];
        $endpoint = str_replace('-', '.', $endpoint);
        $list = Container::find($endpoint);
        foreach($list as $item){
            array_push($result, $item->toArray());
        }

        return $this->response($result);
    }

    //
}

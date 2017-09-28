<?php

namespace App\Http\Controllers;

use App\Model\Container;

class ContainerController extends Controller
{

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

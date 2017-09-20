<?php

namespace App\Http\Controllers;

use App\Model\Container;

class ContainerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getList($endpoint){
        $result = [];
        $list = Container::find($endpoint);
        foreach($list as $item){
            array_push($result, $item->toArray());
        }
        sleep(1);

        return $this->response($result);
    }

    //
}

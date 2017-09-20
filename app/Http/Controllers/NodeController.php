<?php

namespace App\Http\Controllers;

use App\Model\Node;

class NodeController extends Controller
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

    public function getList(){
        $result = [];
        $list = Node::find();
        foreach($list as $item){
            array_push($result, $item->toArray());
        }
        sleep(3);

        return $this->response($result);
    }

    //
}

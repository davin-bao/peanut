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
        $nodeList = Node::find();
        foreach($nodeList as $node){
            array_push($result, $node->toArray());
        }

        return response()->json($result, 200);
    }

    //
}

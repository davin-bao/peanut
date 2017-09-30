<?php

namespace App\Http\Controllers;

use App\Model\Node;
use App\Model\System;
use Illuminate\Http\Request;

class NodeController extends Controller
{

    public function getList(){
        $result = [];
        $list = Node::find();
        foreach($list as $item){
            array_push($result, $item->toArray());
        }

        return $this->response($result);
    }

    public function getDetail($Id){
        $item = Node::get($Id);
        return $this->response($item);
    }

    public function postUpdate($id, Request $request){
        $availability = $request->get('availability', null);
        $name = $request->get('name', null);
        $role = $request->get('role', null);
        $labels = $request->get('labels', null);
        $node = Node::get($id);
        $node->update($availability, $name, $role, $labels);

        return $this->response([]);
    }

    public function getStatus($endpoint){
        $endpoint = str_replace('-', '.', $endpoint);
        $result = System::getStatus($endpoint);

        return $this->response($result);
    }

    //
}

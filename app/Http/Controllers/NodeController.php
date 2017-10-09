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
        $availability = $request->get('Availability', null);
        $name = $request->get('Name', null);
        $role = $request->get('Role', null);
        $labels = $request->get('Labels', null);
        $node = Node::get($id);
        $node->update($availability, $name, $role, $labels);

        return $this->response([]);
    }

    public function postRemove($Id){
        $item = Node::get($Id);
        $item->remove();

        return $this->response();
    }

    public function getStatus($endpoint){
        $endpoint = str_replace('-', '.', $endpoint);
        $result = System::getStatus($endpoint);

        return $this->response($result);
    }

    public function getCreateCommand(){
        $workerCommand = System::getAddWorkNodeCommand();
        $managerCommand = System::getAddManagerNodeCommand();
        return $this->response([
            'workCommand' => $workerCommand,
            'managerCommand' => $managerCommand
        ]);
    }

    //
}

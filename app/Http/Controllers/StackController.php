<?php

namespace App\Http\Controllers;

use App\Model\Stack;
use Illuminate\Http\Request;

class StackController extends Controller
{

    public function getList(){
        $result = [];
        $list = Stack::find();
        foreach($list as $item){
            array_push($result, $item->toArray());
        }

        return $this->response($result);
    }

    public function getDetail($Id){
        $item = Stack::get($Id);
        return $this->response($item);
    }

    public function postCreate(Request $request){
        Stack::create($request);

        return $this->response();
    }

    public function postRemove($Id){
        $item = Stack::get($Id);
        $item->remove();

        return $this->response();
    }

    //
}

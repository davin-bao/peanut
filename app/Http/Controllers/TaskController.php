<?php

namespace App\Http\Controllers;

use App\Model\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function getList(Request $request){
        $result = [];
        $list = Task::find($request->get('serviceId', null));
        foreach($list as $item){
            array_push($result, $item->toArray());
        }

        return $this->response($result);
    }

    public function postCreate(Request $request){
        Task::create($request);

        return $this->response();
    }

    public function postRemove($Id){
        $item = Task::get($Id);
        $item->remove();

        return $this->response();
    }

    //
}

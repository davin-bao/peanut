<?php

namespace App\Http\Controllers;

use App\Model\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{

    public function getList(){
        $result = [];
        $list = Service::find();
        foreach($list as $item){
            array_push($result, $item->toArray());
        }

        return $this->response($result);
    }

    public function postCreate(Request $request){
        Service::create($request);

        return $this->response();
    }

    public function postRemove($Id){
        $item = Service::get($Id);
        $item->remove();

        return $this->response();
    }

    //
}

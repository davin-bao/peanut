<?php

namespace App\Http\Controllers;

use App\Model\Network;
use Illuminate\Http\Request;

class NetworkController extends Controller
{

    public function getList(){
        $result = [];
        $list = Network::find();
        foreach($list as $item){
            array_push($result, $item->toArray());
        }

        return $this->response($result);
    }

    public function postCreate(Request $request){
        Network::create($request);

        return $this->response();
    }

    public function postRemove($Id){
        $item = Network::get($Id);
        $item->remove();

        return $this->response();
    }

    //
}

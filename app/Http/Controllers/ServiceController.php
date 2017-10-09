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

    public function getLog(Request $request, $Id){
        $stdout=$request->get('stdout', 1);
        $stderr=$request->get('stderr', 0);
        $since=$request->get('since', 0);
        $timestamps=$request->get('timestamps', 1);
        $tail=$request->get('tail', '2000');
        $item = Service::getLogs($Id, $stdout, $stderr, $since, $timestamps, $tail);

        echo $item;
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

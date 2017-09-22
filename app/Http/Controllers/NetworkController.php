<?php

namespace App\Http\Controllers;

use App\Model\Network;

class NetworkController extends Controller
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
        $list = Network::find();
        foreach($list as $item){
            array_push($result, $item->toArray());
        }
        sleep(1);

        return $this->response($result);
    }

    //
}

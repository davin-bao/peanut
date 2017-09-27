<?php

namespace App\Http\Controllers;

use App\Model\Compose;
use Illuminate\Http\Request;

class ComposeController extends Controller
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
        $list = Compose::find();
        foreach($list as $item){
            array_push($result, $item->toArray());
        }

        return $this->response($result);
    }

    public function postCreate(Request $request){
        Compose::create($request);

        return $this->response();
    }

    public function postRemove($Name){
        $item = Compose::get($Name);
        $item->remove();

        return $this->response();
    }

    //
}

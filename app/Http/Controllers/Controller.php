<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    public function __construct()
    {
        $this->middleware('enable_cross_request');
    }

    public function response($content=[]){
        return response()->json($content, 200);
    }
}

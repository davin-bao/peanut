<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    public function __construct()
    {

    }

    public function response($content=[]){
        return response()->json($content, 200);
    }
}

<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    //

    public function response($content){
        return response()->json($content, 200)->header('Access-Control-Allow-Origin', '*');
    }
}

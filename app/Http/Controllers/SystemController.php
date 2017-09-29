<?php

namespace App\Http\Controllers;

use App\Model\System;
use Illuminate\Http\Request;

class SystemController extends Controller
{

    public function getStatus(){
        $result = System::getStatus();

        return $this->response($result);
    }

    public function postCommand(Request $request){
        System::putCommand($request->get('Id'), $request->get('Command'));

        return $this->response();
    }

    public function getCommandResult($Id){
        $result = System::getCommandResult($Id);

        return $this->response($result);
    }

    public function postCommandResult(Request $request){
        $Id = $request->get('Id');
        $Result = $request->get('Result');
        System::saveCommandResult($Id, $Result);
        \Log::info($Id .' : '. $Result);

        return $this->response();
    }
}

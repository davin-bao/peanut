<?php
namespace App\Model;

class Container extends DockerApiModel {
    public function getByNode(){
        return $this->get('containers');
    }
}
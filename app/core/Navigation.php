<?php

namespace App\Core;

use App\Models\Nav;

class Navigation{

    public function __construct(){
        $this->navModel = new Nav();
    }
    public function index(){
        $data = [];
        $fromTable = $this->navModel->getNav();
        foreach($fromTable as $item){
            $data["$item->name"] = $item->name;
        }
        require_once "../app/views/includes/nav.php";
    }
    
}
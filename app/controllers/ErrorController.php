<?php

namespace App\Controllers;

class Errorcontroller extends Controller{

    public function notfound()
    {
        \http_response_code(404);
        $this->view("notfound");
    }

    public function internalerror(){
        \http_response_code(500);
        $this->view("internalerror");
    }
}
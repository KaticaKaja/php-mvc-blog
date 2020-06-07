<?php

namespace App\Controllers;

class ErrorController extends Controller{

    public function notfound()
    {
        // \header("Content-Type: application/json");
        \http_response_code(404);
        $this->view("notfound");
    }

    public function badrequest(){
        \http_response_code(400);
        $this->view("badrequest");
    }
}
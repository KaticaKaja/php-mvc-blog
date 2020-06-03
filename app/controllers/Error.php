<?php

namespace App\Controllers;

class Error extends Controller{

    public function notfound()
    {
        // \header("Content-Type: application/json");
        \http_response_code(404);
        $this->view("notfound");
    }

    public function notauthorized(){

    }
}
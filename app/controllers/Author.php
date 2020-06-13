<?php 
namespace App\Controllers;

class Author extends Controller{

    public function index(){
        // $data = [];
        $this->view("authorpage");
    }
    
}
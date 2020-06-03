<?php 
namespace App\Controllers;
class Controller{
    // public function __construct(){
    //     echo "helou iz kontrolera";
    // }
    public function view($view, $data=[]){
        $fixedPath = "../app/views";
        if(file_exists("$fixedPath/$view.php")){
            require_once "$fixedPath/includes/header.php";
            require_once "$fixedPath/$view.php";
            require_once "$fixedPath/includes/footer.php";
        }
        
    }
}
<?php 
namespace App\Controllers;
class Controller{
    // public function __construct(){
    //     echo "helou iz kontrolera";
    // }
    public function model($model){
        $model = "App\Models\\$model";
        return new $model();
    }

    public function view($view, $data = [], $optional = []){
        $fixedPath = "../app/views";
        if(file_exists("$fixedPath/$view.php")){
            require_once "$fixedPath/includes/header.php";
            require_once "$fixedPath/$view.php";
            require_once "$fixedPath/includes/footer.php";
        }
        
    }
}
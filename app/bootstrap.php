<?php

use App\Core\Route;
ob_start();
require_once "config/config.php";
require_once "helpers/redirect.php";
require_once "config/autoloading.php";
// require_once "config/connection.php";
// require_once "core/Routes.php";
new Route();
// if($_SERVER['QUERY_STRING'] == ''){
//     echo "prazan<br>";
// }
// else{
//     echo $_SERVER['QUERY_STRING'].'<br>';
// }
// // echo $_GET['url'].'<br>';
// echo $_SERVER['REQUEST_URI'].'<br>';
// $url = trim(REQUEST,'/');
// $url = explode('/',$url);
// var_dump($url);
// echo "<br>";
// if(isset($url[1])){
//     echo "setovano";
// }
// else{
//     echo "nije setovano";
// }
// if(!empty($url[0])){
//     if(file_exists(\APPROOT.'\/controllers/'.$url[0].'.php')){
//         // $this->currentController = ucwords($url[0]);
//         echo "postoji";
//     }
//     else{
//         echo "ne postoji";        
//         // $this->currentController = 'Home';
//     }
// }
// else{
//     echo "postoji";
// }

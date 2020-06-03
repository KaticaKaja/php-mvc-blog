<?php 
spl_autoload_register(function($classname){
    $classname = strtolower($classname);
    $classPath = explode('\\', $classname);
    $lastParth = ucfirst(array_pop($classPath));
    $classPath = implode('/',$classPath);
    //$classPath = strtolower($classPath);
    $classPath.='/'.$lastParth.".php";
    require_once '../'.$classPath;
});
//psr4 standard
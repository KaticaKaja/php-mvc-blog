<?php
namespace App\Core;
class Route {
    
    protected $namespace = "App\Controllers\\";
    protected $currentController;
    protected $currentMethod;
    protected $params = [];
    public function __construct()
    {
        $this->prepareUrl();

        $this->currentController = new $this->currentController;
        
        if(!\method_exists($this->currentController, $this->currentMethod)){
            $this->currentMethod = "notfound";
            $this->currentController = $this->namespace."ErrorController";
            $this->currentController = new $this->currentController;
        }
    
        call_user_func_array([$this->currentController,$this->currentMethod], $this->params);
    }

    protected function prepareUrl(){
        
        $request = filter_var(trim(\REQUEST, '/'),\FILTER_SANITIZE_URL);
        //write this $request in accessLog.txt
        \access_log($request);

        if(!empty($request)){
            $url = \explode('/',$request);
            if(isset($url[0]) && \file_exists(\APPROOT.'\/controllers/'.ucfirst($url[0]).'.php')){
                $this->currentController = $this->namespace.ucfirst($url[0]);
            }
            else{
                $this->currentController = $this->namespace."ErrorController";
                // echo "njema kontroler<br>";
            }
            
            $this->currentMethod = isset($url[1]) ? $url[1] : "index"; //eventualno naci drugo resenje, ako kontroler ne moze da ima defaultni index metod
            unset($url[0]);
            unset($url[1]);
            $this->params = !empty($url) ? \array_values($url) : [];
        }
        else{
            $this->currentController ="App\Controllers\Home";
            $this->currentMethod = "index";
        }
    }

    // public static function set($route, $function){

    //     self::$validRoutes[] = $route;

    //     if(\REQUEST === $route){
    //         $function->__invoke();
    //     }
    // }

}
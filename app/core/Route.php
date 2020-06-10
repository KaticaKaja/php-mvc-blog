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
        $time = time();
        $request = filter_var(trim(\REQUEST, '/'),\FILTER_SANITIZE_URL);
        $logFile = \fopen(\APPROOT."\logs\accessLog.txt", "a");
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
        $content = $time.":".$request.":$userId\n";
        $exc = ['pages/search', 'pages/page', 'pages/filterpagination'];
        if(!\in_array($request, $exc)){
            \fwrite($logFile, $content);
        }
            
        \fclose($logFile);
        // $vremeSad = time();
        // $juce = \strtotime("-1 day",$vremeSad);
        // $pre2 = \strtotime("-2 day", $vremeSad);
        // $juceLepo = \date("d;M;Y;H;i;s", $juce);
        // $prekjuceLepo = \date("d;M;Y;H;i;s", $pre2);
        //upisivanje u pageAcess_log.txt => $request.":".$vreme."\n"; \date("d;M;Y;H;i;s",time());
        // echo $request.":".$vremeSad;
        // echo "<br>$juceLepo";
        // echo "<br>$prekjuceLepo";
        if(!empty($request)){
            $url = \explode('/',$request);
            if(isset($url[0]) && \file_exists(\APPROOT.'\/controllers/'.ucfirst($url[0]).'.php')){
                $this->currentController = $this->namespace.ucfirst($url[0]);
                // echo "usao u setovan kontroler<br>";
                // echo $this->currentController;
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
            $this->currentController ="App\Controllers\Pages";
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
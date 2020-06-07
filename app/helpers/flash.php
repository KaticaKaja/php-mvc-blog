<?php

session_start();

function flash($name = '', $message = '', $class='alert alert-success'){
    if(!empty($name)){
        if(!empty($message) && empty($_SESSION[$name])){
            if(!empty($_SESSION[$name])){
                unset($_SESSION[$name]);
            }
            if(!empty($_SESSION[$name.'_class'])){
                unset($_SESSION[$name.'_class']);
            }

            $_SESSION[$name] = $message;
            $_SESSION[$name.'_class'] = $class; 
        }
        elseif(empty($message) && !empty($_SESSION[$name])){
            $class = !empty($_SESSION[$name.'_class']) ? $_SESSION[$name.'_class'] : '';
            echo '<div class="container d-flex justify-content-center"><div class="'.$class.' alert-fixed py-3 w-25 text-center" id="msg-flash" role="alert" data-auto-dismiss="2500">'.$_SESSION[$name].'</div></div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name.'_class']);
        }
    }
    
}
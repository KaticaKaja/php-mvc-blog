<?php

function redirect($url, $statusCode = 303)
{
   header('Location: '.'http://'.$_SERVER['HTTP_HOST'].dirname(dirname($_SERVER['PHP_SELF'])). $url, false, $statusCode);
   die();
}
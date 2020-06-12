<?php

function access_log($request)
{
    $time = time();
    $logFile = \fopen(\APPROOT."\logs\accessLog.txt", "a");
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
    $content = $time.":".$request.":$userId\n";
    $exc = ['home/search', 'home/page', 'home/filterpagination'];
    if(!\in_array($request, $exc)){
        \fwrite($logFile, $content);
    }
    \fclose($logFile);
}
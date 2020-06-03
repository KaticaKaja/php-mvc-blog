<?php

try {
    $host = citanjeIzEnv("HOST");
    $baza = citanjeIzEnv("DATABASE");
    $username = citanjeIzEnv("USERNAME");
    $password = citanjeIzEnv("PASSWORD");
   
    $conn = new PDO("mysql:host=$host;dbname=$baza;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $ex){
    echo $ex->getMessage();
}

function executeQuery($query){
    global $conn;
    return $conn->query($query)->fetchAll();
}

function citanjeIzEnv($flag) {
    $fajl = APPROOT.'/config/.env';
    $niz = file($fajl);

    $vrednostFlaga = "";
    foreach($niz as $red) {
        $red = trim($red);
        list($identifikar, $vrednost) = explode("=", $red);

        if($identifikar == $flag){
            $vrednostFlaga = $vrednost;
            break;
        }
    }
    return $vrednostFlaga;
}
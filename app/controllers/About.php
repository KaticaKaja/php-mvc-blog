<?php

namespace App\Controllers;

class About extends Controller{

    protected $info = ['zika', 'pera', 'mika'];
    
    // public function __construct()
    // {
    //     echo "this is about us";
    // }
    public function index($id=1, $nesto = []){
        $users = \executeQuery('SELECT * FROM users');

        echo \json_encode($users);
    }
}
<?php

namespace App\Controllers;

class About extends Controller{

    protected $info = ['zika', 'pera', 'mika'];
    
    // public function __construct()
    // {
    //     echo "this is about us";
    // }
    public function index($id=1, $nesto = []){
        $users = [
            [
                'name' => 'Katica',
                "age" => 24
            ],
            [
                'name' => 'Bega',
                "age" => 25
            ]
        ];

        echo \json_encode($users);
    }
}
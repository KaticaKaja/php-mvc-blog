<?php

namespace App\Controllers;
use App\Core\Database;

class Home extends Controller{

    // public static function view(){
    //     echo "created view";
    // }
    private $db;

    public function __construct(){
        $this->db = Database::getInstance();
    }
    public function index(Type $var = null)
    {
        $this->db->query('SELECT * FROM posts');
        $posts = $this->db->resultSet();
        $data = ['posts' => $posts];
        // echo \json_encode($data);
        $this->view('home', $data);
    }

    public function change(){
        \header('Content-type: application/json');
        // if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
        //     echo "jeste";
        // }
        // else{
        //     echo "nije";
        // }
        $this->db->query('SELECT * FROM posts');
        $posts = $this->db->resultSet();
        // $data = ['posts' => $posts];
        //     echo $data;
        echo \json_encode($posts);
    }
}
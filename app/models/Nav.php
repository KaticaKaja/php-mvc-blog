<?php

namespace App\Models;
use App\Core\Database;
class Nav{

    private $db;

    public function __construct(){
        $this->db = Database::getInstance();
    }

    public function getNav()
    {
        $this->db->query('SELECT * FROM navigation');

        $results = $this->db->resultSet();
        return $results;
    }
}
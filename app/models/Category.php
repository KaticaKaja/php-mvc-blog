<?php

namespace App\Models;
use App\Core\Database;
class Category{

    private $db;

    public function __construct(){
        $this->db = Database::getInstance();
    }

    public function getCategories(){
        $this->db->query('SELECT * FROM categories');

        $results = $this->db->resultSet();
        return $results;
    }
    public function getCategoriesWithPostNum(){
        $this->db->query('SELECT c.*, COUNT(p.id) as numPosts FROM categories as c INNER JOIN posts as p ON c.id=p.category_id WHERE p.published = 1 GROUP BY c.id');

        $results = $this->db->resultSet();
        return $results;
    }
    
    public function deleteCategory($id)
    {   
        $this->db->query('DELETE FROM categories where id=:id');
        $this->db->bind(':id', $id);

        if($this->db->execute()){
            return true;
        }
        else{
            return false;
        }
    }

    public function addCategory($data)
    {   
        $this->db->query('INSERT INTO categories (name) VALUES (:name)');
        $this->db->bind(':name', $data['name']);

        if($this->db->execute()){
            return true;
        }
        else{
            return false;
        }
    }

    public function editCategory($data)
    {   
        $this->db->query('UPDATE categories SET name = :name WHERE id = :id');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':id', $data['id']);
        if($this->db->execute()){
            return true;
        }
        else{
            return false;
        }
    }
}
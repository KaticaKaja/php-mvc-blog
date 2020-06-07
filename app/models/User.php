<?php
namespace App\Models;
use App\Core\Database;
class User{

    private $db;

    public function __construct(){
        $this->db = Database::getInstance();
    }

    public function register($data){
        $this->db->query('INSERT INTO users (password,email,lastName,firstName) VALUES(:password,:email,:lastName,:firstName)');
        $this->db->bind(':password',$data['password']);
        $this->db->bind(':email',$data['email']);
        $this->db->bind(':lastName',$data['lastName']);
        $this->db->bind(':firstName',$data['firstName']);

        if($this->db->execute()){
            return true;
        }
        else{
            return false;
        }
    }

    public function login($email, $password){

        $row = $this->findUserByEmail($email);
        
        $hashedPassword = $row->password;
        if(password_verify($password, $hashedPassword)){
            return $row;
        }
        else{
            return false;
        }
    }

    public function findUserByEmail($email, $id=''){

        if(!empty($id)){
            $this->db->query('SELECT * FROM users WHERE email = :email AND id<>:id');
            $this->db->bind(':email',$email);
            $this->db->bind(':id',$id);
        }
        else{
            $this->db->query('SELECT * FROM users WHERE email = :email');
            $this->db->bind(':email',$email);
        }
        
        $row = $this->db->single();
        if($this->db->rowCount() > 0){
            return $row;
        }
        else{
            return false;
        }
    }

    public function updateUser($data)
    {
        $this->db->query('UPDATE users SET email=:email, password=:password, type_id=:type_id WHERE id=:id');

        $this->db->bind(':id',$data['id']);
        $this->db->bind(':type_id',$data['type_id']);
        $this->db->bind(':email',(!empty($data['emailNew']) ? $data['emailNew'] : $data['email']));
        $this->db->bind(':password',$data['password']);

        if($this->db->execute()){
            return true;
        }
        else{
            return false;
        }
    }

    public function getUserById($id)
    {
        $this->db->query('SELECT * FROM users where id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();
        return $row;
    }

    public function isAdmin($id){

        $user = $this->getUserById($id);

        if($user->type_id == 1){
            return true;
        }
        else{
            return false;
        }
    }

    public function getAllUsers()
    {
        $this->db->query('SELECT u.*, ut.name FROM users as u INNER JOIN user_type as ut ON ut.id = u.type_id');

        $row = $this->db->resultSet();
        return $row;

    }

    public function deleteUser($id){

        $this->db->query('DELETE FROM users where id=:id AND type_id!=1');
        $this->db->bind(':id', $id);

        if($this->db->execute()){
            return true;
        }
        else{
            return false;
        }
    }
    
}

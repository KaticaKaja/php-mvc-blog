<?php
namespace App\Models;
use App\Core\Database;
class Post{

    private $db;

    public function __construct(){
        $this->db = Database::getInstance();
    }

    public function getPosts(){

        $this->db->query('SELECT p.*, u.firstName, u.lastName, c.name FROM posts as p INNER JOIN users as u ON p.user_id = u.id  INNER JOIN categories as c ON c.id = p.category_id ORDER BY p.created_at DESC');
        
        $results = $this->db->resultSet();
        return $results;
    }

    public function onlyPublished($pageNum = 0){

        if(!empty($pageNum)){
            $start = ($pageNum - 1) * 4;
            $this->db->query("SELECT p.*, u.firstName, u.lastName, c.name  FROM posts as p INNER JOIN users as u ON p.user_id = u.id INNER JOIN categories as c ON c.id = p.category_id WHERE p.published = 1 ORDER BY p.created_at DESC LIMIT :start, 4");
            $this->db->bind(':start', $start);
        }
        else{
             $this->db->query('SELECT p.*, u.firstName, u.lastName, c.name FROM posts as p INNER JOIN users as u ON p.user_id = u.id  INNER JOIN categories as c ON c.id = p.category_id WHERE p.published = 1 ORDER BY p.created_at DESC');
        }
        $results = $this->db->resultSet();
        return $results;
    }
    public function publishPost($data){
        
        $this->db->query('UPDATE posts SET published=:published WHERE id=:id');

        $this->db->bind(':id',$data['id']);
        $this->db->bind(':published',$data['published']);

        if($this->db->execute()){
            return true;
        }
        else{
            return false;
        }
    }
    public function searchPosts($content)
    {
        $this->db->query('SELECT p.id, p.title, p.imgSrc, p.imgAlt, p.created_at, u.firstName, u.lastName, c.name FROM posts as p INNER JOIN users as u ON p.user_id = u.id  INNER JOIN categories as c ON c.id = p.category_id
        WHERE (`title` LIKE :content) OR (`body` LIKE :content)');
        $this->db->bind(':content', '%'.$content.'%');
        $results = $this->db->resultSet();
        return $results;
    }

    public function getPostsByCategory($id){
        $this->db->query('SELECT p.*, u.firstName, u.lastName, c.name FROM posts as p INNER JOIN users as u ON p.user_id = u.id  INNER JOIN categories as c ON c.id = p.category_id
        WHERE c.id = :id AND p.published = 1 ORDER BY p.created_at DESC');
        $this->db->bind(':id', $id);
        $results = $this->db->resultSet();
        return $results;
    }
    //only posts from this user
    public function getUserPosts($id){
        $this->db->query('SELECT p.*, u.firstName, u.lastName  FROM posts as p INNER JOIN users as u ON p.user_id = u.id WHERE p.user_id = :id ORDER BY p.created_at DESC');
        $this->db->bind(':id', $id);
        $results = $this->db->resultSet();
        return $results;
    }


    public function addPost($data){

        $this->db->query('INSERT INTO posts (user_id, category_id, title, body, imgSrc, imgAlt) VALUES(:user_id,:category_id,:title,:body, :imgSrc, :title)');
        
        $this->db->bind(':user_id',$data['user_id']);
        $this->db->bind(':category_id',$data['category_id']);
        $this->db->bind(':title',$data['title']);
        $this->db->bind(':body',$data['body']);
        $this->db->bind(':imgSrc',$data['imgSrc']);

        if($this->db->execute()){
            return true;
        }
        else{
            return false;
        }
    }

    public function getPostById($id)
    {
        $this->db->query('SELECT * FROM posts where id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();
        return $row;
    }

    public function deletePost($id){

        $this->db->query('DELETE FROM posts where id=:id');
        $this->db->bind(':id', $id);

        if($this->db->execute()){
            return true;
        }
        else{
            return false;
        }
    }
    
    public function checkUserRate($data){
        $this->db->query('SELECT * FROM rating_posts WHERE user_id = :user_id && post_id = :post_id');
        $this->db->bind(':user_id',$data['userId']);
        $this->db->bind(':post_id',$data['postId']);

        $row = $this->db->single();
        if($this->db->rowCount() > 0){
            return $row;
        }
        else{
            return false;
        }
    }

    public function userRatedPosts($id){
        $this->db->query('SELECT post_id,rated_value FROM rating_posts WHERE user_id = :id');
        $this->db->bind(':id', $id);
        $results = $this->db->resultSet();
        return $results;
    }

    public function topRatedPosts(){
        $this->db->query('SELECT p.*, SUM(rated_value) as sumValue FROM `rating_posts` as rp INNER JOIN posts as p ON p.id = rp.post_id WHERE p.published = 1 GROUP BY post_id ORDER BY sumValue DESC LIMIT 3');
        $results = $this->db->resultSet();
        return $results;
    }
    public function ratingPost($data){

        if(!$this->checkUserRate($data)){
            $this->db->query('INSERT INTO rating_posts (post_id,user_id,rated_value) VALUES(:post_id,:user_id,:rated_value)');

            $this->db->bind(':user_id',$data['userId']);
            $this->db->bind(':post_id',$data['postId']);
            $this->db->bind(':rated_value',$data['ratedValue']);

            if($this->db->execute()){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
        
    }

    public function updatePost($data)
    {
        $this->db->query('UPDATE posts SET category_id=:category_id, title=:title, body=:body, imgSrc = :imgSrc, imgAlt = :imgAlt WHERE id=:id');

        $this->db->bind(':id',$data['id']);
        $this->db->bind(':category_id',$data['category_id']);
        $this->db->bind(':title',$data['title']);
        $this->db->bind(':body',$data['body']);
        $this->db->bind(':imgSrc',$data['imgSrc']);
        $this->db->bind(':imgAlt',$data['imgAlt']);

        if($this->db->execute()){
            return true;
        }
        else{
            return false;
        }
    }
    
}
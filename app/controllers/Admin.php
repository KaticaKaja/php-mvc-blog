<?php
namespace App\Controllers;

use Exception;

class Admin extends Controller{

    public function __construct(){

        $this->userModel = $this->model('User');

        if(!isset($_SESSION['user_id'])){
            
            \flash('adminErr_msg', 'Please login first', 'alert alert-danger');
            \redirect('users/login');
        }
        else if(!$this->userModel->isAdmin($_SESSION['user_id'])){
            \flash('adminErr_msg', 'You are NOT an admin', 'alert alert-danger');
            \redirect('posts');
        }
            $this->navModel = $this->model('Nav');
            $this->postModel = $this->model('Post');
            $this->categoryModel = $this->model('Category');
            $this->allUsers = $this->userModel->getAllUsers();
            $this->allPosts = $this->postModel->getPosts();
            $this->allCategories = $this->categoryModel->getCategories();
            $this->allPages = $this->navModel->getNav();
            
        
    }
    public function currentlyLoggedIn(){
        \header("Content-Type: application/json");
        $readFile = \file_get_contents(\APPROOT."\logs\loggedInLog.txt");
        $data = \substr_count($readFile, ";");
        echo \json_encode($data);
    }
    public function pageVisits()
    {
        \header("Content-Type: application/json");
        $readFile = @file_get_contents(\APPROOT."\logs\accessLog.txt");
        if($readFile === false){
            \http_response_code(500);
            die();
        }
        $readFileInArray = \explode("\n", $readFile);
        $filteredArray = [];
        $pages = [];
        $skipPages = ['admin/pagevisits'];
        foreach ($readFileInArray as $row) {
            $row = \explode(":", $row);
            if($row[0] >= \strtotime("-1 day", time()) && !\in_array($row[1], $skipPages)){
                $filteredArray[] = $row;
                if($row[1] == '' || $row[1] == 'home/index'){
                    $row[1] = 'home';
                }
                if(substr_count($row[1],'/') > 1){
                    $helpVar = \explode("/", $row[1]);
                    \array_pop($helpVar);
                    $row[1] = \implode("/", $helpVar);
                }
                $pages[] = $row[1];
            }

        }
        $pages = \array_count_values($pages);
        \http_response_code(200);
        echo \json_encode($pages);
    }
    public function index(){
        $data = [
            'users' => $this->allUsers,
            'posts' => $this->allPosts,
            'categories' => $this->allCategories,
            'pages' => $this->allPages
        ];
   
        $this->view('admin/index', $data);
    }
    
    public function delete($what,$id){
        \header("Content-Type: application/json");
        if($what == 'user'){
            if($this->userModel->deleteUser($id)){
                
                
                \http_response_code(200);
                \flash('admin_message', "User deleted");
            }
            else{
                \http_response_code(500);
            }
            
        }
        if($what == 'post'){
            $post = $this->postModel->getPostById($id);
            $postImage = $post->img_dest;
                if (file_exists($postImage)) {
                    unlink($postImage);
                  } else {
                   \http_response_code(500);}
            if($this->postModel->deletePost($id)){
                \http_response_code(200);
                \flash('adminpost_message', "Post deleted");
            }
            else{
                \http_response_code(500);
            }
            
        }
        if($what == 'category'){
            if($this->categoryModel->deleteCategory($id)){
                \http_response_code(200);
                \flash('admincategory_message', "Category deleted");
            }
            else{
                \http_response_code(500);
            }
        }

        \redirect('admin');

    }
    public function addcategory(){
        $data = [
            'name' => $_POST['name']
        ];
        if($data['name'] == ''){
            echo \json_encode('This field is required');
        } 
        else if(!\preg_match('/([A-Z][A-z0-9\s]+)/', $data['name'])){
            echo \json_encode('Bad format.');
        }
        else{
            if($this->categoryModel->addCategory($data)){
            \http_response_code(201);
            \flash('admincategory_message', "Category added");
            }
            else{
                \http_response_code(500);
            }
        }
        
    }
    public function editcategory()
    {   
        $data = [
            'id' => $_POST['id'],
            'name' => $_POST['name']
        ];
        if($data['name'] == ''){
            echo \json_encode('This field is required');
        } 
        else if(!\preg_match('/([A-Z][A-z0-9\s]+)/', $data['name'])){
            echo \json_encode('Bad format.');
        }
        else{
            if($this->categoryModel->editCategory($data)){
            \http_response_code(204);
            \flash('admincategory_message', "Category updated");
            }
            else{
                \http_response_code(500);
            }
        }
    }
    // public function addpost(){
        
    //     $data = [
    //         'title' => trim($_POST['title']),
    //         'body' => $_POST['body'],
    //         'user_id' => $_SESSION['user_id'],
    //         'category_id' => $_POST['categories'],
    //         'imgSrc' => 'http://localhost/mojSajt/public/img/uploads/profile1.jfif',
    //         'imgAlt' => trim($_POST['title'])
    //     ];
        
    //     $errors = [ 
    //         'category_err' => '',
    //         'title_err' => '',
    //         'body_err' => ''
    //     ];

    //     if(empty($data['title'])){
    //         $data['title_err'] = "Please enter title";
    //     }
    //     else if(!\preg_match('/([A-Z][A-z0-9\s]+)/', $data['title'])){
    //         $data['title_err'] = "Bad format";
    //     }
    //     if(empty($data['category_id'])){
    //         $errors['category_err'] = "Please choose a category";
    //     }
    //     if(empty($data['body'])){
    //         $errors['body_err'] = "Please enter body text";
    //     }
    //     if(empty($errors['title_err']) && empty($errors['body_err']) && empty($errors['category_err'])){
    //         if($this->postModel->addPost($data)){
    //             \http_response_code(201);
    //             \flash('adminpost_message', 'Post added');
    //         }
    //         else{
                
    //             \http_response_code(500);
    //             die('Something went wrong');
    //         }
    //     }
    //     else{
    
    //             echo \json_encode($errors);  
    //     }
    // }

    // public function publish()
    // {
    //     $data = [];
    //     $data = [
    //        'id' => $_POST['id'],
    //        'published' => $_POST['publishValue']
    //     ];

    //     if($this->postModel->publishPost($data)){
    //         \flash('publishPost_admin', 'This post is published');
    //         \http_response_code(200);
    //         $report['msg'] = 'success';
    //         echo \json_encode($report);
    //     }
    //     else{
    //         $report['msg'] = 'error';
    //         echo \json_encode($report);
    //         \http_response_code(500);
    //     }
        
    // }
    
    // public function adduser(){
    //     if(isset($_POST['submit'])){
    //         $nameReg = "/^([A-Z][a-z]{2,}(\s|\-)?)*$/"; 
    //         $emailReg = "/^(\w+(\.|\-)?)*\@\w+(\.com|\.rs)|\.ict.edu.rs$/";
    //         $fName = trim($_POST['firstName']);
    //         $lName = trim($_POST['lastName']);
    //         $email = trim($_POST['email']);
    //         $password = trim($_POST['password']);
    //         $confPasswd = trim($_POST['confPasswd']);

    //         $data = [
    //             'firstName'=> $fName,
    //             'lastName'=> $lName,
    //             'email'=> $email,
    //             'password'=> $password,
    //             'confPasswd'=> $confPasswd
    //         ];
    //         $errors = [
    //             'firstName_err'=> '',
    //             'lastName_err'=> '',
    //             'email_err'=> '',
    //             'password_err'=> '',
    //             'confPasswd_err'=> ''
    //         ];

    //         if(!preg_match($nameReg, $fName) || empty($fName)){
    //              $errors['firstName_err'] = 'Please enter your first name.';
    //         }
    //         if(!preg_match($nameReg, $lName) || empty($lName)){
    //              $errors['lastName_err'] = 'Please enter your last name.';
    //         }
    //         if(!preg_match($emailReg, $email) || empty($email)){
    //              $errors['email_err'] = 'Enter a valid e-mail address.';
    //         }
    //         else{
    //             if($this->userModel->findUserByEmail($email)){
    //                  $errors['email_err'] = 'Email is already taken.';
    //             }
    //         }
    //         if(empty($password) || strlen($password) < 6){
    //              $errors['password_err'] = 'Password must be at least 6 characters.';
    //         }
    //         if(empty($confPasswd)){
    //              $errors['confPasswd_err'] = 'Please confirm password.';
    //         }
    //         elseif($confPasswd != $password){
    //              $errors['confPasswd_err'] = 'Passwords do not match.';
    //         }

    //         if(empty( $errors['firstName_err']) && empty( $errors['lastName_err']) && empty( $errors['email_err']) && empty( $errors['password_err']) && empty( $errors['confPasswd_err'])){

    //              $data['password'] = password_hash($password, PASSWORD_DEFAULT);

    //             if($this->userModel->register($data)){
    //                 \http_response_code(201);
    //                 \flash('admin_message', 'User added');
    //             }
    //             else{
    //                 \http_response_code(500);
    //             }
    //         }
    //         else{
                 
    //              echo \json_encode($errors);
    //             //  \http_response_code(400);
    //         }
    //     }
    // }
}
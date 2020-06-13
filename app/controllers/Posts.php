<?php
namespace App\Controllers;

class Posts extends Controller{

    public function __construct(){
        
        if(!isset($_SESSION['user_id'])){
            \flash("posts_msg", "You have to login first", "alert alert-danger");
            redirect('users/login');
        }

        $this->postModel = $this->model('Post');
        $this->categoryModel = $this->model('Category');
        $this->userModel = $this->model('User');
    }
    public function editpost($id)
    {
        \header('Content-Type: application/json');
        $post = $this->postModel->getPostById($id);
        $data = [
            'id' => $id,
            'category_id' => $post->category_id,
            'title' => $post->title,
            'body' => $post->body,
            'imgSrc' => $post->img_src,
            'imgAlt' => $post->title
        ];
        $flag = 0;

        $title = $_POST['title'];
        $category = $_POST['category'];
        $body = $_POST['body'];
        $image = isset($_FILES['image']) ? $_FILES['image'] : '';
        $fileName = isset($_FILES['image']) ? $_FILES['image']['name'] : '';
        $fileError = isset($_FILES['image']) ? $_FILES['image']['error'] : '';
        $tmpPath = isset($_FILES['image']) ? $image['tmp_name'] : '';
        $fileExt = \explode('.', $fileName);
        $fileActualExt = \strtolower(end($fileExt));
        $type = isset($image['type']) ? $image['type'] : '';
        $allowed = ['image/png', 'image/jpeg', 'image/jpg'];
        $fileSize = isset($_FILES['image']) ? $_FILES['image']['size'] : '';

        $errors = [
            'title_err' => '',
            'category_err' => '',
            'body_err' => '',
            'img_err' => ''
        ];
        if(empty($image)){
            
            $flag = 1;
        }
        elseif(\in_array($type, $allowed)){
            if($fileError === 0){
                
                if($fileSize < 2000000){
                    $fileNameNew = \uniqid('', true).'.'.$fileActualExt;
                    $fileDestination = \ROOT.'\public\assets\img\\'.$fileNameNew; //modifikovati na hostingu 
                }
                else{
            
                    $errors["img_err"] = "Your file is too big";
                }
            }
            else{
                \http_response_code(500);
                $errors["img_err"] = "There was an error while uploading image file";
                
            }
        }
        else{
            
    
            $errors["img_err"] = "This file type is not allowed";
            
        }
        if(empty($title)){
    
            $errors["title_err"] = "Please enter title";
            
        }
        else if(!\preg_match('/([A-Z][A-z0-9\s]+)/', $title)){
    
            $errors["title_err"] = "Bad format";
            
        }
        if(empty($category)){
    
            $errors["category_err"] = "Please choose a category";
            // echo \json_encode($errors);
        }
        if(empty($body)){
    
            $errors["body_err"] = "Please enter body text";
            
        }
        
        if(empty($errors['title_err']) && empty($errors['body_err']) && empty($errors['category_err']) && empty($errors['img_err'])){
            if(($flag != 1)){
                \move_uploaded_file($tmpPath, $fileDestination);
                $dimensions = \getimagesize($fileDestination);
                $imageCurrentSizeX = $dimensions[0];
                $imageCurrentSizeY = $dimensions[1];
                $height = 400;
                $width = ($imageCurrentSizeX * $height) / $imageCurrentSizeY;
                $blankImage = \imagecreatetruecolor($width,$height);
                if($fileActualExt == 'png'){
                    $smallerImage = \imagecreatefrompng($fileDestination);
                    \imagecopyresampled($blankImage,$smallerImage,0,0,0,0,$width,$height,$imageCurrentSizeX,$imageCurrentSizeY);
                    \imagepng($blankImage, \ROOT."\public\assets\img\smaller\\$fileNameNew", 100);
                    
                }
                else{
                    $smallerImage = \imagecreatefromjpeg($fileDestination);
                    \imagecopyresampled($blankImage,$smallerImage,0,0,0,0,$width,$height,$imageCurrentSizeX,$imageCurrentSizeY);
                    \imagejpeg($blankImage, \ROOT."\public\assets\img\smaller\\$fileNameNew", 100);
                } 
            }
            $data = [
                'id' => $id,
                'title' => trim($title),
                'body' => $body,
                // 'user_id' => $_SESSION['user_id'],
                'category_id' => $category,
                'imgSrc' => empty($flag) ? /*URLROOT.*/'http://localhost/sajt/public/assets/img/'.$fileNameNew : $post->img_src,
                'imgAlt' => trim($title)
            ];
            if($this->postModel->updatePost($data)){
                \http_response_code(200);
                \flash('post_message', 'Post edited');
                $msg = 'success';
                echo \json_encode($msg);
            }
            else{
                
                \http_response_code(500);
                // die('Something went wrong'); //URADITI JS NA 500 STRANU
                //status code
            }
        }
        else{
            \http_response_code(422);
            echo \json_encode($errors);
        }
    }
    public function addpost()
    {
        \header('Content-Type: application/json');
        $title = $_POST['title'];
        $category = $_POST['category'];
        $body = $_POST['body'];
        $image = isset($_FILES['image']) ? $_FILES['image'] : '';
        $fileName = isset($_FILES['image']) ? $_FILES['image']['name'] : '';
        $fileError = isset($_FILES['image']) ? $_FILES['image']['error'] : '';
        $tmpPath = isset($_FILES['image']) ? $image['tmp_name'] : '';
        $fileExt = \explode('.', $fileName);
        $fileActualExt = \strtolower(end($fileExt));
        $type = isset($image['type']) ? $image['type'] : '';
        $allowed = ['image/png', 'image/jpeg', 'image/jpg'];
        $fileSize = isset($_FILES['image']) ? $_FILES['image']['size'] : '';
        
        $data = [
            'title' => '',
            'body' => '',
            'user_id' => '',
            'category_id' => '',
            'imgSrc' => '',
            'imgAlt' => '',
            'imgDest' => ''
        ];
        $errors = [
            'title_err' => '',
            'category_err' => '',
            'body_err' => '',
            'img_err' => ''
        ];
        
        if(empty($title)){
    
            $errors["title_err"] = "Please enter title";
            
        }
        else if(!\preg_match('/([A-Z][A-z0-9\s]+)/', $title)){
    
            $errors["title_err"] = "Bad format";
            
        }
        if(empty($category)){
    
            $errors["category_err"] = "Please choose a category";
            // echo \json_encode($errors);
        }
        if(empty($body)){
    
            $errors["body_err"] = "Please enter body text";
            
        }
        if(empty($image)){
    
            $errors["img_err"] = "Please choose an image for your post";
            
        }
        if(\in_array($type, $allowed)){
            if($fileError === 0){
                
                if($fileSize < 2000000){
                    $fileNameNew = \uniqid('', true).'.'.$fileActualExt;
                    // $data['imgSrc'] = /*URLROOT.*/'http://localhost/sajt/public/assets/img/'.$fileNameNew;
                    $fileDestination = \ROOT.'\public\assets\img\\'.$fileNameNew; //modifikovati na hostingu     
                }
                else{
            
                    $errors["img_err"] = "Your file is too big";
                }
            }
            else{
                \http_response_code(500);
                $errors["img_err"] = "There was an error while uploading image file";
                
            }
        }
        else{
            
    
            $errors["img_err"] = "This file type is not allowed";
            
        }
        if(empty($errors['title_err']) && empty($errors['body_err']) && empty($errors['category_err']) && empty($errors['img_err'])){
            \move_uploaded_file($tmpPath, $fileDestination);
            $dimensions = \getimagesize($fileDestination);
            $imageCurrentSizeX = $dimensions[0];
            $imageCurrentSizeY = $dimensions[1];
            $height = 400;
            $width = ($imageCurrentSizeX * $height) / $imageCurrentSizeY;
            $blankImage = \imagecreatetruecolor($width,$height);
            if($fileActualExt == 'png'){
                $smallerImage = \imagecreatefrompng($fileDestination);
                \imagecopyresampled($blankImage,$smallerImage,0,0,0,0,$width,$height,$imageCurrentSizeX,$imageCurrentSizeY);
                \imagepng($blankImage, \ROOT."\public\assets\img\smaller\\$fileNameNew", 100);
                // $data['imgSrc'] = /*URLROOT.*/\ROOT."\public\assets\img\smaller\\$fileNameNew";
                
            }
            else{
                $smallerImage = \imagecreatefromjpeg($fileDestination);
                \imagecopyresampled($blankImage,$smallerImage,0,0,0,0,$width,$height,$imageCurrentSizeX,$imageCurrentSizeY);
                \imagejpeg($blankImage, \ROOT."\public\assets\img\smaller\\$fileNameNew", 100);
                // $data['imgSrc'] = /*URLROOT.*/\ROOT."\public\assets\img\smaller\\$fileNameNew";
            } 
            $data = [
                'title' => trim($title),
                'body' => $body,
                'user_id' => $_SESSION['user_id'],
                'category_id' => $category,
                'imgSrc' => /*URLROOT*/'http://localhost/sajt/public/assets/img/smaller/'.$fileNameNew,
                'imgAlt' => trim($title)
            ];
            if($this->postModel->addPost($data)){
                \http_response_code(201);
                \flash('post_message', 'Post added');
                $msg = 'success';
                echo \json_encode($msg);
            }
            else{
                
                \http_response_code(500);
                die('Something went wrong'); //URADITI JS NA 500 STRANU
                //status code
            }
        }
        else{
            \http_response_code(422);
            echo \json_encode($errors);
        }

    }
    public function index(){

        $id = $_SESSION['user_id'];
        $posts = $this->postModel->getUserPosts($id);
        $data = [
            'posts' => $posts
        ];
        $this->view('posts/index', $data);
    }

    public function add(){
        $categories = $this->categoryModel->getCategories();
        $optional = [
            'categoriesList' => $categories
        ];
        
            $data = [
                'title' => '',
                'body' => ''
            ];

            $this->view('posts/add', $data, $optional);
    }

    public function show($id){
        $post = $this->postModel->getPostById($id);
        $user = $this->userModel->getUserById($post->user_id);
        $userRatedPosts = $this->postModel->userRatedPosts($_SESSION['user_id']);
        
        $data = [
            'post' => $post,
            'user' => $user,
            'thisPostValue' => ''
        ];
        foreach($userRatedPosts as $post){
            if($id == json_encode((int)$post->post_id)){
                $thisPostValue = $post->rated_value;
                $data['thisPostValue'] = $thisPostValue;
            }
        }
        
        $this->view('posts/show', $data); 
    }

    public function delete($id){

        if($this->postModel->deletePost($id)){
            $dir = explode('\\',__DIR__);
            $dir = end($dir);
            if($dir == 'admin'){
                \flash('adminpost_message', "Post deleted");
                \redirect('admin');
            }
            \flash('post_message', "Post deleted");
            \redirect('posts');
        }else{
            //status code
            die('Something went wrong');
        }

    }

    public function ratepost(){

        $postId = $_POST['id'];
        $userId = $_SESSION['user_id'];
        $ratedValue = $_POST['ratedValue'];

        $data = [
            'postId' => $postId,
            'userId' => $userId,
            'ratedValue' => $ratedValue
        ];

        if($this->postModel->ratingPost($data)){
            \http_response_code(201);
            $_SESSION['userRatedPosts'][] = (int)$postId;
           \flash('rate_msg', 'Thank you for the feedback');
           $msg = 'success';
           echo \json_encode($msg);
        }
        else{
            \http_response_code(500);
            \flash('rate_msg', 'There was an error','alert alert-danger');
            $msg = 'error';
            echo \json_encode($msg);
        }

    }

    
    public function edit($id){
        $categories = $this->categoryModel->getCategories();
        $optional = [
            'categoriesList' => $categories
        ];
        $post = $this->postModel->getPostById($id);
        $data = [
            'id' => $id,
            'title' => '',
            'body' => '',
            'user_id' => '',
            'category_id' => '',
            'imgSrc' => '',
            'imgAlt' => ''
        ];
            if($this->userModel->isAdmin($_SESSION['user_id']) || ($post->user_id == $_SESSION['user_id'])){

                $data = [
                    'id' => $id,
                    'category_id' => $post->category_id,
                    'title' => $post->title,
                    'body' => $post->body,
                    'imgSrc' => $post->img_src,
                    'imgAlt' => $post->title
                ];

                $this->view('posts/edit', $data, $optional);
            }
            else{
                \redirect('posts');
            }
            
    }  
}
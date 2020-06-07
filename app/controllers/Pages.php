<?php
namespace App\Controllers;

class Pages extends Controller{

    public function __construct(){
    
        $this->postModel = $this->model('Post');
        $this->categoryModel = $this->model('Category');
    }
    public function index(){
        $value = 1;
        $numOfpostShowing = 4;
        $postsAll = $this->postModel->onlyPublished();
        $postsNum = count($postsAll); 
        $pages = ($postsNum % $numOfpostShowing != 0) ? (floor($postsNum / $numOfpostShowing) + 1) : (floor($postsNum / $numOfpostShowing));
        $categories = $this->categoryModel->getCategoriesWithPostNum();
        $posts = $this->postModel->onlyPublished($value);
        $topRated = $this->postModel->topRatedPosts();
        
        $data = [
            'posts' => $posts,
            'pages' => $pages,
            'categories'=> $categories,
            'topRated' => $topRated
        ];
   
        $this->view('pages/index', $data);
    }
    
    public function filterpagination(){
        $numOfpostShowing = 4;
        $catId = $_POST['categoryId'];
        $postscategory = $this->postModel->getPostsByCategory($catId);
        $posts = $postscategory;
        $postsNum = count($posts);
        $pages = ($postsNum % $numOfpostShowing != 0) ? (floor($postsNum / $numOfpostShowing) + 1) : (floor($postsNum / $numOfpostShowing));
        
        $data = [
            'posts' => $posts,
            'pages' => $pages
        ];

        echo \json_encode($data);
    }

    // public function notfound(){

    //     $this->view('pages/notfound');
    // }

    public function page(){
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $pageNum = $_POST['id'];
        $posts = $this->postModel->onlyPublished($pageNum);
        \http_response_code(200);
        echo \json_encode($posts);
    }

    public function search()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $content = $_POST['content'];
        $posts = $this->postModel->searchPosts($content);
        \http_response_code(200);
        echo \json_encode($posts);
    }
  
}
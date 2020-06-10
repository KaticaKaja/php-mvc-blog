<?php 
namespace App\Controllers;

use App\Controllers\Controller;

class Validation extends Controller{

    public static $userId = '';
    
    public function __construct(){
        if(isset($_SESSION['user_id'])){
            self::$userId= $_SESSION['user_id'];
        }
        $this->userModel = $this->model('User');
        $this->postModel = $this->model('Post');
    }

    public function login()
    {
        \header('Content-Type: application/json');

        $email = trim($_POST['email']);
        $emailReg = "/^(\w+(\.|\-)?)*\@\w+(\.com|\.rs)|\.ict.edu.rs$/";
        $password = trim($_POST['password']);
        
        $data = [
            'email'=> $email,
            'password'=> $password,
            'email_err'=> '',
            'password_err'=> '',
            'msg' => ''

        ];
        if(!preg_match($emailReg, $email) || empty($email)){
            $data['email_err'] = 'Enter a valid e-mail address.';
        }
        if(empty($password) || strlen($password) < 6){
            $data['password_err'] = 'Password must be at least 6 characters.';
        }

        if($this->userModel->findUserByEmail($email)){
            //user found
        }
        else{
            $data['email_err'] = "User not found";
        }

        if(empty($data['email_err']) && empty($data['password_err'])){
            $loggedInUser = $this->userModel->login($email, $password);

            if($loggedInUser){
                // create session
                $content = \file_get_contents(\APPROOT."\Logs\loggedInLog.txt");
                $loggedInLog = fopen(\APPROOT."\Logs\loggedInLog.txt", "a");
                // if(strpos($content,"1:$loggedInUser->id") !== false){
                // }
                // else{
                    \fwrite($loggedInLog, "1:$loggedInUser->id;");

                // }
                
                \fclose($loggedInLog);

                $this->createUserSession($loggedInUser);
                \http_response_code(200);
                $data['msg'] = 'success';
                echo \json_encode($data);
                
            }
            else{
                $data['password_err'] = "Password incorrect";

                \http_response_code(422);
                echo \json_encode($data);
            }
        }
        else{
            \http_response_code(422);
            echo \json_encode($data);
        }
    }

    public function register()
    {
        \header('Content-Type: application/json');

        $nameReg = "/^([A-Z][a-z]{2,}(\s|\-)?)*$/"; 
        $emailReg = "/^(\w+(\.|\-)?)*\@\w+(\.com|\.rs)|\.ict.edu.rs$/";
        $fName = trim($_POST['firstName']);
        $lName = trim($_POST['lastName']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confPasswd = trim($_POST['confPasswd']);

        $dataMsg = [
            'msg' => ''
        ];
        $data = [
            'firstName'=> $fName,
            'lastName'=> $lName,
            'email'=> $email,
            'password'=> $password,
            'confPasswd'=> $confPasswd
        ];
        $errors = [
            'firstName_err'=> '',
            'lastName_err'=> '',
            'email_err'=> '',
            'password_err'=> '',
            'confPasswd_err'=> ''
        ];

        if(!preg_match($nameReg, $fName) || empty($fName)){
            $errors['firstName_err'] = 'Please enter your first name.';
        }
        if(!preg_match($nameReg, $lName) || empty($lName)){
            $errors['lastName_err'] = 'Please enter your last name.';
        }
        if(!preg_match($emailReg, $email) || empty($email)){
            $errors['email_err'] = 'Enter a valid e-mail address.';
        }
        else{
            if($this->userModel->findUserByEmail($email)){
                $errors['email_err'] = 'Email is already taken.';
            }
            else{
                
                $errors['email_err'] = '';
            }
        }
        if(empty($password) || strlen($password) < 6){
            $errors['password_err'] = 'Password must be at least 6 characters.';
        }
        if(empty($confPasswd)){
            $errors['confPasswd_err'] = 'Please confirm password.';
        }
        elseif($confPasswd != $password){
            $errors['confPasswd_err'] = 'Passwords do not match.';
        }

        if(empty($errors['firstName_err']) && empty($errors['lastName_err']) && empty($errors['email_err']) && empty($errors['password_err']) && empty($errors['confPasswd_err'])){

            $data['password'] = password_hash($password, PASSWORD_DEFAULT);

            if($this->userModel->register($data)){
                \http_response_code(201);
                flash('register_msg','You are registered, please log in');
                $dataMsg['msg'] = "success";
                echo \json_encode($dataMsg);
            }else{
                \http_response_code(500);
                $dataMsg['msg'] = 'Try again later, server error.';
                echo \json_encode($dataMsg);
            }
        }
        else{
            \http_response_code(422);
            echo \json_encode($errors);
        }

    }

    private function createUserSession($user){

        $ratedPosts = [];
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_type'] = $user->type_id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_firstName'] = $user->firstName;
        $_SESSION['userRatedPosts'] = $this->postModel->userRatedPosts($user->id);
        foreach($_SESSION['userRatedPosts'] as $post){
            $ratedPosts[] = (int)$post->post_id;
        }
        $_SESSION['userRatedPosts'] = $ratedPosts;
        // redirect('posts');
    }

    public static function isLoggedIn(){
        if(self::$userId){
            return true;
        }
        else{
            return false;
        }
    }

    // public static function getInstance() {
    //     global $dbh;
    //     if ($dbh == null) {
            
    //         $dbh = new Validation();
    //     }
    //         return $dbh;
    // }
}

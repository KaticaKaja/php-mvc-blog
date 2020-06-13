<?php 
namespace App\Controllers;

use App\Controllers\Controller;

class Validation extends Controller{

    public static $userId;
    
    public function __construct(){
        // if(isset($_SESSION['user_id'])){
        //     self::$userId= $_SESSION['user_id'];
        // }
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
            // 'email'=> $email,
            // 'password'=> $password,
            'msg' => ''
        ];
        $errors = [
            'email_err'=> '',
            'password_err'=> ''
        ];
        if(!preg_match($emailReg, $email) || empty($email)){
            $errors['email_err'] = 'Enter a valid e-mail address.';
        }
        if(empty($password) || strlen($password) < 6){
            $errors['password_err'] = 'Password must be at least 6 characters.';
        }

        if($this->userModel->findUserByEmail($email)){
            //user found
        }
        else{
            $errors['email_err'] = "User not found";
        }

        if(empty($errors['email_err']) && empty($errors['password_err'])){
            $loggedInUser = $this->userModel->login($email, $password);

            if($loggedInUser){
                // create session
                $content = \file_get_contents(\APPROOT."/logs/loggedInLog.txt");
                $loggedInLog = fopen(\APPROOT."/logs/loggedInLog.txt", "a");
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
                $errors['password_err'] = "Password incorrect";

                \http_response_code(422);
                echo \json_encode($errors);
            }
        }
        else{
            \http_response_code(422);
            echo \json_encode($errors);
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

    public function settings($id)
    {
        \header('Content-Type: application/json');
        $datamsg = [
            'msg' => ''
        ];
        if(!Validation::isLoggedIn()){
            \http_response_code(401);
            $datamsg['msg'] = 'unauthorized';
            echo \json_encode($datamsg);
        }
        if(!($this->userModel->isAdmin($_SESSION['user_id']) || ($id == $_SESSION['user_id']))){
            \http_response_code(403);
            $datamsg['msg'] = 'forbidden';
            echo \json_encode($datamsg);
        }
        $user = $this->userModel->getUserById($id);
        $email = $_POST['email'];
        $emailReg = '/^(\w+(\.|\-)?)*\@\w+(\.com|\.rs)|\.ict.edu.rs$/';
        $password = $_POST['password'];
        $type_id = $_POST['admin'];
        $sessionUserType = $_POST['sessionUserType'];
        $data = [
            'id' => $id,
            'email' => '',
            'password' => '',
            'type_id' => $user->type_id
        ];
        $errors = [];
        
        if(!empty($email)){
            if(!preg_match($emailReg, $email)){
        
                $errors['email_err'] = 'Enter a valid e-mail address.';
            }
            else if($this->userModel->findUserByEmail($email, $id)){

                $errors['email_err'] = 'Email is already taken.';
            }
            else{
                $data['email'] = $email;
            }
        }
        else{
            $data['email'] = $user->email;
        }
        if(empty($password)){
            $data['password'] = $user->password;
        }
        else{
    
            if(strlen($password) < 6){

                $errors['password_err'] = "Password must be at least 6 characters.";
                
            }
            else{
    
                $data['password'] = $password;
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
        }
        if($sessionUserType == 1){
            $data['type_id'] = ($type_id == 'false') ? 2 : 1;
        }
        
        if(empty($errors['email_err']) && empty($errors['password_err'])){

            if($this->userModel->updateUser($data)){
                \http_response_code(204);
                \flash('settings_msg','Settings updated');
            }
            else{
                $datamsg['msg'] = 'internalerror';
                \http_response_code(500);
                echo \json_encode($datamsg);
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
    }

    public static function isLoggedIn(){
        self::$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
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

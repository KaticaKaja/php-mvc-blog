<?php
namespace App\Controllers;


class Users extends Controller{

    public function __construct(){
        // $this->userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
        $this->userModel = $this->model('User');
        $this->postModel = $this->model('Post');
    }

    // public function index()
    // {
    //     \redirect('users/login');
    // }
    public function register(){
        // if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //     $nameReg = "/^([A-Z][a-z]{2,}(\s|\-)?)*$/"; 
        //     $emailReg = "/^(\w+(\.|\-)?)*\@\w+(\.com|\.rs)|\.ict.edu.rs$/";
        //     $fName = trim($_POST['firstName']);
        //     $lName = trim($_POST['lastName']);
        //     $email = trim($_POST['email']);
        //     $password = trim($_POST['password']);
        //     $confPasswd = trim($_POST['confPasswd']);

        //     $data = [
        //         'firstName'=> $fName,
        //         'lastName'=> $lName,
        //         'email'=> $email,
        //         'password'=> $password,
        //         'confPasswd'=> $confPasswd
        //     ];
        //     $errors = [
        //         'firstName_err'=> '',
        //         'lastName_err'=> '',
        //         'email_err'=> '',
        //         'password_err'=> '',
        //         'confPasswd_err'=> ''
        //     ];

        //     if(!preg_match($nameReg, $fName) || empty($fName)){
        //         $errors['firstName_err'] = 'Please enter your first name.';
        //     }
        //     if(!preg_match($nameReg, $lName) || empty($lName)){
        //         $errors['lastName_err'] = 'Please enter your last name.';
        //     }
        //     if(!preg_match($emailReg, $email) || empty($email)){
        //         $errors['email_err'] = 'Enter a valid e-mail address.';
        //     }
        //     else{
        //         if($this->userModel->findUserByEmail($email)){
        //             $errors['email_err'] = 'Email is already taken.';
        //         }
        //         else{
                    
        //             $errors['email_err'] = '';
        //         }
        //     }
        //     if(empty($password) || strlen($password) < 6){
        //         $errors['password_err'] = 'Password must be at least 6 characters.';
        //     }
        //     if(empty($confPasswd)){
        //         $errors['confPasswd_err'] = 'Please confirm password.';
        //     }
        //     elseif($confPasswd != $password){
        //         $errors['confPasswd_err'] = 'Passwords do not match.';
        //     }

        //     if(empty($errors['firstName_err']) && empty($errors['lastName_err']) && empty($errors['email_err']) && empty($errors['password_err']) && empty($errors['confPasswd_err'])){

        //         $data['password'] = password_hash($password, PASSWORD_DEFAULT);

        //         if($this->userModel->register($data)){

        //             flash('register_msg','You are registered, please log in');
        //         }else{
        //             $data['msg'] = 'Try again later, server error.';
        //             echo \json_encode($data['msg']);
        //         }
        //     }
        //     else{
        //         // echo \json_encode('Ima gresaka');
        //         echo \json_encode($errors);
        //     }

        // }
        // else{

            $data = [
                'firstName'=> '',
                'lastName'=> '',
                'email'=> '',
                'password'=> '',
                'confPasswd'=> '',
                'firstName_err'=> '',
                'lastName_err'=> '',
                'email_err'=> '',
                'password_err'=> '',
                'confPasswd_err'=> ''

            ];
            
            $this->view('users/register',$data);
        // }
    }

    public function settings($id)
    {
        if(!Validation::isLoggedIn()){
            \flash('settingsErr_msg', 'Please login to your account first', 'alert alert-danger');
            redirect('users/login');
        }
        $user = $this->userModel->getUserById($id);
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $email = $_POST['emailNew'];
            $emailReg = '/^(\w+(\.|\-)?)*\@\w+(\.com|\.rs)|\.ict.edu.rs$/';
            $password = $_POST['passwordNew'];
            $data = [
                'id' => $id,
                'type_id'=> isset($_POST['admin']) ? 1 : 2,
                'email' => $user->email,
                'emailNew' => ''
            ];
            if(!empty($email)){
                if(!preg_match($emailReg, $email)){
            
                    $data['email_err'] = 'Enter a valid e-mail address.';
                }
                else if($this->userModel->findUserByEmail($email, $id)){

                    $data['email_err'] = 'Email is already taken.';
                }
                else{
                    $data['emailNew'] = $email;
                }
            }
            if(empty($password)){
                $data['password'] = $user->password;
            }
           else{
        
                if(strlen($password) < 6){

                    $data['password_err'] = "Password must be at least 6 characters.";
                    
                }
                else{
        
                    $data['password'] = $password;
                    $data['password'] = password_hash($password, PASSWORD_DEFAULT);
                }
            }
          
            if(empty($data['email_err']) && empty($data['password_err'])){

                if($this->userModel->updateUser($data)){
                    \http_response_code(204);
                    \flash('settings_msg','Settings updated');
                    \redirect('posts');
                }
                else{
                    \http_response_code(500);
                }
            }
            else{
                $this->view('users/settings', $data);
            }
        }
        else{
            
            if($this->userModel->isAdmin($_SESSION['user_id']) || ($user->id == $_SESSION['user_id'])){
               
                $data = [
                'id' => $id,
                'type_id'=> $user->type_id,
                'email' => $user->email,
                'emailNew' => ''
               ];

               $this->view('users/settings', $data);
            }
            else{
                \flash('otheruserSettings_err',"You can only edit your own settings", 'alert alert-danger');
                \redirect('posts');
                
            }
        }
    }
    public function login(){
        if(Validation::isLoggedIn()){
            \redirect('posts');
        }
        // if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //     $email = trim($_POST['email']);
        //     $emailReg = "/^(\w+(\.|\-)?)*\@\w+(\.com|\.rs)|\.ict.edu.rs$/";
        //     $password = trim($_POST['password']);
            
        //     $data = [
        //         'email'=> $email,
        //         'password'=> $password,
        //         'email_err'=> '',
        //         'password_err'=> ''

        //     ];
        //     if(!preg_match($emailReg, $email) || empty($email)){
        //         $data['email_err'] = 'Enter a valid e-mail address.';
        //     }
        //     if(empty($password) || strlen($password) < 6){
        //         $data['password_err'] = 'Password must be at least 6 characters.';
        //     }

        //     if($this->userModel->findUserByEmail($email)){
        //         //user found
        //     }
        //     else{
        //         $data['email_err'] = "User not found";
        //     }

        //     if(empty($data['email_err']) && empty($data['password_err'])){
        //         $loggedInUser = $this->userModel->login($email, $password);

        //         if($loggedInUser){
        //             // create session
        //             $content = \file_get_contents(\APPROOT."\Logs\loggedInLog.txt");
        //             $loggedInLog = fopen(\APPROOT."\Logs\loggedInLog.txt", "a");
        //             // if(strpos($content,"1:$loggedInUser->id") !== false){
        //             // }
        //             // else{
        //                 \fwrite($loggedInLog, "1:$loggedInUser->id;");

        //             // }
                    
        //             \fclose($loggedInLog);
        //             // session_start();

        //             $this->createUserSession($loggedInUser);
        //             \redirect("posts");
                    
        //         }
        //         else{
        //             $data['password_err'] = "Password incorrect";

        //             $this->view('users/login', $data);
        //         }
        //     }
        //     else{
        //         $this->view('users/login',$data);
        //     }
            
        // }
        // else{

            $data = [
                'email'=> '',
                'password'=> ''
                // 'email_err'=> '',
                // 'password_err'=> ''

            ];
            
            $this->view('users/login',$data);
        // }
    }

    // public function createUserSession($user){

    //     $_SESSION['user_id'] = $user->id;
    //     $_SESSION['user_type'] = $user->type_id;
    //     $_SESSION['user_email'] = $user->email;
    //     $_SESSION['user_firstName'] = $user->firstName;
    //     $_SESSION['userRatedPosts'] = $this->postModel->userRatedPosts($user->id);
    //     foreach($_SESSION['userRatedPosts'] as $post){
    //         $ratedPosts[] = (int)$post->post_id;
    //     }
    //     $_SESSION['userRatedPosts'] = $ratedPosts;
    //     // redirect('posts');
    // }

    public function logout(){

        $content = \file_get_contents(\APPROOT."\Logs\loggedInLog.txt");
        
        $id = $_SESSION['user_id'];
        if($id == null){
            \flash('login_msg', "You have to login first", "alert alert-danger");
            \redirect("users/login");
        }
        if(strpos($content,"1:$id") !== false){
            $loggedInLog = fopen(\APPROOT."\Logs\loggedInLog.txt", "w");
            $search = "/1:$id;/";
            $subcontent = \preg_replace($search,"",$content,1);
            \fwrite($loggedInLog, $subcontent);
            \fclose($loggedInLog);
        }
        
        

        unset($_SESSION['user_id']);
        unset($_SESSION['user_type']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_firstName']);
        unset($_SESSION['userRatedPosts']);
        // Unset all of the session variables.
            $_SESSION = array();

            // If it's desired to kill the session, also delete the session cookie.
            // Note: This will destroy the session, and not just the session data!
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }

            // Finally, destroy the session.
            session_destroy();
        
        redirect('users/login');
    }
    
    // public function isLoggedIn(){
    //     if($this->userId){
    //         return true;
    //     }
    //     else{
    //         return false;
    //     }
    // }

}
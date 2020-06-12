<?php
namespace App\Controllers;


class Users extends Controller{

    public function __construct(){
        $this->userModel = $this->model('User');
    }

    public function register(){
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
    }

    public function settings($id)
    {
        $user = $this->userModel->getUserById($id);
        if(!Validation::isLoggedIn()){
            \flash('settingsErr_msg', 'Please login to your account first', 'alert alert-danger');
            redirect('users/login');
        }
        if(!$this->userModel->isAdmin($_SESSION['user_id']) && ($id != $_SESSION['user_id'])){
            \flash('settingsErr_msg', 'You can only edit your own credentials', 'alert alert-danger');
            redirect("users/settings/".$_SESSION['user_id']);
        }
        
        $data = [
            'id' => $id,
            'type_id'=> $user->type_id,
            'email' => $user->email
        ];
        $this->view('users/settings', $data);
    }
    public function login(){
        if(Validation::isLoggedIn()){
            \redirect('posts');
        }
            $data = [
                'email'=> '',
                'password'=> ''
            ];
            
            $this->view('users/login',$data);
    }

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
    
}
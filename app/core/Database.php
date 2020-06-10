<?php
namespace App\Core;

use PDOException;

class Database{

    private $host;
    private $database;
    private $username;
    private $password;

    protected static $dbh; 
    private $error;

    public function __construct(){

        $this->host = $this->readEnv("HOST");
        $this->database = $this->readEnv("DATABASE");
        $this->username = $this->readEnv("USERNAME");
        $this->password = $this->readEnv("PASSWORD");

        $dsn = "mysql:host=$this->host;dbname=$this->database;charset=utf8";
        $options = array(
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ
        );

        try{
            self::$dbh = new \PDO($dsn, $this->username, $this->password, $options);
        }
        catch(PDOException $e){
            switch($e->getCode()){
                case 2002:
                    $this->error = "There was an error with code: ".$e->getCode().";<br>On the line: ".$e->getLine().";<br>With this message: ".explode(":", $e->getMessage())[2].";";
                break;
                case 1049:
                    $this->error = "There was an error with code: ".$e->getCode().";<br>On the line: ".$e->getLine().";<br>With this message: ".substr($e->getMessage(), \strripos($e->getMessage(), ']')+2).";";
                break;
                case 1044:
                    $this->error = "There was an error with code: ".$e->getCode().";<br>On the line: ".$e->getLine().";<br>With this message: Access denied for this user (bad username);";
                break;
                case 1045:
                    $this->error = "There was an error with code: ".$e->getCode().";<br>On the line: ".$e->getLine().";<br>With this message: Access denied for this user (bad password);";
                break;

            }

            die('There was an internal error - 500. Give us some time to fix it.');
            //necemo echo nego upis u tekst fajl user dobija 500 stranu
        }
    }

    public function query($sql){

        $this->stmt = self::$dbh->prepare($sql);
    }

    public function bind($param, $value, $type=null){
        if(is_null($type)){
            switch(true){
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                break;
                default:
                $type = \PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param,$value,$type);
    }

    public function execute(){
        //ove vatamo greske
        try {
            return $this->stmt->execute();

        } catch (PDOException $e) {
            switch($e->getCode()){
                case "42S22":
                    echo "There was an error with code: ".$e->getCode().";<br>On the line: ".$e->getLine().";<br>File: ".explode("\\",$e->getFile())[6]."<br>With this message: ".explode(":", $e->getMessage())[1].";";
                break;
                case "42000":
                    echo "There was an error with code: ".$e->getCode().";<br>On the line: ".$e->getLine().";<br>File: ".explode("\\",$e->getFile())[6]."<br>With this message: ".explode(":", $e->getMessage())[1].";";
                break;
                case "42S02":
                    // echo "There was an error with code: ".$e->getCode().";<br>On the line: ".$e->getLine().";<br>File: ".explode("\\",$e->getFile())[6]."<br>With this message: ".explode(":", $e->getMessage())[1].";";
                    // \redirect("errorcontroller/internalerror");
                    \http_response_code(500);
                break;
            }
            // echo $e->getMessage();
            die();
        }
    }

    public function resultSet(){
        $this->execute();
        return $this->stmt->fetchAll();
    }

    public function single(){
        $this->execute();
        return $this->stmt->fetch();
    }

    public function rowCount(){
        return $this->stmt->rowCount();
    }

    public static function getInstance() {
        global $dbh;
        if ($dbh == null) {
            
            $dbh = new Database();
        }
            return $dbh;
    }

    private function readEnv($flag){
        $file = \APPROOT.'/config/.env';
        $content = file($file);
    
        $flagValue = "";
        foreach($content as $row) {
            $row = trim($row);
            list($identifier, $value) = explode("=", $row);
    
            if($identifier == $flag){
                $flagValue = $value;
                break;
            }
        }
        return $flagValue;
    }
}
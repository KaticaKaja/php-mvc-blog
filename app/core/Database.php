<?php
namespace App\Core;

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
            $this->error = $e->getMessage();

            echo $this->error;
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
        return $this->stmt->execute();
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
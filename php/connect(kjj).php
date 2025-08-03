<?php
class database{
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $database = "gofit_database";
    function connect(){
        
        $connection = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        return $connection;
    }
    
    function read($query){
        
        $conn  = $this->connect();
        $result = mysqli_query($conn, $query);

        if (!$result){
            return false;
        }else{
            $data = false;
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
            return $data;
        }
    }


    function save($query){
        $conn  = $this->connect();
        $result = mysqli_query($conn, $query);

        if(!$result){
            return false;
        }else{
            return true;
        }
    }
}

$DB = new Databse();



?>
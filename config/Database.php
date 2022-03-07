<?php

class Database {
    // Specify database credentials
    private $host = "localhost";
    private $db_name = "php_oop_crud_level_1";
    private $username = "root";
    private $password = "";
    private $conn;

    //get database connection
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:hos=" .$this->host .";dbname=" . $this->db_name, $this->username,$this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception){
            echo "Connection error: " .$exception->getMessage();
        }

        return $this->conn;
    }

}
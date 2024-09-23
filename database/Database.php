<?php
class Database {
    private const HOST = "localhost";
    private const DB_NAME = "capstone";
    private const USERNAME = "root";
    private const PASSWORD = "root";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this::HOST . ";dbname=" . $this::DB_NAME, $this::USERNAME, $this::PASSWORD);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>

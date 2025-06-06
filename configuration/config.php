<?php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $dbname = "leave_tracking_db";
    private $port = "3307";
    private $pdo;

    public function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};port={$this->port}";
            $this->pdo = new PDO($dsn, $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Connection Success!";
        } catch (PDOException $e) {
            die("Not connected: " . $this->password . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}
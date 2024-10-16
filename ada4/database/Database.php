<?php

class Database {
    private $host = 'localhost';
    private $db_name = 'ada4';
    private $user_name = 'root';
    private $password = '';
    private $conn;

    public function connect(): PDO {
        $this->conn = null;
        
        try{
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->db_name",
                $this->user_name,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo 'Good connection with database ' . $this->db_name;
        } catch (PDOException $exception) {
            echo "Error trying connection: " . $exception->getMessage();
        }
        
        return $this->conn;
    }

    public function query($sql, $params = []): bool|PDOStatement {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $exception) {
            echo "Error trying to execute query: " . $exception->getMessage();
            return false;
        }
    }

    public function close(): void {
        $this->conn = null;
        echo "Connection closed";
    }
}
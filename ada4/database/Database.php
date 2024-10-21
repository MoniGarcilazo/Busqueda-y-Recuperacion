<?php

class Database {
    private $host = 'localhost:3305';
    private $db_name = 'ada4';
    private $user_name = 'root';
    private $password = 'password';
    private $conn;  

    public function __construct() {
        $this->connect();
    }

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

    public function insertDocument($nombre_documento, $ruta_documento): void {
        // Conectar a la base de datos
        $conn = $this->connect();

        // Consulta para verificar si el documento ya existe (basado en el nombre del archivo)
        $query_check = "SELECT COUNT(*) FROM documents WHERE nombre_documento = :nombre_documento";
        $stmt_check = $conn->prepare($query_check);
        $stmt_check->bindParam(':nombre_documento', $nombre_documento);
        $stmt_check->execute();
        $conteo = $stmt_check->fetchColumn();

        // Si no existe, inserta el documento
        if ($conteo == 0) {
            $query_insert = "INSERT INTO documents (nombre_documento, ruta_documento) VALUES (:nombre_documento, :ruta_documento)";
            $stmt_insert = $conn->prepare($query_insert);
            $stmt_insert->bindParam(':nombre_documento', $nombre_documento);
            $stmt_insert->bindParam(':ruta_documento', $ruta_documento);

            if ($stmt_insert->execute()) {
                echo "Documento insertado exitosamente.";
            } else {
                echo "Error al insertar el documento.";
            }
        } else {
            echo "El documento ya existe en la base de datos.";
        }

        // Cerrar la conexión
        $this->close();
    }

    public function insertTerm($term): void {
        // Conectar a la base de datos
        $conn = $this->connect();

        // Consulta para verificar si el término ya existe
        $query_check = "SELECT COUNT(*) FROM vocabulary WHERE term = :term";
        $stmt_check = $conn->prepare($query_check);
        $stmt_check->bindParam(':term', $term);
        $stmt_check->execute();
        $conteo = $stmt_check->fetchColumn();

        // Si no existe, inserta el término
        if ($conteo == 0) {
            $query_insert = "INSERT INTO vocabulary (term) VALUES (:term)";
            $stmt_insert = $conn->prepare($query_insert);
            $stmt_insert->bindParam(':term', $term);

            if ($stmt_insert->execute()) {
                echo "Término insertado exitosamente.";
            } else {
                echo "Error al insertar el término.";
            }
        } else {
            echo "El término ya existe en la base de datos.";
        }

        // Cerrar la conexión
        $this->close();
    }
}
<?php
include '../models/Document.php';
include '../database/Database.php';
include '../controllers/InvertedIndex.php';

class Management {
    private $path;

    public function __construct($path) {
        $this->path = $path;
    }

    // Pedir todos los ducumentos asociados al path
    public function getAllFiles(): array {
        $documents = [];

        if(is_dir($this->path)) {
            $directory = opendir($this->path);

            while(($file = readdir($directory)) !== false) {
                // ignore special files like . and ..
                if($file != '.' && $file != '..' &&pathinfo($file, PATHINFO_EXTENSION) === '.txt') {
                    $file_path = $this->path . '/' . $file;
                    $document = new Document($file_path);
                    $documents[] = $document;
                }
            }

            closedir($directory);

        } else {
            echo 'El directorio no existe';
        }

        return $documents;
    }
    // Obterner la informacion de los documentos con Document()
    // Generar el indice invertido con InvertedIndex
    // Subir la informacion a las tablas de la bd
}

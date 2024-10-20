<?php
include '../models/Document.php';
include '../database/Database.php';
include '../controllers/InvertedIndex.php';

class Management {
    private $path;

    public function __construct($path) {
        $this->path = $path;
        $invertedIndex = new InvertedIndex($path);
        $inverted_index = $invertedIndex->getInvertedIndex();
        print_r($inverted_index);
    }

    // Pedir todos los ducumentos asociados al path
    // Obterner la informacion de los documentos con Document()
    
    // Generar el indice invertido con InvertedIndex

    // Subir la informacion a las tablas de la bd
}

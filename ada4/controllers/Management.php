<?php
include '../models/Document.php';
include '../database/Database.php';
include '../controllers/InvertedIndex.php';
include '../database/queries.php';

class Management {
    private $path;
    private $db;

    public function __construct($path) {
        $this->path = $path;
        $this->db = new Database();
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

    public function uploadDocuments(array $documents): void {
        foreach ($documents as $document) {
            $name = $document->getName();
            $date = $document->getDate();
            $description = $document->getDescription();
            $size = $document->getDocSize();
            $url = $this->path . '/' . $name;

            global $insert_document_template;

            $this->db->query($insert_document_template, [
                ':name' => $name,
                ':creation_date' => $date,
                ':url' => $url,
                ':description' => $description
            ]);

            $documentId = $this->db->query("SELECT LAST_INSERT_ID()")->fetchColumn();
            $this->uploadVocabulary($document, $documentId);
        }
    }

    public function uploadVocabulary(Document $document, int $documentId): void {
        $frequency = $document->getFrequency();

        foreach ($frequency as $term => $count) {
            // Verificar si el termino existe en la tabla `vocabulary`
            $sql = "SELECT id FROM vocabulary WHERE terms = :terms";
            $vocabResult = $this->db->query($sql, [':terms' => $term]);
            $vocabId = $vocabResult->fetchColumn();

            if (!$vocabId) {
                global $insert_vocabulary_template_not_exist;
                $this->db->query($insert_vocabulary_template_not_exist, [':term' => $term]);
                $vocabId = $this->db->query("SELECT LAST_INSERT_ID()")->fetchColumn();
            } else {
                // Si ya existe, incrementar el numero de documentos en los que aparece
                global $insert_vocabulary_template_exist;
                $this->db->query($insert_vocabulary_template_exist, [':id' => $vocabId]);
            }

            // Insertar la frecuencia del termino en el documento en la tabla `postings`
            global $insert_posting_template;
            $this->db->query(
                $insert_posting_template,
                [
                    ':id_doc' => $documentId,
                    ':id_term' => $vocabId,
                    ':frequency' => $count
                ]    
            );
        }
    }
}

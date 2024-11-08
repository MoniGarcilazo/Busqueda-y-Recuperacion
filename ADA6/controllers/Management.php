<?php
include '../models/Document.php';
include '../database/Database.php';
include '../controllers/InvertedIndex.php';
include '../database/queries.php';

class Management {
    private $path;
    private $db;
    private $url_app_files = '../database/files/';

    public function __construct($path) {
        $this->path = $path;
        $this->db = new Database();
        $this->db->connect();
    }

    // Pedir todos los ducumentos asociados al path
    public function getAllFiles(): array {
        $documents = [];

        if(is_dir($this->path)) {
            $files = glob($this->path . '*.txt');

            foreach ($files as $file_path) {
                    $document = new Document($file_path);
                    $documents[] = $document;
            }

        } else {
            echo 'El directorio no existe';
        }

        return $documents;
    }

    public function uploadDocument($document) {
        $name = $document->getName();
        $saved_files_url = $this->url_app_files . $name;
        //$url = $this->path . '/' . $name;

        //Verificar si el documento existe
        $query = "SELECT id FROM documents WHERE name_doc = :name_doc";
        $stmt = $this->db->query($query, [':name_doc' => $name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo '<p>El documento que intentas ingresar ya existe, por favor, cambia el nombre de tu documento</p>';
            return $result['id'];
        } else { // Insertar el documento si no existe
            global $insert_document_template;

            $content = file_get_contents($document->getFilePath());

            $params = [
                ':name_doc' => $name,
                ':creation_date' => $document->getDate(),
                ':url_doc' => $saved_files_url,
                ':description_doc' => $document->getDescription(),
                ':texto_archivo' => $content
            ];

            $this->db->query($insert_document_template, $params);

            return $this->db->query("SELECT LAST_INSERT_ID() AS id")->fetch(PDO::FETCH_ASSOC)['id'];
        }
    }

    private function insertTerm($term) {
        global $insert_vocabulary_template_not_exist;
        global $insert_vocabulary_template_exist;

        // Verificar si el término ya existe
        $query = "SELECT id FROM vocabulary WHERE terms = :term";
        $stmt = $this->db->query($query, [':term' => $term]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $term_id = $result['id'];
            $this->db->query($insert_vocabulary_template_exist, [':id' => $term_id]);
            return $result['id'];  // Devolver el id del término existente
        } else {
            // Insertar el término si no existe
            $params = [
                ':terms' => $term
            ];
            $this->db->query($insert_vocabulary_template_not_exist, $params);

            // Recuperar el ID del término insertado
            return $this->db->query("SELECT LAST_INSERT_ID() AS id")->fetch(PDO::FETCH_ASSOC)['id'];
        }
    }

    private function insertPosting($document_id, $term_id, $frequency) {
        global $insert_posting_template;

        // Verificar si existe un registro en posting para este documento y termino
        $query = "SELECT id FROM postings WHERE id_doc = :id_doc AND id_term = :id_term";
        $stmt = $this->db->query($query,[
            ':id_doc' => $document_id,
            ':id_term' => $term_id
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['id'];
        } else {
            $params = [
                ':id_doc' => $document_id,
                ':id_term' => $term_id,
                ':frequency' => $frequency
            ];
            $this->db->query($insert_posting_template, $params);

            return $this->db->query("SELECT LAST_INSERT_ID() AS id")->fetch(PDO::FETCH_ASSOC)['id'];
        }
    }

    public function insertPositions($document_id, $terms) {
        global $insert_position_template;

        $terms_frequencies = array_count_values($terms);

        foreach ($terms as $i => $term) {
            $term_id = $this->insertTerm($term);
            $post_id = $this->insertPosting(
                $document_id,
                $term_id,
                $terms_frequencies[$term]
            );

            $params = [
                ':id_post' => $post_id,
                ':id_term' => $term_id,
                ':id_doc' => $document_id,
                ':position' => $i + 1
            ];

            $this->db->query($insert_position_template, $params);
        }
    }

    public function saveUserFiles(): void {
        if (!is_dir($this->url_app_files)) {
            mkdir($this->url_app_files, 0777, true);
        }

        $files = scandir($this->path);

        foreach ($files as $file) {
            // ignorar directorios especiales
            if ($file != '.' && $file != '..') {
                $source =$this->path . DIRECTORY_SEPARATOR . $file;
                $destination = $this->url_app_files . DIRECTORY_SEPARATOR . $file;

                rename($source, $destination);
            }
        }
    }

    public function closeDBConnection() {
        $this->db->close();
    }
}
<?php
include '../controllers/InvertedIndex.php';

$upload_dir = "../uploads/files/";

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = $_POST['query'] ?? '';

    $invertedIndex = new InvertedIndex($upload_dir);
    $inverted_index = $invertedIndex->getInvertedIndex();

    if (array_key_exists($query, $inverted_index)) {
        $files = $inverted_index[$query];
        $frequency= count($files);

        echo json_encode([
            'success' => true,
            'query' => $query,
            'files' => $files,
            'frequency' => $frequency,
            'message' => 'El término se encontró en los documentos'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'El término no se encontró en los documentos'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}
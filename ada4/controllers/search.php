<?php
$upload_dir = "../uploads/files/";

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = $_POST['query'] ?? '';

    $inverted_index = generateInvertedIndex($upload_dir);

    if (array_key_exists($query, $inverted_index)) {
        $files = $inverted_index[$query];
        $frequency= count($files);

        echo json_encode([
            'success' => true,
            'query' => $query,
            'files' => $files,
            'frequency' => $frequency
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

function generateInvertedIndex($dir){
    $inverted_index = [];

    $files = glob($dir . '*.txt');

    foreach ($files as $file_path) {
        $file_name = basename($file_path);
        $content = file_get_contents($file_path);

        $content = strtolower($content);
        $content = preg_replace('/[^\p{L}\p{N}\s]/u', '', $content);

        $words = explode(' ', $content);

        foreach ($words as $word) {
            if (empty($word)) {
                continue;
            }

            if (!isset($inverted_index[$word])) {
                $inverted_index[$word] = [];
            }

            if (!in_array($file_name, $inverted_index[$word])) {
                $inverted_index[$word][] = $file_name;
            }
        }
    }

    return $inverted_index;
}
?>

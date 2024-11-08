<?php/*
include '../controllers/InvertedIndex.php';
include '../controllers/cosineSimilarity.php';

$upload_dir = "../uploads/files/";

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = $_POST['query'] ?? '';

    // Vectores que representan 5 documentos
    $documentVectors = [
        [1, 2, 3, 0, 1],  // Documento 1
        [0, 1, 0, 2, 0],  // Documento 2
        [3, 0, 2, 1, 1],  // Documento 3
        [1, 1, 1, 0, 0],  // Documento 4
        [2, 0, 0, 3, 1]   // Documento 5
    ];

    // Query que queremos comparar con los documentos
    $queryVector = [1, 1, 2, 0, 1];  // Query a comparar

    // Arreglo para guardar las similitudes
    $similarities = [];

    // Calculamos la similitud coseno para cada documento
    foreach ($documentVectors as $index => $documentVector) {
        $similarity = cosineSimilarity($queryVector, $documentVector);
        $similarities[] = [
            'document' => $index + 1,  // Número del documento
            'similarity' => $similarity
        ];
    }

    // Ordenamos los resultados por la similitud, de mayor a menor
    usort($similarities, function($a, $b) {
        return $b['similarity'] <=> $a['similarity'];
    });

    // Imprimimos los resultados ordenados
    foreach ($similarities as $result) {
        //echo "Documento " . $result['document'] . ": Similitud coseno = " . $result['similarity'] . "\n";
    }

    echo json_encode([
        'success' => true,
        'title' => 'File 3',
        'content' => 'El gato y el perro juegan juntos.',
        'url' => '',
        'similitudCoseno' => '0.73999',
        'message' => 'El término se encontró en los documentos'
    ]);
}*/
?>
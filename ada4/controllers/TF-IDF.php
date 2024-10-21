<?php
function calculateTF($term, $document) {
    $words = str_word_count(strtolower($document), 1);
    $termCount = array_count_values($words);
    return isset($termCount[$term]) ? $termCount[$term] / count($words) : 0;
}

function calculateIDF($term, $documents) {
    $docCount = count($documents);
    $termInDocs = 0;

    foreach ($documents as $document) {
        if (strpos(strtolower($document), $term) !== false) {
            $termInDocs++;
        }
    }

    return ($termInDocs > 0) ? log($docCount / $termInDocs) : 0;
}

function calculateTFIDF($term, $document, $documents) {
    $tf = calculateTF($term, $document);
    $idf = calculateIDF($term, $documents);
    return $tf * $idf;
}

$directory = './uploads/'; // Directorio donde están los archivos
$files = array_diff(scandir($directory), ['.', '..']); // Obtiene los archivos, excluyendo '.' y '..'
$documents = [];

foreach ($files as $file) {
    $documents[$file] = file_get_contents($directory . $file); // Cargamos el contenido de cada archivo
}

$query = isset($_POST['query']) ? strtolower($_POST['query']) : ''; // Consulta ingresada por el usuario
$terms = explode(' ', $query); // Dividimos la consulta en términos

// Arreglo para almacenar la relevancia de cada archivo
$tfidfScores = [];

// Calculamos el TF-IDF para cada término de la consulta y para cada archivo
foreach ($documents as $fileName => $documentContent) {
    $tfidfTotal = 0;
    
    foreach ($terms as $term) {
        $tfidfTotal += calculateTFIDF($term, $documentContent, $documents);
    }

    $tfidfScores[$fileName] = $tfidfTotal; // Guardamos el puntaje TF-IDF total para el archivo
}

// Ordenamos los archivos por relevancia TF-IDF (de mayor a menor)
arsort($tfidfScores);

// Imprimimos los archivos ordenados por relevancia
echo "<h2>Archivos ordenados por relevancia (TF-IDF):</h2>";
echo "<ul>";
foreach ($tfidfScores as $fileName => $score) {
    echo "<li>Archivo: <strong>$fileName</strong> - Relevancia: $score</li>";
}
echo "</ul>";


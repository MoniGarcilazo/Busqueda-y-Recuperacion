<?php
// Los documentos
$upload_dir = "uploads/";

$documents = [
    1 => "El gato está en el tejado.",
    2 => "El perro duerme en el suelo.",
    3 => "El gato y el perro juegan juntos."
];

// Función para crear un índice invertido
function create_inverted_index($documents) {
    $inverted_index = [];

    foreach ($documents as $doc_id => $text) {
        // Convertir el texto a minúsculas y eliminar puntuación
        $text = strtolower($text);
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);

        // Dividir en palabras
        $words = explode(' ', $text);

        // Añadir cada palabra al índice invertido
        foreach ($words as $word) {
            if (!isset($inverted_index[$word])) {
                $inverted_index[$word] = [];
            }
            if (!in_array($doc_id, $inverted_index[$word])) {
                $inverted_index[$word][] = $doc_id;
            }
        }
    }
    return $inverted_index;
}

// Crear el índice invertido
$inverted_index = create_inverted_index($documents);

// Mostrar el índice invertido
print_r($inverted_index);
?>

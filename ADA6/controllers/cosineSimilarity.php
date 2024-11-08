<?php
function cosineSimilarity($vectorA, $vectorB) {
    // Paso 1: Producto punto
    $dotProduct = 0.0;
    $magnitudeA = 0.0;
    $magnitudeB = 0.0;
    
    foreach ($vectorA as $key => $valueA) {
        $valueB = $vectorB[$key] ?? 0; // Si no hay valor para B en esa posición, asumir 0
        $dotProduct += ($valueA * $valueB); // Producto punto
        $magnitudeA += pow($valueA, 2);     // Suma de los cuadrados para el vector A
        $magnitudeB += pow($valueB, 2);     // Suma de los cuadrados para el vector B
    }
    
    // Paso 2: Magnitudes (raíz cuadrada de la suma de los cuadrados)
    $magnitudeA = sqrt($magnitudeA);
    $magnitudeB = sqrt($magnitudeB);
    
    // Paso 3: Similaridad coseno
    if ($magnitudeA == 0 || $magnitudeB == 0) {
        return 0.0; // Si uno de los vectores es cero, no hay similitud
    } else {
        return $dotProduct / ($magnitudeA * $magnitudeB);
    }
}
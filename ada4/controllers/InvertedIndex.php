<?php
include_once '../models/Document.php';

class InvertedIndex {
    private $invertedIndex = [];
    private $index = [];

    // public function __construct($path) {
    //     $this->invertedIndex = $this->generateInvertedIndex($path);
    // }

    public function __construct($documents) {
        $this->buildIndex($documents);
    }

    // function generateInvertedIndex($path): array{
    //     $inverted_index = [];

    //     $files = glob($path . '*.txt');    

    //     foreach ($files as $file_path) {
    //         $document = new Document($file_path);

    //         foreach ($document->getFrequency() as $palabra => $frecuencia) {
    //             echo "La palabra '$palabra' aparece $frecuencia veces. <br />";
    //         }
    //         $file_name = $document->getName();

    //         foreach ($document->getVocabulary() as $term) {
    //             if (empty($term)) {
    //                 continue;
    //             }

    //             if (!isset($inverted_index[$term])) {
    //                 $inverted_index[$term] = [];
    //             }

    //             if (!in_array($file_name, $inverted_index[$term])) {
    //                 $inverted_index[$term][] = $file_name;
    //             }
    //         } 
    //     }

    //     //$numDocs = generateNumDocs($inverted_index);

    //     // foreach ($numDocs as $palabra => $cant) {
    //         // echo "La palabra '$palabra' esta en $cant docs. <br />";
    //     // }

    //     return $inverted_index;
    // }

    private function buildIndex($documents) {
        foreach ($documents as $document) {
            $frequency = $document->getFrequency();
            $doc_name = $document->getName();

            // Iterar sobre cada termino la frecuencia de terminos del documento
            foreach ($frequency as $term => $count) {
                // Si el termino no esta en el indice se agrega
                if(!array_key_exists($term, $this->index)) {
                    $this->index[$term] = [];
                }

                $this->index[$term][$doc_name] = $count;
            }
        }
    }

    public function getIndex(): array {
        return $this->index;
    }

    public function search($term): array|null {
        if (array_key_exists($term, $this->index)) {
            return $this->index[$term];
        } else {
            return null;
        }
    }

    public function getInvertedIndex(): array {
        return $this->invertedIndex;
    }

}
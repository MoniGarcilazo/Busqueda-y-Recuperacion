<?php
include_once '../models/Document.php';

class InvertedIndex {
    private $invertedIndex;

    public function __construct($path) {
        $this->invertedIndex = $this->generateInvertedIndex($path);
    }

    function generateInvertedIndex($path): array{
        $inverted_index = [];

        $files = glob($path . '*.txt');    

        foreach ($files as $file_path) {
            $document = new Document($file_path);
            $document->generateTermsFrequency();

            foreach ($document->getFrequency() as $palabra => $frecuencia) {
                echo "La palabra '$palabra' aparece $frecuencia veces. <br />";
            }
            $file_name = $document->getName();

            foreach ($document->getVocabulary() as $term) {
                if (empty($term)) {
                    continue;
                }

                if (!isset($inverted_index[$term])) {
                    $inverted_index[$term] = [];
                }

                if (!in_array($file_name, $inverted_index[$term])) {
                    $inverted_index[$term][] = $file_name;
                }
            } 
        }

        //$numDocs = generateNumDocs($inverted_index);

        // foreach ($numDocs as $palabra => $cant) {
            // echo "La palabra '$palabra' esta en $cant docs. <br />";
        // }

        return $inverted_index;
    }

    public function getInvertedIndex(): array {
        return $this->invertedIndex;
    }

}
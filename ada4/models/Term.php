<?php
class Term {
    private $term;
    private $numDocs = [];

    public function __construct($inverted_index) {
        $this->numDocs = $this->generateNumDocs($inverted_index);
    }

    function generateNumDocs($inverted_index): array {
        $numDocs = [];
        foreach($inverted_index as $term => $files) {
            $numDocs[$term] = count($files);
        }
        return $numDocs;
    }

    public function getNumDocs(): array {
        return $this->numDocs;
    }
}
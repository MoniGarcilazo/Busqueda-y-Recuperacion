<?php
class Document {
    private $document_path;
    private $name;
    private $date;
    private $size;
    private $terms;
    private $description;
    private $cantTerms;
    private $frequency = [];

    public function __construct($document_path) {
        $this->document_path = $document_path;
        $this->name = basename($document_path);
        $this->date = date("Y/m/d");
        $this->description = file_get_contents($document_path);
        $this->size = filesize($document_path);
        
        $content = strtolower($this->description);
        $content = preg_replace('/[^\p{L}\p{N}\s]/u', '', $content);
        $this->terms = explode(' ', $content);
        $this->cantTerms = count($this->terms);
    }

    public function generateTermsFrequency(): array {
        foreach($this->terms as $term) {
            if(array_key_exists($term, $this->frequency)) {
                $this->frequency[$term]++;
            } else {
                $this->frequency[$term] = 1;
            }
        }

        return  $this->frequency;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDate(): string {
        return $this->date;
    }

    public function getDocSize(): bool|int {
        return $this->size;
    }

    public function getVocabulary(): array|bool {
        return $this->terms;
    }

    public function getDescription(): bool|string {
        return $this->description;
    }

    public function getAmountTerms(): int {
        return $this->cantTerms;
    }

    public function getFrequency(): array {
        return $this->frequency;
    }

}

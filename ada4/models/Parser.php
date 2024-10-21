<?php

class Parser {
    private $tokens;
    private $position = 0;
    private $currentToken;

    public function __construct($tokens) {
        $this->tokens = $tokens;
        $this->currentToken = $this->tokens[$this->position] ?? null;
    }

    private function advance() {
        $this->position++;
        $this->currentToken = $this->tokens[$this->position] ?? null;
    }

    private function expect($type) {
        if ($this->currentToken['type'] === $type) {
            $this->advance();
        } else {
            throw new Exception("Error de sintaxis: se esperaba $type");
        }
    }

    public function parse() {
        return $this->expression();
    }

    private function expression() {
        $result = $this->term();

        while ($this->currentToken && ($this->currentToken['type'] == 'AND' || $this->currentToken['type'] == 'OR')) {
            $operator = $this->currentToken['type'];
            $this->advance();
            $right = $this->term();
            $result = ['type' => $operator, 'left' => $result, 'right' => $right];
        }

        return $result;
    }

    private function term() {
        if ($this->currentToken['type'] == 'NOT') {
            $this->advance();
            $term = $this->term();
            return ['type' => 'NOT', 'term' => $term];
        }

        return $this->factor();
    }

    private function factor() {
        switch ($this->currentToken['type']) {
            case 'WORD':
                $value = $this->currentToken['value'];
                $this->advance();
                return ['type' => 'WORD', 'value' => $value];
            case 'CADENA':
                $value = $this->currentToken['value'];
                $this->advance();
                return ['type' => 'CADENA', 'value' => $value];
            case 'PATRON':
                $value = $this->currentToken['value'];
                $this->advance();
                return ['type' => 'PATRON', 'value' => $value];
            case 'CAMPOS':
                $value = $this->currentToken['value'];
                $this->advance();
                return ['type' => 'CAMPOS', 'value' => $value];
            default:
                throw new Exception("Token inesperado: " . $this->currentToken['type']);
        }
    }
}
?>
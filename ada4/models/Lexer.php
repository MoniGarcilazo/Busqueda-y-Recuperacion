<?php

class Lexer {
    private $input;
    private $tokens = [];
    private $position = 0;

    public function __construct($input) {
        $this->input = strtolower($input); 
        $this->tokenize();
    }

    private function tokenize() {
        $patterns = [
            'AND' => '/\band\b/i',
            'OR' => '/\bor\b/i',
            'NOT' => '/\bnot\b/i',
            'CADENA' => '/cadena\(([^)]+)\)/i',
            'PATRON' => '/patron\(([^)]+)\)/i',
            'CAMPOS' => '/campos\(([^)]+)\)/i',
            'WORD' => '/\b\w+\b/' 
        ];
    
        $previousWasWord = false;
        while ($this->position < strlen($this->input)) {
            foreach ($patterns as $type => $pattern) {

                if (in_array($type, ['AND', 'OR', 'NOT'])) {
                    if (preg_match($pattern, $this->input, $matches, PREG_OFFSET_CAPTURE, $this->position)) {
                        if ($matches[0][1] == $this->position) {
                            $this->tokens[] = [
                                'type' => $type,
                                'value' => strtoupper($matches[0][0])  // Asegurar que el valor sea mayúsculas
                            ];
                            $this->position += strlen($matches[0][0]);
                            $previousWasWord = false;  // No es palabra clave, es operador
                            continue 2;
                        }
                    }
                }
                if ($type === 'CADENA' && preg_match($patterns['CADENA'], $this->input, $matches, PREG_OFFSET_CAPTURE, $this->position)) {
                    if ($matches[0][1] == $this->position) {
                        $this->tokens[] = [
                            'type' => $type,
                            'value' => $matches[1][0] 
                        ];
                        $this->position += strlen($matches[0][0]);
                        continue 2;
                    }
                }
                if ($type === 'PATRON' && preg_match($patterns['PATRON'], $this->input, $matches, PREG_OFFSET_CAPTURE, $this->position)) {
                    if ($matches[0][1] == $this->position) {
                        
                        $this->tokens[] = [
                            'type' => $type,
                            'value' => $matches[1][0]  
                        ];
                        $this->position += strlen($matches[0][0]);
                        continue 2;
                    }
                }
                
                if ($type === 'CAMPOS' && preg_match($patterns['CAMPOS'], $this->input, $matches, PREG_OFFSET_CAPTURE, $this->position)) {
                    if ($matches[0][1] == $this->position) {
                        $this->tokens[] = [
                            'type' => $type,
                            'value' => $matches[1][0] 
                        ];
                        $this->position += strlen($matches[0][0]);
                        continue 2;
                    }
                }
                if ($type === 'WORD' && preg_match($patterns['WORD'], $this->input, $matches, PREG_OFFSET_CAPTURE, $this->position)) {
                    if ($matches[0][1] == $this->position) {
                        // Si la palabra anterior fue un término, insertamos un OR implícito
                        if ($previousWasWord) {
                            $this->tokens[] = [
                                'type' => 'OR',  // Insertar OR implícito entre palabras
                                'value' => 'OR'
                            ];
                        }
                        $this->tokens[] = [
                            'type' => 'WORD',
                            'value' => $matches[0][0]
                        ];
                        $this->position += strlen($matches[0][0]);
                        $previousWasWord = true;  // Marcamos que este token fue un término
                        continue 2;
                    }
                }
            }

            $this->position++;
        }
    }
    

    public function getTokens() {
        return $this->tokens;
    }
}
?>
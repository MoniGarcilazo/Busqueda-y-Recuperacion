<?php
require_once 'Lexer.php';
require_once 'Parser.php';
include '../database/Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['query'])) {
        $query = $_POST['query'];
        generarConsulta($query);
    }
}

function parseQuery($query){
    $lexer = new Lexer($query);
    $tokens = $lexer->getTokens();

    $parser = new Parser($tokens);
    $ast = $parser->parse();
    
    return $ast;
}

function astToSQL($ast) {
    if (!is_array($ast)) {
        return '';
    }

    switch ($ast['type']) {
        case 'AND':
            return '(' . astToSQL($ast['left']) . ' AND ' . astToSQL($ast['right']) . ')';
        case 'OR':
            return '(' . astToSQL($ast['left']) . ' OR ' . astToSQL($ast['right']) . ')';
        case 'NOT':
            return 'NOT (' . astToSQL($ast['term']) . ')';
        case 'WORD':
            return "(documents.description_doc LIKE '%" . $ast['value']. "%')";
        case 'CADENA':
            return "(documents.description_doc LIKE '%" . $ast['value']. "%')";
        case 'PATRON':
            return "(documents.description_doc LIKE '%" . $ast['value']. "%')";
        case 'CAMPOS':
            return ''; // No pude hacerlo, no se como
        default:
            return '';
    }
}

function generarConsulta($query) {
    $conn = new mysqli("localhost", "root", "2003", "ada4");

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }else{
        //echo "Conexión establecidad";
    }
    
    /*$ast = parseQuery($query);

    $sqlQuery = astToSQL($ast);
    #echo "Consulta SQL generada:<br> SELECT * FROM products WHERE " .$sqlQuery. "<br><br>";*/

    $sql = "SELECT *, MATCH (texto_archivo) AGAINST('$query') as relevancia, ROW_NUMBER() 
            OVER (ORDER BY MATCH (texto_archivo) AGAINST('$query') DESC) AS rango
            FROM documents WHERE 
            MATCH (texto_archivo) AGAINST('$query') ORDER BY relevancia DESC";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border=1> <tr> <th> Rank </th> <th> Documento </th> <th> ID </th> <th> Contenido </th> <th> Relevancia </th> </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr> <td> " . $row["rango"] . "</td> <td>". $row["name_doc"] . 
            "</td><td> " . $row["id"] . " </td><td> " . $row["texto_archivo"] . "</td> <td>" . $row["relevancia"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No se encontraron resultados.";
    }



    $conn->close();
}
?>


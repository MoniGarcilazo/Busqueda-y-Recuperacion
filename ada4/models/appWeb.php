<?php
require_once 'Lexer.php';
require_once 'Parser.php';

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
            return "(products.product_name LIKE '%" . $ast['value'] . "%' 
                     OR products.quantity_per_unit LIKE '%" . $ast['value'] . "%' 
                     OR products.category LIKE '%" . $ast['value'] . "%')";
        case 'CADENA':
            return "(products.product_name LIKE '%" . $ast['value'] . "%' 
                    OR products.quantity_per_unit LIKE '%" . $ast['value'] . "%' 
                    OR products.category LIKE '%" . $ast['value'] . "%')";
        case 'PATRON':
            return "(products.product_name LIKE '%" . $ast['value'] . "%' 
                     OR products.quantity_per_unit LIKE '%" . $ast['value'] . "%' 
                     OR products.category LIKE '%" . $ast['value'] . "%')";
        case 'CAMPOS':
            return ''; // No pude hacerlo, no se como
        default:
            return '';
    }
}

function generarConsulta($query) {
    $conn = new mysqli("localhost", "root", "", "northwind");

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $ast = parseQuery($query);

    $sqlQuery = astToSQL($ast);
    #echo "Consulta SQL generada:<br> SELECT * FROM products WHERE " .$sqlQuery. "<br><br>";

    $sql = "SELECT * FROM products WHERE " .$sqlQuery;

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border=1> <tr> <th> Producto </th> <th> Cantidad por unidad </th> <th> Categoría </th> </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr> <td> " . $row["product_name"] . 
            "</td><td> " . $row["quantity_per_unit"] . " </td><td> " . $row["category"] . "</td></tr>";
        }
        echo "</table";
    } else {
        echo "No se encontraron resultados.";
    }

    $conn->close();
}
?>


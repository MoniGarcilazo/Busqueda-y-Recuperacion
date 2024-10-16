<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Ruben Alvarado">
        <title>Indización y búsqueda</title>
        <link rel="stylesheet" href="./styles/index.css">
    </head>
    <body>
        <div>
            <form id="form" action="./controllers/process.php" method="POST">
                <fieldset>
                    <input type="text" id="text" name="text" placeholder="Consultar"/>
                </fieldset>
            </form>
        </div>
        <article id="results"></article>
    </body>
</html>

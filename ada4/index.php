<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Indización y búsqueda</title>
        <link rel="stylesheet" href="./styles/index.css">
    </head>
    <body>
        <div>
            <h1>Subir archivos</h1>
            <form action="./controllers/upload.php" method="POST" enctype="multipart/form-data">
                <label for="files">Seleccionar archivos de texto: </label>
                <br/>
                <input type="file" name="files[]" id="files" multiple accept=".txt">
                <br/><br/>
                <button type="submit">Subir archivos</button>
            </form>
        </div>
        <section id="results"></section>
    </body>
</html>

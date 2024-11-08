<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Indización y búsqueda</title>
        <link rel="stylesheet" href="./styles/index.css">
        <link rel="stylesheet" href="./upload_file.css">
        <link rel="stylesheet" href="./finder.css">
        <script src="Scripts/script1.js"></script>
    </head>
    <body>
        <div>
            <section class="upload">
                <h1>Subir archivos</h1>
                <form id="uploadForm" action="./controllers/upload.php" method="POST" enctype="multipart/form-data">
                    <label for="files">Selecciona tus documentos: </label>
                    <br/>
                    <input type="file" name="files[]" id="files" multiple accept=".txt">
                    <button id="submit" type="submit">Subir archivos</button>
                </form>
            </section>
            <br/>
            <br/>
            <section class="search">
                <h1>Consultas</h1>
                <form id="searchForm" onsubmit="event.preventDefault(); enviarConsulta();">
                    <input type="text" id="query" name="query" placeholder="Buscar..." required>
                    <button type="submit">Consultar</button>
                    <br/>
                </form>
            </section>
        </div>
        <div id="resultados"></div>
    </body>
</html>

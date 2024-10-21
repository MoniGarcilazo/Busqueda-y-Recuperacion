document.addEventListener('DOMContentLoaded', function() {
    const consultaForm = document.getElementById('searchForm');
    const resultadosDiv = document.getElementById('resultados');

    consultaForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar que el formulario recargue la página

        const query = document.getElementById('query').value;

        fetch('./controllers/search.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'query=' + encodeURIComponent(query)
        })
        .then(response => response.json()) // Suponiendo que query.php devuelve JSON
        .then(data => {
            // Limpiar resultados anteriores
            resultadosDiv.innerHTML = '';

            // Mostrar los resultados
            if (data.success) {
                const p = document.createElement('p');
                p.innerHTML = `
                    <h2><a href="${data.url}" target="_blank">Titulo: ${data.title}</h2></a>
                    <p>Contenido: ${data.content}</p>
                    <p><small>Similitud coseno: ${data.similitudCoseno}</small></p>
                    `;
                resultadosDiv.appendChild(p);
                
            } else {
                resultadosDiv.innerHTML = `<p>Error: no se encontro el término</p>`;
            }
        })
        .catch(error => {
            resultadosDiv.innerHTML = '<p>Ocurrió un error en la consulta</p>';
            console.error('Error:', error);
        });
    });
});

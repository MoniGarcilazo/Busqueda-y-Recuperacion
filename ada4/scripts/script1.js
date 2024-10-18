document.addEventListener('DOMContentLoaded', function() {
    const consultaForm = document.getElementById('searchForm');
    const resultadosDiv = document.getElementById('resultados');

    // Escuchar el evento de envío del formulario de consulta
    consultaForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar que el formulario recargue la página

        const query = document.getElementById('query').value;

        // Realizar una solicitud AJAX (usando fetch) para consultar los resultados
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
                p.textContent = `El término "${data.query}" aparece "${data.frequency}" veces en total, en los archivos: "${data.files.join(', ')}".`;
                resultadosDiv.appendChild(p);
            } else {
                resultadosDiv.innerHTML = `<p>${data.message}</p>`;
            }
        })
        .catch(error => {
            resultadosDiv.innerHTML = '<p>Ocurrió un error en la consulta</p>';
            console.error('Error:', error);
        });
    });
});

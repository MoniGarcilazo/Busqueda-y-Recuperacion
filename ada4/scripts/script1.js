function enviarConsulta() {
    var query = document.getElementById("query").value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "models/appWeb.php", true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("resultados").innerHTML = xhr.responseText;
        }
    };

    xhr.send("query=" + encodeURIComponent(query));
}

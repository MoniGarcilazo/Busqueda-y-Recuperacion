let query: string = "Docker And Gato Or programacion";
query = query.toLowerCase();

//reemplazar por los operadores que acepta Solr
query = query.replace('and', 'AND');
query = query.replace('or', 'OR');
query = query.replace('not', 'NOT');

let queryAux: string = query;
queryAux = queryAux.replace(/ /g, '+'); // suponiendo que la string tenga espacios en blanco, si no no sirve

let finalQuery = `http://localhost:8983/solr/ejemplo/select?q=${queryAux}&wt=json`;

//console.log(finalQuery);
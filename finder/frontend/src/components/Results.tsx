import { useLocation, useNavigate } from 'react-router-dom';
import '../styles/Results.css';

import { createSnippet } from '../services/AddSnippet';

function Results() {
    const location = useLocation();
    const navigate = useNavigate();

    const results = location.state?.results || [];
    const query = location.state?.query || '';

    if (!results.length) {
        return (
            <div>
                <h2>No se encontraron resultados para "{query}"</h2>
                <button className="btn" onClick={() => navigate('/')}>Volver a buscar</button>
            </div>
        );
    }

    return (
        <div className="results">
            <button className="btn" onClick={() => navigate('/')}>Nueva búsqueda</button>
            <h2>Resultados para "{query}"</h2>
            <ul>
                {results.map((result: { id: string; title: string; content: string }) => (
                    <div key={result.id}>
                        <h3 
                            onClick={() =>
                                navigate(`/details/${result.id}`, {
                                    state: { title: result.title, content: result.content },
                                })
                            }
                        >
                            {result.title}
                        </h3>
                        <p>{createSnippet(result.content)}</p>
                    </div>
                ))}
            </ul>
        </div>
    );
}

export default Results;

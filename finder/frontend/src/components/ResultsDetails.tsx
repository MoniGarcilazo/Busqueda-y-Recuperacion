import { useLocation, useNavigate } from 'react-router-dom';

const ResultDetails = () => {
    const location = useLocation();
    const { title, content } = location.state || {};
    const navigate = useNavigate();

    if (!title || !content) {
        return <p>No se encontraron detalles. Por favor, vuelve a los resultados.</p>;
    }

    return (
        <div>
            <div>
                <button className="btn" onClick={() => navigate(-1)}>Volver</button>
            </div>
            <h1>{title}</h1>
            <p>{content}</p>
        </div>
    );
};

export default ResultDetails;

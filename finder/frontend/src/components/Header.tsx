import { processDocuments } from "../api/solr";

function Header() {

    const handleSubmit = async () => {
        await processDocuments();
    };

    return (
        <>
         <div>
            <button className="btn" onClick={handleSubmit}>Agregar Documento</button>
        </div>
        <h1 className="header">
            MIABEN
        </h1>
        </>
       
    );
}

export default Header;
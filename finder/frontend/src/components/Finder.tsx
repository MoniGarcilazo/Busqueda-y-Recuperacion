import { ChangeEvent, useState, useEffect } from 'react';
import { IconField } from 'primereact/iconfield';
import { InputIcon } from 'primereact/inputicon';
import { InputText } from 'primereact/inputtext';
import axios from 'axios';
import '../styles/Finder.css';

 


function Finder() {
    const [input, setInput] = useState<string>('');
    const [suggestions, setSuggestions] = useState<string[]>([]);
    const [corrections, setCorrections] = useState<string[]>([]);
    const [loading, setLoading] = useState<boolean>(false);

    const handleInputChange = (event: ChangeEvent<HTMLInputElement>) => {
        setInput(event.target.value);
    };

    const fetchSuggestions = async (query: string) => {
        if (!query) {
            setSuggestions([]);
            setCorrections([]);
            return;
        }

        setLoading(true);
        try {
            // Obtener sugerencias de autocompletado
            const response = await fetch(
                `https://api.datamuse.com/sug?s=${query}&lang=es`
            );
            const data = await response.json();
            setSuggestions(data.map((item: { word: string }) => item.word));

            // Obtener correcciones utilizando TextGears
            // Obtener correcciones (LanguageTool)
            const languageToolResponse = await axios.post(
                'https://languagetool.org/api/v2/check',
                new URLSearchParams({
                    text: query,
                    language: 'es',
                }),
                {
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                }
            );
            const corrections = languageToolResponse.data.matches.map(
                (match: { replacements: { value: string }[] }) =>
                    match.replacements.length > 0 ? match.replacements[0].value : null
            ).filter(Boolean); // Filtrar valores nulos
            setCorrections(corrections);
            
        } catch (error) {
            console.error("Error fetching suggestions or corrections:", error);
        } finally {
            setLoading(false);
        }
    };
    

    useEffect(() => {
        const debounce = setTimeout(() => {
            fetchSuggestions(input);
        }, 300); 
        return () => clearTimeout(debounce);
    }, [input]);

    const handleSuggestionClick = (suggestion: string) => {
        setInput(suggestion);
        setSuggestions([]);
        setCorrections([]);
    };

    const reset = () => {
        setInput('');
        setSuggestions([]);
        setCorrections([]);
    };

    // const querySolr = async (query: string) => {
    //     const response = await fetch(`http://localhost:8000/api/solr?q=${query}`);
    //     const data = await response.json();
    //     return data.response.docs;
    // };
    return (
        <section className="flex gap-3 finder">
            <form id="finder" action="" method="GET" className="field">
                <IconField iconPosition="left">
                    <InputIcon className="pi pi-search"></InputIcon>
                    <InputText
                        placeholder="Buscar"
                        className="field input-text"
                        value={input}
                        onChange={handleInputChange}
                    />
                </IconField>
                {input !== '' && (
                    <InputIcon
                        className="pi pi-times reset-icon"
                        onClick={reset}
                    ></InputIcon>
                )}
                {suggestions.length > 0 && (
                <ul className={`suggestions ${input ? 'show' : ''}`}>
                    {loading ? (
                        <li className="loading">Cargando...</li>
                    ) : (
                        <>
                            {corrections.length > 0 && (
                                <li className="correction-header">¿Quisiste decir?</li>
                            )}
                            {corrections.map((correction) => (
                                <li
                                    key={correction}
                                    onClick={() => handleSuggestionClick(correction)}
                                    className="correction"
                                >
                                    {correction}
                                </li>
                            ))}
                            <li className="correction-header">Sigue escribiendo...</li>
                            {suggestions.map((suggestion) => (
                                <li
                                    key={suggestion}
                                    onClick={() => handleSuggestionClick(suggestion)}
                                >
                                    {suggestion}
                                </li>
                            ))}
                            
                        </>
                    )}
                </ul>
            )}
            </form>

            
        </section>
    );
}

export default Finder;

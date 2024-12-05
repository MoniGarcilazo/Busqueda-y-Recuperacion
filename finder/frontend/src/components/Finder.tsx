import { ChangeEvent, useState, useEffect, FormEvent } from 'react';
import { useNavigate } from 'react-router-dom';
import { IconField } from 'primereact/iconfield';
import { InputIcon } from 'primereact/inputicon';
import { InputText } from 'primereact/inputtext';
import axios from 'axios';
import '../styles/Finder.css';

import { searchSolr } from '../api/solr';
import { SearchParams } from '../interfaces/solr_search';
import { booleanSearch, isBooleanQuery } from '../services/BooleanSearch';
import Header from './Header';

import { NormalizeQuery } from '../interfaces/boolean_search';

function Finder() {
    const [input, setInput] = useState<string>('');
    const [suggestions, setSuggestions] = useState<string[]>([]);
    const [corrections, setCorrections] = useState<string[]>([]);
    const [loading, setLoading] = useState<boolean>(false);
    const navigate = useNavigate(); 
    

    const handleInputChange = (event: ChangeEvent<HTMLInputElement>) => {
        setInput(event.target.value);
    };

    const handleSearchSubmit = async (event: FormEvent) => {

        event.preventDefault();
        if (!input.trim()) return;

        let input2: SearchParams;

        const booleanQuery: NormalizeQuery = booleanSearch(input);

        if (isBooleanQuery(input)) {
            input2 = {
                q: booleanQuery.query,
                field: 'content',
                rows: 10,
                q_op: booleanQuery.operator,
            };
        } else {
            input2 = {
                q: booleanQuery.query,
                field: 'content',
                rows: 10,
                q_op: 'OR',
            };
        }

        setLoading(true);

        try {
            const data = await searchSolr(input2);
            navigate('/results', { state: { results: data.docs, query: input } });
            console.log('Resultados:', data);
        } catch (error) {
            console.error('Error al realizar la búsqueda:', error);
        } finally {
            setLoading(false);
        }
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

    return (
    <>
        <Header />
        <section className="flex gap-3 finder">
            <form id="finder" action="" onSubmit={handleSearchSubmit} method="GET" className="field">
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
        </>
    );
    
}

export default Finder;

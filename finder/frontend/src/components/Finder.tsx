import { ChangeEvent, useState, useEffect, FormEvent } from 'react';
import { IconField } from 'primereact/iconfield';
import { InputIcon } from 'primereact/inputicon';
import { InputText } from 'primereact/inputtext';
import axios from 'axios';
import '../styles/Finder.css';

import { searchSolr } from '../api/solr';
import { SearchParams } from '../interfaces/solr_search';
import { booleanSearch, isBooleanQuery } from '../services/BooleanSearch';

function Finder() {
    const [input, setInput] = useState<string>('');
    const [suggestions, setSuggestions] = useState<string[]>([]);
    const [corrections, setCorrections] = useState<string[]>([]);
    const [loading, setLoading] = useState<boolean>(false);
    const [results, setResults] = useState<any[]>([]);

    const handleInputChange = (event: ChangeEvent<HTMLInputElement>) => {
        setInput(event.target.value);
    };

    const handleSearchSubmit = async (event: FormEvent) => {
        const query1 = "Docker OR linux";
        const query2 = "Docker AND linux";
        const query3 = "Docker linux";

        console.log(booleanSearch(query1));
        console.log(booleanSearch(query2));
        console.log(booleanSearch(query3));

        console.log(isBooleanQuery(query1));
        console.log(isBooleanQuery(query2));
        console.log(isBooleanQuery(query3));

        event.preventDefault();
        if (!input.trim()) return;

        setLoading(true);

        const input2: SearchParams = {
            q: input,
            field: 'content',
            rows: 10,
            q_op: 'OR',
          };

        try {
            const data = await searchSolr(input2);
            setResults(data);
            console.log('Resultados:', data);
        } catch (error) {
            console.error('Error al realizar la búsqueda:', error);
        } finally {
            setLoading(false);
        }
    };

    console.log(results);

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

    return (
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
    );
}

export default Finder;

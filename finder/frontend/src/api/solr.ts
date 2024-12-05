import axios from "axios";

import { SearchParams, Results } from "../interfaces/solr_search";
import { AddDocument } from "../interfaces/solr_add";

export const searchSolr = async (queryParams: SearchParams): Promise<Results | null> => {
    try {
        const response = await axios.get<Results>('http://localhost:8000/search/', {
            params: queryParams,
        });
        return response.data;
    } catch (error) {
        console.error('Error al hacer la consulta: ', error);
        return null;
    }
}

export const addDocument = async (document: AddDocument): Promise<void> => {
    try {
        const response = await axios.post('http://localhost:8000/add_document/', document, {
            headers: {
                'Content-Type': 'application/json',
            },
        });
        //return response.data;
    } catch (error) {
        console.error('Error al agregar documento: ', error);
    }
}
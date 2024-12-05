import { NormalizeQuery } from "../interfaces/boolean_search";

export const booleanSearch = (inputQuery: string): NormalizeQuery => {
    const normalizedInput = inputQuery.trim().toUpperCase();

    let operator: "AND" | "OR" | null = null;
    let query = "";

    if (normalizedInput.includes(" AND ")) {
        operator = "AND";
        query = normalizedInput.replace(/ AND /g, "+");
    } else if (normalizedInput.includes(" OR ")) {
        operator = 'OR';
        query = normalizedInput.replace(/ OR /, "+");
    } else {
        query = inputQuery.replace(/\s+/g, "+");
    }

    return {
        query,
        operator,
    }
}

export const isBooleanQuery = (query: string): boolean => {
    const normalizeQuery = query.toLowerCase();
    return normalizeQuery.includes("and") || normalizeQuery.includes('or');
}
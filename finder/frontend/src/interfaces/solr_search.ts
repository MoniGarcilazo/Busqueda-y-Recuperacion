export interface SearchParams {
    q: string;
    field: string;
    rows: number;
    q_op: string | null;
}

interface SearchDocument {
    id: string;
    title: string;
    content: string;
}

export interface Results {
    numFound: number,
    start: number,
    numFoundExact: boolean,
    docs: SearchDocument[]
}
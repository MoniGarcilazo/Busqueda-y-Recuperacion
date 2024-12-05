export interface NormalizeQuery {
    query: string;
    operator: "AND" | "OR" | null;
}
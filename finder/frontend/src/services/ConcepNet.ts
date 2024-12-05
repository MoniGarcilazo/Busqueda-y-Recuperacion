import axios from 'axios';

export class SynonymService {
  private baseUrl: string;
  private language: string;

  constructor() {
    this.baseUrl = 'https://api.conceptnet.io/c';
    this.language = 'es';
  }

  /**
   * Construye la URL para la consulta de sinónimos.
   * @param query La palabra para buscar sinónimos.
   * @returns URL construida para la consulta.
   */
  private buildUrl(query: string): string {
    return `${this.baseUrl}/${this.language}/${query}`;
  }

  /**
   * Obtiene los sinónimos para una palabra dada.
   * @param query La palabra para buscar sinónimos.
   * @returns Un array con los sinónimos.
   */
  async getSynonyms(query: string): Promise<string[]> {
    const url = this.buildUrl(query);

    try {
      const response = await axios.get(url);

      const edges = response.data.edges || [];
      const synonyms: string[] = edges
        .filter((edge: any) => edge.rel.label === 'Synonym')
        .map((edge: any) => edge.end.label);

      return synonyms;
    } catch (error) {
      console.error('Error al obtener sinónimos:', error);
      throw new Error('No se pudieron obtener los sinónimos.');
    }
  }
}

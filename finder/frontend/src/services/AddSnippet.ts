// Se puede borrar, es una interfaz para regresar el JSON que devuelve Solr
export interface SolrResponse {
    responseHeader: {
      status: number;
      QTime: number;
      params: {
        q: string;
        wt: string;
      };
    };
    response: {
      numFound: number;
      start: number;
      numFoundExact: boolean;
      docs: SolrDocument[];
    };
  }
  
  // Interfaz de ingreso de datos JSON (datos directo de Solr)
  export interface SolrDocument {
    id: string;
    title: string[];
    content: string[];
    _version_: number;
    _root_: string;
  }
  
  // Interfaz de salida de datos JSON (datos modificados con snippet)
  export interface ModifiedDocument {
    id: string;
    title: string[];
    snippet: string;
    content: string[];
    version: number;
    root: string;
  }
  
  /**
   * Se puede borrar.
   * Función que realiza una solicitud HTTP GET a la URL proporcionada y devuelve los datos en formato JSON.
   * @param solrUrl - La URL del endpoint de Solr.
   * @returns Una promesa que resuelve con los datos JSON obtenidos.
   */
  export const fetchSolrData = async (solrUrl: string): Promise<SolrResponse> => {
    try {
      const response = await fetch(solrUrl);
      if (!response.ok) {
        throw new Error("Error al obtener datos de Solr");
      }
      const solrData = await response.json();
      return solrData;
    } catch (error) {
      console.error("Error al realizar la solicitud a Solr:", error);
      throw error;
    }
  };
  
  /**
   * Función que procesa una lista de documentos de Solr, añadiendo un snippet a cada uno.
   * @param documents - Array de documentos de Solr a procesar.
   * @returns Un array de documentos modificados con el snippet añadido.
   */
  export const addSnippetsToDocuments = (
    documents: SolrDocument[]
  ): ModifiedDocument[] => {
    return documents.map((doc) => {
      const contentText = doc.content[0];
      const snippet = contentText.split(" ").slice(0, 15).join(" ") + "...";
      return {
        id: doc.id,
        title: doc.title,
        snippet,
        content: doc.content,
        version: doc._version_,
        root: doc._root_,
      };
    });
  };
  
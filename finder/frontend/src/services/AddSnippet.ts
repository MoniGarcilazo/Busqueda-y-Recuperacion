  /**
   * Función que procesa una lista de documentos de Solr, añadiendo un snippet a cada uno.
   * @param documents - Array de documentos de Solr a procesar.
   * @returns Un array de documentos modificados con el snippet añadido.
   */
  export const createSnippet = (inputText: string): string => {
    const words = inputText.split(" ");
    const reducedWords = words.slice(0, 20);

    return reducedWords.join(" ") + (words.length > 20 ? "..." : "");
  };
  
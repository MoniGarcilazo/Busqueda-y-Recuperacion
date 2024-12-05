//npm install @google/generative-ai
let query: string = "http://localhost:8983/solr/ejemplo/select?q=computadora%20OR%20gato&wt=json";

let parts: string[] = query.split("&wt=json");
let part1: string = parts[0];
let part2: string = "&wt=json";

let queryParams: string[] = part1.split("select?q=");
let part3: string = queryParams[1];

let words: string[] = part3.split("%20");

let relevantWords: string[] = words.filter(word => !["AND", "NOT", "OR"].includes(word.toUpperCase()));

//console.log("Palabras relevantes:", relevantWords);

import { GoogleGenerativeAI } from "@google/generative-ai";

const genAI = new GoogleGenerativeAI("AIzaSyAlvQXiFOjQFLMdt2mwcZGP_xy6VQZ9jGM");
const model = genAI.getGenerativeModel({ model: "gemini-1.5-flash" });

(async () => {
    for (let i = 0; i < relevantWords.length; i++) {
        let word: string = relevantWords[i];
        //console.log("Palabra relevante:", word);

        let prompt: string = 'Dame un sinónimo de la palabra ' + word + ' en el siguiente formato: palabra1"%20"palabra2';
        //console.log(prompt);

        const result = await model.generateContent(prompt);
        //console.log(result.response.text());
        part1 += '%20' + result.response.text().trim();
    }
    query = part1 + part2;

    console.log(query);
})();
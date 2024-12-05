from contextlib import asynccontextmanager
from typing import Union

from fastapi import FastAPI, Query, HTTPException
from fastapi.middleware.cors import CORSMiddleware

from pydantic import BaseModel
from pathlib import Path

import requests
import uuid
import os

from src.docs.openapi_tag import openapi_tags

@asynccontextmanager
async def lifespan(app: FastAPI):
    print("Starting application")
    yield
    print("Shutting down application")

app = FastAPI(
    title="MIABEN API",
    summary="API for MIABEN information search and retrieval application",
    version="0.0.1",
    contact={
        "name": "Ruben Alvarado",
        "email": "A18003305@alumnos.uady.mx",
    },
    openapi_tags=openapi_tags,
    lifespan=lifespan,
)

class Document(BaseModel):
    title: str
    content: str

@app.get("/")
def read_root():
    return {"Hello": "World"}

@app.get("/items/{item_id}")
def read_item(item_id: int, q: Union[str, None] = None):
    return {"item_id": item_id, "q": q}

SOLR_BASE_URL = "http://localhost:8983/solr/core/select"
SOLR_UPDATE_URL = "http://localhost:8983/solr/core/update?commit=true"
TEXT_FILES_PATH = Path("../db")

@app.get("/search/")
async def search_solr(
    field: str = Query("content", description="Campo para buscar por defecto"),
    q: str = Query(..., description="Consulta para buscar en Solr"),
    rows: int = Query(10, description="Resultados por devolver", ge=1, le=100),
    q_op: str = Query("OR", description="Operador logico para la consulta booleana", regex="^(OR|AND)$")
):
    """
    Endpoint para la busqueda y recuperacion de archivos de Solr
    Los campos que recibe son field, q (query), rows, q_op
    """
    params = {
        "q": f"{field}:{q}",
        "rows": rows,
        "wt": "json",
        "q.op": q_op,
        "indent": "true",
    }

    try:
        response = requests.get(SOLR_BASE_URL, params=params)
        response.raise_for_status() 
    except requests.exceptions.RequestException as e:
        raise HTTPException(status_code=500, detail=f"Error al conectar con Solr: {e}")
    
    solr_response = response.json()
    
    if "response" not in solr_response:
        raise HTTPException(status_code=500, detail="Respuesta inesperada de Solr")
    
    return solr_response["response"]

@app.post("/add_document/")
async def add_document(document: Document):
    """
    Endpoint para agregar un documento a Solr.
    Recibe un documento en formato JSON con los campos, 'title', 'content'.
    """
    document_id = str(uuid.uuid4())
    
    solr_document = [
        {
            "id": document_id,
            "title": document.title,
            "content": document.content,
        }
    ]

    try:
        # Realizar la solicitud POST a Solr para actualizar el índice
        response = requests.post(SOLR_UPDATE_URL, json=solr_document, headers={"Content-Type": "application/json"})
        response.raise_for_status()  # Verificar si hubo algún error en la solicitud
    except requests.exceptions.RequestException as e:
        raise HTTPException(status_code=500, detail=f"Error al agregar documento a Solr: {e}")

    # Retornar una respuesta de éxito
    return {"message": "Documento agregado correctamente", "solr_response": response.json()}

@app.post("/crawling/")
async def process_and_add_documents():
    """
    Lee todos los archivos .txt de una carpeta especificada, extrae el título y contenido,
    y los agrega a Solr utilizando el endpoint /add_document/.
    """
    if not  TEXT_FILES_PATH.exists() or not TEXT_FILES_PATH.is_dir():
        raise HTTPException(
            status_code=404,
            detail="La carpeta no existe o no es válida."
        )
    
    for file in TEXT_FILES_PATH.glob("*.txt"):
        try:
            with file.open("r", encoding="utf-8") as f:
                lines = f.readlines()
            
            if not lines:
                continue  # Ignora archivos vacíos

            title = lines[0].strip()  # La primera línea es el título
            content = "\n".join(line.strip() for line in lines[1:])  # El resto es el contenido

            # Crea un documento con el título y contenido
            document = Document(title=title, content=content)

            # Llama al endpoint add_document para agregar el documento a Solr
            response = await add_document(document)

            os.remove(file)
            print(f"Documento '{title}' agregado y archivo '{file.name}' eliminado.")

            print(f"Documento '{title}' agregado correctamente a Solr.")

        except Exception as e:
            raise HTTPException(status_code=500, detail=f"Error al procesar el archivo {file.name}: {str(e)}")

    return {"message": "Todos los documentos fueron procesados y agregados a Solr correctamente."}

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
    allow_credentials=True,
)
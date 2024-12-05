from contextlib import asynccontextmanager
from typing import Union

from fastapi import FastAPI, Query, HTTPException
from fastapi.middleware.cors import CORSMiddleware

from pydantic import BaseModel

import requests
import uuid

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

@app.get("/search/")
async def search_solr(
    field: str = Query("content", description="Campo para buscar por defecto"),
    q: str = Query(..., description="Consulta para buscar en Solr"),
    rows: int = Query(10, description="Resultados por devolver", ge=1, le=100),
):
    params = {
        "q": f"{field}:{q}",
        "rows": rows,
        "wt": "json",
        "q.op": "OR",
        "indent": "true",
    }

    try:
        response = requests.get(SOLR_BASE_URL, params=params)
        response.raise_for_status()  # Verifica si la solicitud fue exitosa
    except requests.exceptions.RequestException as e:
        raise HTTPException(status_code=500, detail=f"Error al conectar con Solr: {e}")
    
    # Procesar la respuesta de Solr
    solr_response = response.json()
    
    if "response" not in solr_response:
        raise HTTPException(status_code=500, detail="Respuesta inesperada de Solr")
    
    return solr_response["response"]

@app.post("/add_document/")
async def add_document(document: Document):
    """
    Endpoint para agregar un documento a Solr.
    Recibe un documento en formato JSON con los campos 'id', 'title', 'content'.
    """
    document_id = str(uuid.uuid4())
    # Preparar el documento en formato JSON compatible con Solr
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

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
    allow_credentials=True,
)
from contextlib import asynccontextmanager
from typing import Union

from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware

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

@app.get("/")
def read_root():
    return {"Hello": "World"}

@app.get("/items/{item_id}")
def read_item(item_id: int, q: Union[str, None] = None):
    return {"item_id": item_id, "q": q}

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
    allow_credentials=True,
)
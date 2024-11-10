# MIABEN API + FaastAPI

# Setting the FastAPI application
## Create a virtual environment
First, create a directory for the project. Preferably call it 'backend'.

Inside that directory run the command:

```bash
python -m venv .venv
```

That command create a new virtual environment in a directory called .venv

## Activate the virtual environment
Just run the following command

```bash
.venv/Scripts/activate.ps1
```

## Upgrade pip
Make sure the virtual environment is active (with the command above) and run:

```bash
python -m pip install --upgrade pip
```

## Install all dependencies from the requirements.txt
You can use the requirements.txt to install its packages

```bash
pip install -r requirements.txt
```

## Deactivate the virtual environment
Once you are done working on the project you can deactivate the virutal environment

```bash
deactivate
```

# Run the application
After you activated the virtual environment, you can run the API by:

```bash
uvicorn main:app --reload
```
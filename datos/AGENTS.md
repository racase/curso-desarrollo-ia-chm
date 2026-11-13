# AGENTS.md — InfraData (datos/)

Proyecto Python de analítica sobre lecturas de sensores en PostgreSQL + TimescaleDB.
Es un proyecto de formación: prioriza código claro, convencional y fácil de revisar.

## Mapa

- `src/infradata/`: código. `config.py` (configuración), `db.py` (conexión), `__main__.py` (CLI).
- `tests/`: pytest. Los tests con base de datos llevan el marcador `integration`.
- `db/init/`: esquema y datos de ejemplo. Solo se ejecutan al crear el volumen de Docker.
- `openspec/`: especificaciones y cambios (cuando exista).

## Comandos

- Instalar: `uv sync`
- Base de datos: `docker compose up -d`
- Tests: `uv run pytest`
- Lint y formato: `uv run ruff check .` y `uv run ruff format .`
- Comprobación rápida: `uv run python -m infradata check`

## Convenciones

- Python 3.11+, con anotaciones de tipo en funciones públicas.
- Código, nombres y docstrings en inglés. Mensajes al usuario y documentación en español.
- SQL siempre parametrizado con psycopg (`%s`), nunca construido con f-strings.
- Las agregaciones temporales se hacen en SQL con funciones de TimescaleDB (`time_bucket`),
  no trayendo todas las filas a Python.
- Las fechas son `timestamptz` en UTC.
- Toda funcionalidad nueva lleva tests; la lógica que no necesita base de datos se prueba sin ella.
- Añade dependencias solo si son necesarias y explícalo en el cambio.

## No tocar

- `db/init/*.sql`: el conjunto de datos es común a todo el curso. Si un cambio necesita
  modificar el esquema, pregunta antes.
- Ficheros `.env`.
- Nada fuera de `datos/`.

## Terminado significa

- `uv run ruff check .` sin errores.
- `uv run pytest` en verde con la base de datos levantada.
- El cambio cumple los criterios de aceptación de su especificación.

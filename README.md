# Curso de desarrollo con IA — CHM

Repositorio de trabajo del curso «Desarrollo con IA: de escribir prompts al desarrollo con agentes».
Contiene los dos proyectos de demostración sobre los que practicaremos el ciclo de
Spec-Driven Development con OpenSpec y Codex.

| Carpeta | Proyecto | Stack | Estado inicial |
|---|---|---|---|
| `web/` | InfraTicket, gestor de incidencias | Laravel, Filament, PostgreSQL | Login y usuarios de prueba |
| `datos/` | InfraData, analítica de sensores | Python, PostgreSQL + TimescaleDB | Lecturas de sensores cargadas, sin analítica |

Los dos proyectos llegan casi vacíos a propósito: la funcionalidad la construiremos en clase,
una feature cada vez.

## Cómo empezar

1. Crea tu copia del repositorio (botón «Use this template» o fork) y clónala.
2. Sigue el `README.md` de la carpeta con la que vayas a trabajar.
3. Abre el agente **dentro de esa carpeta** (`cd web` o `cd datos`), no en la raíz:
   cada proyecto tiene su propio `AGENTS.md`.

OpenSpec no está inicializado. Lo haremos juntos en clase con `openspec init`.

## Normas

- No subas ficheros `.env` ni credenciales reales.
- Trabaja siempre en una rama, nunca directamente en `main`.